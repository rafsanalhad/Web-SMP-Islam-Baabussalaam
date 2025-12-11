@extends('layouts.admin')

@section('title', 'Kelola Berita')
@section('breadcrumb', 'Kelola Berita')

@section('styles')
<style>
    .modal-dialog.modal-md {
        max-width: 600px;
    }

    .modal-body {
        font-size: 0.85rem;
    }

    .modal-header {
        background: linear-gradient(135deg, #2a9d8f 0%, #21867a 100%);
        color: white;
        border-radius: 8px 8px 0 0;
    }

    .modal-header.modal-header-edit {
        background: linear-gradient(135deg, #f4a261 0%, #e76f51 100%);
    }

    .btn-close {
        filter: brightness(0) invert(1);
    }

    table.dataTable tbody tr {
        transition: all 0.2s;
    }

    table.dataTable tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75rem;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .animate__animated {
        animation-duration: 0.5s;
    }
</style>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-left-primary shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Akademik</div>
                        <div class="h5 mb-0 fw-bold">{{ $countAcademic }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-left-success shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Event</div>
                        <div class="h5 mb-0 fw-bold">{{ $countEvent }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-left-info shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Prestasi</div>
                        <div class="h5 mb-0 fw-bold">{{ $countAchievement }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-trophy fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-left-warning shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Pengumuman</div>
                        <div class="h5 mb-0 fw-bold">{{ $countAnnouncement }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary">Daftar Berita</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Tambah Berita
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="newsTable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Gambar</th>
                        <th width="25%">Judul</th>
                        <th width="15%">Kategori</th>
                        <th width="10%">Status</th>
                        <th width="15%">Tanggal</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($news as $index => $n)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            @if($n->image && file_exists(public_path('assets/img/news/' . $n->image)))
                            <img src="{{ asset('assets/img/news/' . $n->image) }}" class="img-fluid rounded" style="width: 60px; height: 45px; object-fit: cover;">
                            @else
                            <span class="text-muted small">No Image</span>
                            @endif
                        </td>
                        <td>{{ $n->title }}</td>
                        <td>
                            @if($n->category == 'academic')
                            <span class="badge bg-primary">Akademik</span>
                            @elseif($n->category == 'event')
                            <span class="badge bg-success">Event</span>
                            @elseif($n->category == 'achievement')
                            <span class="badge bg-info">Prestasi</span>
                            @else
                            <span class="badge bg-warning">Pengumuman</span>
                            @endif
                        </td>
                        <td>
                            @if($n->status == 'published')
                            <span class="badge bg-success">Published</span>
                            @else
                            <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td class="small">{{ $n->created_at->format('d M Y') }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-btn"
                                data-id="{{ $n->id }}"
                                data-title="{{ $n->title }}"
                                data-excerpt="{{ $n->excerpt }}"
                                data-content="{{ $n->content }}"
                                data-category="{{ $n->category }}"
                                data-status="{{ $n->status }}"
                                data-image="{{ $n->image }}"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="{{ route('admin.berita.toggle', $n->id) }}?status={{ $n->status == 'published' ? 'draft' : 'published' }}"
                                class="btn btn-{{ $n->status == 'published' ? 'secondary' : 'success' }} btn-sm">
                                <i class="fas fa-{{ $n->status == 'published' ? 'eye-slash' : 'eye' }}"></i>
                            </a>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $n->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg animate__animated animate__zoomIn">
        <div class="modal-content shadow-lg rounded-4">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Tambah Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ringkasan</label>
                        <textarea class="form-control" name="excerpt" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Konten <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="content" rows="5" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" name="category" required>
                                <option value="academic">Akademik</option>
                                <option value="event">Event</option>
                                <option value="achievement">Prestasi</option>
                                <option value="announcement">Pengumuman</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Gambar</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg animate__animated animate__zoomIn">
        <div class="modal-content shadow-lg rounded-4">
            <div class="modal-header modal-header-edit">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" id="edit_title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ringkasan</label>
                        <textarea class="form-control" name="excerpt" id="edit_excerpt" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Konten <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="content" id="edit_content" rows="5" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" name="category" id="edit_category" required>
                                <option value="academic">Akademik</option>
                                <option value="event">Event</option>
                                <option value="achievement">Prestasi</option>
                                <option value="announcement">Pengumuman</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" id="edit_status" required>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Gambar Saat Ini</label>
                        <div id="current_image"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ganti Gambar</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form method="POST" action="" id="deleteForm">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<script>
    $(document).ready(function() {
        // Initialize DataTable safely: destroy existing instance first to avoid reinitialise warning
        if ($.fn.DataTable.isDataTable('#newsTable')) {
            try {
                $('#newsTable').DataTable().clear().destroy();
            } catch (e) {
                console.warn('DataTable destroy error:', e);
            }
        }

        $('#newsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            order: [
                [5, 'desc']
            ]
        });

        // Edit button handler
        $('.edit-btn').click(function() {
            const id = $(this).data('id');
            const title = $(this).data('title');
            const excerpt = $(this).data('excerpt');
            const content = $(this).data('content');
            const category = $(this).data('category');
            const status = $(this).data('status');
            const image = $(this).data('image');

            $('#edit_title').val(title);
            $('#edit_excerpt').val(excerpt);
            $('#edit_content').val(content);
            $('#edit_category').val(category);
            $('#edit_status').val(status);

            if (image) {
                $('#current_image').html(`<img src="{{ asset('assets/img/news/') }}/${image}" class="img-fluid rounded" style="max-width: 200px;">`);
            } else {
                $('#current_image').html('<span class="text-muted">Tidak ada gambar</span>');
            }

            $('#editForm').attr('action', `{{ url('admin/berita') }}/${id}`);
        });

        // Delete button handler
        $('.delete-btn').click(function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Hapus Berita?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteForm').attr('action', `{{ url('admin/berita') }}/${id}`);
                    $('#deleteForm').submit();
                }
            });
        });
    });

    // Success notification
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    @endif

    // DataTables: initialize only tables that are not yet initialised (prevents reinitialise warning)
    $(document).ready(function() {
        $('.table').each(function() {
            if (!$.fn.DataTable.isDataTable(this)) {
                $(this).DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                    },
                    pageLength: 10,
                    order: [[0, 'asc']]
                });
            }
        });
    });
</script>
@endsection
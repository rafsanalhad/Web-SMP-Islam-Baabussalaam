@extends('layouts.admin')
@section('title', 'Kelola Halaman')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-file-alt me-2"></i>Kelola Halaman</h1>
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus me-1"></i> Tambah Halaman</button>
</div>
<div class="row mb-4 gy-3">
    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card border-left-primary shadow-sm h-100 text-center">
            <div class="card-body py-3"><i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                <h5 class="fw-bold mb-0">{{$pages->count()}}</h5><small class="text-muted">Total Halaman</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card border-left-success shadow-sm h-100 text-center">
            <div class="card-body py-3"><i class="fas fa-eye fa-2x text-success mb-2"></i>
                <h5 class="fw-bold mb-0">{{$pages->where('status','published')->count()}}</h5><small class="text-muted">Published</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card border-left-warning shadow-sm h-100 text-center">
            <div class="card-body py-3"><i class="fas fa-eye-slash fa-2x text-warning mb-2"></i>
                <h5 class="fw-bold mb-0">{{$pages->where('status','draft')->count()}}</h5><small class="text-muted">Draft</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card border-left-info shadow-sm h-100 text-center">
            <div class="card-body py-3"><i class="fas fa-cog fa-2x text-info mb-2"></i>
                <h5 class="fw-bold mb-0">{{$pages->where('is_system',1)->count()}}</h5><small class="text-muted">System Pages</small>
            </div>
        </div>
    </div>
</div>
<div class="card shadow">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-primary">Daftar Halaman</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th width="50">No</th>
                        <th>Judul</th>
                        <th width="150">Slug</th>
                        <th width="100">Status</th>
                        <th width="100">Tipe</th>
                        <th width="120">Update</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>@foreach($pages as $i=>$p)<tr>
                        <td class="text-center">{{$i+1}}</td>
                        <td><strong>{{$p->title}}</strong><br><small class="text-muted">{{substr(strip_tags($p->content),0,60)}}...</small></td>
                        <td><code>/{{$p->slug}}</code></td>
                        <td class="text-center"><span class="badge bg-{{$p->status=='published'?'success':'warning'}}">{{ucfirst($p->status)}}</span></td>
                        <td class="text-center">@if($p->is_system)<span class="badge bg-info">System</span>@else<span class="badge bg-secondary">Custom</span>@endif</td>
                        <td class="text-center"><small>{{$p->updated_at->format('d M Y')}}</small></td>
                        <td class="text-center">
                            <div class="btn-group"><button class="btn btn-sm btn-info btn-preview" data-title="{{$p->title}}" data-slug="{{$p->slug}}" data-content="{{$p->content}}" data-status="{{$p->status}}"><i class="fas fa-eye"></i></button>@if($p->is_system==0)<button class="btn btn-sm btn-warning btn-edit" data-id="{{$p->id}}" data-title="{{$p->title}}" data-slug="{{$p->slug}}" data-content="{{$p->content}}" data-status="{{$p->status}}"><i class="fas fa-edit"></i></button>
                                <form action="{{route('admin.halaman.destroy',$p->id)}}" method="POST" style="display:inline">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash"></i></button></form>@else<button class="btn btn-sm btn-secondary" disabled><i class="fas fa-lock"></i></button>@endif
                            </div>
                        </td>
                    </tr>@endforeach</tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="{{route('admin.halaman.store')}}">@csrf<div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Halaman</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><label>Judul Halaman</label><input type="text" name="title" class="form-control mb-2" required><label>Slug</label><input type="text" name="slug" class="form-control mb-2" placeholder="tanpa spasi" required><label>Konten</label><textarea name="content" class="form-control mb-2" rows="4"></textarea><label>Status</label><select name="status" class="form-select mb-2">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select></div>
                <div class="modal-footer"><button class="btn btn-primary" type="submit">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="" id="editForm">@csrf @method('PUT')<input type="hidden" name="id" id="edit-id">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Edit Halaman</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><label>Judul</label><input type="text" name="title" id="edit-title" class="form-control mb-2" required><label>Slug</label><input type="text" name="slug" id="edit-slug" class="form-control mb-2" required><label>Konten</label><textarea name="content" id="edit-content" class="form-control mb-2" rows="4"></textarea><label>Status</label><select name="status" id="edit-status" class="form-select mb-2">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select></div>
                <div class="modal-footer"><button type="submit" class="btn btn-warning">Simpan Perubahan</button></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Preview Halaman</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h4 id="preview-title"></h4>
                <p><code id="preview-slug"></code></p>
                <hr>
                <div id="preview-content" class="mb-3"></div><span id="preview-status" class="badge"></span>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        @if(session('success')) Swal.fire({
            icon: 'success',
            title: '{{session('
            success ')}}',
            showConfirmButton: false,
            timer: 1500
        });
        @endif
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.onclick = () => {
                const id = btn.dataset.id;
                document.getElementById('edit-id').value = id;
                document.getElementById('editForm').action = '/admin/halaman/' + id;
                document.getElementById('edit-title').value = btn.dataset.title;
                document.getElementById('edit-slug').value = btn.dataset.slug;
                document.getElementById('edit-content').value = btn.dataset.content;
                document.getElementById('edit-status').value = btn.dataset.status;
                new bootstrap.Modal(document.getElementById('editModal')).show();
            };
        });
        document.querySelectorAll('.btn-preview').forEach(btn => {
            btn.onclick = () => {
                document.getElementById('preview-title').textContent = btn.dataset.title;
                document.getElementById('preview-slug').textContent = '/' + btn.dataset.slug;
                document.getElementById('preview-content').innerHTML = btn.dataset.content;
                let badge = document.getElementById('preview-status');
                badge.textContent = btn.dataset.status;
                badge.className = 'badge ' + (btn.dataset.status === 'published' ? 'bg-success' : 'bg-warning');
                new bootstrap.Modal(document.getElementById('previewModal')).show();
            };
        });
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.onclick = e => {
                e.preventDefault();
                const form = btn.closest('form');
                Swal.fire({
                    title: 'Hapus Halaman?',
                    text: 'Tindakan ini tidak bisa dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then(r => {
                    if (r.isConfirmed) form.submit();
                });
            };
        });
    });
</script>
<style>
    .table th,
    .table td {
        padding: 10px 8px;
        font-size: 0.9rem;
    }

    code {
        background: #f8f9fa;
        padding: 3px 6px;
        border-radius: 4px;
        color: #e83e8c;
    }

    .card-header {
        background-color: #f8f9fa;
    }

    .table {
        margin-bottom: 0;
        border-collapse: separate !important;
        border-spacing: 0 6px;
    }

    .table th {
        background-color: #f8f9fa;
        padding: 14px 12px;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
    }

    .table td {
        background: #fff;
        padding: 14px 12px;
        vertical-align: middle;
        border-top: 1px solid #dee2e6;
    }

    .table-hover tbody tr:hover td {
        background-color: #f1f3f6;
    }

    .table-responsive {
        padding: 15px 20px;
    }

    .btn-group .btn {
        margin: 0 2px;
    }

    code {
        background: #f8f9fa;
        padding: 3px 6px;
        border-radius: 4px;
        color: #e83e8c;
        font-size: 0.85rem;
    }

    .badge {
        font-size: 0.8rem;
        padding: 6px 10px;
    }

    .card-header {
        background-color: #f8f9fa;
        padding: 15px 20px;
    }

    .card.border-left-primary,
    .card.border-left-success,
    .card.border-left-warning,
    .card.border-left-info {
        border-left-width: 5px !important;
    }

    .card-body {
        padding: 1rem 0.75rem !important;
    }

    .card-body small {
        font-size: 0.85rem;
    }
</style>
@endsection

@section('scripts')
<script>
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
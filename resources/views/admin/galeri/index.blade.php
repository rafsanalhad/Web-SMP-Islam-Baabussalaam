@extends('layouts.admin')
@section('title', 'Kelola Galeri')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0 text-gray-800"><i class="fas fa-images me-2 text-primary"></i>Kelola Galeri</h1>
    <button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus me-1"></i> Tambah Gambar</button>
</div>
<div class="row mb-4 gy-3">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stat-card shadow-sm border-0 text-center h-100">
            <div class="card-body py-3"><i class="fas fa-images fa-2x text-primary mb-2"></i>
                <h5 class="fw-bold mb-0">{{$gallery->count()}}</h5><small class="text-muted">Total Gambar</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stat-card shadow-sm border-0 text-center h-100">
            <div class="card-body py-3"><i class="fas fa-calendar-alt fa-2x text-warning mb-2"></i>
                <h5 class="fw-bold mb-0">{{$gallery->where('category','event')->count()}}</h5><small class="text-muted">Acara</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stat-card shadow-sm border-0 text-center h-100">
            <div class="card-body py-3"><i class="fas fa-building fa-2x text-success mb-2"></i>
                <h5 class="fw-bold mb-0">{{$gallery->where('category','facility')->count()}}</h5><small class="text-muted">Fasilitas</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stat-card shadow-sm border-0 text-center h-100">
            <div class="card-body py-3"><i class="fas fa-trophy fa-2x text-danger mb-2"></i>
                <h5 class="fw-bold mb-0">{{$gallery->where('category','achievement')->count()}}</h5><small class="text-muted">Prestasi</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stat-card shadow-sm border-0 text-center h-100">
            <div class="card-body py-3"><i class="fas fa-users fa-2x text-info mb-2"></i>
                <h5 class="fw-bold mb-0">{{$gallery->where('category','activity')->count()}}</h5><small class="text-muted">Kegiatan</small>
            </div>
        </div>
    </div>
</div>
<style>
    .stat-card {
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .stat-card i {
        display: block;
        margin-bottom: .5rem;
    }

    .stat-card h5 {
        font-size: 1.1rem;
    }

    .stat-card small {
        color: #6c757d;
        font-size: 0.85rem;
    }

    @media (min-width:1200px) {
        .col-xl-2 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }

    .table {
        border-spacing: 0 6px !important;
    }

    .table td,
    .table th {
        vertical-align: middle !important;
        padding: 0.45rem 0.5rem !important;
    }

    .table thead th {
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.4rem;
        border-radius: 6px;
    }

    .btn-group-sm .btn i {
        font-size: 0.75rem;
    }

    .card-body {
        background-color: #fff;
        border-radius: 0 0 0.5rem 0.5rem;
    }

    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
<div class="card shadow-sm">
    <div class="card-body p-2">
        <div class="table-responsive">
            <table id="galleryTable" class="table table-sm table-hover align-middle mb-0" style="font-size:0.82rem;">
                <thead class="table-light text-center align-middle">
                    <tr>
                        <th width="35">#</th>
                        <th width="70">Gambar</th>
                        <th width="130">Judul</th>
                        <th width="110">Kategori</th>
                        <th>Deskripsi</th>
                        <th width="110">Aksi</th>
                    </tr>
                </thead>
                <tbody>@if($gallery->isEmpty())<tr>
                        <td colspan="6" class="text-center text-muted py-3"><i class="fas fa-image me-1"></i> Belum ada data galeri</td>
                    </tr>@else @foreach($gallery as $i=>$g)<tr>
                        <td class="text-center">{{$i+1}}</td>
                        <td class="text-center">@if($g->image)<img src="{{asset('assets/img/gallery/'.$g->image)}}" class="rounded shadow-sm" style="width:55px;height:45px;object-fit:cover;">@else<div class="bg-light text-muted small rounded d-flex align-items-center justify-content-center" style="width:55px;height:45px;">No Img</div>@endif</td>
                        <td class="text-truncate" style="max-width:120px;"><strong>{{$g->title}}</strong></td>
                        <td class="text-center"><span class="badge bg-secondary px-2 py-1" style="font-size:0.7rem;">{{$g->category}}</span></td>
                        <td class="small text-muted text-truncate" style="max-width:220px;">{{$g->description}}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group"><button class="btn btn-outline-info btn-preview" title="Lihat" data-image="{{asset('assets/img/gallery/'.$g->image)}}" data-title="{{$g->title}}" data-category="{{$g->category}}" data-description="{{$g->description}}"><i class="fas fa-eye"></i></button><button class="btn btn-outline-warning btn-edit" title="Edit" data-id="{{$g->id}}" data-title="{{$g->title}}" data-description="{{$g->description}}" data-category="{{$g->category}}" data-image="{{$g->image}}"><i class="fas fa-edit"></i></button>
                                <form action="{{route('admin.galeri.destroy',$g->id)}}" method="POST" style="display:inline">@csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger btn-delete" title="Hapus"><i class="fas fa-trash"></i></button></form>
                            </div>
                        </td>
                    </tr>@endforeach @endif</tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="{{route('admin.galeri.store')}}" enctype="multipart/form-data">@csrf<div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Gambar</h5><button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><label>Judul</label><input type="text" name="title" class="form-control mb-2" required><label>Deskripsi</label><textarea name="description" class="form-control mb-2" rows="3"></textarea><label>Kategori</label><select name="category" class="form-select mb-2">
                        <option value="event">Acara</option>
                        <option value="facility">Fasilitas</option>
                        <option value="achievement">Prestasi</option>
                        <option value="activity">Kegiatan</option>
                    </select><label>Gambar</label><input type="file" name="image" class="form-control mb-2" accept="image/*" required></div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="" id="editForm" enctype="multipart/form-data">@csrf @method('PUT')<input type="hidden" name="id" id="edit-id">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Edit Gambar</h5><button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><label>Judul</label><input type="text" name="title" id="edit-title" class="form-control mb-2" required><label>Deskripsi</label><textarea name="description" id="edit-description" class="form-control mb-2" rows="3"></textarea><label>Kategori</label><select name="category" id="edit-category" class="form-select mb-2">
                        <option value="event">Acara</option>
                        <option value="facility">Fasilitas</option>
                        <option value="achievement">Prestasi</option>
                        <option value="activity">Kegiatan</option>
                    </select><label>Gambar (opsional)</label><input type="file" name="image" class="form-control mb-2" accept="image/*">
                    <div id="edit-preview" class="text-center mt-2"></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-warning">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Preview Gambar</h5><button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center"><img id="preview-img" src="" class="img-fluid rounded mb-3" style="max-height:420px;">
                <h5 id="preview-title"></h5><span id="preview-category" class="badge bg-secondary mb-3"></span>
                <p id="preview-description" class="text-muted"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
console.log('Galeri scripts section loaded');
console.log('jQuery available:', typeof jQuery !== 'undefined');
console.log('DataTables available:', typeof $.fn.DataTable !== 'undefined');

$(document).ready(function() {
    console.log('Document ready fired');
    
    // SweetAlert notifications
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '{{ session("success") }}',
        showConfirmButton: false,
        timer: 1500
    });
    @endif

    // Edit button handler
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            document.getElementById('edit-id').value = id;
            document.getElementById('editForm').action = '/admin/galeri/' + id;
            document.getElementById('edit-title').value = this.dataset.title;
            document.getElementById('edit-description').value = this.dataset.description;
            document.getElementById('edit-category').value = this.dataset.category;
            const img = this.dataset.image;
            document.getElementById('edit-preview').innerHTML = img ? `<img src="/assets/img/gallery/${img}" class="img-fluid rounded" style="max-height:120px;">` : `<small class="text-muted">Tidak ada gambar</small>`;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    });

    // Preview button handler
    document.querySelectorAll('.btn-preview').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('preview-img').src = this.dataset.image;
            document.getElementById('preview-title').textContent = this.dataset.title;
            document.getElementById('preview-category').textContent = this.dataset.category;
            document.getElementById('preview-description').textContent = this.dataset.description;
            new bootstrap.Modal(document.getElementById('previewModal')).show();
        });
    });

    // Delete button handler
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // DataTable initialization
    console.log('Attempting DataTable init...');
    console.log('Table exists:', $('#galleryTable').length);
    
    if ($('#galleryTable').length > 0) {
        try {
            if (!$.fn.DataTable.isDataTable('#galleryTable')) {
                $('#galleryTable').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                    },
                    pageLength: 10,
                    order: [[0, 'asc']]
                });
                console.log('DataTable initialized successfully!');
            } else {
                console.log('DataTable already initialized');
            }
        } catch(err) {
            console.error('DataTable init error:', err);
        }
    } else {
        console.error('Table #galleryTable not found!');
    }
});
</script>
@endsection
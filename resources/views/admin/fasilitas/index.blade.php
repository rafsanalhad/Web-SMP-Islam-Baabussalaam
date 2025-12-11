@extends('layouts.admin')
@section('title', 'Kelola Fasilitas')
@section('content')
<style>
    .facility-img {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        display: block;
        margin: auto;
    }

    .preview-img {
        display: block;
        margin: auto;
        max-width: 100%;
        border-radius: 8px;
    }
</style>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="text-primary fw-bold"><i class="fas fa-building"></i> Kelola Fasilitas</h5>
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus"></i> Tambah</button>
</div>
<div class="row mb-4">
    @php
    $cards=[["Ruang Kelas","class","primary","chalkboard"],["Laboratorium","lab","success","flask"],["Olahraga","sport","warning","running"],["Lainnya","other","info","building"]];
    foreach($cards as $c):
    @endphp
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-{{$c[2]}} shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-{{$c[2]}} text-uppercase mb-1">{{$c[0]}}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$facilities->where('category',$c[1])->count()}}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-{{$c[3]}} fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    @php endforeach; @endphp
</div>
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle table-sm text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Fitur</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>@if($facilities->isEmpty())<tr>
                        <td colspan="7" class="text-muted py-3">Belum ada data fasilitas</td>
                    </tr>@else @foreach($facilities as $no=>$f)<tr>
                        <td>{{$no+1}}</td>
                        <td>@if($f->image)<img src="{{asset('assets/img/facilities/'.$f->image)}}" class="facility-img">@else<span class="text-muted small fst-italic">Tidak ada</span>@endif</td>
                        <td class="text-start">{{$f->name}}</td>
                        <td>{{ucfirst($f->category)}}</td>
                        <td>{{substr($f->description,0,50)}}...</td>
                        <td>{{$f->features}}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1"><button class="btn btn-info btn-sm btn-preview" data-name="{{$f->name}}" data-category="{{$f->category}}" data-description="{{$f->description}}" data-features="{{$f->features}}" data-image="{{$f->image}}"><i class="fas fa-eye"></i></button><button class="btn btn-warning btn-sm btn-edit" data-id="{{$f->id}}" data-name="{{$f->name}}" data-category="{{$f->category}}" data-description="{{$f->description}}" data-features="{{$f->features}}" data-image="{{$f->image}}"><i class="fas fa-edit"></i></button>
                                <form action="{{route('admin.fasilitas.destroy',$f->id)}}" method="POST" style="display:inline">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus fasilitas ini?')"><i class="fas fa-trash"></i></button></form>
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
            <form method="post" action="{{route('admin.fasilitas.store')}}" enctype="multipart/form-data">@csrf<div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title">Tambah Fasilitas</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><label class="form-label">Nama Fasilitas</label><input type="text" name="name" class="form-control form-control-sm mb-2" required><label class="form-label">Kategori</label><select name="category" class="form-select form-select-sm mb-2">
                        <option value="class">Ruang Kelas</option>
                        <option value="lab">Laboratorium</option>
                        <option value="sport">Olahraga</option>
                        <option value="other">Lainnya</option>
                    </select><label class="form-label">Deskripsi</label><textarea name="description" class="form-control form-control-sm mb-2" rows="2"></textarea><label class="form-label">Fitur</label><input type="text" name="features" class="form-control form-control-sm mb-2" placeholder="Pisahkan dengan koma"><label class="form-label">Gambar</label><input type="file" name="image" class="form-control form-control-sm"></div>
                <div class="modal-footer py-2"><button type="submit" class="btn btn-primary btn-sm">Tambah</button></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="" id="editForm" enctype="multipart/form-data">@csrf @method('PUT')<div class="modal-header bg-warning text-white py-2">
                    <h6 class="modal-title">Edit Fasilitas</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><input type="hidden" name="id" id="edit-id"><label class="form-label">Nama Fasilitas</label><input type="text" name="name" id="edit-name" class="form-control form-control-sm mb-2" required><label class="form-label">Kategori</label><select name="category" id="edit-category" class="form-select form-select-sm mb-2">
                        <option value="class">Ruang Kelas</option>
                        <option value="lab">Laboratorium</option>
                        <option value="sport">Olahraga</option>
                        <option value="other">Lainnya</option>
                    </select><label class="form-label">Deskripsi</label><textarea name="description" id="edit-description" class="form-control form-control-sm mb-2" rows="2"></textarea><label class="form-label">Fitur</label><input type="text" name="features" id="edit-features" class="form-control form-control-sm mb-2"><label class="form-label">Gambar</label><input type="file" name="image" class="form-control form-control-sm mb-2">
                    <div id="edit-preview" class="text-center"></div>
                </div>
                <div class="modal-footer py-2"><button type="submit" class="btn btn-warning btn-sm">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h6 class="modal-title">Detail Fasilitas</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center"><img id="preview-img" src="" class="preview-img mb-3">
                <h5 id="preview-name"></h5>
                <p><span id="preview-category" class="badge bg-secondary"></span></p>
                <p id="preview-description" class="small text-muted"></p>
                <div id="preview-features" class="text-start d-inline-block"></div>
            </div>
            <div class="modal-footer py-2"><button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success')) Swal.fire({
        icon: 'success',
        title: '{{session('
        success ')}}',
        showConfirmButton: false,
        timer: 1200
    });
    @endif
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.onclick = () => {
            const id = btn.dataset.id;
            document.getElementById('edit-id').value = id;
            document.getElementById('editForm').action = '/admin/fasilitas/' + id;
            document.getElementById('edit-name').value = btn.dataset.name;
            document.getElementById('edit-category').value = btn.dataset.category;
            document.getElementById('edit-description').value = btn.dataset.description;
            document.getElementById('edit-features').value = btn.dataset.features;
            const preview = document.getElementById('edit-preview');
            preview.innerHTML = btn.dataset.image ? `<img src='/assets/img/facilities/${btn.dataset.image}' width='100' class='rounded shadow-sm mt-2'>` : `<span class='text-muted small'>Tidak ada gambar</span>`;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        };
    });
    document.querySelectorAll('.btn-preview').forEach(btn => {
        btn.onclick = () => {
            document.getElementById('preview-name').textContent = btn.dataset.name;
            document.getElementById('preview-category').textContent = btn.dataset.category;
            document.getElementById('preview-description').textContent = btn.dataset.description;
            document.getElementById('preview-img').src = btn.dataset.image ? `/assets/img/facilities/${btn.dataset.image}` : '';
            const f = btn.dataset.features.split(',');
            document.getElementById('preview-features').innerHTML = f.map(x => `<p class='mb-0'><i class='fas fa-check text-success me-1'></i>${x.trim()}</p>`).join('');
            new bootstrap.Modal(document.getElementById('previewModal')).show();
        };
    });
</script>
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
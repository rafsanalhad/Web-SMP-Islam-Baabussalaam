@extends('layouts.admin')
@section('title', 'Kelola Pengguna')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-table me-2"></i>Daftar Pengguna</h6>
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-user-plus me-1"></i>Tambah Pengguna</button>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{session('
        success ')}}',
        showConfirmButton: false,
        timer: 1500
    });
</script>
@endif
<div class="row g-3 mb-4">
    @php
    $cards=[
    ["Total Pengguna","primary","fa-users",$users->count()],
    ["Active Users","success","fa-user-check",$users->where('status','active')->count()],
    ["Inactive Users","secondary","fa-user-times",$users->where('status','inactive')->count()],
    ["Admin","warning","fa-crown",$users->where('role','admin')->count()],
    ["Editor","info","fa-edit",$users->where('role','editor')->count()]
    ];
    foreach($cards as $c):
    @endphp
    <div class="col-6 col-md-4 col-lg-3 col-xl-2">
        <div class="card stat-card border-0 shadow-sm h-100 rounded-3 bg-gradient bg-{{$c[1]}} bg-opacity-10 position-relative overflow-hidden">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div class="text-{{$c[1]}} fw-semibold text-uppercase small mb-2">{{$c[0]}}</div>
                <div class="h3 fw-bold text-{{$c[1]}}">{{$c[3]}}</div><i class="fas {{$c[2]}} fa-3x text-{{$c[1]}} opacity-25 position-absolute top-50 end-0 translate-middle-y me-3"></i>
            </div>
        </div>
    </div>
    @php endforeach; @endphp
</div>
<style>
    .stat-card {
        transition: all 0.3s ease-in-out;
        min-height: 120px;
    }

    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .stat-card .card-body {
        position: relative;
        z-index: 1;
    }

    .stat-card i {
        pointer-events: none;
    }

    @media (max-width:768px) {
        .stat-card {
            text-align: center;
        }

        .stat-card i {
            position: static !important;
            opacity: 0.4;
            margin-top: 5px;
        }
    }
</style>
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light py-3"></div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle small">
                <thead class="table-light text-center">
                    <tr>
                        <th width="50">No</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th width="90">Role</th>
                        <th width="90">Status</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>@if($users->isEmpty())<tr>
                        <td colspan="7" class="text-center text-muted py-3">Belum ada pengguna</td>
                    </tr>@else @foreach($users as $no=>$u)<tr>
                        <td class="text-center">{{$no+1}}</td>
                        <td><strong>{{$u->username}}</strong></td>
                        <td>{{$u->fullname}}</td>
                        <td>{{$u->email}}</td>
                        <td class="text-center"><span class="badge bg-{{$u->role=='admin'?'warning':'info'}}">{{ucfirst($u->role)}}</span></td>
                        <td class="text-center"><span class="badge bg-{{$u->status=='active'?'success':'secondary'}}">{{ucfirst($u->status)}}</span></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group"><button class="btn btn-warning btn-edit" data-id="{{$u->id}}" data-username="{{$u->username}}" data-fullname="{{$u->fullname}}" data-email="{{$u->email}}" data-role="{{$u->role}}" data-status="{{$u->status}}"><i class="fas fa-edit"></i></button>@if($u->id!=auth()->id())<form action="{{route('admin.users.destroy',$u->id)}}" method="POST" style="display:inline">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-delete"><i class="fas fa-trash"></i></button></form>@else<button class="btn btn-secondary" disabled><i class="fas fa-user"></i></button>@endif</div>
                        </td>
                    </tr>@endforeach @endif</tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="{{route('admin.users.store')}}">@csrf<div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title">Tambah Pengguna</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><label>Username</label><input type="text" name="username" class="form-control form-control-sm mb-2" required><label>Nama Lengkap</label><input type="text" name="fullname" class="form-control form-control-sm mb-2" required><label>Email</label><input type="email" name="email" class="form-control form-control-sm mb-2" required><label>Password</label><input type="password" name="password" class="form-control form-control-sm mb-2" required>
                    <div class="row">
                        <div class="col-6"><label>Role</label><select name="role" class="form-select form-select-sm mb-2">
                                <option value="admin">Admin</option>
                                <option value="editor" selected>Editor</option>
                            </select></div>
                        <div class="col-6"><label>Status</label><select name="status" class="form-select form-select-sm mb-2">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select></div>
                    </div>
                </div>
                <div class="modal-footer py-2"><button type="submit" class="btn btn-primary btn-sm">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="" id="editForm">@csrf @method('PUT')<input type="hidden" name="id" id="edit-id">
                <div class="modal-header bg-warning text-white py-2">
                    <h6 class="modal-title">Edit Pengguna</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><label>Username</label><input type="text" id="edit-username" class="form-control form-control-sm mb-2" readonly><label>Nama Lengkap</label><input type="text" name="fullname" id="edit-fullname" class="form-control form-control-sm mb-2" required><label>Email</label><input type="email" name="email" id="edit-email" class="form-control form-control-sm mb-2" required>
                    <div class="row">
                        <div class="col-6"><label>Role</label><select name="role" id="edit-role" class="form-select form-select-sm mb-2">
                                <option value="admin">Admin</option>
                                <option value="editor">Editor</option>
                            </select></div>
                        <div class="col-6"><label>Status</label><select name="status" id="edit-status" class="form-select form-select-sm mb-2">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select></div>
                    </div>
                </div>
                <div class="modal-footer py-2"><button type="submit" class="btn btn-warning btn-sm">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            document.getElementById('edit-id').value = id;
            document.getElementById('editForm').action = '/admin/users/' + id;
            document.getElementById('edit-username').value = btn.dataset.username;
            document.getElementById('edit-fullname').value = btn.dataset.fullname;
            document.getElementById('edit-email').value = btn.dataset.email;
            document.getElementById('edit-role').value = btn.dataset.role;
            document.getElementById('edit-status').value = btn.dataset.status;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    });
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            const form = btn.closest('form');
            Swal.fire({
                title: 'Yakin hapus pengguna ini?',
                text: 'Tindakan ini tidak dapat dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((res) => {
                if (res.isConfirmed) {
                    form.submit();
                }
            });
        });
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
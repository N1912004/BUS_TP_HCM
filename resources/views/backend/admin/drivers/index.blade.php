@extends('backend.admin.index_admin')

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title mb-0">Quản lý tài xế</h4>
    <div>
        <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-2"></i> Thêm tài xế
        </a>
    </div>
</div>

<div class="card card-table">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-white">
                    <tr>
                        <th>Họ tên</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Bằng lái</th>
                        <th>Tuyến xe</th>
                        <th>Tên đăng nhập</th>
                        <th>Mật khẩu</th>
                        <th style="width:100px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($drivers as $driver)
                    <tr>
                        <td>{{ $driver->fullname }}</td>
                        <td>{{ $driver->birthday }}</td>
                        <td>{{ $driver->gender }}</td>
                        <td>{{ $driver->address }}</td>
                        <td>{{ $driver->phone_number }}</td>
                        <td>{{ $driver->license_number }}</td>
                        <td>{{ $driver->busRoute->name ?? 'N/A' }}</td>
                        <td>{{ $driver->username }}</td>
                        <td>{{ $driver->password_plain ?? '••••••' }}</td>
                        <td>
                            <a href="{{ route('admin.drivers.edit', $driver->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.drivers.destroy', $driver->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa tài xế này?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            Danh sách tài xế sẽ hiển thị ở đây.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

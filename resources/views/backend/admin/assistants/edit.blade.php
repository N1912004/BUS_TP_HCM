@extends('backend.admin.index_admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title mb-0">Chỉnh sửa Phụ Xe</h4>
    <div>
        <a href="{{ route('admin.assistants.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-2"></i> Quay lại danh sách
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.assistants.update', $assistant->id) }}" method="POST">
            @csrf
            @method('PUT')
            {{-- Họ và tên --}}
            <div class="mb-3">
                <label for="fullname" class="form-label">Họ và Tên</label>
                <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                       id="fullname" name="fullname" value="{{ old('fullname', $assistant->fullname) }}" required>
                @error('fullname')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Ngày sinh --}}
            <div class="mb-3">
                <label for="birthday" class="form-label">Ngày sinh</label>
                <input type="date" class="form-control @error('birthday') is-invalid @enderror"
                       id="birthday" name="birthday" value="{{ old('birthday', $assistant->birthday ? \Carbon\Carbon::parse($assistant->birthday)->format('Y-m-d') : '') }}" required>
                @error('birthday')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Giới tính --}}
            <div class="mb-3">
                <label for="gender" class="form-label">Giới tính</label>
                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                    <option value="">-- Chọn giới tính --</option>
                    <option value="Nam" {{ old('gender', $assistant->gender) == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ old('gender', $assistant->gender) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Địa chỉ --}}
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control @error('address') is-invalid @enderror"
                       id="address" name="address" value="{{ old('address', $assistant->address) }}" required>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Số điện thoại --}}
            <div class="mb-3">
                <label for="phone_number" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                       id="phone_number" name="phone_number" value="{{ old('phone_number', $assistant->phone_number) }}" required>
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tuyến xe --}}
            <div class="mb-3">
                <label for="bus_route_id" class="form-label">Tuyến xe</label>
                <select class="form-select @error('bus_route_id') is-invalid @enderror" id="bus_route_id" name="bus_route_id" required>
                    <option value="">-- Chọn tuyến xe --</option>
                    @foreach($busRoutes as $route)
                        <option value="{{ $route->id }}" {{ old('bus_route_id', $assistant->bus_route_id) == $route->id ? 'selected' : '' }}>
                            {{ $route->ma_tuyen }} - {{ $route->diem_di }} đến {{ $route->diem_den }} ({{ \Carbon\Carbon::parse($route->thoi_gian_bat_dau)->format('H:i') }} - {{ \Carbon\Carbon::parse($route->thoi_gian_ket_thuc)->format('H:i') }})
                        </option>
                    @endforeach
                </select>
                @error('bus_route_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tên đăng nhập --}}
            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror"
                       id="username" name="username" value="{{ old('username', $assistant->username) }}" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-2"></i> Cập nhật Phụ Xe
            </button>
        </form>
    </div>
</div>
@endsection

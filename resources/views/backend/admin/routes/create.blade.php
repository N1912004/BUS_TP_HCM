@extends('backend.admin.index_admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title mb-0">{{ isset($route) ? 'Sửa Tuyến xe' : 'Thêm Tuyến xe mới' }}</h4>
    <div>
        <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-2"></i> Quay lại danh sách
        </a>
    </div>
</div>
    {{-- Hiển thị lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form thêm/sửa tuyến --}}
    <form action="{{ isset($route) ? route('admin.routes.update', $route->id) : route('admin.routes.store') }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @if(isset($route))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="ma_tuyen" class="form-label">Mã tuyến</label>
            <input type="text" class="form-control" id="ma_tuyen" name="ma_tuyen"
                   value="{{ old('ma_tuyen', $route->ma_tuyen ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="diem_di" class="form-label">Điểm đi</label>
            <input type="text" class="form-control" id="diem_di" name="diem_di"
                   value="{{ old('diem_di', $route->diem_di ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="diem_den" class="form-label">Điểm đến</label>
            <input type="text" class="form-control" id="diem_den" name="diem_den"
                   value="{{ old('diem_den', $route->diem_den ?? '') }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="thoi_gian_bat_dau" class="form-label">Thời gian bắt đầu</label>
                <input type="time" class="form-control" id="thoi_gian_bat_dau" name="thoi_gian_bat_dau"
                       value="{{ old('thoi_gian_bat_dau', $route->thoi_gian_bat_dau ?? '') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="thoi_gian_ket_thuc" class="form-label">Thời gian kết thúc</label>
                <input type="time" class="form-control" id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc"
                       value="{{ old('thoi_gian_ket_thuc', $route->thoi_gian_ket_thuc ?? '') }}" required>
            </div>
        </div>

        <div class="text-end">
            <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">{{ isset($route) ? 'Cập nhật tuyến xe' : 'Lưu tuyến xe' }}</button>
        </div>
    </form>
</div>
@endsection

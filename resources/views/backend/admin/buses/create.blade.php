@extends('backend.admin.index_admin')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title">Thêm xe mới</h4>
    <a href="{{ route('admin.buses.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>

  <div class="card">
    <div class="card-body">
      <form action="{{ route('admin.buses.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label>Biển số</label>
          <input type="text" name="bus_number" class="form-control" value="{{ old('bus_number') }}" required>
          @error('bus_number') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label>{{ __('messages.bus_model') }}</label>
            <input type="text" name="model" class="form-control" value="{{ old('model') }}">
          </div>
          <div class="col-md-2 mb-3">
            <label>Năm</label>
            <input type="number" name="year" class="form-control" value="{{ old('year') }}">
          </div>
          <div class="col-md-3 mb-3">
            <label>Sức chứa</label>
            <input type="number" name="capacity" class="form-control" value="{{ old('capacity', 30) }}">
          </div>
          <div class="col-md-3 mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-control">
              <option value="active">Hoạt động</option>
              <option value="maintenance">Bảo trì</option>
              <option value="retired">Ngừng hoạt động</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label>Tuyến</label>
          <select name="bus_route_id" class="form-control">
            <option value="">-- Chọn tuyến --</option>
            @foreach($routes as $rt)
              <option value="{{ $rt->id }}">{{ $rt->ma_tuyen ?? $rt->id }} - {{ $rt->diem_di ?? '' }} → {{ $rt->diem_den ?? '' }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label>Tài xế</label>
          <select name="driver_id" class="form-control">
            <option value="">-- Chọn tài xế --</option>
            @foreach($drivers as $d)
              <option value="{{ $d->id }}">{{ $d->fullname ?? $d->username ?? $d->id }}</option>
            @endforeach
          </select>
        </div>

        <button class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.buses.index') }}" class="btn btn-light">Hủy</a>
      </form>
    </div>
  </div>
</div>
@endsection

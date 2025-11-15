@extends('backend.admin.index_admin')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title">Sửa thông tin xe #{{ $bus->id }}</h4>
    <a href="{{ route('admin.buses.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-body">
      <form action="{{ route('admin.buses.update', $bus->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Biển số -->
        <div class="mb-3">
          <label>Biển số</label>
          <input type="text" name="bus_number" class="form-control" value="{{ old('bus_number', $bus->bus_number) }}" required>
          @error('bus_number') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Model, Năm, Sức chứa, Trạng thái giữ nguyên như cũ -->
        <div class="row">
          <div class="col-md-4 mb-3">
            <label>Model</label>
            <input type="text" name="model" class="form-control" value="{{ old('model', $bus->model) }}">
          </div>

          <div class="col-md-2 mb-3">
            <label>Năm</label>
            <input type="number" name="year" class="form-control" value="{{ old('year', $bus->year) }}">
          </div>

          <div class="col-md-3 mb-3">
            <label>Sức chứa</label>
            <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $bus->capacity ?? 30) }}">
          </div>

          <div class="col-md-3 mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-control">
              <option value="active" {{ old('status', $bus->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
              <option value="maintenance" {{ old('status', $bus->status) == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
              <option value="retired" {{ old('status', $bus->status) == 'retired' ? 'selected' : '' }}>Ngừng hoạt động</option>
            </select>
          </div>
        </div>

        <!-- Tài xế -->
        <div class="mb-3">
          <label>Tài xế</label>
          <select id="driver_id" name="driver_id" class="form-control" onchange="loadRoutesByDriver(this.value)">
            <option value="">-- Chọn tài xế --</option>
            @foreach($drivers as $driver)
              <option value="{{ $driver->id }}" {{ old('driver_id', $bus->driver_id) == $driver->id ? 'selected' : '' }}>
                {{ $driver->fullname ?? $driver->username ?? $driver->id }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Tuyến xe -->
        <div class="mb-3">
          <label for="bus_route_id" class="form-label">Tuyến xe</label>
          <select class="form-select @error('bus_route_id') is-invalid @enderror" id="bus_route_id" name="bus_route_id">
            <option value="">-- Chọn tuyến xe --</option>
            <!-- Tuyến sẽ được load từ JavaScript -->
          </select>
          @error('bus_route_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex gap-2">
          <button class="btn btn-primary">Lưu thay đổi</button>
          <a href="{{ route('admin.buses.index') }}" class="btn btn-light">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>

@section('scripts')
  <script>
 function loadRoutesByDriver(driverId, selectedRouteId = null) {
    const busRouteSelect = document.getElementById('bus_route_id');
    busRouteSelect.innerHTML = '<option value="">-- Chọn tuyến --</option>';

    if (!driverId) return;

    fetch(`{{ route('api.admin.routes.byDriver', ['driverId' => '__DRIVER_ID__']) }}`.replace('__DRIVER_ID__', driverId))
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            data.routes.forEach(route => {
                const option = document.createElement('option');
                option.value = route.id;
                option.textContent = `${route.ma_tuyen} - ${route.diem_di} → ${route.diem_den}`;
                if (selectedRouteId && route.id == selectedRouteId) {
                    option.selected = true;
                }
                busRouteSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching routes:', error));
}

// Initial load if a driver is already selected (e.g., on page load for editing)
document.addEventListener('DOMContentLoaded', function() {
    const driverId = document.getElementById('driver_id').value;
    const selectedBusRouteId = "{{ old('bus_route_id', $bus->bus_route_id) }}";
    if (driverId) {
        loadRoutesByDriver(driverId, selectedBusRouteId);
    }
});
  </script>
@endsection

@endsection

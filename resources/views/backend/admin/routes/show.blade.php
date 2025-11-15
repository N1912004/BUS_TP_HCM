@extends('backend.admin.index_admin')

@section('content')
<div class="container-fluid">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="page-title mb-0">Chi tiết tuyến xe</h4>
            <small class="text-muted">Thông tin chi tiết của tuyến xe {{ $route->ma_tuyen }}</small>
        </div>
        <div>
            <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-2"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Thông tin tuyến xe</h5>
            <hr>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Mã tuyến:</strong></div>
                <div class="col-md-8">{{ $route->ma_tuyen }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Điểm đi:</strong></div>
                <div class="col-md-8">{{ $route->diem_di }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Điểm đến:</strong></div>
                <div class="col-md-8">{{ $route->diem_den }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Thời gian bắt đầu:</strong></div>
                <div class="col-md-8">{{ \Carbon\Carbon::parse($route->thoi_gian_bat_dau)->format('H:i') }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Thời gian kết thúc:</strong></div>
                <div class="col-md-8">{{ \Carbon\Carbon::parse($route->thoi_gian_ket_thuc)->format('H:i') }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Trạng thái:</strong></div>
                <div class="col-md-8">
                    @if($route->is_active)
                        <span class="badge bg-success">Hoạt động</span>
                    @else
                        <span class="badge bg-danger">Không hoạt động</span>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Ngày tạo:</strong></div>
                <div class="col-md-8">{{ $route->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Cập nhật cuối:</strong></div>
                <div class="col-md-8">{{ $route->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

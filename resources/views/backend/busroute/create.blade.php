@extends('backend.layouts.app')

@extends('backend.layouts.app')

@section('title', isset($busRoute) ? 'Sửa tuyến xe' : 'Thêm tuyến xe mới')

@push('styles')
    <link href="{{ asset('backend/css/busroutes.css') }}" rel="stylesheet">
@endpush

@section('content_header')
    <div class="content-header">
        <h4>{{ isset($busRoute) ? 'Sửa tuyến xe' : 'Thêm tuyến xe mới' }}</h4>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ isset($busRoute) ? 'Sửa tuyến xe' : 'Thêm tuyến xe mới' }}</h5>
            </div>
            <div class="ibox-content">
                <form action="{{ isset($busRoute) ? route('admin.busroutes.update', $busRoute->id) : route('admin.busroutes.store') }}" method="POST">
                    @csrf
                    @if(isset($busRoute))
                        @method('PUT')
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="ma_tuyen">Mã tuyến</label>
                        <input type="text" id="ma_tuyen" name="ma_tuyen" class="form-control @error('ma_tuyen') is-invalid @enderror" placeholder="Nhập mã tuyến" value="{{ old('ma_tuyen', $busRoute->ma_tuyen ?? '') }}">
                        @error('ma_tuyen')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="diem_di">Điểm đi</label>
                        <input type="text" id="diem_di" name="diem_di" class="form-control" value="{{ old('diem_di', $busRoute->diem_di ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="diem_den">Điểm đến</label>
                        <input type="text" id="diem_den" name="diem_den" class="form-control" value="{{ old('diem_den', $busRoute->diem_den ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="ngay">Ngày</label>
                        <input type="date" id="ngay" name="ngay" class="form-control" value="{{ old('ngay', $busRoute->ngay ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="thoi_gian_bat_dau">Thời gian bắt đầu</label>
                        <input type="time" id="thoi_gian_bat_dau" name="thoi_gian_bat_dau" class="form-control" value="{{ old('thoi_gian_bat_dau', $busRoute->thoi_gian_bat_dau ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="thoi_gian_ket_thuc">Thời gian kết thúc</label>
                        <input type="time" id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc" class="form-control" value="{{ old('thoi_gian_ket_thuc', $busRoute->thoi_gian_ket_thuc ?? '') }}">
                    </div>
                    <div class="form-actions">
                        <a href="{{ route('admin.busroutes.index') }}" class="btn btn-default">Hủy</a>
                        <button type="submit" class="btn btn-primary">{{ isset($busRoute) ? 'Cập nhật' : 'Thêm mới' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

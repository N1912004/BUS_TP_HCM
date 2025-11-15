@extends('backend.layouts.app')

@section('title', 'Quản lý tuyến xe')

@push('styles')
    <link href="{{ asset('backend/css/busroutes.css') }}" rel="stylesheet">
@endpush

@section('content_header')
    <div class="content-header">
        <h4>Danh sách tuyến xe</h4>
        <a href="{{ route('admin.busroutes.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Thêm tuyến xe</a>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.toggle-status-form').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            var form = $(this);
            var url = form.attr('action');
            var method = form.attr('method');
            var token = form.find('input[name="_token"]').val();
            var is_active_before_toggle = form.find('button').hasClass('btn-outline-warning'); // Check current state

            // Confirmation dialog
            var confirmMessage = is_active_before_toggle ?
                'Bạn có chắc muốn vô hiệu hóa tuyến này không?' :
                'Bạn có chắc muốn kích hoạt tuyến này không?';

            if (!confirm(confirmMessage)) {
                return; // User cancelled
            }

            $.ajax({
                url: url,
                type: method,
                data: {
                    _token: token,
                    _method: 'PUT'
                },
                success: function(response) {
                    if (response.success) {
                        // Update UI
                        var button = form.find('button');
                        var statusBadge = form.closest('tr').find('.badge');

                        if (response.is_active) {
                            button.removeClass('btn-outline-info').addClass('btn-outline-warning');
                            button.attr('title', 'Vô hiệu hóa tuyến xe');
                            button.find('i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
                            statusBadge.removeClass('badge-danger').addClass('badge-success').text('Hoạt động');
                            form.attr('onsubmit', "return confirm('Bạn có chắc muốn vô hiệu hóa tuyến " + response.ma_tuyen + " không?');");
                        } else {
                            button.removeClass('btn-outline-warning').addClass('btn-outline-info');
                            button.attr('title', 'Kích hoạt tuyến xe');
                            button.find('i').removeClass('fa-toggle-on').addClass('fa-toggle-off');
                            statusBadge.removeClass('badge-success').addClass('badge-danger').text('Không hoạt động');
                            form.attr('onsubmit', "return confirm('Bạn có chắc muốn kích hoạt tuyến " + response.ma_tuyen + " không?');");
                        }
                        alert(response.message);
                    } else {
                        alert('Có lỗi xảy ra: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Đã xảy ra lỗi khi cập nhật trạng thái.');
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th>Mã tuyến</th>
                                <th>Điểm đi</th>
                                <th>Điểm đến</th>
                                <th>Thời gian bắt đầu</th>
                                <th>Thời gian kết thúc</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($busRoutes as $busRoute)
                                <tr>
                                    <td>{{ $busRoute->ma_tuyen }}</td>
                                    <td>{{ $busRoute->diem_di }}</td>
                                    <td>{{ $busRoute->diem_den }}</td>
                                    <td>{{ \Carbon\Carbon::parse($busRoute->thoi_gian_bat_dau)->format('H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($busRoute->thoi_gian_ket_thuc)->format('H:i') }}</td>
                                    <td>
                                        @if($busRoute->is_active)
                                            <span class="badge badge-success">Hoạt động</span>
                                        @else
                                            <span class="badge badge-danger">Không hoạt động</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{-- Nút sửa --}}
                                        <a href="{{ route('admin.busroutes.edit', $busRoute->id) }}"
                                            class="btn btn-sm btn-outline-primary me-1" title="Chỉnh sửa tuyến xe"
                                            data-bs-toggle="tooltip">
                                            <i class="fa fa-pencil"></i>
                                        </a>

                                        {{-- Nút xem (redirects to edit for now) --}}
                                        <a href="{{ route('admin.busroutes.show', $busRoute->id) }}"
                                            class="btn btn-sm btn-outline-success me-1" title="Xem chi tiết tuyến xe"
                                            data-bs-toggle="tooltip">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        {{-- Nút ẩn/hiện --}}
                                        <form action="{{ route('admin.busroutes.toggleStatus', $busRoute->id) }}" method="POST"
                                            style="display:inline-block;" class="toggle-status-form">
                                            {{-- Removed onsubmit as it's handled by AJAX now --}}
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm {{ $busRoute->is_active ? 'btn-outline-warning' : 'btn-outline-info' }}"
                                                title="{{ $busRoute->is_active ? 'Vô hiệu hóa tuyến xe' : 'Kích hoạt tuyến xe' }}" data-bs-toggle="tooltip">
                                                <i class="fa {{ $busRoute->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                            </button>
                                        </form>

                                        {{-- Nút xóa --}}
                                        <form action="{{ route('admin.busroutes.destroy', $busRoute->id) }}" method="POST"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa tuyến {{ $busRoute->ma_tuyen }} không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="Xóa tuyến xe" data-bs-toggle="tooltip">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

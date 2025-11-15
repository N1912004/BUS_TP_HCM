@extends('backend.admin.index_admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Quản lý Vé</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a></li>
                            <li><a href="#">Config option 2</a></li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-5 m-b-xs">
                            <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary btn-sm">Thêm Vé Mới</a>
                        </div>
                        <div class="col-sm-4 m-b-xs">
                            <!-- Optional: Filters or bulk actions -->
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" placeholder="Tìm kiếm" class="input-sm form-control"> <span class="input-group-btn">
                                <button type="button" class="btn btn-sm btn-primary"> Tìm!</button> </span>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Người dùng</th>
                                <th>Loại Vé</th>
                                <th>Giá Vé</th>
                                <th>Có Thẻ SV</th>
                                <th>Tuổi</th>
                                <th>Ngày Tạo</th>
                                <th>Hành Động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->user->fullname ?? 'N/A' }}</td>
                                <td>{{ $ticket->ticket_type }}</td>
                                <td>{{ number_format($ticket->price, 0, ',', '.') }} VND</td>
                                <td>
                                    @if($ticket->has_student_card)
                                        <span class="label label-primary">Có</span>
                                    @else
                                        <span class="label label-default">Không</span>
                                    @endif
                                </td>
                                <td>{{ $ticket->age ?? 'N/A' }}</td>
                                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-xs btn-warning">Sửa</a>
                                    <form action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">Xóa</button>
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
    </div>
</div>
@endsection

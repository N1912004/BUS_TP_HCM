@extends('backend.admin.index_admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title mb-0">Quản lý Phụ xe</h4>
    <div>
        <a href="{{ route('admin.assistants.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-2"></i> Thêm phụ xe
        </a>
    </div>
</div>

<div class="card card-table">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-white">
                    <tr>
                        <th style="width:80px">ID</th>
                        <th>Tên phụ xe</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Tuyến xe</th>
                        <th>Tên đăng nhập</th>
                        <th style="width:90px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assistants as $assistant)
                    <tr>
                        <td>{{ $assistant->id }}</td>
                        <td>{{ $assistant->fullname }}</td>
                        <td>{{ $assistant->birthday ? \Carbon\Carbon::parse($assistant->birthday)->format('d/m/Y') : '' }}</td>
                        <td>{{ $assistant->gender }}</td>
                        <td>{{ $assistant->address }}</td>
                        <td>{{ $assistant->phone_number }}</td>
                        <td>
                            @if($assistant->busRoute)
                                {{ $assistant->busRoute->ma_tuyen }} - {{ $assistant->busRoute->diem_di }} đến {{ $assistant->busRoute->diem_den }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $assistant->username }}</td>
                        <td class="action-icons">
                            <a href="{{ route('admin.assistants.edit', $assistant->id) }}" title="Chỉnh sửa">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.assistants.destroy', $assistant->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phụ xe này?');" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline delete" title="Xóa">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Không có phụ xe nào được tìm thấy.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

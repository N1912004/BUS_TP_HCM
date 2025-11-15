@extends('backend.admin.index_admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title">{{ __('messages.bus_management') }}</h4>

    <a href="{{ route('admin.buses.create') }}" class="btn btn-primary">
        + {{ __('messages.add_new_bus') }}
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card card-table">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{{ __('messages.bus_id') }}</th>
                    <th>{{ __('messages.bus_number') }}</th>
                    <th>{{ __('messages.bus_model') }}</th>
                    <th>{{ __('messages.bus_year') }}</th>
                    <th>{{ __('messages.bus_capacity') }}</th>
                    <th>{{ __('messages.bus_driver') }}</th>
                    <th>{{ __('messages.bus_route') }}</th>
                    <th>{{ __('messages.bus_status') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach($buses as $bus)
                <tr>
                    <td>{{ $bus->id }}</td>
                    <td>{{ $bus->bus_number }}</td>
                    <td>{{ $bus->model }}</td>
                    <td>{{ $bus->year }}</td>
                    <td>{{ $bus->capacity }}</td>
                    <td>{{ $bus->driver->fullname ?? __('messages.not_available') }}</td>
                    <td>{{ $bus->busRoute->ma_tuyen ?? __('messages.not_available') }}</td>
                    <td>
                        @if($bus->status == 'active')
                            <span class="badge bg-success">{{ __('messages.status_active') }}</span>
                        @elseif($bus->status == 'maintenance')
                            <span class="badge bg-warning">{{ __('messages.status_maintenance') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ $bus->status }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.buses.edit', $bus->id) }}" class="btn btn-sm btn-warning">{{ __('messages.edit') }}</a>
                        <form action="{{ route('admin.buses.destroy', $bus->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('{{ __('messages.confirm_delete_bus') }}')" class="btn btn-sm btn-danger">
                                {{ __('messages.delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

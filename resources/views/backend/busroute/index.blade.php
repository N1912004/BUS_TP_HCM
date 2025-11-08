@extends('backend.layouts.app')

@section('title', 'Bus Route Management')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Bus Route Management</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        List of Bus Routes
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <p>This is the bus route management page.</p>
                        <div class="row">
                            <div class="col-sm-12">
                                <form action="{{ route('admin.busroutes.index') }}" method="GET" class="form-inline mb-3">
                                    <div class="form-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search bus routes..." value="{{ request('search') }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Route Number</th>
                                        <th>Start Location</th>
                                        <th>End Location</th>
                                        <th>Distance (km)</th>
                                        <th>Duration (minutes)</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($busRoutes as $busRoute)
                                        <tr>
                                            <td>{{ $busRoute->id }}</td>
                                            <td>{{ $busRoute->route_number }}</td>
                                            <td>{{ $busRoute->start_location }}</td>
                                            <td>{{ $busRoute->end_location }}</td>
                                            <td>{{ $busRoute->distance }}</td>
                                            <td>{{ $busRoute->duration }}</td>
                                            <td>{{ $busRoute->created_at }}</td>
                                            <td>{{ $busRoute->updated_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
@endsection

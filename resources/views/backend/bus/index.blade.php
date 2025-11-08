@extends('backend.layouts.app')

@section('title', 'Bus Management')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Bus Management</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        List of Buses
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <p>This is the bus management page.</p>
                        <div class="row">
                            <div class="col-sm-12">
                                <form action="{{ route('admin.buses.index') }}" method="GET" class="form-inline mb-3">
                                    <div class="form-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search buses..." value="{{ request('search') }}">
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
                                        <th>Bus Number</th>
                                        <th>Capacity</th>
                                        <th>Model</th>
                                        <th>Year</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($buses as $bus)
                                        <tr>
                                            <td>{{ $bus->id }}</td>
                                            <td>{{ $bus->bus_number }}</td>
                                            <td>{{ $bus->capacity }}</td>
                                            <td>{{ $bus->model }}</td>
                                            <td>{{ $bus->year }}</td>
                                            <td>{{ $bus->status }}</td>
                                            <td>{{ $bus->created_at }}</td>
                                            <td>{{ $bus->updated_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                           </table>
                       </div>
                       <div class="route-detail-view hidden" id="route-detail-view">
                           <div class="route-detail-header">
                               <button class="back-btn" id="back-to-list-btn"><i class="fas fa-arrow-left"></i></button>
                               <h2 id="route-detail-title"></h2>
                           </div>
                           <div id="map-container" style="width: 100%; height: 400px;"></div>
                           <div class="route-detail-tabs">
                               <button class="tab" id="detail-tab-schedule" data-tab="schedule">Biểu đồ giờ</button>
                               <button class="tab active" id="detail-tab-stops" data-tab="stops">Trạm dừng</button>
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

@section('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obscp+WJc="
        crossorigin=""/>
     <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

    <script>
        var map = L.map('map-container').setView([51.505, -0.09], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // TODO: Fetch route coordinates and display the route on the map
    </script>

    <script>
        // Add event listener to the bus route table
        const busTable = document.querySelector('.table');
        busTable.addEventListener('click', (event) => {
            const target = event.target;

            // Check if the clicked element is a table row
            if (target.tagName === 'TD') {
                const row = target.parentElement;

                // Get the bus ID from the data-bus-id attribute
                const busId = row.querySelector('td:first-child').textContent;

                // Fetch the coordinates from the server
                fetch(`/admin/buses/${busId}/coordinates`)
                    .then(response => response.json())
                    .then(coordinates => {
                        // Display the route on the map
                        // TODO: Parse the coordinates and display the route on the map using Leaflet
                        console.log(coordinates);

                        // Parse the coordinates and create a polyline
                        const polyline = L.polyline(coordinates, { color: 'red' }).addTo(map);

                        // Zoom the map to fit the polyline
                        map.fitBounds(polyline.getBounds());
                    })
                    .catch(error => {
                        console.error('Error fetching coordinates:', error);
                        alert('Error fetching coordinates. Please check the console for details.');
                    });

                // Show the route detail view
                document.getElementById('route-detail-view').classList.remove('hidden');
            }
        });
    </script>
@endsection

@extends('backend.layouts.app')

@section('title', 'Tra Cứu & Tìm Đường')

@section('content')
<style>
    .tab-container {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }
    .tab-button {
        padding: 10px 20px;
        cursor: pointer;
        border: 1px solid #ccc;
        background-color: #f0f0f0;
        font-weight: bold;
        color: #555;
        transition: background-color 0.3s ease;
    }
    .tab-button.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
    .tab-button:first-child {
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
    }
    .tab-button:last-child {
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
    }
    .tab-content {
        display: none;
        padding: 20px;
        border: 1px solid #eee;
        border-radius: 5px;
        background-color: white;
    }
    .tab-content.active {
        display: block;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tra Cứu & Tìm Đường Xe Buýt</h3>
                </div>
                <div class="card-body">
                    <div class="tab-container">
                        <div class="tab-button active" data-tab="search-routes">TRA CỨU</div>
                        <div class="tab-button" data-tab="find-route">TÌM ĐƯỜNG</div>
                    </div>

                    <div id="search-routes" class="tab-content active">
                        {{-- Content for "TRA CỨU" will go here --}}
                        @include('backend.user.bus_route_listing')
                    </div>

                    <div id="find-route" class="tab-content">
                        {{-- Content for "TÌM ĐƯỜNG" will go here --}}
                        @include('backend.user.bus_route_search')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Deactivate all buttons and hide all content
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Activate clicked button and show corresponding content
                this.classList.add('active');
                const targetTab = this.getAttribute('data-tab');
                document.getElementById(targetTab).classList.add('active');
            });
        });
    });
</script>
@endsection

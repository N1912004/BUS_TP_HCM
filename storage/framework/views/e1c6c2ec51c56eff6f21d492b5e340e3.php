<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo e(__('map_route.title')); ?></title>

  <!-- CSS -->
  <link href="<?php echo e(asset('backend/user/style.css')); ?>" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    /* Basic reset and map height */
    body { margin: 0; padding: 0; font-family: sans-serif; }
    #map { height: 100vh; }

    /* Styles from bus_route_listing.blade.php */
    .search-bar {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 25px;
        padding: 8px 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .search-bar input {
        border: none;
        outline: none;
        flex-grow: 1;
        padding: 5px;
        font-size: 16px;
    }
    .search-bar .search-icon {
        margin-right: 10px;
        color: #888;
    }
    .bus-route-list {
        list-style: none;
        padding: 0;
    }
    .bus-route-item {
        display: flex;
        align-items: center;
        background-color: #f9f9f9;
        border: 1px solid #eee;
        border-radius: 8px;
        margin-bottom: 15px;
        padding: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .bus-route-item .icon {
        font-size: 24px;
        color: #007bff;
        margin-right: 15px;
    }
    .bus-route-item .details {
        flex-grow: 1;
    }
    .bus-route-item .route-number {
        font-weight: bold;
        font-size: 1.1em;
        color: #333;
        margin-bottom: 5px;
    }
    .bus-route-item .route-info {
        font-size: 0.9em;
        color: #666;
        margin-bottom: 3px;
    }
    .bus-route-item .route-time,
    .bus-route-item .route-price {
        font-size: 0.85em;
        color: #888;
        display: flex;
        align-items: center;
    }
    .bus-route-item .route-time i,
    .bus-route-item .route-price i {
        margin-right: 5px;
    }
    .bus-route-item .price {
        font-weight: bold;
        color: #28a745;
        margin-left: auto;
        font-size: 1.1em;
    }

    /* Styles from bus_route_search.blade.php (adjusted for sidebar) */
    .find-route-inputs {
      margin-bottom: 15px;
    }
    .find-route-inputs input {
      width: calc(100% - 10px);
      padding: 8px;
      margin-bottom: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    .find-route-inputs button {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }
    .find-route-inputs button:hover {
      background-color: #0056b3;
    }

    /* Styles for header tabs to match image */
    .header-tabs {
      display: flex;
      justify-content: center;
      width: 100%;
      background-color: #fff;
      border-bottom: 1px solid #eee;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .header-tabs .tab {
      flex: 1;
      text-align: center;
      padding: 15px 0;
      font-size: 16px;
      font-weight: bold;
      color: #888;
      background: none;
      border: none;
      cursor: pointer;
      position: relative;
      transition: color 0.3s ease;
    }

    .header-tabs .tab i {
      margin-right: 8px;
      color: #888;
      transition: color 0.3s ease;
    }

    .header-tabs .tab.active {
      color: #28a745; /* Green color from image */
    }

    .header-tabs .tab.active i {
      color: #28a745;
    }

    .header-tabs .tab.active::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 3px;
      background-color: #28a745; /* Green underline from image */
    }

    /* Sidebar & Content Layout */
    .content {
      display: flex;
      height: calc(100vh - 110px); /* Adjust height based on header */
    }
    aside.sidebar {
      width: 320px;
      background: #fff;
      border-right: 1px solid #eee;
      padding: 15px;
      box-sizing: border-box;
      overflow-y: auto;
      position: relative; /* For footer positioning */
      display: flex;
      flex-direction: column;
    }
    .sidebar-footer {
      margin-top: auto; /* Pushes footer to the bottom */
      padding-top: 15px;
      border-top: 1px solid #eee;
      text-align: center;
      font-size: 0.8em;
      color: #888;
    }
    .map-container {
      flex-grow: 1;
      position: relative;
    }
    .route-card {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      background-color: white;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      z-index: 1000;
      width: 300px;
      display: none; /* Hidden by default */
    }
    .route-card h3 {
      margin-top: 0;
      color: #333;
    }
    .route-card p {
      margin-bottom: 5px;
      font-size: 0.9em;
      color: #666;
    }
    .close-card {
      position: absolute;
      top: 10px;
      right: 10px;
      background: none;
      border: none;
      font-size: 1.2em;
      cursor: pointer;
      color: #888;
    }
    .locate-btn {
      position: absolute;
      bottom: 20px;
      right: 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      font-size: 1.2em;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
      z-index: 1000;
    }
    .hidden { display: none; }

    /* Styles for Route Detail View */
    .route-detail-view {
      padding: 15px;
      background-color: #fff;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .route-detail-header {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .route-detail-header .back-btn {
      background: none;
      border: none;
      font-size: 24px;
      margin-right: 10px;
      cursor: pointer;
      color: #333;
    }

    .route-detail-header h2 {
      margin: 0;
      font-size: 20px;
      color: #333;
      flex-grow: 1;
    }

    .route-detail-tabs {
      display: flex;
      margin-bottom: 15px;
      border-bottom: 1px solid #eee;
    }

    .route-detail-tabs .tab {
      flex: 1;
      text-align: center;
      padding: 10px 0;
      font-size: 14px;
      font-weight: bold;
      color: #888;
      background: none;
      border: none;
      cursor: pointer;
      position: relative;
      transition: color 0.3s ease;
    }

    .route-detail-tabs .tab.active {
      color: #28a745;
    }

    .route-detail-tabs .tab.active::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 2px;
      background-color: #28a745;
    }

    .station-list {
      list-style: none;
      padding: 0;
      margin: 0;
      flex-grow: 1;
      overflow-y: auto;
    }

    .station-item {
      display: flex;
      align-items: center;
      padding: 10px 0;
      position: relative;
    }

    .station-item:not(:last-child)::before {
      content: '';
      position: absolute;
      left: 9px; /* Adjust to align with the dot */
      top: 20px;
      bottom: -5px;
      width: 2px;
      background-color: #ccc;
      z-index: 0;
    }

    .station-item .dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background-color: #ccc;
      margin-right: 15px;
      z-index: 1;
    }

    .station-item:first-child .dot {
      background-color: #28a745; /* Green for the first station */
    }

    .station-item:last-child .dot {
      background-color: #28a745; /* Green for the last station */
    }

    .station-item span {
      font-size: 16px;
      color: #333;
    }
  </style>
</head>

<body>
  <!-- HEADER -->
  <header class="busmap-header">
    <div class="header-top">
      <div   class="logo">
        <a href="<?php echo e(url()->current()); ?>" >
        <img src="<?php echo e(asset('backend/logo/logo.png')); ?>" alt="BusMap">
        </a>
        <span><?php echo e(__('map_route.app_name')); ?></span>
      </div>

      <div class="header-right">
        <div class="city-select" id="city-select-btn"><i class="fa fa-city"></i> <span id="current-city-display"><?php echo e(__('map_route.city_name')); ?></span></div>
    
        <div class="lang-select dropdown-toggle" id="lang-dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <a href="<?php echo e(route('lang', 'vi')); ?>" class="<?php echo e(app()->getLocale() == 'vi' ? 'active' : ''); ?>">VI</a>
          <span>|</span>
          <a href="<?php echo e(route('lang', 'en')); ?>" class="<?php echo e(app()->getLocale() == 'en' ? 'active' : ''); ?>">EN</a>
        </div>
        <button 
            class="btn btn-light d-flex align-items-center" 
            type="button" 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
          >
            <span>
                <?php echo e(Auth::user()-> username?? 'Guest'); ?>

            </span>
            <i class="fas fa-sign-out-alt ms-2"></i>
          </button>
        <img src="https://i.pravatar.cc/40" alt="User" class="user-avatar" /> <!-- Keep user avatar, but it's not the dropdown trigger -->
      </div>
    </div>
  </header>
  <form id="logout-form" action="<?php echo e(route('auth.logout')); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
  </form>
  <div class="header-tabs">
    <button class="tab active" id="tab-search" data-tab="search-routes"><i class="fa fa-search"></i> <?php echo e(__('map_route.search')); ?></button>
    <button class="tab" id="tab-route" data-tab="find-route"><i class="fa fa-route"></i> <?php echo e(__('map_route.find_route')); ?></button>
    <button class="tab" id="tab-nearby-stops" data-tab="nearby-routes"><i class="fa fa-map-marker-alt"></i> <?php echo e(__('map_route.nearby_routes')); ?></button>
    <button class="tab" id="toggle-sidebar"><i class="fa fa-bars"></i></button>
  </div>
  <!-- BODY -->
  <div class="content">
    <aside class="sidebar" id="sidebar">
      <!-- TRA CỨU -->
      <div class="tab-content active" id="search-routes">
        <div class="search-bar">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="bus-route-search-input" placeholder="<?php echo e(__('map_route.search_bus_route')); ?>">
        </div>

        <ul class="bus-route-list" id="list-container">
            
        </ul>
      </div>

      <!-- TÌM ĐƯỜNG -->
      <div class="tab-content hidden" id="find-route">
        <h3><?php echo e(__('map_route.find_route')); ?></h3>
        <div class="find-route-inputs">
          <div class="input-with-icon">
            <input type="text" id="start-point" placeholder="<?php echo e(__('map_route.start_point')); ?>" />
            <button id="locate-start-point-btn" class="icon-button" title="<?php echo e(__('map_route.get_current_location')); ?>"><i class="fas fa-crosshairs"></i></button>
          </div>
          <input type="text" id="end-point" placeholder="<?php echo e(__('map_route.end_point')); ?>" />
          <button id="find-route-btn"><i class="fa fa-magnifying-glass"></i> <?php echo e(__('map_route.find_route_button')); ?></button>
        </div>
        <p><i><?php echo e(__('map_route.location_note')); ?></i></p>
      </div>

      <!-- TUYẾN GẦN ĐÂY -->
      <div class="tab-content hidden" id="nearby-routes">
        <h3><?php echo e(__('map_route.nearby_bus_routes')); ?></h3>
        <p><i><?php echo e(__('map_route.nearby_routes_note')); ?></i></p>
        <ul class="bus-route-list" id="nearby-routes-list">
          
        </ul>
      </div>

      <div class="sidebar-footer">
        © 2025 <?php echo e(__('map_route.footer_text')); ?>

      </div>
    </aside>

    <!-- Route Detail View (Hidden by default) -->
    <div class="route-detail-view hidden" id="route-detail-view">
      <div class="route-detail-header">
        <button class="back-btn" id="back-to-list-btn"><i class="fas fa-arrow-left"></i></button>
        <h2 id="route-detail-title"></h2>
      </div>
      <div class="route-detail-content" id="route-detail-content">
        <!-- Content for selected tab will be loaded here -->
        <div class="tab-pane active" id="stops-pane">
          <ul class="station-list" id="station-list">
            <!-- Station items will be dynamically loaded here -->
          </ul>
        </div>
      </div>
    </div>

    <!-- MAP -->
    <main class="map-container">
      <div id="map"></div>
      <!-- Route Info Card -->
      <div class="route-card" id="route-card" style="display: none;">
        <button class="close-card" id="close-card"><i class="fa fa-times"></i></button>
        <h3 id="route-card-title"></h3>
        <p id="route-card-desc"></p>
        <p><strong><?php echo e(__('map_route.from')); ?>:</strong> <span id="route-card-from"></span></p>
        <p><strong><?php echo e(__('map_route.to')); ?>:</strong> <span id="route-card-to"></span></p>
        <p><strong><?php echo e(__('map_route.distance')); ?>:</strong> <span id="route-card-distance"><?php echo e(__('map_route.calculating')); ?></span></p>
        <p><strong><?php echo e(__('map_route.price')); ?>:</strong> <span id="route-card-price"><?php echo e(__('map_route.calculating')); ?></span></p>
      </div>
      <button class="locate-btn" id="locate-btn"><i class="fa fa-location-crosshairs"></i></button>
      <div id="location-message" class="hidden" style="position: absolute; bottom: 70px; right: 20px; background-color: rgba(255, 0, 0, 0.7); color: white; padding: 10px; border-radius: 5px; z-index: 1000;"></div>
    </main>
  </div>

  <!-- JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
  <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
  <script>
    window.AppBaseUrl = "<?php echo e(url('/')); ?>";
    window.translations = {
        'map_route.route_number': '<?php echo e(__("map_route.route_number")); ?>',
        'map_route.metro_line': '<?php echo e(__("map_route.metro_line")); ?>',
        'map_route.calculating': '<?php echo e(__("map_route.calculating")); ?>',
        'map_route.no_nearby_routes': '<?php echo e(__("map_route.no_nearby_routes")); ?>',
        'map_route.no_bus_stops': '<?php echo e(__("map_route.no_bus_stops")); ?>',
        'map_route.cannot_load_bus_stops': '<?php echo e(__("map_route.cannot_load_bus_stops")); ?>',
        'map_route.address_unknown': '<?php echo e(__("map_route.address_unknown")); ?>',
        'map_route.browser_no_geolocation': '<?php echo e(__("map_route.browser_no_geolocation")); ?>',
        'map_route.getting_location': '<?php echo e(__("map_route.getting_location")); ?>',
        'map_route.location_found': '<?php echo e(__("map_route.location_found")); ?>',
        'map_route.cannot_find_address': '<?php echo e(__("map_route.cannot_find_address")); ?>',
        'map_route.location_error': '<?php echo e(__("map_route.location_error")); ?>',
        'map_route.permission_denied': '<?php echo e(__("map_route.permission_denied")); ?>',
        'map_route.position_unavailable': '<?php echo e(__("map_route.position_unavailable")); ?>',
        'map_route.timeout': '<?php echo e(__("map_route.timeout")); ?>',
        'map_route.unknown_error': '<?php echo e(__("map_route.unknown_error")); ?>',
        'map_route.your_location': '<?php echo e(__("map_route.your_location")); ?>',
        'map_route.switched_to_city': '<?php echo e(__("map_route.switched_to_city")); ?>',
        'map_route.invalid_choice': '<?php echo e(__("map_route.invalid_choice")); ?>',
        'map_route.enter_start_end_points': '<?php echo e(__("map_route.enter_start_end_points")); ?>',
        'map_route.error_finding_route': '<?php echo e(__("map_route.error_finding_route")); ?>',
        'map_route.no_coords_for_route': '<?php echo e(__("map_route.no_coords_for_route")); ?>',
        'map_route.error_displaying_route': '<?php echo e(__("map_route.error_displaying_route")); ?>',
        'map_route.route_not_found': '<?php echo e(__("map_route.route_not_found")); ?>',
        'map_route.missing_origin_destination': '<?php echo e(__("map_route.missing_origin_destination")); ?>',
        'map_route.cannot_display_route_missing_points': '<?php echo e(__("map_route.cannot_display_route_missing_points")); ?>',
        'map_route.error_geocoding_routing': '<?php echo e(__("map_route.error_geocoding_routing")); ?>',
        'map_route.no_nearby_bus_stops': '<?php echo e(__("map_route.no_nearby_bus_stops")); ?>',
        'map_route.cannot_load_nearby_bus_stops': '<?php echo e(__("map_route.cannot_load_nearby_bus_stops")); ?>',
        'map_route.select_city': '<?php echo e(__("map_route.select_city")); ?>'
    };
  </script>
  <script src="<?php echo e(asset('backend/user/script.js')); ?>?v=<?php echo e(time()); ?>"></script>
</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/laravelversion1.com/resources/views/backend/user/map_route.blade.php ENDPATH**/ ?>
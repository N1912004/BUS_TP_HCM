// script.js

let busRoutes = []; // Global variable to store bus routes
let nearbyBusRoutes = []; // Global variable to store nearby bus routes
let map = null;
let routingControl = null;
let mapInitialized = false;
let userLocationMarker = null; // Marker for user's current location

// Global variables for city customization
let currentCityName = "TP Hồ Chí Minh"; // Default to Ho Chi Minh City, initialized here to prevent undefined issues
let currentCityCoordinates = [10.776889, 106.700806]; // Default to Ho Chi Minh City

// Function to calculate price based on distance
function calculatePrice(distanceInKm) {
    const pricePerKm = 3000; // 3,000 VNĐ per km
    const maxDistancePrice = 7000; // Max 7,000 VNĐ for the distance component

    let calculatedPrice = Math.min(distanceInKm * pricePerKm, maxDistancePrice);
    // Assuming the 20,000 VNĐ is a fixed base fare that is always applied.
    // If the examples (6k, 3k, 7k) are total prices, then the 20,000 VNĐ might be a misunderstanding or not always applied.
    // For now, I will assume the 20,000 VNĐ is a separate fixed fee, and the distance-based cost is added to it.
    // However, given the examples, it's more likely the 20,000 VNĐ is a general reference and the actual route price is just the distance-based one.
    // I will implement based on the examples (max 7k) and clarify if needed.
    return calculatedPrice; // Return in VNĐ
}

// Function to initialize map
function initializeMap() {
    if (mapInitialized) return;

    map = L.map('map', { zoomControl: true }).setView(currentCityCoordinates, 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    mapInitialized = true;
}

// store polylines to control styling/select
let polylineGroup = {};
let nearbyRoutePolylineGroup = {}; // To store polylines for nearby routes
// activeRoutePolylines is no longer needed for multi-selection in this context

// Function to fetch bus routes from the API
async function fetchBusRoutes() {
  try {
    const response = await fetch(`${window.AppBaseUrl}/api/bus-routes`);
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    console.log('Fetched bus routes data:', data); // Log the fetched data
    busRoutes = data; // Directly assign data for now

    // Ensure map is initialized before rendering list and drawing polylines
    initializeMap();
    renderList();
    // drawPolylines(); // Don't draw all polylines initially, only nearby ones when requested
  } catch (error) {
    console.error('Error fetching bus routes:', error.message, error); // Log more details about the error
  }
}

// Function to fetch nearby bus routes from the API
async function fetchNearbyBusRoutes(lat, lon) {
  try {
    const response = await fetch(`${window.AppBaseUrl}/api/bus-routes/nearby?lat=${lat}&lon=${lon}`);
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    console.log('Fetched nearby bus routes data:', data);
    nearbyBusRoutes = data;
    renderNearbyRoutesList(data);
    displayNearbyRoutePolylines(data);
  } catch (error) {
    console.error('Error fetching nearby bus routes:', error.message, error);
  }
}

// render sidebar list
let listContainer; // Declare globally, initialized in DOMContentLoaded

function showMetroLineDetails(route) {
  // This function is called when Metro Line 1 is clicked.
  // It should display the route detail view.
  showRouteDetailView(route);
}

function renderList(type = 'search-routes', filterText = '') {
  if (!listContainer) {
      listContainer = document.getElementById('list-container');
  }
  if (!listContainer) {
      console.error('list-container element not found.');
      return;
  }
  listContainer.innerHTML = '';
  if (type === 'search-routes') {
    busRoutes.filter(r => (r.name + r.desc).toLowerCase().includes(filterText.toLowerCase()))
      .forEach(r => {
        const el = document.createElement('li');
        el.className = 'bus-route-item';
        el.dataset.id = r.id;
        el.innerHTML = `
            <div class="icon"><i class="fas fa-bus"></i></div>
            <div class="details">
                <div class="route-number">${r.name}</div>
                
            </div>
            <div class="price" id="price-${r.id}"><i class="fas fa-dollar-sign"></i> ${window.translations['map_route.calculating']}</div>
        `;
        // Special handling for Metro Line 1
        if (r.name.includes('Metro 1')) {
            el.onclick = () => showMetroLineDetails(r); // Pass the full route object
        } else {
            if (r.coords && r.coords.length > 0) {
                console.log(`Route ${r.id} has coordinates. Making it clickable.`);
                el.onclick = () => selectRoute(r.id, busRoutes); // Pass busRoutes
            } else {
                console.warn('Route', r.id, 'has no coordinates. Not making it clickable for map selection. Coords:', r.coords);
                el.classList.add('disabled-route-item'); // Optional: Add a class to style non-clickable items
            }
        }
        listContainer.appendChild(el);
      });
  }
}

function renderNearbyRoutesList(routes) {
  const nearbyRoutesList = document.getElementById('nearby-routes-list'); // New list for nearby routes
  if (!nearbyRoutesList) {
      console.error('nearby-routes-list element not found.');
      return;
  }
  nearbyRoutesList.innerHTML = '';
  if (routes.length === 0) {
    nearbyRoutesList.innerHTML = `<li>${window.translations['map_route.no_nearby_routes']}</li>`;
    return;
  }

  routes.forEach(r => {
    const el = document.createElement('li');
    el.className = 'bus-route-item';
    el.dataset.id = r.id;
    el.innerHTML = `
        <div class="icon"><i class="fas fa-bus"></i></div>
            <div class="details">
                <div class="route-number">${r.name}</div>
                <div class="route-info">${r.desc}</div>
            </div>
            <div class="price" id="price-${r.id}"><i class="fas fa-dollar-sign"></i> ${window.translations['map_route.calculating']}</div>
        `;
        if (r.coords && r.coords.length > 0) {
            // Special handling for Metro Line 1 in nearby routes as well
            if (r.name.includes('Metro 1')) {
                el.onclick = () => showRouteDetailView(r); // Pass the full route object
        } else {
            el.onclick = () => selectRoute(r.id, nearbyBusRoutes); // Pass nearbyBusRoutes
        }
    } else {
        el.classList.add('disabled-route-item');
    }
    nearbyRoutesList.appendChild(el);
  });
}

// draw polylines for each route
function clearAllPolylines() {
  // Clear polylines from the main search list (if any were drawn)
  Object.values(polylineGroup).forEach(poly => {
    if (map.hasLayer(poly)) {
      map.removeLayer(poly);
    }
  });
  polylineGroup = {};

  // Clear polylines from the nearby routes list
  Object.values(nearbyRoutePolylineGroup).forEach(poly => {
    if (map.hasLayer(poly)) {
      map.removeLayer(poly);
    }
  });
  nearbyRoutePolylineGroup = {};

  if (routingControl) { // Clear routing control if active
      map.removeControl(routingControl);
      routingControl = null;
  }
}

function displayNearbyRoutePolylines(routes) {
  if (!map) {
    console.error('Map is not initialized. Cannot draw nearby route polylines.');
    return;
  }

  // Clear only previously drawn nearby routes
  Object.values(nearbyRoutePolylineGroup).forEach(poly => {
    if (map.hasLayer(poly)) {
      map.removeLayer(poly);
    }
  });
  nearbyRoutePolylineGroup = {};

  routes.forEach(r => {
    if (r.coords && r.coords.length > 0) {
      const poly = L.polyline(r.coords, { color: '#1e73be', weight: 4, opacity: 0.8 }).addTo(map);
      nearbyRoutePolylineGroup[r.id] = poly;
      poly.on('click', () => selectRoute(r.id, nearbyBusRoutes));
    }
  });

  if (routes.length > 0) {
    const allCoords = routes.flatMap(route => route.coords);
    if (allCoords.length > 0) {
      map.fitBounds(L.latLngBounds(allCoords), { padding: [50, 50] });
    }
  }
}

async function selectRoute(id, sourceRoutes) {
  const route = sourceRoutes.find(r => r.id === id);
  if (!route) {
    console.error('Route not found for ID:', id);
    return;
  }

  // Clear any existing routing control before creating a new one
  if (routingControl) {
      map.removeControl(routingControl);
      routingControl = null;
  }
  // Also clear any simple polylines that might be on the map
  clearAllPolylines();

  // Geocode origin and destination
  const startPointText = route.origin;
  const endPointText = route.destination;

  if (!startPointText || !endPointText) {
      console.error('Route origin or destination is missing for route ID:', id);
      alert(window.translations['map_route.cannot_display_route_missing_points']);
      return;
  }

  try {
    let waypoints = [];
    let usePredefinedCoords = false;

    // Check if route.coords are available and valid
    if (route.coords && Array.isArray(route.coords) && route.coords.length > 1) {
      // Validate that all coordinates are valid [lat, lon] pairs
      const validCoords = route.coords.every(coord =>
        Array.isArray(coord) && coord.length === 2 && typeof coord[0] === 'number' && typeof coord[1] === 'number'
      );

      if (validCoords) {
        waypoints = route.coords.map(coord => L.latLng(coord[0], coord[1]));
        usePredefinedCoords = true;
        console.log('Using predefined coordinates for route ID:', id, 'Waypoints:', waypoints);
      } else {
        console.warn('Route ID:', id, 'has invalid predefined coordinates. Falling back to geocoding.');
      }
    }

    if (!usePredefinedCoords) {
      // If predefined coords are not used or invalid, try geocoding origin and destination
      const startPointText = route.origin;
      const endPointText = route.destination;

      if (!startPointText || !endPointText) {
          console.error('Route origin or destination is missing for route ID:', id);
          alert(window.translations['map_route.cannot_display_route_missing_points']);
          return;
      }

      const [startLatLng, endLatLng] = await Promise.all([
        geocodeAddress(startPointText),
        geocodeAddress(endPointText)
      ]);

      if (startLatLng && endLatLng) {
        waypoints = [
          L.latLng(startLatLng.lat, startLatLng.lon),
          L.latLng(endLatLng.lat, endLatLng.lon)
        ];
        console.log('Using geocoded origin/destination for route ID:', id, 'Waypoints:', waypoints);
      } else {
        alert(window.translations['map_route.no_coords_for_route'] + '. ' + window.translations['map_route.try_again']);
        console.error('Could not geocode origin or destination for route ID:', id);
        return; // Exit if geocoding fails and no predefined coords
      }
    }

    // Initialize routing control with the determined waypoints
    routingControl = L.Routing.control({
      waypoints: waypoints,
      routeWhileDragging: false,
      addWaypoints: false,
      draggableWaypoints: false,
      fitSelectedRoutes: true,
      lineOptions: {
        styles: [{ color: '#0056a3', weight: 6, opacity: 1 }]
      },
      altLineOptions: {
        styles: [{ color: '#b3d1ff', weight: 4, opacity: 0.8 }]
      },
      geocoder: L.Control.Geocoder.nominatim()
    }).addTo(map);

    // Get route summary and calculate price
    routingControl.on('routesfound', function(e) {
      const routes = e.routes;
      if (routes.length > 0) {
        const summary = routes[0].summary;
        const distanceInKm = summary.totalDistance / 1000;
        const price = calculatePrice(distanceInKm);

        document.getElementById('route-card-distance').innerText = `${distanceInKm.toFixed(2)} km`;
        document.getElementById('route-card-price').innerText = `${price.toLocaleString('vi-VN')} VNĐ`;

        const listItemPriceElement = document.querySelector(`#price-${route.id}`);
        if (listItemPriceElement) {
          listItemPriceElement.innerHTML = `<i class="fas fa-dollar-sign"></i> ${price.toLocaleString('vi-VN')} VNĐ`;
        }
      }
    });

    // Display route card
    showRouteCard(route);

    // Remove 'selected' class from all route items
    document.querySelectorAll('.bus-route-item').forEach(item => {
      item.classList.remove('selected');
    });

    // Add 'selected' class to the clicked route item
    const selectedItem = document.querySelector(`.bus-route-item[data-id="${id}"]`);
    if (selectedItem) {
      selectedItem.classList.add('selected');
    }

  } catch (error) {
    console.error('Error geocoding or initializing routing for route ID:', id, error);
    alert(window.translations['map_route.error_displaying_route'] + '. ' + window.translations['map_route.try_again_later']);
  }
}



// show info card
const card = document.getElementById('route-card');
function showRouteCard(route) {
  const routeNumberDisplay = (route.route_number === '0') ? `${window.translations['map_route.route_number']} 0` : route.route_number;
  document.getElementById('route-card-title').innerText = `${route.name} — ${routeNumberDisplay}`;
  document.getElementById('route-card-desc').innerText = route.desc;
  document.getElementById('route-card-from').innerText = route.origin;
  document.getElementById('route-card-to').innerText = route.destination;
  card.style.display = 'block';
}

    // Elements for route detail view
    const routeDetailView = document.getElementById('route-detail-view');
    const routeDetailTitle = document.getElementById('route-detail-title');
    const backToListBtn = document.getElementById('back-to-list-btn');
    const searchRoutesTabContent = document.getElementById('search-routes');
    const findRouteTabContent = document.getElementById('find-route');
    const stationList = document.getElementById('station-list');
    const detailTabs = document.querySelectorAll('.route-detail-tabs .tab');
    const tabPanes = document.querySelectorAll('.route-detail-content .tab-pane');

    async function showRouteDetailView(route) {
      routeDetailTitle.innerText = route.name; // Set the title
      routeDetailView.classList.remove('hidden'); // Show the detail view
      searchRoutesTabContent.classList.add('hidden'); // Hide search content
      findRouteTabContent.classList.add('hidden'); // Hide find route content
      document.getElementById('nearby-routes').classList.add('hidden'); // Hide nearby routes content

      // Fetch and render bus stops for the selected route
      await fetchBusStops(route.id);

      // Clear any selected route highlights when showing detail view
      document.querySelectorAll('.bus-route-item').forEach(item => {
          item.classList.remove('selected');
      });
      clearAllPolylines(); // Clear all polylines from map
      if (routingControl) { // Clear routing control if active
          map.removeControl(routingControl);
          routingControl = null;
      }

      // Activate the "Trạm dừng" tab by default
      detailTabs.forEach(tab => tab.classList.remove('active'));
      tabPanes.forEach(pane => pane.classList.add('hidden'));
      document.getElementById('detail-tab-stops').classList.add('active');
      document.getElementById('stops-pane').classList.remove('hidden');
    }

    backToListBtn.addEventListener('click', () => {
      routeDetailView.classList.add('hidden'); // Hide the detail view
      searchRoutesTabContent.classList.remove("hidden"); // Show search content
      // Also ensure the main tabs are correctly set
      document.getElementById("tab-search").classList.add("active");
      document.getElementById("tab-route").classList.remove("active");
      document.getElementById("tab-nearby-stops").classList.remove("active");
      // Clear any selected route highlights when returning to list view
      document.querySelectorAll('.bus-route-item').forEach(item => {
          item.classList.remove('selected');
      });
      clearAllPolylines(); // Clear all polylines from map
      if (routingControl) { // Clear routing control if active
          map.removeControl(routingControl);
          routingControl = null;
      }
    });

    detailTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        detailTabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        tabPanes.forEach(pane => pane.classList.add('hidden'));
        const targetTabPaneId = tab.dataset.tab + '-pane';
        document.getElementById(targetTabPaneId).classList.remove('hidden');
      });
    });

    async function fetchBusStops(routeId) {
      console.log('Attempting to fetch bus stops for routeId:', routeId);
      const url = `${window.AppBaseUrl}/api/bus-routes/${routeId}/stations`;
      console.log('Fetching from URL:', url);
      try {
        const response = await fetch(url);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        console.log('Fetched bus stops:', data.stations);
        renderBusStops(data.stations);
      } catch (error) {
        console.error('Error fetching bus stops:', error);
        stationList.innerHTML = `<li>${window.translations['map_route.cannot_load_bus_stops']}</li>`;
      }
    }

    function renderBusStops(stops) {
      stationList.innerHTML = ''; // Clear previous stops
      if (stops.length === 0) {
        stationList.innerHTML = `<li>${window.translations['map_route.no_bus_stops']}</li>`;
        return;
      }

      stops.forEach((stop, index) => {
        // Only render if the stop name is not one of the specified names
        const excludedStations = ['Ga Bến Thành', 'Ga Nhà hát Thành phố', 'Ga Ba Son'];
        if (!excludedStations.includes(stop.name)) {
          const li = document.createElement('li');
          li.className = 'station-item';
          li.innerHTML = `<div class="dot"></div><span>${stop.name}</span>`;
          stationList.appendChild(li);
        }
      });
    }

    // Geocoding function
    async function geocodeAddress(address) {
        console.log('Geocoding address:', address);
        console.log('Current City Name for Geocoding:', currentCityName);

        // Attempt 1: Geocode with the address as-is
        let query1 = address;
        console.log('Attempt 1: Geocoding with query:', query1);
        let result1 = await fetchNominatim(query1);
        if (result1) {
            return result1;
        }

        // Attempt 2: Geocode with address + currentCityName + Vietnam
        let query2 = `${address}, ${currentCityName}, Vietnam`;
        console.log('Attempt 2: Geocoding with context query:', query2);
        let result2 = await fetchNominatim(query2);
        if (result2) {
            return result2;
        }

        console.warn('No geocoding results found for address:', address, 'after both attempts.');
        return null;
    }

    async function fetchNominatim(query) {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`);
            if (!response.ok) {
                throw new Error(`Nominatim HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            console.log('Nominatim response for query', query, ':', data);
            if (data && data.length > 0) {
                return { lat: parseFloat(data[0].lat), lon: parseFloat(data[0].lon) };
            }
            return null;
        } catch (error) {
            console.error('Error during geocoding for query:', query, error);
            return null;
        }
    }

    // Global variable to store bus stop markers
    let busStopMarkers = L.featureGroup();

    // Function to fetch nearby bus stops
    async function fetchNearbyBusStops(lat, lon) {
      try {
        const response = await fetch(`${window.AppBaseUrl}/api/bus-stops/nearby?lat=${lat}&lon=${lon}`);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        console.log('Fetched nearby bus stops:', data);
        renderNearbyBusStops(data);
        displayBusStopMarkers(data);
      } catch (error) {
        console.error('Error fetching nearby bus stops:', error);
        document.getElementById('nearby-stops-list').innerHTML = `<li>${window.translations['map_route.cannot_load_nearby_bus_stops']}</li>`;
      }
    }

    function renderNearbyBusStops(stops) {
      const nearbyStopsList = document.getElementById('nearby-stops-list');
      nearbyStopsList.innerHTML = '';
      if (stops.length === 0) {
        nearbyStopsList.innerHTML = `<li>${window.translations['map_route.no_nearby_bus_stops']}</li>`;
        return;
      }

      stops.forEach(stop => {
        const li = document.createElement('li');
        li.className = 'bus-route-item'; // Reusing existing style for list items
        li.innerHTML = `
          <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
          <div class="details">
            <div class="route-number">${stop.name}</div>
            <div class="route-info">${stop.address || window.translations['map_route.address_unknown']}</div>
          </div>
        `;
        li.onclick = () => {
          map.setView([stop.latitude, stop.longitude], 17); // Zoom to the selected stop
          // Optionally highlight the marker
        };
        nearbyStopsList.appendChild(li);
      });
    }

    function displayBusStopMarkers(stops) {
      if (!map) {
        console.error('Map is not initialized. Cannot display bus stop markers.');
        return;
      }

      // Clear existing bus stop markers
      busStopMarkers.clearLayers();

      stops.forEach(stop => {
        const marker = L.marker([stop.latitude, stop.longitude]).addTo(busStopMarkers);
        marker.bindPopup(`<b>${stop.name}</b><br>${stop.address || ''}`);
      });

      busStopMarkers.addTo(map);
      if (stops.length > 0) {
        map.fitBounds(busStopMarkers.getBounds(), { padding: [50, 50] });
      }
    }


    document.addEventListener('DOMContentLoaded', function() {
        initializeMap(); // Initialize map immediately on DOMContentLoaded
        currentCityName = window.translations['map_route.city_name']; // Initialize here
        const tabSearch = document.getElementById("tab-search");
        const tabRoute = document.getElementById("tab-route");
    const tabNearbyRoutes = document.getElementById("tab-nearby-stops"); // Renamed from tab-nearby-stops
    const sidebar = document.getElementById('sidebar');
    const searchRoutesTabContent = document.getElementById('search-routes');
    const findRouteTabContent = document.getElementById('find-route');
    const nearbyRoutesTabContent = document.getElementById('nearby-routes'); // New tab content for nearby routes
    const routeDetailView = document.getElementById('route-detail-view'); // Assuming this exists

    tabSearch.addEventListener("click", () => {
        tabSearch.classList.add("active");
        tabRoute.classList.remove("active");
        tabNearbyRoutes.classList.remove("active");
        searchRoutesTabContent.classList.remove("hidden");
        findRouteTabContent.classList.add("hidden");
        nearbyRoutesTabContent.classList.add("hidden");
        routeDetailView.classList.add('hidden');
        busStopMarkers.clearLayers();
        clearAllPolylines(); // Clear all polylines when switching tabs
        if (routingControl) { // Clear routing control if active
            map.removeControl(routingControl);
            routingControl = null;
        }
        // Remove 'selected' class from all route items
        document.querySelectorAll('.bus-route-item').forEach(item => {
            item.classList.remove('selected');
        });
        // Re-render the full list of bus routes for the search tab
        renderList();
        map.invalidateSize(); // Ensure map resizes correctly
    });

    tabRoute.addEventListener("click", () => {
        tabRoute.classList.add("active");
        tabSearch.classList.remove("active");
        tabNearbyRoutes.classList.remove("active");
        findRouteTabContent.classList.remove("hidden");
        searchRoutesTabContent.classList.add("hidden");
        nearbyRoutesTabContent.classList.add("hidden");
        routeDetailView.classList.add('hidden');
        // setTimeout(initializeMap, 100); // Map is already initialized
        busStopMarkers.clearLayers();
        clearAllPolylines(); // Clear all polylines when switching tabs
        // Routing control will be handled by the find route button itself
        map.invalidateSize(); // Ensure map resizes correctly
    });

    tabNearbyRoutes.addEventListener("click", () => {
        tabNearbyRoutes.classList.add("active");
        tabSearch.classList.remove("active");
        tabRoute.classList.remove("active");
        nearbyRoutesTabContent.classList.remove("hidden");
        searchRoutesTabContent.classList.add("hidden");
        findRouteTabContent.classList.add("hidden");
        routeDetailView.classList.add('hidden');
        // setTimeout(initializeMap, 100); // Map is already initialized
        busStopMarkers.clearLayers(); // Clear nearby stop markers
        clearAllPolylines(); // Clear all polylines before potentially drawing new ones
        if (routingControl) { // Clear routing control if active
            map.removeControl(routingControl);
            routingControl = null;
        }
        // Remove 'selected' class from all route items
        document.querySelectorAll('.bus-route-item').forEach(item => {
            item.classList.remove('selected');
        });
        // The actual fetching and drawing of nearby routes will be triggered by locate-btn
        // If user location is already known, re-fetch and display nearby routes and stops
        if (userLocationMarker) {
            const latlng = userLocationMarker.getLatLng();
            fetchNearbyBusRoutes(latlng.lat, latlng.lng);
            fetchNearbyBusStops(latlng.lat, latlng.lng); // Add this line
        }
        map.invalidateSize(); // Ensure map resizes correctly
    });

        // Check URL for tab parameter on page load
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');

        if (activeTab === 'route') {
            tabRoute.click(); // Simulate click to activate route tab and initialize map
        } else if (activeTab === 'nearby-stops') {
            tabNearbyRoutes.click(); // Simulate click to activate nearby stops tab
        }
        else {
            tabSearch.click(); // Default to search tab
        }

        // Bus route search/filter logic
        const searchInput = document.getElementById('bus-route-search-input');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = searchInput.value.toLowerCase();
                renderList('search-routes', searchTerm); // Use renderList for filtering
            });
        }

        // Find route logic
        const findRouteBtn = document.getElementById('find-route-btn');
        const startPointInput = document.getElementById('start-point');
        const endPointInput = document.getElementById('end-point');
        const locateStartPointBtn = document.getElementById('locate-start-point-btn');

        if (findRouteBtn) {
            findRouteBtn.addEventListener('click', function() {
                const startPointText = startPointInput.value;
                const endPointText = endPointInput.value;

                if (!startPointText || !endPointText) {
                    alert(window.translations['map_route.enter_start_end_points']);
                    return;
                }

                Promise.all([
                    geocodeAddress(startPointText),
                    geocodeAddress(endPointText)
                ]).then(results => {
                    const startLatLng = results[0];
                    const endLatLng = results[1];

                    if (startLatLng && endLatLng) {
                        if (routingControl) {
                            map.removeControl(routingControl);
                        }

                        const waypoints = [
                            L.latLng(startLatLng.lat, startLatLng.lon),
                            L.latLng(endLatLng.lat, endLatLng.lon)
                        ];

                        const validWaypoints = waypoints.filter(wp => wp instanceof L.LatLng);

                        if (validWaypoints.length >= 2) {
                            try {
                                routingControl = L.Routing.control({
                                    waypoints: validWaypoints,
                                    routeWhileDragging: true,
                                    geocoder: L.Control.Geocoder.nominatim()
                                }).addTo(map);

                                map.fitBounds(routingControl.getWaypoints().map(wp => wp.latLng));
                            } catch (e) {
                                console.error("Error initializing Leaflet Routing Machine in find route:", e);
                                alert(window.translations['map_route.error_finding_route'] + '. ' + window.translations['map_route.try_again_later']);
                            }
                        } else {
                            console.error("Cannot initialize routing: Insufficient or invalid waypoints for find route.", validWaypoints);
                            alert(window.translations['map_route.error_geocoding_routing']); // More specific error
                        }
                    } else {
                        alert(window.translations['map_route.error_geocoding_routing']); // More specific error
                    }
                }).catch(error => {
                    console.error('Lỗi khi tìm kiếm tuyến đường:', error);
                    alert(window.translations['map_route.error_geocoding_routing']); // More specific error
                });
            });
        }

        // Locate start point functionality
        if (locateStartPointBtn) {
            locateStartPointBtn.onclick = () => {
                if (!navigator.geolocation) {
                    showLocationMessage(window.translations['map_route.browser_no_geolocation'], true);
                    return;
                }
                showLocationMessage(window.translations['map_route.getting_location'], false);
                navigator.geolocation.getCurrentPosition(async (pos) => {
                    const lat = pos.coords.latitude;
                    const lon = pos.coords.longitude;

                    try {
                        const address = await reverseGeocode(lat, lon);
                        if (address) {
                            startPointInput.value = address;
                            showLocationMessage(window.translations['map_route.location_found'], false);
                        } else {
                            showLocationMessage(window.translations['map_route.cannot_find_address'], true);
                        }
                    } catch (error) {
                        console.error('Error during reverse geocoding:', error);
                        showLocationMessage(window.translations['map_route.error_getting_address'], true);
                    }
                }, (error) => {
                    let errorMessage = window.translations['map_route.location_error'];
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += ' ' + window.translations['map_route.permission_denied'];
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += ' ' + window.translations['map_route.position_unavailable'];
                            break;
                        case error.TIMEOUT:
                            errorMessage += ' ' + window.translations['map_route.timeout'];
                            break;
                        case error.UNKNOWN_ERROR:
                            errorMessage += ' ' + window.translations['map_route.unknown_error'];
                            break;
                    }
                    showLocationMessage(errorMessage, true);
                });
            };
        }

        // Reverse geocoding function
        async function reverseGeocode(lat, lon) {
            console.log('Reverse geocoding coordinates:', lat, lon);
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`);
                if (!response.ok) {
                    throw new Error(`Nominatim HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                console.log('Nominatim reverse geocode response for', lat, lon, ':', data);
                if (data && data.display_name) {
                    return data.display_name;
                }
                console.warn('No reverse geocoding results found for coordinates:', lat, lon);
                return null;
            } catch (error) {
                console.error('Error during reverse geocoding for coordinates:', lat, lon, error);
                return null;
            }
        }

        // locate me
        const locateBtn = document.getElementById('locate-btn');
        const locationMessageDiv = document.getElementById('location-message');

        function showLocationMessage(message, isError = true) {
            locationMessageDiv.innerText = message;
            locationMessageDiv.style.backgroundColor = isError ? 'rgba(255, 0, 0, 0.7)' : 'rgba(0, 123, 255, 0.7)';
            locationMessageDiv.classList.remove('hidden');
            setTimeout(() => {
                locationMessageDiv.classList.add('hidden');
            }, 5000); // Hide after 5 seconds
        }

        if (locateBtn) {
            locateBtn.onclick = () => {
                if (!navigator.geolocation) {
                    showLocationMessage(window.translations['map_route.browser_no_geolocation'], true);
                    return;
                }
                showLocationMessage(window.translations['map_route.getting_location'], false); // Indicate loading
                navigator.geolocation.getCurrentPosition((pos) => {
                    const lat = pos.coords.latitude, lon = pos.coords.longitude;
                    map.setView([lat, lon], 15);
                    
                    // Clear previous user location marker if exists
                    if (userLocationMarker) {
                        map.removeLayer(userLocationMarker);
                    }
                    userLocationMarker = L.marker([lat, lon]).addTo(map)
                        .bindPopup(window.translations['map_route.your_location']).openPopup();
                    L.circle([lat, lon], { radius: 50, color: '#1e73be', fill: true, fillOpacity: 0.3 }).addTo(map);
                    showLocationMessage(window.translations['map_route.location_found'], false); // Success message

                    // After getting location, fetch nearby bus routes AND bus stops
                    fetchNearbyBusRoutes(lat, lon);
                    fetchNearbyBusStops(lat, lon); // Add this line

                    // Manually activate the nearby routes tab without triggering its click event
                    tabNearbyRoutes.classList.add("active");
                    tabSearch.classList.remove("active");
                    tabRoute.classList.remove("active");
                    nearbyRoutesTabContent.classList.remove("hidden");
                    searchRoutesTabContent.classList.add("hidden");
                    findRouteTabContent.classList.add("hidden");
                    routeDetailView.classList.add('hidden');
                    setTimeout(initializeMap, 100);
                    busStopMarkers.clearLayers(); // Clear nearby stop markers
                    clearAllPolylines(); // Clear all polylines before potentially drawing new ones
                    if (routingControl) { // Clear routing control if active
                        map.removeControl(routingControl);
                        routingControl = null;
                    }
                    // Remove 'selected' class from all route items
                    document.querySelectorAll('.bus-route-item').forEach(item => {
                        item.classList.remove('selected');
                    });
                }, (error) => {
                    let errorMessage = window.translations['map_route.location_error'];
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += ' ' + window.translations['map_route.permission_denied'];
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += ' ' + window.translations['map_route.position_unavailable'];
                            break;
                        case error.TIMEOUT:
                            errorMessage += ' ' + window.translations['map_route.timeout'];
                            break;
                            case error.UNKNOWN_ERROR:
                            errorMessage += ' ' + window.translations['map_route.unknown_error'];
                            break;
                    }
                    showLocationMessage(errorMessage, true); // Display error on page
                });
            };
        }

        // close card
        document.getElementById('close-card').onclick = () => { card.style.display = 'none'; };

        // toggle sidebar on small screens
        const toggleBtn = document.getElementById('toggle-sidebar');
        if (toggleBtn) {
          toggleBtn.onclick = () => {
            sidebar.classList.toggle('open');
          };
        }


        // Fetch bus routes when the script loads
        fetchBusRoutes();

        // City selection logic
        const citySelectBtn = document.getElementById('city-select-btn');
        const currentCityDisplay = document.getElementById('current-city-display');

        // Update initial display
        currentCityDisplay.innerText = currentCityName;

        citySelectBtn.addEventListener('click', async () => {
            const cities = {
                'TP Hồ Chí Minh': [10.776889, 106.700806],
                'Hà Nội': [21.0278, 105.8342],
                'Đà Nẵng': [16.0544, 108.2022],
                'Cần Thơ': [10.0452, 105.7469],
                'Hải Phòng': [20.8633, 106.6833]
            };

            let cityOptions = Object.keys(cities).map((city, index) => `${index + 1}. ${city}`).join('\n');
            let choice = prompt(`${window.translations['map_route.select_city']}:\n${cityOptions}\n${window.translations['map_route.enter_corresponding_number']}:`);

            if (choice) {
                const selectedCityIndex = parseInt(choice) - 1;
                const selectedCityName = Object.keys(cities)[selectedCityIndex];
                const selectedCityCoords = Object.values(cities)[selectedCityIndex];

                if (selectedCityName && selectedCityCoords) {
                    currentCityName = selectedCityName;
                    currentCityCoordinates = selectedCityCoords;
                    currentCityDisplay.innerText = currentCityName;

                    // Re-initialize map with new city coordinates
                    map.setView(currentCityCoordinates, 13);
                    // Clear existing markers and routes
                    clearAllPolylines();
                    busStopMarkers.clearLayers();
                    if (routingControl) {
                        map.removeControl(routingControl);
                        routingControl = null;
                    }
                    // Re-fetch bus routes for the new city (if applicable, assuming API supports city filtering)
                    // For now, we just re-render the list based on existing data or clear it.
                    // If the API needs to be updated to filter by city, that would be a backend task.
                    renderList(); // Re-render the list of all routes
                    showLocationMessage(`${window.translations['map_route.switched_to_city']}: ${currentCityName}`, false);
                } else {
                    showLocationMessage(window.translations['map_route.invalid_choice'], true);
                }
            }
        });

        // User dropdown logic (now handled by Bootstrap)
    });

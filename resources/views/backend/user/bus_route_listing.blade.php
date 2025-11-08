<style>
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
</style>

<div class="bus-route-search-interface">
    <div class="search-bar">
        <i class="fas fa-search search-icon"></i> {{-- Font Awesome search icon --}}
        <input type="text" id="bus-route-search-input" placeholder="Tìm tuyến xe">
    </div>

    <ul class="bus-route-list">
        {{-- Example Bus Route Item (will be dynamically loaded) --}}
        <li class="bus-route-item">
            <div class="icon"><i class="fas fa-bus"></i></div>
            <div class="details">
                <div class="route-number">Tuyến số Metro 1</div>
                <div class="route-info">Bến Thành - Suối Tiên</div>
                <div class="route-time"><i class="far fa-clock"></i> 05:00 - 22:00</div>
            </div>
            <div class="price"><i class="fas fa-dollar-sign"></i> 20,000 VNĐ</div>
        </li>
        <li class="bus-route-item">
            <div class="icon"><i class="fas fa-bus"></i></div>
            <div class="details">
                <div class="route-number">Tuyến số D4</div>
                <div class="route-info">Vinhomes Grand Park - Bến xe buýt Sài Gòn</div>
                <div class="route-time"><i class="far fa-clock"></i> 05:00 - 22:00</div>
            </div>
            <div class="price"><i class="fas fa-dollar-sign"></i> 7,000 VNĐ</div>
        </li>
        <li class="bus-route-item">
            <div class="icon"><i class="fas fa-bus"></i></div>
            <div class="details">
                <div class="route-number">Tuyến số 01</div>
                <div class="route-info">Bến Thành - Bến xe buýt Chợ Lớn</div>
                <div class="route-time"><i class="far fa-clock"></i> 05:00 - 20:15</div>
            </div>
            <div class="price"><i class="fas fa-dollar-sign"></i> 5,000 VNĐ</div>
        </li>
        <li class="bus-route-item">
            <div class="icon"><i class="fas fa-bus"></i></div>
            <div class="details">
                <div class="route-number">Tuyến số 03</div>
                <div class="route-info">Bến Thành - Thạnh Xuân</div>
                <div class="route-time"><i class="far fa-clock"></i> 04:00 - 21:00</div>
            </div>
            <div class="price"><i class="fas fa-dollar-sign"></i> 6,000 VNĐ</div>
        </li>
        <li class="bus-route-item">
            <div class="icon"><i class="fas fa-bus"></i></div>
            <div class="details">
                <div class="route-number">Tuyến số 04</div>
                <div class="route-info">Bến Thành - Cộng Hòa - Bến xe An Sương</div>
                <div class="route-time"><i class="far fa-clock"></i> 05:00 - 20:15</div>
            </div>
            <div class="price"><i class="fas fa-dollar-sign"></i> 6,000 VNĐ</div>
        </li>
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('bus-route-search-input');
        const busRouteList = document.querySelector('.bus-route-list');
        const busRouteItems = busRouteList.querySelectorAll('.bus-route-item');

        searchInput.addEventListener('keyup', function() {
            const searchTerm = searchInput.value.toLowerCase();

            busRouteItems.forEach(item => {
                const routeNumber = item.querySelector('.route-number').textContent.toLowerCase();
                const routeInfo = item.querySelector('.route-info').textContent.toLowerCase();

                if (routeNumber.includes(searchTerm) || routeInfo.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>

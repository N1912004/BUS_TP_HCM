console.log('script.js loaded and executing.');
document.addEventListener('DOMContentLoaded', function() {
    const getCoordinatesBtn = document.getElementById('getCoordinatesBtn');
    if (getCoordinatesBtn) {
        getCoordinatesBtn.addEventListener('click', async function() {
            const diemDi = document.getElementById('diem_di').value;
            const diemDen = document.getElementById('diem_den').value;

            if (!diemDi || !diemDen) {
                alert('Vui lòng nhập điểm đi và điểm đến.');
                return;
            }

            try {
                // Get coordinates for diem_di
                const coordsDi = await getGeocodeCoordinates(diemDi);
                if (coordsDi) {
                    document.getElementById('latitude_di').value = coordsDi.lat;
                    document.getElementById('longitude_di').value = coordsDi.lon;
                } else {
                    alert(`Không tìm thấy tọa độ cho điểm đi: ${diemDi}`);
                    document.getElementById('latitude_di').value = '';
                    document.getElementById('longitude_di').value = '';
                }

                // Get coordinates for diem_den
                const coordsDen = await getGeocodeCoordinates(diemDen);
                if (coordsDen) {
                    document.getElementById('latitude_den').value = coordsDen.lat;
                    document.getElementById('longitude_den').value = coordsDen.lon;
                } else {
                    alert(`Không tìm thấy tọa độ cho điểm đến: ${diemDen}`);
                    document.getElementById('latitude_den').value = '';
                    document.getElementById('longitude_den').value = '';
                }

            } catch (error) {
                console.error('Lỗi khi lấy tọa độ:', error);
                alert('Đã xảy ra lỗi khi lấy tọa độ. Vui lòng thử lại.');
            }
        });
    }

    async function getGeocodeCoordinates(address) {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`;
        const response = await fetch(url, {
            headers: {
                'User-Agent': 'LaravelBusApp/1.0 (contact@example.com)' // Replace with your actual app name and email
            }
        });
        const data = await response.json();
        if (data && data.length > 0) {
            return { lat: data[0].lat, lon: data[0].lon };
        }
        return null;
    }

    const generateCoordsBtn = document.getElementById('generateCoordsBtn');
    if (generateCoordsBtn) {
        console.log('Generate Coords Button found.');
        generateCoordsBtn.addEventListener('click', async function() {
            console.log('Generate Coords Button clicked.');
            const diemDi = document.getElementById('diem_di').value;
            const diemDen = document.getElementById('diem_den').value;
            const coordsTextarea = document.getElementById('coords');

            console.log('Điểm đi:', diemDi);
            console.log('Điểm đến:', diemDen);

            if (!diemDi || !diemDen) {
                alert('Vui lòng nhập điểm đi và điểm đến để tạo tọa độ tự động.');
                return;
            }

            try {
                console.log('Attempting to get coordinates for diem_di...');
                const coordsDi = await getGeocodeCoordinates(diemDi);
                console.log('Coordinates for diem_di:', coordsDi);

                console.log('Attempting to get coordinates for diem_den...');
                const coordsDen = await getGeocodeCoordinates(diemDen);
                console.log('Coordinates for diem_den:', coordsDen);

                if (coordsDi && coordsDen) {
                    const startLat = parseFloat(coordsDi.lat);
                    const startLon = parseFloat(coordsDi.lon);
                    const endLat = parseFloat(coordsDen.lat);
                    const endLon = parseFloat(coordsDen.lon);

                    const routeCoords = [];
                    const numPoints = 5; // Number of intermediate points

                    // Add start point
                    routeCoords.push([startLat, startLon]);

                    // Interpolate points
                    for (let i = 1; i <= numPoints; i++) {
                        const fraction = i / (numPoints + 1);
                        const lat = startLat + (endLat - startLat) * fraction;
                        const lon = startLon + (endLon - startLon) * fraction;
                        routeCoords.push([lat, lon]);
                    }

                    // Add end point
                    routeCoords.push([endLat, endLon]);

                    coordsTextarea.value = JSON.stringify(routeCoords, null, 2);
                    alert('Tọa độ đã được tạo tự động.');
                } else {
                    alert('Không thể tìm thấy tọa độ cho điểm đi hoặc điểm đến. Vui lòng kiểm tra lại tên địa điểm.');
                }

            } catch (error) {
                console.error('Lỗi khi tạo tọa độ tự động:', error);
                alert('Đã xảy ra lỗi khi tạo tọa độ tự động. Vui lòng thử lại.');
            }
        });
    }
});

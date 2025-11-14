<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserPageTest extends TestCase
{
    use RefreshDatabase;

    // Biến để lưu trữ người dùng mẫu
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Tạo người dùng mẫu và lưu vào biến $this->user
        $this->user = User::create([
            'fullname' => 'Map User',
            'username' => '123123',
            'email' => 'mapuser@example.com',
            'password' => Hash::make('123123'),
            'is_verified' => 1, 
        ]);
    }


    /** @test */
    public function test_nguoi_dung_da_dang_nhap_co_the_truy_cap_trang_ban_do()
    {
        $this->actingAs($this->user)->get('/map-route')->assertStatus(200);
    }

    /** @test */
    public function test_trang_ban_do_chua_cac_thanh_phan_tim_kiem()
    {
        $response = $this->actingAs($this->user)->get('/map-route');

        // Đã sửa: Thay 'Tìm kiếm tuyến xe buýt' bằng 'Search bus route' (dựa trên HTML thực tế)
        $response->assertStatus(200);
        $response->assertSee('Search bus route'); 
        $response->assertSee('<input type="text" id="bus-route-search-input"', false); 
        $response->assertSee('SEARCH', false); // Kiểm tra nút SEARCH trên tab
    }

    /** @test */
    public function test_trang_ban_do_chua_container_ban_do()
    {
        // 1. Giả lập người dùng đã đăng nhập
        $response = $this->actingAs($this->user)->get('/map-route');

        // 2. Kiểm tra: Trang có chứa container cho bản đồ
        $response->assertStatus(200);
        // Giả sử bạn dùng một div có id="map" để chứa bản đồ
        $response->assertSee('<div id="map"', false); 
    }
        /** @test */
    public function test_api_chon_thanh_pho_tra_ve_tp_ho_chi_minh_cho_input_1()
    {
        $response = $this->actingAs($this->user)->postJson('/api/select-city', [
            'city_code' => 1, 
        ]);

        // 3. Kiểm tra:
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'city_name' => 'TP Hồ Chí Minh',
            'city_code' => 1,
        ]);
    }
        /** @test */
    public function test_api_chon_thanh_pho_tra_ve_hai_phong_cho_input_5()
    {
        // 1. Giả lập người dùng đã đăng nhập
        // 2. Gửi yêu cầu POST đến API xử lý chọn thành phố với input là '5'
        $response = $this->actingAs($this->user)->postJson('/api/select-city', [
            'city_code' => 5, // Input số 5 (Hải Phòng)
        ]);

        // 3. Kiểm tra:
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'city_name' => 'Hải Phòng', // Kiểm tra tên thành phố trả về
            'city_code' => 5,
        ]);
    }
        /** @test */
    public function test_api_chon_thanh_pho_tra_ve_loi_cho_input_khong_hop_le()
    {
        // 1. Giả lập người dùng đã đăng nhập
        // 2. Gửi yêu cầu POST với input không hợp lệ (ví dụ: 6, vì chỉ có 1-5)
        $response = $this->actingAs($this->user)->postJson('/api/select-city', [
            'city_code' => 6, // Input không hợp lệ
        ]);

        // 3. Kiểm tra:
        $response->assertStatus(422); // Lỗi Validation
        $response->assertJsonValidationErrors(['city_code']); // Kiểm tra lỗi validation
    }
        /** @test */
    public function test_api_chon_thanh_pho_tra_ve_loi_cho_input_khong_phai_la_so()
    {
        // 1. Giả lập người dùng đã đăng nhập
        // 2. Gửi yêu cầu POST với input không phải là số
        $response = $this->actingAs($this->user)->postJson('/api/select-city', [
            'city_code' => 'abc', // Input không phải là số
        ]);

        // 3. Kiểm tra:
        $response->assertStatus(422); // Lỗi Validation
        $response->assertJsonValidationErrors(['city_code']); // Kiểm tra lỗi validation
    }
        /** @test */
    public function test_chuyen_doi_ngon_ngu_sang_tieng_viet_thanh_cong()
    {
        // 1. Giả lập người dùng đã đăng nhập và truy cập trang
        $this->actingAs($this->user)->get('/map-route');

        // 2. Gửi yêu cầu chuyển đổi ngôn ngữ sang 'vi'
        // Route: /lang/vi
        $response = $this->get('/lang/vi');

        // 3. Kiểm tra:
        // Phải chuyển hướng ngược lại trang trước đó (302)
        $response->assertStatus(302);
        
        // Kiểm tra Session đã được đặt thành 'vi' chưa
        $response->assertSessionHas('locale', 'vi');

        // 4. Truy cập lại trang /map-route để kiểm tra nội dung đã Việt hóa
        $responseVi = $this->actingAs($this->user)->get('/map-route');

        // Kiểm tra các chuỗi đã được Việt hóa (dựa trên hình ảnh)
        $responseVi->assertStatus(200);
        $responseVi->assertSee('TRA CỨU'); // Kiểm tra Tab TRA CỨU
        $responseVi->assertSee('Tìm tuyến xe'); // Kiểm tra Placeholder
        $responseVi->assertSee('TÌM ĐƯỜNG'); // Kiểm tra Tab TÌM ĐƯỜNG
        $responseVi->assertSee('TUYẾN GẦN ĐÂY'); // Kiểm tra Tab TUYẾN GẦN ĐÂY
    }

    /** @test */
    public function test_chuyen_doi_ngon_ngu_sang_tieng_anh_thanh_cong()
    {
        // 1. Giả lập người dùng đã đăng nhập và truy cập trang
        $this->actingAs($this->user)->get('/map-route');

        // 2. Gửi yêu cầu chuyển đổi ngôn ngữ sang 'en'
        // Route: /lang/en
        $response = $this->get('/lang/en');

        // 3. Kiểm tra:
        // Phải chuyển hướng ngược lại trang trước đó (302)
        $response->assertStatus(302);
        
        // Kiểm tra Session đã được đặt thành 'en' chưa
        $response->assertSessionHas('locale', 'en');

        // 4. Truy cập lại trang /map-route để kiểm tra nội dung đã Anh hóa
        $responseEn = $this->actingAs($this->user)->get('/map-route');

        // Kiểm tra các chuỗi đã được Anh hóa (dựa trên code HTML trước đó)
        $responseEn->assertStatus(200);
        $responseEn->assertSee('SEARCH'); // Kiểm tra Tab SEARCH
        $responseEn->assertSee('Search bus route'); // Kiểm tra Placeholder
        $responseEn->assertSee('FIND ROUTE'); // Kiểm tra Tab FIND ROUTE
        $responseEn->assertSee('NEARBY ROUTES'); // Kiểm tra Tab NEARBY ROUTES
    }

    /** @test */
    public function test_chuyen_doi_ngon_ngu_voi_locale_khong_hop_le_bi_chan()
    {
        // 1. Gửi yêu cầu chuyển đổi ngôn ngữ với locale không hợp lệ
        $response = $this->get('/lang/fr'); // 'fr' không có trong ['en', 'vi']

        // 2. Kiểm tra: Phải bị chặn (400 Bad Request)
        $response->assertStatus(400);
    }
        /** @test */
    public function test_trang_ban_do_chua_cac_thanh_phan_tim_duong()
    {
        // 1. Giả lập người dùng đã đăng nhập
        $response = $this->actingAs($this->user)->get('/map-route');

        // 2. Kiểm tra: Trang có chứa các thành phần tìm đường (Find Route)
        $response->assertStatus(200);
        
        // Kiểm tra tiêu đề/tab
        $response->assertSee('FIND ROUTE'); 
        
        // Kiểm tra Placeholder cho điểm bắt đầu (Starting point)
        $response->assertSee('Starting point', false); 
        
        // Kiểm tra Placeholder cho điểm kết thúc (Destination)
        $response->assertSee('Destination', false); 
        
        // Kiểm tra nút tìm kiếm
        $response->assertSee('FIND ROUTE', false); 
        
        // Kiểm tra nút định vị cho điểm bắt đầu
        $response->assertSee('<button id="locate-start-point-btn"', false);
    }

    /** @test */
    public function test_api_tim_duong_giua_hai_diem_tra_ve_chuyen_di_thanh_cong()
    {
        // 1. Giả lập người dùng đã đăng nhập
        // 2. Gửi yêu cầu POST đến API tìm đường đi với TÊN ĐỊA ĐIỂM
        $response = $this->actingAs($this->user)->postJson('/api/find-route', [
            // Đã sửa: Gửi tên địa điểm thay vì tọa độ
            'start_point' => 'Chợ Bến Thành', 
            'destination' => 'Sân Bay Tân Sơn Nhất', 
        ]);

        // 3. Kiểm tra:
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'route_options' => [
                '*' => [ 
                    'bus_route_id',
                    'steps', 
                    'total_distance',
                    'polyline', 
                ]
            ]
        ]);
        $response->assertJson([
            'success' => true,
        ]);
    }

    /** @test */
    public function test_api_tim_duong_tra_ve_loi_khi_thieu_diem_bat_dau()
    {
        // 1. Giả lập người dùng đã đăng nhập
        // 2. Gửi yêu cầu POST thiếu điểm bắt đầu
        $response = $this->actingAs($this->user)->postJson('/api/find-route', [
            // Đã sửa: Kiểm tra thiếu trường 'start_point'
            'destination' => 'Sân Bay Tân Sơn Nhất', 
        ]);

        // 3. Kiểm tra:
        $response->assertStatus(422); // Lỗi Validation
        // Đã sửa: Kiểm tra lỗi validation cho trường 'start_point'
        $response->assertJsonValidationErrors(['start_point']); 
    }
        /** @test */
    public function test_trang_ban_do_chua_cac_thanh_phan_tuyen_gan_day()
    {
        // 1. Giả lập người dùng đã đăng nhập 
        $response = $this->actingAs($this->user)->get('/map-route');

        // 2. Kiểm tra: Trang có chứa các thành phần của tab Tuyến Gần Đây
        $response->assertStatus(200);
        
        // Kiểm tra tiêu đề/tab
        $response->assertSee('NEARBY ROUTES'); 
        
        // ĐÃ SỬA: Kiểm tra ID của nút định vị
        $response->assertSee('id="locate-btn"', false); 
        $response->assertSee('fa-location-crosshairs', false); 
        
        // Kiểm tra văn bản hướng dẫn
        $response->assertSee('Click the locate button to find bus routes near your location.'); 
    }

    /** @test */
    public function test_api_tuyen_gan_day_tra_ve_cac_tuyen_gan_vi_tri_hien_tai()
    {
        // 1. Giả lập người dùng đã đăng nhập
        // 2. Gửi yêu cầu POST đến API tìm tuyến gần nhất với tọa độ mẫu
        // Giả định: API là /api/nearly-route
        $response = $this->actingAs($this->user)->postJson('/api/nearly-route', [
            // Tọa độ mẫu (ví dụ: gần trung tâm TP HCM)
            'current_lat' => 10.7758, 
            'current_lng' => 106.7017, 
            'radius_km' => 1, // Bán kính 1km
        ]);

        // 3. Kiểm tra:
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'nearby_routes' => [
                '*' => [ // Kiểm tra cấu trúc của mỗi tuyến gần đó
                    'id',
                    'name',
                    'distance_to_stop_m', // Khoảng cách đến trạm gần nhất
                ]
            ]
        ]);
        $response->assertJson([
            'success' => true,
        ]);
    }

    /** @test */
    public function test_api_tuyen_gan_day_tra_ve_loi_khi_thieu_toa_do()
    {
        // 1. Giả lập người dùng đã đăng nhập
        // 2. Gửi yêu cầu POST thiếu tọa độ
        $response = $this->actingAs($this->user)->postJson('/api/nearly-route', [
            'radius_km' => 1, 
        ]);

        // 3. Kiểm tra:
        $response->assertStatus(422); // Lỗi Validation
        $response->assertJsonValidationErrors(['current_lat', 'current_lng']);
    }
}
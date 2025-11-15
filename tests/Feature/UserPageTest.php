<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserPageTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Tạo người dùng test nếu chưa tồn tại
        $this->user = User::firstOrCreate(
            ['username' => '123123'],
            [
                'fullname' => 'Map User',
                'email' => 'mapuser@example.com',
                'password' => Hash::make('123123'),
                'is_verified' => 1,
            ]
        );
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

        $response->assertStatus(200);
        $response->assertSee('Search bus route'); 
        $response->assertSee('<input type="text" id="bus-route-search-input"', false); 
        $response->assertSee('SEARCH', false);
    }

    /** @test */
    public function test_trang_ban_do_chua_container_ban_do()
    {
        $response = $this->actingAs($this->user)->get('/map-route');
        $response->assertStatus(200);
        $response->assertSee('<div id="map"', false);
    }

    /** @test */
    public function test_api_chon_thanh_pho_tra_ve_tp_ho_chi_minh_cho_input_1()
    {
        $response = $this->actingAs($this->user)->postJson('/api/select-city', [
            'city_code' => 1,
        ]);

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
        $response = $this->actingAs($this->user)->postJson('/api/select-city', [
            'city_code' => 5,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'city_name' => 'Hải Phòng',
            'city_code' => 5,
        ]);
    }

    /** @test */
    public function test_api_chon_thanh_pho_tra_ve_loi_cho_input_khong_hop_le()
    {
        $response = $this->actingAs($this->user)->postJson('/api/select-city', [
            'city_code' => 6,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['city_code']);
    }

    /** @test */
    public function test_api_chon_thanh_pho_tra_ve_loi_cho_input_khong_phai_la_so()
    {
        $response = $this->actingAs($this->user)->postJson('/api/select-city', [
            'city_code' => 'abc',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['city_code']);
    }

    /** @test */
    public function test_chuyen_doi_ngon_ngu_sang_tieng_viet_thanh_cong()
    {
        $this->actingAs($this->user)->get('/map-route');

        $response = $this->get('/lang/vi');
        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'vi');

        $responseVi = $this->actingAs($this->user)->get('/map-route');
        $responseVi->assertStatus(200);
        $responseVi->assertSee('TRA CỨU');
        $responseVi->assertSee('Tìm tuyến xe');
        $responseVi->assertSee('TÌM ĐƯỜNG');
        $responseVi->assertSee('TUYẾN GẦN ĐÂY');
    }

    /** @test */
    public function test_chuyen_doi_ngon_ngu_sang_tieng_anh_thanh_cong()
    {
        $this->actingAs($this->user)->get('/map-route');

        $response = $this->get('/lang/en');
        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'en');

        $responseEn = $this->actingAs($this->user)->get('/map-route');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('SEARCH');
        $responseEn->assertSee('Search bus route');
        $responseEn->assertSee('FIND ROUTE');
        $responseEn->assertSee('NEARBY ROUTES');
    }

    /** @test */
    public function test_chuyen_doi_ngon_ngu_voi_locale_khong_hop_le_bi_chan()
    {
        $response = $this->get('/lang/fr');
        $response->assertStatus(400);
    }

    /** @test */
    public function test_trang_ban_do_chua_cac_thanh_phan_tim_duong()
    {
        $response = $this->actingAs($this->user)->get('/map-route');
        $response->assertStatus(200);
        $response->assertSee('FIND ROUTE');
        $response->assertSee('Starting point', false);
        $response->assertSee('Destination', false);
        $response->assertSee('FIND ROUTE', false);
        $response->assertSee('<button id="locate-start-point-btn"', false);
    }

    /** @test */
    public function test_api_tim_duong_giua_hai_diem_tra_ve_chuyen_di_thanh_cong()
    {
        $response = $this->actingAs($this->user)->postJson('/api/find-route', [
            'start_point' => 'Chợ Bến Thành',
            'destination' => 'Sân Bay Tân Sơn Nhất',
        ]);

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
        $response->assertJson(['success' => true]);
    }

    /** @test */
    public function test_api_tim_duong_tra_ve_loi_khi_thieu_diem_bat_dau()
    {
        $response = $this->actingAs($this->user)->postJson('/api/find-route', [
            'destination' => 'Sân Bay Tân Sơn Nhất',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['start_point']);
    }

    /** @test */
    public function test_trang_ban_do_chua_cac_thanh_phan_tuyen_gan_day()
    {
        $response = $this->actingAs($this->user)->get('/map-route');
        $response->assertStatus(200);
        $response->assertSee('NEARBY ROUTES');
        $response->assertSee('id="locate-btn"', false);
        $response->assertSee('fa-location-crosshairs', false);
        $response->assertSee('Click the locate button to find bus routes near your location.');
    }

    /** @test */
    public function test_api_tuyen_gan_day_tra_ve_cac_tuyen_gan_vi_tri_hien_tai()
    {
        $response = $this->actingAs($this->user)->postJson('/api/nearly-route', [
            'current_lat' => 10.7758,
            'current_lng' => 106.7017,
            'radius_km' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'nearby_routes' => [
                '*' => [
                    'id',
                    'name',
                    'distance_to_stop_m',
                ]
            ]
        ]);
        $response->assertJson(['success' => true]);
    }

    /** @test */
    public function test_api_tuyen_gan_day_tra_ve_loi_khi_thieu_toa_do()
    {
        $response = $this->actingAs($this->user)->postJson('/api/nearly-route', [
            'radius_km' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['current_lat', 'current_lng']);
    }
}

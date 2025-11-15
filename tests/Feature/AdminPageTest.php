<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Ticket;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\BusRoute;
class AdminPageTest extends TestCase
{
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Tạo admin test nếu chưa tồn tại
        $this->admin = Admin::firstOrCreate(
            ['username' => 'admin123'],
            [
                'fullname' => 'Admin Test',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'is_active' => 1,
            ]
        );

    }

    /** @test */
    public function test_admin_co_the_dang_nhap_thanh_cong()
    {
        $response = $this->post('/admin/login', [
            'username' => 'admin123',
            'password' => 'admin111',
        ]);

        $response->assertRedirect('/admin'); // Sau khi đăng nhập thành công
        $this->assertAuthenticatedAs($this->admin, 'admin');
    }

    /** @test */
    public function test_admin_co_the_truy_cap_bang_dieu_khien()
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Quản lý tuyến xe');
    }

    /** @test */
    public function test_admin_co_the_truy_cap_cac_trang_quan_ly()
    {
        $pages = [
            '/admin/tickets' => 'Quản lý Vé',
            '/admin/routes' => 'Quản lý tuyến xe',
            '/admin/buses' => 'Quản lý xe buýt',
            '/admin/drivers' => 'Tài xế',
            '/admin/assistants' => 'Phụ xe',
        ];

        foreach ($pages as $url => $text) {
            $response = $this->actingAs($this->admin, 'admin')->get($url);
            $response->assertStatus(200);
            $response->assertSee($text);
        }
    }

    /** @test */
    public function test_admin_khong_the_truy_cap_khi_chua_dang_nhap()
    {
        $pages = [
            '/admin',
            '/admin/routes',
            '/admin/tickets',
            '/admin/drivers',
            '/admin/assistants',
            '/admin/busroutes',
            '/admin/buses',
        ];

        foreach ($pages as $url) {
            $response = $this->get($url);
            $response->assertRedirect('/admin/login'); // Redirect về login nếu chưa đăng nhập
        }
    }

    /** @test */
    public function test_admin_logout_thanh_cong()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->post('/admin/logout');
        $response->assertRedirect('/admin/login');
        $this->assertGuest('admin');
    }
    /** @test */
    public function test_admin_co_the_them_tai_xe()
    {
        $this->actingAs($this->admin, 'admin');

        $postData = [
            'fullname' => 'Nguyen Van A',
            'birthday' => '1990-01-01',
            'gender' => 'Nam',
            'address' => 'TP HCM',
            'phone_number' => '0909123456',
            'email' => 'driver' . time() . '@example.com', // unique
            'license_number' => 'LX123456',
            'bus_route_id' => 1,
            'username' => 'driver' . time(), // unique
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/admin/drivers', $postData);

        // Kỳ vọng redirect về danh sách tài xế
        $response->assertRedirect('/admin/drivers');

        // Kiểm tra dữ liệu đã lưu trong DB
        $this->assertDatabaseHas('users', [
            'fullname' => 'Nguyen Van A',
            'username' => 'nguyenvana',
            'email' => 'nguyenvana@example.com',
            'role' => 'driver', // quan trọng
        ]);
    }

    /** @test */
    public function test_admin_them_tai_xe_fail_khi_thieu_thong_tin_bat_buoc()
    {
        $this->actingAs($this->admin, 'admin');

        // Dữ liệu thiếu fullname
        $postData = [
            'birthday' => '1990-01-01',
            'gender' => 'Nam',
            'address' => '123 Đường ABC, Quận 1',
            'phone_number' => '0909123456',
            'email' => 'test@example.com',
            'license_number' => 'LX123456',
            'bus_route_id' => 1,
            'username' => 'testuser',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        $response = $this->post('/admin/drivers', $postData);

        $response->assertSessionHasErrors(['fullname']);
    }

    /** @test */
    public function test_admin_co_the_vao_trang_them_tai_xe()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get('/admin/drivers/create');

        $response->assertStatus(200);
        $response->assertSee('Họ và Tên');
        $response->assertSee('Ngày sinh');
        $response->assertSee('Giới tính');
        $response->assertSee('Lưu Tài xế');
    }
    /** @test */
    public function test_admin_co_the_chinh_sua_tai_xe()
    {
        $this->actingAs($this->admin, 'admin');

        // Tạo tài xế test
        $driver = \App\Models\User::factory()->create([
            'role' => 'driver',
            'fullname' => 'Nguyen Van A',
            'username' => 'driver_edit_' . time(),
            'email' => 'driver_edit_' . time() . '@example.com',
        ]);

        $updateData = [
            'fullname' => 'Nguyen Van B',
            'birthday' => '1991-02-02',
            'gender' => 'Nam',
            'address' => '123 Updated Street',
            'phone_number' => '0909876543',
            'email' => 'updated_' . time() . '@example.com',
            'license_number' => 'LX987654',
            'bus_route_id' => 2,
            'username' => 'driver_updated_' . time(),
        ];

        $response = $this->put("/admin/drivers/{$driver->id}", $updateData);

        $response->assertRedirect('/admin/drivers');

        $this->assertDatabaseHas('users', [
            'id' => $driver->id,
            'fullname' => 'Nguyen Van B',
            'username' => $updateData['username'],
            'email' => $updateData['email'],
            'role' => 'driver',
        ]);
    }

    /** @test */
    public function test_admin_co_the_xoa_tai_xe()
    {
        $this->actingAs($this->admin, 'admin');

        // Tạo tài xế test
        $driver = \App\Models\User::factory()->create([
            'role' => 'driver',
            'fullname' => 'Nguyen Van Delete',
            'username' => 'driver_delete_' . time(),
            'email' => 'driver_delete_' . time() . '@example.com',
        ]);

        $response = $this->delete("/admin/drivers/{$driver->id}");

        $response->assertRedirect('/admin/drivers');

        $this->assertDatabaseMissing('users', [
            'id' => $driver->id,
            'role' => 'driver',
        ]);
    }
    /** @test */
    /** @test */
    public function test_admin_quay_lai_danh_sach_tai_xe()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get('/admin/drivers/create');
        $response->assertStatus(200);

        // Kiểm tra HTML có link tới /admin/drivers
        $response->assertSee('/admin/drivers');
    }
    /** @test */
    public function test_admin_co_the_them_phu_xe()
    {
        $this->actingAs($this->admin, 'admin');

        $postData = [
            'fullname' => 'Nguyen Van B',
            'birthday' => '1990-01-01',
            'gender' => 'Nam',
            'address' => 'TP HCM',
            'phone_number' => '0909123456',
            'bus_route_id' => 1,
            'username' => 'assistant' . time(), // unique
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/admin/assistants', $postData);

        // Kỳ vọng redirect về danh sách phụ xe
        $response->assertRedirect('/admin/assistants');

        // Kiểm tra dữ liệu đã lưu trong DB (bỏ email)
        $this->assertDatabaseHas('users', [
            'fullname' => 'Nguyen Van B',
            'username' => $postData['username'],
            'role' => 'assistant', // quan trọng
        ]);
    }

    /** @test */
    public function test_admin_them_phu_xe_fail_khi_thieu_thong_tin_bat_buoc()
    {
        $this->actingAs($this->admin, 'admin');

        $postData = [
            'birthday' => '1990-01-01',
            'gender' => 'Nam',
            'address' => '123 Đường ABC, Quận 1',
            'phone_number' => '0909123456',
            'bus_route_id' => 1,
            'username' => 'testuser',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        $response = $this->post('/admin/assistants', $postData);

        $response->assertSessionHasErrors(['fullname']);
    }

    /** @test */
    public function test_admin_co_the_vao_trang_them_phu_xe()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get('/admin/assistants/create');

        $response->assertStatus(200);
        $response->assertSee('Họ và Tên');
        $response->assertSee('Ngày sinh');
        $response->assertSee('Giới tính');
        $response->assertSee('Lưu Phụ Xe');
    }

    /** @test */
    public function test_admin_co_the_chinh_sua_phu_xe()
    {
        $this->actingAs($this->admin, 'admin');

        $assistant = \App\Models\User::factory()->create([
            'role' => 'assistant',
            'fullname' => 'Nguyen Van B',
            'username' => 'assistant_edit_' . time(),
        ]);

        $updateData = [
            'fullname' => 'Nguyen Van C',
            'birthday' => '1991-02-02',
            'gender' => 'Nam',
            'address' => '123 Updated Street',
            'phone_number' => '0909876543',
            'bus_route_id' => 2,
            'username' => 'assistant_updated_' . time(),
        ];

        $response = $this->put("/admin/assistants/{$assistant->id}", $updateData);

        $response->assertRedirect('/admin/assistants');

        $this->assertDatabaseHas('users', [
            'id' => $assistant->id,
            'fullname' => 'Nguyen Van C',
            'username' => $updateData['username'],
            'role' => 'assistant',
        ]);
    }

    /** @test */
    public function test_admin_co_the_xoa_phu_xe()
    {
        $this->actingAs($this->admin, 'admin');

        $assistant = \App\Models\User::factory()->create([
            'role' => 'assistant',
            'fullname' => 'Nguyen Van Delete',
            'username' => 'assistant_delete_' . time(),
            'email' => 'assistant_delete_' . time() . '@example.com',
        ]);

        $response = $this->delete("/admin/assistants/{$assistant->id}");

        $response->assertRedirect('/admin/assistants');

        $this->assertDatabaseMissing('users', [
            'id' => $assistant->id,
            'role' => 'assistant',
        ]);
    }

    /** @test */
    public function test_admin_quay_lai_danh_sach_phu_xe()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get('/admin/assistants/create');
        $response->assertStatus(200);

        // Kiểm tra HTML có link tới /admin/assistants
        $response->assertSee('/admin/assistants');
    }
    /** @test */
    public function test_admin_them_ve_nguoi_thuong()
    {
        $this->actingAs($this->admin, 'admin');

        $user = \App\Models\User::factory()->create();

        $postData = [
            'user_id' => $user->id,
            'ticket_type' => 'regular',
            'price_hidden' => 6000, // người thường
        ];

        $response = $this->post('/admin/tickets', $postData);

        $response->assertRedirect('/admin/tickets');

        $this->assertDatabaseHas('tickets', [
            'user_id' => $user->id,
            'ticket_type' => 'regular',
            'price' => 6000,
        ]);
    }

    /** @test */
    public function test_admin_them_ve_nguoi_gia()
    {
        $this->actingAs($this->admin, 'admin');

        $user = \App\Models\User::factory()->create([
            'birthday' => now()->subYears(70)->toDateString() // > 65 tuổi
        ]);

        $postData = [
            'user_id' => $user->id,
            'ticket_type' => 'elderly',
            'age' => 70,
            'price_hidden' => 0, // người già miễn phí
        ];

        $response = $this->post('/admin/tickets', $postData);

        $response->assertRedirect('/admin/tickets');

        $this->assertDatabaseHas('tickets', [
            'user_id' => $user->id,
            'ticket_type' => 'elderly',
            'price' => 0,
        ]);
    }
    /** @test */
    public function test_admin_them_ve_sinh_vien_co_the()
    {
        $this->actingAs($this->admin, 'admin');
        $user = \App\Models\User::factory()->create();

        $postData = [
            'user_id' => $user->id,
            'ticket_type' => 'student',
            'has_student_card' => 1,
            'price_hidden' => 3000,
        ];

        $response = $this->post('/admin/tickets', $postData);

        $response->assertRedirect('/admin/tickets');

        $this->assertDatabaseHas('tickets', [
            'user_id' => $user->id,
            'ticket_type' => 'student',
            'price' => 3000,
        ]);
    }

    /** @test */
    public function test_admin_them_ve_sinh_vien_khong_the()
    {
        $this->actingAs($this->admin, 'admin');
        $user = \App\Models\User::factory()->create();

        $postData = [
            'user_id' => $user->id,
            'ticket_type' => 'student',
            // không check has_student_card
            'price_hidden' => 6000,
        ];

        $response = $this->post('/admin/tickets', $postData);

        $response->assertRedirect('/admin/tickets');

        $this->assertDatabaseHas('tickets', [
            'user_id' => $user->id,
            'ticket_type' => 'student',
            'price' => 6000,
        ]);
    }
    /** @test */
    /** @test */
    /** @test */
    public function test_admin_co_the_chinh_sua_ve()
    {
        $this->actingAs($this->admin, 'admin');

        // Tạo vé test
        $ticket = Ticket::create([
            'user_id' => 1,
            'ticket_type' => 'student',
            'has_student_card' => 1,
            'age' => null,
            'price' => 3000,
        ]);

        // Cập nhật: sinh viên không thẻ
        $updateData = [
            'user_id' => 1,
            'ticket_type' => 'student',
            'has_student_card' => 0,
            'age' => null,
        ];

        $response = $this->put("/admin/tickets/{$ticket->id}", $updateData);
        $response->assertRedirect('/admin/tickets');

        $ticket->refresh();
        $this->assertEquals(6000, $ticket->price);
        $this->assertEquals(0, $ticket->has_student_card);
        $this->assertEquals('student', $ticket->ticket_type);
    }

    /** @test */
    public function test_admin_co_the_xoa_ve()
    {
        $this->actingAs($this->admin, 'admin');

        $ticket = Ticket::create([
            'user_id' => 1,
            'ticket_type' => 'regular',
            'has_student_card' => 0,
            'age' => null,
            'price' => 6000,
        ]);

        $response = $this->delete("/admin/tickets/{$ticket->id}");
        $response->assertRedirect('/admin/tickets');

        $this->assertDatabaseMissing('tickets', [
            'id' => $ticket->id,
        ]);
    }
    /** @test */
    public function test_admin_co_the_tim_kiem_tuyen_theo_id()
    {
        $this->actingAs($this->admin, 'admin');

        $route = BusRoute::create([
            'ma_tuyen' => '99',
            'diem_di' => 'A',
            'diem_den' => 'B',
            'thoi_gian_bat_dau' => '05:00:00',
            'thoi_gian_ket_thuc' => '20:00:00',
        ]);

        $response = $this->get('/admin/routes?bus_route_id=' . $route->id);
        $response->assertStatus(200);
        $response->assertSee($route->ma_tuyen);
        $response->assertSee($route->diem_di);
        $response->assertSee($route->diem_den);
    }

    /** @test */
    public function test_admin_co_the_xem_chi_tiet_tuyen()
    {
        $this->actingAs($this->admin, 'admin');

        $route = BusRoute::create([
            'ma_tuyen' => '01',
            'diem_di' => 'Bến Thành',
            'diem_den' => 'Bến Xe Miền Đông',
            'thoi_gian_bat_dau' => '05:00:00',
            'thoi_gian_ket_thuc' => '20:00:00',
        ]);

        $response = $this->get("/admin/routes/{$route->id}");
        $response->assertStatus(200);
        $response->assertSee($route->ma_tuyen);
        $response->assertSee($route->diem_di);
        $response->assertSee($route->diem_den);
        $response->assertSee('05:00');
        $response->assertSee('20:00');
    }

    /** @test */
    public function test_admin_co_the_chinh_sua_tuyen()
    {
        $this->actingAs($this->admin, 'admin');

        $route = BusRoute::create([
            'ma_tuyen' => '01' . time(),
            'diem_di' => 'Bến Thành',
            'diem_den' => 'Bến Xe Miền Đông',
            'thoi_gian_bat_dau' => '05:00:00',
            'thoi_gian_ket_thuc' => '20:00:00',
        ]);
        $maTuyenUpdated = '01A' . substr(time(), -7);

        $updateData = [
            'ma_tuyen' => $maTuyenUpdated,
            'diem_di' => 'BX A',
            'diem_den' => 'BX B',
            'thoi_gian_bat_dau' => '06:00:00',
            'thoi_gian_ket_thuc' => '21:00:00',
        ];

        $response = $this->put("/admin/routes/{$route->id}", $updateData);
        $response->assertRedirect('/admin/routes');

        $this->assertDatabaseHas('bus_routes', [
            'id' => $route->id,
            'ma_tuyen' => $maTuyenUpdated,
            'diem_di' => 'BX A',
            'diem_den' => 'BX B',
        ]);
    }
    /** @test */
    public function test_admin_co_the_them_tuyen()
    {
        $this->actingAs($this->admin, 'admin');
        $maTuyen = '99' . substr(time(), -8);

        $data = [
            'ma_tuyen' => $maTuyen,
            'diem_di' => 'A',
            'diem_den' => 'B',
            'thoi_gian_bat_dau' => '05:00:00',
            'thoi_gian_ket_thuc' => '20:00:00',
        ];

        $response = $this->post('/admin/routes', $data);
        $response->assertRedirect('/admin/routes');

        $this->assertDatabaseHas('bus_routes', [
            'ma_tuyen' => $maTuyen,
            'diem_di' => 'A',
            'diem_den' => 'B',
        ]);
    }
    /** @test */
    public function test_admin_co_the_them_bus()
    {
        $this->actingAs($this->admin, 'admin');

        // Tạo dữ liệu bus mới
        $busNumber = 'B' . substr(time(), -6); // giữ dưới 10 ký tự
        $data = [
            'bus_number' => $busNumber,
            'model' => 'Test Model',
            'year' => 2025,
            'capacity' => 30,
            'status' => 'active',
            'bus_route_id' => 1, // chọn tuyến có sẵn
            'driver_id' => null,  // không chọn tài xế
        ];

        $response = $this->post('/admin/buses', $data);

        $response->assertRedirect('/admin/buses');

        $this->assertDatabaseHas('buses', [
            'bus_number' => $busNumber,
            'model' => 'Test Model',
            'year' => 2025,
            'capacity' => 30,
            'status' => 'active',
            'bus_route_id' => 1,
        ]);
    }

    /** @test */
    public function test_admin_co_the_chinh_sua_bus()
    {
        $this->actingAs($this->admin, 'admin');

        // Tạo bus mẫu
        $bus = \App\Models\Bus::create([
            'bus_number' => 'B' . time(),
            'model' => 'Old Model',
            'year' => 2020,
            'capacity' => 40,
            'status' => 'active',
            'bus_route_id' => 1,
            'driver_id' => null,
        ]);

        // Dữ liệu cập nhật
        $updateData = [
            'bus_number' => 'B' . (time() + 1),
            'model' => 'New Model',
            'year' => 2023,
            'capacity' => 50,
            'status' => 'maintenance',
            'bus_route_id' => 2,
            'driver_id' => null,
        ];

        $response = $this->put("/admin/buses/{$bus->id}", $updateData);

        $response->assertRedirect('/admin/buses');

        $this->assertDatabaseHas('buses', [
            'id' => $bus->id,
            'bus_number' => $updateData['bus_number'],
            'model' => $updateData['model'],
            'year' => $updateData['year'],
            'capacity' => $updateData['capacity'],
            'status' => $updateData['status'],
            'bus_route_id' => $updateData['bus_route_id'],
        ]);
    }
    /** @test */
    public function test_admin_co_the_xoa_bus()
    {
        $this->actingAs($this->admin, 'admin');

        // Tạo bus mẫu
        $bus = \App\Models\Bus::create([
            'bus_number' => 'BDEL123',
            'model' => 'To Be Deleted',
            'year' => 2022,
            'capacity' => 40,
            'status' => 'active',
            'bus_route_id' => 1,
            'driver_id' => null,
        ]);

        // Gửi request xoá
        $response = $this->delete("/admin/buses/{$bus->id}");

        // Kiểm tra redirect
        $response->assertRedirect(route('admin.buses.index'));

        // Kiểm tra database không còn bus
        $this->assertDatabaseMissing('buses', [
            'id' => $bus->id,
            'bus_number' => 'BDEL123',
        ]);
    }
}

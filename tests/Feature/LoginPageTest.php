<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Tạo user và admin nếu chưa tồn tại
        User::firstOrCreate(
            ['username' => 'user123'],
            [
                'fullname' => 'Nguyen Van A',
                'password' => Hash::make('123456'),
                'is_verified' => true,
            ]
        );

        Admin::firstOrCreate(
            ['username' => 'admin123'],
            [
                'password' => Hash::make('admin111'),
            ]
        );
    }

    /** @test */
    public function test_form_dang_nhap_user_hien_thi()
    {
        $response = $this->get(route('auth.loginuser_get'));
        $response->assertStatus(200);
        $response->assertSee('Đăng nhập');
    }

    /** @test */
    public function test_form_dang_nhap_admin_hien_thi()
    {
        $response = $this->get(route('auth.loginadmin_get'));
        $response->assertStatus(200);
        $response->assertSee('Đăng nhập');
    }

    /** @test */
    public function test_user_dang_nhap_thanh_cong()
    {
        $response = $this->post(route('auth.login_user'), [
            'username' => 'user123',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticatedAs(User::where('username', 'user123')->first(), 'web');
    }

    /** @test */
    public function test_user_khong_the_dang_nhap_voi_mat_khau_sai()
    {
        $response = $this->post(route('auth.login_user'), [
            'username' => 'user123',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Tên đăng nhập hoặc mật khẩu không đúng');
        $this->assertGuest('web');
    }

    /** @test */
    public function test_user_khong_the_dang_nhap_khi_khong_nhap_mat_khau()
    {
        $response = $this->post(route('auth.login_user'), [
            'username' => 'user123',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest('web');
    }

    /** @test */
    public function test_user_khong_the_dang_nhap_voi_truong_rong()
    {
        $response = $this->post(route('auth.login_user'), [
            'username' => '',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username', 'password']);
        $this->assertGuest('web');
    }

    /** @test */
    public function test_admin_dang_nhap_thanh_cong()
    {
        $response = $this->post(route('auth.login_admin'), [
            'username' => 'admin123',
            'password' => 'admin111',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs(Admin::where('username', 'admin123')->first(), 'admin');
    }

    /** @test */
    public function test_admin_khong_the_dang_nhap_voi_mat_khau_sai()
    {
        $response = $this->post(route('auth.login_admin'), [
            'username' => 'admin123',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Tên đăng nhập hoặc mật khẩu không đúng');
        $this->assertGuest('admin');
    }

    /** @test */
    public function test_admin_khong_the_dang_nhap_khi_khong_nhap_mat_khau()
    {
        $response = $this->post(route('auth.login_admin'), [
            'username' => 'admin123',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest('admin');
    }

    /** @test */
    public function test_admin_khong_the_dang_nhap_voi_truong_rong()
    {
        $response = $this->post(route('auth.login_admin'), [
            'username' => '',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username', 'password']);
        $this->assertGuest('admin');
    }
    /** @test */
    public function test_form_dang_ky_hien_thi()
    {
        $response = $this->get('/sub'); // form đăng ký
        $response->assertStatus(200);
        $response->assertSee('ĐĂNG KÝ');
    }
    /** @test */
    public function test_user_dang_ky_thanh_cong()
    {
        $response = $this->post('/register', [
            'fullname' => 'Nguyen Van B',
            'username' => 'newuser123',
            'email' => 'newuser@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

        // Redirect sau khi đăng ký thành công
        $response->assertStatus(302);

        // Kiểm tra user đã tạo trong database
        $this->assertDatabaseHas('users', [
            'username' => 'newuser123',
            'email' => 'newuser@example.com',
        ]);
    }

    /** @test */
    public function test_dang_ky_that_bai_khi_thieu_truong_bat_buoc()
    {
        $response = $this->post('/register', [
            'fullname' => '',
            'username' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['fullname', 'username', 'email', 'password']);
    }

    /** @test */
    public function test_dang_ky_that_bai_khi_email_khong_hop_le()
    {
        $response = $this->post('/register', [
            'fullname' => 'User Test',
            'username' => 'usertest',
            'email' => 'not-an-email',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function test_dang_ky_that_bai_khi_mat_khau_xac_nhan_khong_khop()
    {
        $response = $this->post('/register', [
            'fullname' => 'User Test',
            'username' => 'usertest',
            'email' => 'usertest@example.com',
            'password' => '123456',
            'password_confirmation' => 'abcdef', // sai
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function test_khong_duoc_dang_ky_voi_username_trung_lap()
    {
        // Dọn dẹp nếu tồn tại
        User::where('username', 'duplicate123')->delete();
        User::where('email', 'old@example.com')->delete();

        // Tạo user với username bị trùng
        User::factory()->create([
            'username' => 'duplicate123',
            'email' => 'old@example.com',
        ]);

        // Gửi request đăng ký
        $response = $this->post('/register', [
            'fullname' => 'New User',
            'username' => 'duplicate123',  // username trùng
            'email' => 'new@example.com', // email hợp lệ
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Kỳ vọng báo lỗi
        $response->assertSessionHasErrors(['username']);
    }

    /** @test */
    public function test_khong_duoc_dang_ky_voi_email_trung_lap()
    {
        // XÓA email cũ nếu tồn tại
        User::where('email', 'duplicate@example.com')->delete();

        // Tạo user
        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);

        // Gửi request
        $response = $this->post('/register', [
            'fullname' => 'Test User',
            'username' => 'otheruser',
            'email' => 'duplicate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}

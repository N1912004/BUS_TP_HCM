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
}

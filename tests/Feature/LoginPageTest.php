<?php

namespace Tests\Feature;
use App\Models\Admin; 
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase; // Phải có dòng này

class LoginPageTest extends TestCase
{
    /** @test */   
    use RefreshDatabase;
    public function test_tai_trang_chon_dang_nhap()
    {
        $response = $this->get('/roles'); // URL trang chọn quyền đăng nhập
        $response->assertStatus(200);
        $response->assertSee('Hãy chọn quyền đăng nhập');
        $response->assertSee('Admin');
        $response->assertSee('Người dùng');
    }

    /** @test */
    public function test_nhap_nut_admin_va_nguoi_dung()
    {
        $adminResponse = $this->get('/admin');
        $userResponse = $this->get('/user');

        $adminResponse->assertStatus(200);
        $userResponse->assertStatus(200);
    }

    /** @test */
    public function test_dang_nhap_nguoi_dung_thanh_cong()
    {
        $response = $this->post('/loginuser', [
            'username' => '123123',
            'password' => '123123',
        ]);

        $response->assertStatus(302); // Redirect sau khi đăng nhập
        $response->assertRedirect('/user');
    }

    /** @test */
    public function test_dang_nhap_nguoi_dung_sai_mat_khau()
    {
        $response = $this->post('/loginuser', [
            'username' => 'user123',
            'password' => '123123123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Tên đăng nhập hoặc mật khẩu không đúng');
    }

    /** @test */
    public function test_dang_nhap_nguoi_dung_khong_nhap_mat_khau()
    {
        $response = $this->post('/loginuser', [
            'username' => 'user123',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function test_dang_nhap_nguoi_dung_de_trong_cac_truong()
    {
        $response = $this->post('/loginuser', [
            'username' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['username', 'password']);
    }

    /** @test */
    public function test_dang_nhap_admin_thanh_cong()
    {
        $response = $this->post('/loginadmin', [
            'username' => 'admin123',
            'password' => 'admin111',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin');
    }

    /** @test */
    public function test_dang_nhap_admin_sai_mat_khau()
    {
        $admin = Admin::create([
            'username' => 'admin_test_quick',
            'password' => Hash::make('admin111'),
            // Thêm các trường BẮT BUỘC khác của bảng admins vào đây
        ]);
        $response = $this->post('/loginadmin', [
            'username' => 'admin123',
            'password' => '123123123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Tên đăng nhập hoặc mật khẩu không đúng');
    }

    /** @test */
    public function test_dang_nhap_admin_khong_nhap_mat_khau()
    {
        $response = $this->post('/loginadmin', [
            'username' => 'admin123',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function test_dang_nhap_admin_de_trong_cac_truong()
    {
        $response = $this->post('/loginadmin', [
            'username' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['username', 'password']);
    }
}
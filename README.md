# Ứng dụng Quản lý Xe Buýt (Laravel)

Đây là một ứng dụng web được xây dựng bằng Laravel để quản lý các tuyến xe buýt, điểm dừng, xe buýt, tài xế, phụ xe và vé. Ứng dụng này cung cấp giao diện cho người dùng cuối và bảng điều khiển quản trị viên để quản lý hệ thống.

## 1. Giới thiệu

Dự án này là một hệ thống quản lý xe buýt toàn diện, bao gồm các chức năng sau:

* **Quản lý người dùng:** Đăng ký, đăng nhập (người dùng và quản trị viên), quản lý hồ sơ.
* **Quản lý tuyến xe buýt:** Tạo, xem, cập nhật, xóa các tuyến xe buýt.
* **Quản lý điểm dừng xe buýt:** Tạo, xem, cập nhật, xóa các điểm dừng.
* **Quản lý xe buýt:** Thêm, sửa, xóa thông tin xe buýt.
* **Quản lý tài xế và phụ xe:** Quản lý thông tin tài xế và phụ xe.
* **Quản lý vé:** Tạo và quản lý vé.
* **Bản đồ tuyến đường:** Hiển thị các tuyến xe buýt trên bản đồ.
* **API:** Cung cấp các API cho ứng dụng di động hoặc các dịch vụ bên ngoài.

## 2. Cài đặt

Để cài đặt và chạy dự án này trên máy cục bộ của bạn, hãy làm theo các bước sau:

1. **Clone repository:**

   ```bash
   git clone https://github.com/N1912004/BUS_TP_HCM.git
   cd laravelversion1.com
   ```
2. **Cài đặt Composer dependencies:**

   ```bash
   composer install
   ```
3. **Cài đặt Node.js dependencies:**

   ```bash
   npm install
   npm run dev
   ```
4. **Tạo file `.env`:**
   Sao chép file `.env.example` và đổi tên thành `.env`.

   ```bash
   cp .env.example .env
   ```
5. **Tạo khóa ứng dụng:**

   ```bash
   php artisan key:generate
   ```
6. **Cấu hình cơ sở dữ liệu:**
   Mở file `.env` và cấu hình thông tin cơ sở dữ liệu của bạn.

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```
7. **Chạy migrations và seeders:**

   ```bash
   php artisan migrate --seed
   ```

   Lệnh này sẽ tạo các bảng cơ sở dữ liệu và điền dữ liệu mẫu.
8. **Chạy máy chủ phát triển:**

   ```bash
   php artisan serve
   ```

   Ứng dụng sẽ có sẵn tại `http://127.0.0.1:8000`.

## 3. Luồng Code Chính

Dự án được tổ chức theo kiến trúc MVC (Model-View-Controller) của Laravel.

### 3.1. Web (Frontend/Admin Panel)

* **`routes/web.php`**: Định nghĩa các tuyến đường cho giao diện người dùng và bảng điều khiển quản trị viên.
  * **Xác thực (Authentication):**
    * `AuthController`: Xử lý đăng nhập, đăng ký, đăng xuất cho cả người dùng và quản trị viên.
    * `auth.loginuser_get`, `auth.login_user`: Đăng nhập cho người dùng thông thường.
    * `admin/login`: Đăng nhập cho quản trị viên.
    * `register`: Đăng ký tài khoản mới.
    * `logout`: Đăng xuất.
    * `password/email`: Chức năng quên mật khẩu.
  * **Chức năng người dùng:**
    * `/`: Trang chào mừng.
    * `/lang/{locale}`: Chuyển đổi ngôn ngữ (tiếng Anh/tiếng Việt).
    * `/map-route`, `/bus-map`: Hiển thị bản đồ tuyến xe buýt.
    * `/dashboard/user`: Bảng điều khiển người dùng.
    * `/profile`: Hồ sơ người dùng.
  * **Chức năng quản trị viên (tiền tố `/admin` và middleware `auth.admin:admin`):**
    * `/admin`: Bảng điều khiển quản trị viên.
    * `RouteController`: Quản lý các tuyến đường (CRUD).
    * `TicketController`: Quản lý vé (CRUD).
    * `DriverController`: Quản lý tài xế (CRUD).
    * `AssistantController`: Quản lý phụ xe (CRUD).
    * `BusRouteController`: Quản lý các tuyến xe buýt (CRUD).
    * `BusController`: Quản lý xe buýt (CRUD).
* **`app/Http/Controllers/Backend`**: Chứa các controller xử lý logic cho giao diện web và bảng điều khiển quản trị viên.
* **`app/Models`**: Chứa các Eloquent models tương ứng với các bảng trong cơ sở dữ liệu (ví dụ: `User`, `Admin`, `Bus`, `BusRoute`, `BusStop`, `Ticket`, `Route`).
* **`resources/views`**: Chứa các file Blade templates để hiển thị giao diện người dùng và quản trị viên.

### 3.2. API (Mobile/External Services)

* **`routes/api.php`**: Định nghĩa các tuyến đường API, thường được sử dụng bởi ứng dụng di động hoặc các dịch vụ bên ngoài.
  * `/user`: Lấy thông tin người dùng đã xác thực (yêu cầu xác thực Sanctum).
  * `bus-routes`: API tài nguyên cho các tuyến xe buýt (`Api\BusRouteController`).
  * `busstops`: API tài nguyên cho các điểm dừng xe buýt (`Api\BusStopController`).
  * `/register`: API đăng ký người dùng (`Api\RegisterController`).
  * `/login`: API đăng nhập người dùng (`Api\LoginController`).
  * `/admin/stats`: API thống kê cho quản trị viên (`Api\AdminStatsController`).
  * `/admin/routes/{driverId}`: API lấy tuyến đường theo tài xế (`Backend\BusController`).
* **`app/Http/Controllers/Api`**: Chứa các controller xử lý logic cho các API.

## 4. Hướng dẫn sử dụng

### 4.1. Dành cho người dùng

1. **Đăng ký/Đăng nhập:**
   * Truy cập `/loginuser` để đăng nhập hoặc `/register` để đăng ký tài khoản mới.
2. **Xem bản đồ tuyến đường:**
   * Sau khi đăng nhập, truy cập `/map-route` hoặc `/bus-map` để xem các tuyến xe buýt trên bản đồ.
3. **Hồ sơ cá nhân:**
   * Truy cập `/profile` để xem và cập nhật thông tin cá nhân.
4. **Chuyển đổi ngôn ngữ:**
   * Sử dụng `/lang/en` hoặc `/lang/vi` để chuyển đổi ngôn ngữ giao diện.

### 4.2. Dành cho quản trị viên

1. **Đăng nhập quản trị viên:**
   * Truy cập `/admin/login` để đăng nhập vào bảng điều khiển quản trị viên.
2. **Bảng điều khiển quản trị:**
   * Sau khi đăng nhập, bạn sẽ được chuyển hướng đến `/admin` (bảng điều khiển chính).
3. **Quản lý các thực thể:**
   * **Tuyến đường (`Routes`):** Truy cập `/admin/routes` để quản lý các tuyến đường chính.
   * **Vé (`Tickets`):** Truy cập `/admin/tickets` để quản lý vé.
   * **Tài xế (`Drivers`):** Truy cập `/admin/drivers` để quản lý thông tin tài xế.
   * **Phụ xe (`Assistants`):** Truy cập `/admin/assistants` để quản lý thông tin phụ xe.
   * **Tuyến xe buýt (`BusRoutes`):** Truy cập `/admin/busroutes` để quản lý các tuyến xe buýt cụ thể.
   * **Xe buýt (`Buses`):** Truy cập `/admin/buses` để quản lý thông tin xe buýt.

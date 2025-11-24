# Ứng dụng Quản lý Xe Buýt (Laravel)

Đây là một ứng dụng web được xây dựng bằng Laravel để quản lý các tuyến xe buýt, điểm dừng, xe buýt, tài xế, phụ xe và vé. Ứng dụng này cung cấp giao diện cho người dùng cuối và bảng điều khiển quản trị viên để quản lý hệ thống.

## 1. Giới thiệu

Dự án này là một hệ thống quản lý xe buýt toàn diện, bao gồm các chức năng sau:
*   **Quản lý người dùng:** Đăng ký, đăng nhập (người dùng và quản trị viên), quản lý hồ sơ.
*   **Quản lý tuyến xe buýt:** Tạo, xem, cập nhật, xóa các tuyến xe buýt.
*   **Quản lý điểm dừng xe buýt:** Tạo, xem, cập nhật, xóa các điểm dừng.
*   **Quản lý xe buýt:** Thêm, sửa, xóa thông tin xe buýt.
*   **Quản lý tài xế và phụ xe:** Quản lý thông tin tài xế và phụ xe.
*   **Quản lý vé:** Tạo và quản lý vé.
*   **Bản đồ tuyến đường:** Hiển thị các tuyến xe buýt trên bản đồ.
*   **API:** Cung cấp các API cho ứng dụng di động hoặc các dịch vụ bên ngoài.

## 2. Cài đặt

Để cài đặt và chạy dự án này trên máy cục bộ của bạn, hãy làm theo các bước sau:

1.  **Clone repository:**
    ```bash
    git clone https://github.com/N1912004/BUS_TP_HCM.git
    cd laravelversion1.com
    ```

2.  **Cài đặt Composer dependencies:**
    ```bash
    composer install
    ```

3.  **Cài đặt Node.js dependencies:**
    ```bash
    npm install
    npm run dev
    ```

4.  **Tạo file `.env`:**
    Sao chép file `.env.example` và đổi tên thành `.env`.
    ```bash
    cp .env.example .env
    ```

5.  **Tạo khóa ứng dụng:**
    ```bash
    php artisan key:generate
    ```

6.  **Cấu hình cơ sở dữ liệu:**
    Mở file `.env` và cấu hình thông tin cơ sở dữ liệu của bạn.
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

7.  **Chạy migrations và seeders:**
    ```bash
    php artisan migrate --seed
    ```
    Lệnh này sẽ tạo các bảng cơ sở dữ liệu và điền dữ liệu mẫu.

8.  **Chạy máy chủ phát triển:**
    ```bash
    php artisan serve
    ```
    Ứng dụng sẽ có sẵn tại `http://127.0.0.1:8000`.

## 3. Phân tích Luồng Code và Kiến thức Laravel trong Ứng dụng

Dự án này là một ứng dụng web và API được xây dựng trên framework Laravel, tuân thủ chặt chẽ kiến trúc MVC (Model-View-Controller) và sử dụng nhiều tính năng cốt lõi của Laravel để quản lý các tuyến xe buýt, điểm dừng, xe buýt, tài xế, phụ xe và vé.

### 3.1. Kiến Trúc và Luồng Code Chính:

*   **Vòng Đời Request (Request Lifecycle):**
    1.  **Request đến (Incoming Request):** Mọi yêu cầu HTTP đến ứng dụng đều đi qua `public/index.php`.
    2.  **Bootstrap:** `bootstrap/app.php` khởi tạo ứng dụng Laravel, đăng ký các service providers (`App\Providers\AppServiceProvider`, `App\Providers\RouteServiceProvider`) và tải các cấu hình.
    3.  **Routing:** Yêu cầu được chuyển đến `routes/web.php` (cho giao diện web và admin) hoặc `routes/api.php` (cho API). Hệ thống định tuyến (Routing) của Laravel sẽ tìm tuyến đường phù hợp dựa trên URL và phương thức HTTP.
    4.  **Middleware:** Nếu một tuyến đường có middleware được gán (ví dụ: `auth:sanctum` cho API, `auth` cho người dùng đã đăng nhập, `auth.admin:admin` cho quản trị viên), các middleware này sẽ xử lý yêu cầu trước khi nó đến controller, thực hiện các tác vụ như xác thực, kiểm tra quyền hạn, chuyển đổi ngôn ngữ (`App\Http\Middleware\SetLocale.php`).
    5.  **Controller:** Yêu cầu đến một phương thức cụ thể trong Controller. Controller xử lý logic nghiệp vụ.
    6.  **Model (Eloquent ORM):** Controller tương tác với các Model (`App\Models\*`) để truy vấn, tạo, cập nhật hoặc xóa dữ liệu trong cơ sở dữ liệu. Eloquent ORM giúp thao tác với DB dễ dàng thông qua các đối tượng PHP.
    7.  **View (Blade Templating):** Đối với các yêu cầu web, Controller tải và truyền dữ liệu tới một Blade View (`resources/views/*`). Blade engine biên dịch view thành HTML thuần. Đối với API, Controller trả về dữ liệu JSON.
    8.  **Response Trả Về (Response):** Ứng dụng gửi phản hồi HTTP (HTML hoặc JSON) trở lại trình duyệt/client.

### 3.2. Các Kiến Thức Laravel Cốt Lõi Được Sử Dụng:

*   **MVC (Model-View-Controller):**
    *   **Models (`app/Models`):** `User`, `Admin`, `Bus`, `BusRoute`, `BusStop`, `Ticket`, `SessionUser`. Chúng là cầu nối với cơ sở dữ liệu, định nghĩa quan hệ (`User::with('busRoute')`), và xử lý logic liên quan đến dữ liệu.
    *   **Views (`resources/views/backend`):** Các file Blade (`.blade.php`) như `login_user_bus.blade.php`, `index_admin.blade.php`, `map_route.blade.php` để hiển thị giao diện người dùng và quản trị viên.
    *   **Controllers (`app/Http/Controllers`):** Chia thành `Api` (cho API), `Backend` (cho người dùng web), và `Admin` (cho bảng điều khiển quản trị).

*   **Routing (`routes/web.php`, `routes/api.php`):**
    *   **Web Routes:** Định nghĩa các URL cho giao diện người dùng và quản trị viên, bao gồm xác thực, quản lý ngôn ngữ, hiển thị bản đồ và các chức năng CRUD cho admin (dùng `Route::resource` cho các tài nguyên như `routes`, `tickets`, `drivers`, `assistants`, `busroutes`, `buses`).
    *   **API Routes:** Cung cấp các endpoint RESTful cho ứng dụng di động hoặc các dịch vụ bên ngoài, sử dụng `Route::apiResource` và các routes tùy chỉnh.

*   **Eloquent ORM:**
    *   Được sử dụng rộng rãi trong các Controller để tương tác với cơ sở dữ liệu, ví dụ: `BusRoute::all()`, `BusRoute::find($id)`, `BusRoute::create($validated)`, `User::where('username', ...)`, `BusRoute::count()`.
    *   Sử dụng quan hệ (`with('busRoute')`) để tải dữ liệu liên quan.
    *   Truy vấn nâng cao: `Api\BusStopController` sử dụng `selectRaw` với công thức Haversine để tìm điểm dừng xe buýt gần vị trí cụ thể.

*   **Authentication & Authorization (Xác thực & Ủy quyền):**
    *   **Guard (Vệ sĩ):** `Auth::guard('admin')->attempt($credentials)` cho thấy việc sử dụng Guard tùy chỉnh cho quản trị viên, tách biệt với guard `web` mặc định cho người dùng.
    *   **Xác thực người dùng:** `Auth::attempt($credentials)` để đăng nhập người dùng.
    *   **Password Hashing:** `bcrypt($request->password)` trong `Api\RegisterController` và `Hash::make($request->password)` trong `Backend\AuthController::PostRegister` để mã hóa mật khẩu.
    *   **Password Reset:** `Backend\AuthController` triển khai chức năng quên và đặt lại mật khẩu của Laravel, sử dụng `Password::broker()->sendResetLink` và `Password::broker()->reset`.
    *   **API Authentication:** `auth:sanctum` middleware trong `routes/api.php` cho `/user` endpoint, cho thấy việc sử dụng Laravel Sanctum để quản lý API token authentication.
    *   **Middleware ủy quyền:** `middleware('auth.admin:admin')` để bảo vệ các tuyến đường quản trị viên.

*   **Validation (Xác thực dữ liệu):**
    *   **Form Requests:** `App\Http\Requests\AuthRequest` và `App\Http\Requests\RegisterRequest` được sử dụng để định nghĩa các quy tắc xác thực phức tạp và rõ ràng cho các yêu cầu đăng nhập và đăng ký.
    *   **`$request->validate()`:** Được sử dụng trực tiếp trong các Controller (ví dụ `Admin\RouteController::store`, `Api\BusRouteController::nearby`) cho các quy tắc xác thực đơn giản hơn hoặc cục bộ.

*   **Localization (Bản địa hóa):**
    *   `App::setLocale($locale)` và `session()->put('locale', $locale)` trong `routes/web.php` cho phép chuyển đổi ngôn ngữ giao diện (tiếng Anh/tiếng Việt).
    *   Sử dụng hàm `__()` để dịch các chuỗi trong các View và Controller (ví dụ: `__($route->diem_di)`, `__('map_route.metro_line')`).

*   **Middleware:**
    *   `App\Http\Middleware\AdminAuthenticate.php`: Để kiểm tra xem người dùng có phải là quản trị viên đã đăng nhập hay không.
    *   `App\Http\Middleware\SetLocale.php`: Để thiết lập ngôn ngữ dựa trên phiên hoặc tham số URL.

*   **Phản hồi JSON (JSON Responses):**
    *   Sử dụng `response()->json(...)` trong các API Controller để trả về dữ liệu dưới dạng JSON.

*   **Xử lý Ngoại lệ và Log:**
    *   `Log::info`, `Log::warning`, `Log::error` được sử dụng trong `Backend\AuthController` để ghi lại các sự kiện quan trọng và hỗ trợ debug.
    *   `abort(400)` khi ngôn ngữ không hợp lệ.
    *   Trả về phản hồi lỗi (400, 404) trong các API Controller.

## 4. Các API Thực Tế Được Định Nghĩa trong Code:

Dựa trên `routes/api.php` và các Controller liên quan:

*   **User Information:**
    *   `GET /api/user` (Protected by `auth:sanctum`)
        *   **Controller:** Inline closure, returns authenticated user data.
        *   **Mô tả:** Lấy thông tin của người dùng hiện tại đã được xác thực thông qua Laravel Sanctum.

*   **Bus Routes (Tuyến xe buýt):**
    *   `GET /api/bus-routes`
        *   **Controller:** `Api\BusRouteController@index`
        *   **Mô tả:** Lấy danh sách tất cả các tuyến xe buýt với thông tin chi tiết (mã tuyến, điểm đi, điểm đến, tên, mô tả, thời gian, giá, tọa độ).
    *   `POST /api/bus-routes`
        *   **Controller:** `Api\BusRouteController@store`
        *   **Mô tả:** Tạo một tuyến xe buýt mới (hiện tại logic chưa được triển khai).
    *   `GET /api/bus-routes/{id}`
        *   **Controller:** `Api\BusRouteController@show`
        *   **Mô tả:** Hiển thị thông tin chi tiết của một tuyến xe buýt cụ thể theo ID (hiện tại logic chưa được triển khai).
    *   `PUT/PATCH /api/bus-routes/{id}`
        *   **Controller:** `Api\BusRouteController@update`
        *   **Mô tả:** Cập nhật thông tin của một tuyến xe buýt cụ thể theo ID (hiện tại logic chưa được triển khai).
    *   `DELETE /api/bus-routes/{id}`
        *   **Controller:** `Api\BusRouteController@destroy`
        *   **Mô tả:** Xóa một tuyến xe buýt cụ thể theo ID (hiện tại logic chưa được triển khai).
    *   `GET /api/bus-routes/{id}/stations`
        *   **Controller:** `Api\BusRouteController@getStations`
        *   **Mô tả:** Lấy danh sách các điểm dừng (stations) cho một tuyến xe buýt cụ thể. Logic này có các điểm dừng cứng cho "Metro 1" và tạo các điểm dừng giả định từ tọa độ của tuyến.
    *   `GET /api/bus-routes/{id}/schedule`
        *   **Controller:** `Api\BusRouteController@getSchedule`
        *   **Mô tả:** Lấy lịch trình của một tuyến xe buýt cụ thể (sử dụng dữ liệu placeholder).
    *   `GET /api/bus-routes/nearby`
        *   **Controller:** `Api\BusRouteController@nearby`
        *   **Mô tả:** Tìm kiếm các tuyến xe buýt gần một vị trí địa lý (latitude, longitude) trong một bán kính nhất định, sử dụng công thức Haversine.

*   **Bus Stops (Điểm dừng xe buýt):**
    *   `GET /api/busstops`
        *   **Controller:** `Api\BusStopController@index`
        *   **Mô tả:** Lấy danh sách tất cả các điểm dừng xe buýt.
    *   `POST /api/busstops`
        *   **Controller:** `Api\BusStopController@store`
        *   **Mô tả:** Tạo một điểm dừng xe buýt mới.
    *   `GET /api/busstops/{id}`
        *   **Controller:** `Api\BusStopController@show`
        *   **Mô tả:** Hiển thị thông tin chi tiết của một điểm dừng xe buýt cụ thể theo ID.
    *   `GET /api/busstops/nearby`
        *   **Controller:** `Api\BusStopController@nearby`
        *   **Mô tả:** Tìm kiếm các điểm dừng xe buýt gần một vị trí địa lý (latitude, longitude) trong một bán kính nhất định, sử dụng công thức Haversine trong truy vấn SQL.

*   **Authentication (API):**
    *   `POST /api/register`
        *   **Controller:** `Api\RegisterController@register`
        *   **Mô tả:** Đăng ký người dùng mới, lưu thông tin username, fullname, email, password (đã mã hóa) vào bảng `users`.
    *   `POST /api/login`
        *   **Controller:** `Api\LoginController@login`
        *   **Mô tả:** Endpoint đăng nhập (hiện tại logic chưa được triển khai đầy đủ trong `Api\LoginController`).

*   **Admin Statistics (Thống kê quản trị viên):**
    *   `GET /api/admin/stats`
        *   **Controller:** `Api\AdminStatsController@getStats`
        *   **Mô tả:** Lấy các số liệu thống kê tổng quan như tổng số tuyến, xe buýt, người dùng và tài xế.

*   **Routes by Driver (Tuyến đường theo tài xế):**
    *   `GET /api/admin/routes/{driverId}`
        *   **Controller:** `Backend\BusController@getRoutesByDriver`
        *   **Mô tả:** Lấy các tuyến đường được gán cho một tài xế cụ thể (dựa trên `driverId`). Sử dụng Eloquent relationship `User::with('busRoute')`.

## 5. Hướng dẫn sử dụng

## 4. Hướng dẫn sử dụng

### 4.1. Dành cho người dùng

1.  **Đăng ký/Đăng nhập:**
    *   Truy cập `/loginuser` để đăng nhập hoặc `/register` để đăng ký tài khoản mới.
2.  **Xem bản đồ tuyến đường:**
    *   Sau khi đăng nhập, truy cập `/map-route` hoặc `/bus-map` để xem các tuyến xe buýt trên bản đồ.
3.  **Hồ sơ cá nhân:**
    *   Truy cập `/profile` để xem và cập nhật thông tin cá nhân.
4.  **Chuyển đổi ngôn ngữ:**
    *   Sử dụng `/lang/en` hoặc `/lang/vi` để chuyển đổi ngôn ngữ giao diện.

### 4.2. Dành cho quản trị viên

1.  **Đăng nhập quản trị viên:**
    *   Truy cập `/admin/login` để đăng nhập vào bảng điều khiển quản trị viên.
2.  **Bảng điều khiển quản trị:**
    *   Sau khi đăng nhập, bạn sẽ được chuyển hướng đến `/admin` (bảng điều khiển chính).
3.  **Quản lý các thực thể:**
    *   **Tuyến đường (`Routes`):** Truy cập `/admin/routes` để quản lý các tuyến đường chính.
    *   **Vé (`Tickets`):** Truy cập `/admin/tickets` để quản lý vé.
    *   **Tài xế (`Drivers`):** Truy cập `/admin/drivers` để quản lý thông tin tài xế.
    *   **Phụ xe (`Assistants`):** Truy cập `/admin/assistants` để quản lý thông tin phụ xe.
    *   **Tuyến xe buýt (`BusRoutes`):** Truy cập `/admin/busroutes` để quản lý các tuyến xe buýt cụ thể.
    *   **Xe buýt (`Buses`):** Truy cập `/admin/buses` để quản lý thông tin xe buýt.

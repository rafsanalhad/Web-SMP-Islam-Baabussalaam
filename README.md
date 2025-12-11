<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

**Cara Menjalankan (Local)**

Pastikan Anda sudah menginstal `composer` dan `php` di sistem. Ikuti langkah berikut untuk menjalankan aplikasi secara lokal:

-   Salin file lingkungan dan sesuaikan konfigurasi database:

    -   Pada Windows (PowerShell / CMD):

        ```powershell
        copy .env.example .env
        ```

    -   Pada macOS / Linux:

        ```bash
        cp .env.example .env
        ```

-   Instal dependensi PHP:

    ```bash
    composer install
    ```

-   Generate application key:

    ```bash
    php artisan key:generate
    ```

-- Jalankan migrasi database dan seed (bersihkan database terlebih dahulu):

    ```bash
    php artisan migrate:fresh --seed
    ```

-   Apakah perlu mengubah `.env` dan mengisi nama database?

    Ya — sebelum menjalankan migrasi Anda harus mengatur koneksi database di file `.env` (kolom `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). Contoh pengaturan minimal untuk MySQL lokal (Laragon/xampp):

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=root
    DB_PASSWORD=
    ```

    Jika database belum dibuat, buat dahulu lewat phpMyAdmin atau dari terminal MySQL, contohnya:

    ```bash
    mysql -u root -e "CREATE DATABASE nama_database_anda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    ```

-   Menjalankan server pengembangan:

    ```bash
    php artisan serve --host=127.0.0.1 --port=8000
    ```

Setelah langkah di atas selesai, buka http://127.0.0.1:8000 di peramban Anda.

**Akun Login Admin (contoh)**

-   Username: `admin`
-   Password: `admin123`

Catatan: Pastikan data pengguna admin sudah ada di database (seeder) atau buat pengguna admin baru jika perlu.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Redberry](https://redberry.international/laravel-development)**
-   **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

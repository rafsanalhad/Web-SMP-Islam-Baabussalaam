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

| Jenis Evidence | Contoh |
|---|---|
| Bukti kode | Potongan controller, route, atau blade yang menunjukkan sumber masalah |
| HTTP request | Request hasil intercept dari Burp Proxy / Repeater / Intruder |
| HTTP response | Response yang menunjukkan perubahan status, error, atau payload aktif |
| Dampak visual | Alert XSS, file upload tersimpan, status data berubah |
| Before-after code | Snippet kode sebelum dan sesudah mitigasi |

## 3. Ringkasan Temuan

| ID | Vulnerability | OWASP 2021 | Severity | Bukti Utama | Status Validasi |
|---|---|---|---|---|---|
| V-01 | File upload berisiko pada News | A03 Injection | Critical | Upload handler menyimpan filename asli | Valid |
| V-02 | File upload berisiko pada Teacher | A03 Injection | Critical | Upload handler menyimpan filename asli | Valid |
| V-03 | File upload berisiko pada Facility | A03 Injection | Critical | Upload handler memakai ekstensi asli | Valid |
| V-04 | File upload berisiko pada Gallery | A03 Injection | Critical | Upload handler memakai ekstensi asli | Valid |
| V-05 | Aksi perubahan status via GET | A01 / A05 | High | Route GET untuk toggle status | Valid |
| V-06 | Login brute force tanpa limit | A07 | High | Auth::attempt tanpa rate limit | Valid |
| V-07 | Password policy terlalu lemah | A07 | Medium | Min 6 karakter saja | Valid |
| V-08 | Stored XSS pada halaman About | A03 Injection | High | Output raw `{!! !!}` | Valid |
| V-09 | Stored XSS pada halaman Academic | A03 Injection | High | Output raw `{!! !!}` | Valid |
| V-10 | DOM XSS pada preview halaman admin | A03 Injection | High | `innerHTML` dari dataset | Valid |
| V-11 | DOM XSS pada preview guru/staff | A03 Injection | High | `innerHTML` dari input user | Valid |
| V-12 | DOM XSS pada preview fasilitas | A03 Injection | Medium | `innerHTML` dari features | Valid |
| V-13 | Debug mode aktif | A05 Security Misconfiguration | Medium | `APP_DEBUG=true` | Valid |

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

#### PoC Burp Suite
1. Intercept request upload berita.
2. Kirim ke Repeater.
3. Ubah nama file pada multipart body.
4. Kirim request dan amati apakah file tersimpan.
5. Cek akses file dari browser.

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

#### PoC Burp Suite
1. Intercept upload foto teacher.
2. Modifikasi filename di multipart.
3. Kirim request.
4. Verifikasi file hasil upload.

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

#### PoC Burp Suite
1. Intercept request fasilitas.
2. Ubah bagian file multipart.
3. Kirim request.
4. Cek file hasil upload.

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

#### PoC Burp Suite
1. Intercept upload gambar galeri.
2. Modifikasi file part.
3. Kirim request dan verifikasi hasil.

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

#### PoC Burp Suite
1. Login sebagai user aktif.
2. Kirim request GET ke endpoint toggle.
3. Amati status berita berubah.
4. Uji juga dengan request dari halaman eksternal.

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

#### PoC Burp Suite
1. Tangkap request login.
2. Kirim ke Intruder.
3. Variasikan password dengan wordlist.
4. Amati tidak adanya throttling.

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

#### PoC Burp Suite
1. Intercept request create user.
2. Ubah password menjadi 123456 atau sejenisnya.
3. Kirim request.
4. Periksa bahwa akun tetap terbentuk.

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

#### PoC Burp Suite
1. Intercept request penyimpanan konten about.
2. Masukkan payload HTML/JavaScript.
3. Simpan perubahan.
4. Buka halaman `/tentang`.
5. Payload aktif di browser.

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

#### PoC Burp Suite
1. Simpan payload pada konten akademik.
2. Buka halaman `/akademik`.
3. Verifikasi script berjalan.

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

#### PoC Burp Suite
1. Simpan payload di content halaman.
2. Buka daftar halaman admin.
3. Klik tombol preview.
4. Payload muncul sebagai DOM XSS.

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

#### PoC Burp Suite
1. Masukkan payload ke field name atau position.
2. Simpan data.
3. Klik preview.
4. Payload aktif dalam modal.

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

#### PoC Burp Suite
1. Isi field features dengan payload berbahaya.
2. Simpan data.
3. Buka preview fasilitas.
4. Script aktif.

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

#### PoC Burp Suite
1. Kirim request invalid ke endpoint yang memicu error.
2. Amati response di Burp.
3. Jika detail exception muncul, informasi sensitif terekspos.

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

| ID | Dampak Utama | Jika Dibiarkan |
|---|---|---|
| V-01 | Integritas file publik | File berbahaya bisa tersimpan di webroot |
| V-02 | Integritas file publik | Foto/staff file bisa disalahgunakan |
| V-03 | Integritas file publik | Folder fasilitas menjadi media serangan |
| V-04 | Integritas file publik | Upload gallery bisa jadi vektor payload |
| V-05 | Integritas data berita | Status berita bisa diubah tanpa kontrol yang tepat |
| V-06 | Confidentiality | Akun bisa di-bruteforce |
| V-07 | Confidentiality | Password lemah mudah ditebak |
| V-08 | Confidentiality / Integrity | Pengunjung dapat terkena stored XSS |
| V-09 | Confidentiality / Integrity | Pengunjung dapat terkena stored XSS |
| V-10 | Confidentiality / Integrity | Admin panel dapat diserang lewat DOM XSS |
| V-11 | Confidentiality / Integrity | Admin panel dapat diserang lewat DOM XSS |
| V-12 | Confidentiality / Integrity | Preview fasilitas dapat dieksploitasi |
| V-13 | Confidentiality | Informasi internal terekspos lewat error |

## 6. Tabel Evidence yang Disarankan untuk Screenshot

| ID | Screenshot 1 | Screenshot 2 | Screenshot 3 | Screenshot 4 |
|---|---|---|---|---|
| V-01 | Kode upload news | Request Burp | File tersimpan | Snippet fix |
| V-02 | Kode upload teacher | Request Burp | File tersimpan | Snippet fix |
| V-03 | Kode upload facility | Request Burp | File tersimpan | Snippet fix |
| V-04 | Kode upload gallery | Request Burp | File tersimpan | Snippet fix |
| V-05 | Route GET toggle | Request GET | Status berubah | Snippet fix |
| V-06 | Kode login | Intruder | Tanpa lockout | Snippet fix |
| V-07 | Password policy | Request create user | Password lemah diterima | Snippet fix |
| V-08 | Blade raw output | Request simpan payload | Alert XSS | Snippet fix |
| V-09 | Blade raw output | Request simpan payload | Alert XSS | Snippet fix |
| V-10 | JS preview | Payload tersimpan | Alert DOM XSS | Snippet fix |
| V-11 | JS preview | Payload tersimpan | Alert DOM XSS | Snippet fix |
| V-12 | JS preview | Payload tersimpan | Alert DOM XSS | Snippet fix |
| V-13 | `.env` debug | Error response | Stack trace | Snippet fix |

## 7. Kesimpulan

Temuan paling kuat pada target ini ada pada tiga area utama: keamanan file upload, XSS, dan kontrol autentikasi. Untuk kebutuhan proposal skripsi, struktur laporan ini sudah sesuai untuk dijadikan dasar pembahasan asesmen, validasi PoC, edukasi dampak, dan mitigasi before-after.

Jika ingin dibawa ke tahap implementasi, prioritas perbaikan paling tinggi adalah:
1. Menutup file upload yang masih berisiko.
2. Menghapus semua output raw dan `innerHTML` yang berasal dari data user.
3. Menambahkan rate limiting dan password policy yang lebih kuat.
4. Mematikan debug mode pada lingkungan selain lokal.

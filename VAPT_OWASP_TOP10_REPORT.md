# VAPT Report - OWASP Top 10

Target: Web-SMP-Islam-Baabussalaam (Laravel)
Tanggal asesmen: 2026-04-06
Scope: source code review + PoC steps untuk environment testing internal

## Catatan penting

- Semua PoC di dokumen ini hanya untuk lingkungan yang kamu punya izin (lab/skripsi).
- Saya sengaja TIDAK mengubah kode aplikasi, sesuai permintaanmu, agar bukti before tetap ada.

## Ringkasan temuan

| ID   | Judul                                              | OWASP Top 10                                 | Severity | Status PoC |
| ---- | -------------------------------------------------- | -------------------------------------------- | -------- | ---------- |
| F-01 | Middleware admin tidak verifikasi role             | A01 Broken Access Control                    | High     | Valid      |
| F-02 | Privilege escalation: editor bisa buat akun admin  | A01 Broken Access Control                    | Critical | Valid      |
| F-03 | Privilege abuse: editor bisa ubah/hapus user lain  | A01 Broken Access Control                    | High     | Valid      |
| F-04 | State-changing via GET: toggle status berita       | A01 + A05                                    | High     | Valid      |
| F-05 | Login tanpa rate limiting (brute force)            | A07 Identification & Authentication Failures | High     | Valid      |
| F-06 | Kebijakan password lemah (min 6, tanpa complexity) | A07 Identification & Authentication Failures | Medium   | Valid      |
| F-07 | Stored XSS pada konten halaman Tentang             | A03 Injection                                | High     | Valid      |
| F-08 | Stored XSS pada konten halaman Akademik            | A03 Injection                                | High     | Valid      |
| F-09 | DOM XSS pada preview halaman admin                 | A03 Injection                                | High     | Valid      |
| F-10 | DOM XSS pada preview guru/staff admin              | A03 Injection                                | High     | Valid      |
| F-11 | DOM XSS pada preview fitur fasilitas admin         | A03 Injection                                | Medium   | Valid      |
| F-12 | Security Misconfiguration: APP_DEBUG=true          | A05 Security Misconfiguration                | Medium   | Valid      |

---

## F-01 - Middleware admin tidak verifikasi role

Severity: High  
OWASP: A01 Broken Access Control

### Bukti kode (before)

- `app/Http/Middleware/AdminMiddleware.php:23`
- `app/Http/Middleware/AdminMiddleware.php:28`
- `routes/web.php:30`

```php
// app/Http/Middleware/AdminMiddleware.php
if (!Auth::check()) {
    return redirect()->route('login');
}

if (Auth::user()->status !== 'active') {
    Auth::logout();
    return redirect()->route('login')->withErrors(['username' => 'Akun Anda tidak aktif.']);
}

return $next($request);
```

### Kenapa ini vuln

Route admin memakai middleware `admin`, tapi middleware hanya cek login + status aktif, tidak cek `role === admin`.

### PoC (testing)

1. Login sebagai user role `editor`.
2. Buka `/admin/dashboard` atau `/admin/users`.
3. Request diterima (harusnya ditolak untuk non-admin).

### Rekomendasi fix (after - contoh)

```php
if (!Auth::check()) {
    return redirect()->route('login');
}

$user = Auth::user();
if ($user->status !== 'active') {
    Auth::logout();
    return redirect()->route('login')->withErrors(['username' => 'Akun Anda tidak aktif.']);
}

if ($user->role !== 'admin') {
    abort(403, 'Forbidden');
}

return $next($request);
```

---

## F-02 - Privilege escalation: editor bisa buat akun admin

Severity: Critical  
OWASP: A01 Broken Access Control

### Bukti kode (before)

- `app/Http/Controllers/Admin/UserController.php:25`
- `routes/web.php:56`

```php
// UserController@store
'role' => 'required|in:admin,editor',
```

### Kenapa ini vuln

Karena F-01, user editor bisa akses endpoint `/admin/users` lalu membuat akun baru role `admin`.

### PoC (testing)

1. Login sebagai editor.
2. Kirim POST ke `/admin/users` dengan payload role=admin.
3. Logout, login pakai akun baru admin -> akses penuh.

Contoh request (form-urlencoded):

```http
POST /admin/users
username=pivot_admin
fullname=Pivot Admin
email=pivot_admin@test.local
password=Password123!
role=admin
status=active
```

### Rekomendasi fix (after - contoh)

```php
if (auth()->user()->role !== 'admin') {
    abort(403);
}

// Atau policy/gate untuk store user
```

---

## F-03 - Privilege abuse: editor bisa ubah/hapus user lain

Severity: High  
OWASP: A01 Broken Access Control

### Bukti kode (before)

- `app/Http/Controllers/Admin/UserController.php:45`
- `app/Http/Controllers/Admin/UserController.php:69`

```php
if ($user->id == auth()->id()) {
    return redirect()->route('admin.users.index')->with('error', 'Tidak dapat mengubah akun sendiri!');
}
```

### Kenapa ini vuln

Hanya mencegah edit/hapus diri sendiri. Tidak mencegah editor mengedit admin lain (misalnya nonaktifkan admin).

### PoC (testing)

1. Login sebagai editor.
2. Ubah user admin target lewat PUT `/admin/users/{id}` jadi `inactive`.
3. Admin target tidak bisa login.

### Rekomendasi fix (after - contoh)

```php
if (auth()->user()->role !== 'admin') {
    abort(403);
}

if ($user->role === 'admin') {
    abort(403, 'Tidak boleh mengubah akun admin lain');
}
```

---

## F-04 - State-changing via GET: toggle status berita

Severity: High  
OWASP: A01 + A05

### Bukti kode (before)

- `routes/web.php:38`
- `resources/views/admin/berita/index.blade.php` (link GET untuk toggle)

```php
Route::get('/berita/{id}/toggle-status', [NewsController::class, 'toggleStatus'])->name('admin.berita.toggle');
```

### Kenapa ini vuln

Aksi ubah data (publish/draft) dilakukan dengan GET. Ini rawan CSRF-style triggering via link/image/script dari website lain.

### PoC (testing)

Saat admin sedang login, buka halaman attacker yang memuat:

```html
<img src="http://target.local/admin/berita/1/toggle-status" />
```

Status berita berubah tanpa interaksi form CSRF yang benar.

### Rekomendasi fix (after - contoh)

```php
// routes/web.php
Route::post('/berita/{id}/toggle-status', [NewsController::class, 'toggleStatus'])
    ->name('admin.berita.toggle');
```

Dan gunakan `<form method="POST">@csrf`.

---

## F-05 - Login tanpa rate limiting (brute force)

Severity: High  
OWASP: A07

### Bukti kode (before)

- `app/Http/Controllers/AuthController.php:28`

```php
if (Auth::attempt($credentials, $request->filled('remember'))) {
    // ...
}
```

### Kenapa ini vuln

Tidak ada throttle/RateLimiter per username+IP, sehingga password dapat ditebak via brute force.

### PoC (testing)

Gunakan script loop kirim POST login berkali-kali ke `/admin/login`. Tidak ada lockout/throttle response.

Contoh pseudo-command:

```bash
for p in password1 password2 password3; do
  curl -s -X POST http://target.local/admin/login -d "username=admin&password=$p"
done
```

### Rekomendasi fix (after - contoh)

```php
use Illuminate\Support\Facades\RateLimiter;

$key = strtolower($request->input('username')).'|'.$request->ip();
if (RateLimiter::tooManyAttempts($key, 5)) {
    return back()->withErrors(['username' => 'Terlalu banyak percobaan login.']);
}

if (!Auth::attempt($credentials, $request->filled('remember'))) {
    RateLimiter::hit($key, 60);
    return back()->withErrors(['username' => 'Username atau password salah.']);
}

RateLimiter::clear($key);
```

---

## F-06 - Kebijakan password lemah

Severity: Medium  
OWASP: A07

### Bukti kode (before)

- `app/Http/Controllers/Admin/UserController.php:24`

```php
'password' => 'required|min:6',
```

### Kenapa ini vuln

Minimal 6 karakter tanpa kompleksitas membuat password lemah lebih mudah ditebak.

### PoC (testing)

Buat user dengan password sederhana `123456` atau `qwerty1` (jika lolos min length).

### Rekomendasi fix (after - contoh)

```php
use Illuminate\Validation\Rules\Password;

'password' => ['required', Password::min(12)->letters()->mixedCase()->numbers()->symbols()],
```

---

## F-07 - Stored XSS pada halaman Tentang

Severity: High  
OWASP: A03 Injection

### Bukti kode (before)

- `resources/views/frontend/about.blade.php:13`

```blade
{!! $page->content !!}
```

### Kenapa ini vuln

Output raw HTML tanpa sanitasi. Jika attacker (atau editor terkompromi) menyimpan payload JS di `page->content`, script dieksekusi di browser pengunjung.

### PoC (testing)

1. Masuk admin halaman management.
2. Isi konten dengan payload:

```html
<script>
    alert("XSS-ABOUT");
</script>
```

3. Buka `/tentang` dan payload berjalan.

### Rekomendasi fix (after - contoh)

```blade
{{ $page->content }}
```

Atau sanitasi whitelist HTML (mis. HTMLPurifier) jika rich text diperlukan.

---

## F-08 - Stored XSS pada halaman Akademik

Severity: High  
OWASP: A03 Injection

### Bukti kode (before)

- `resources/views/frontend/academic.blade.php:13`

```blade
{!! $page->content !!}
```

### Kenapa ini vuln

Sama seperti F-07, output raw HTML dapat mengeksekusi JS berbahaya.

### PoC (testing)

1. Simpan payload ke konten halaman akademik.
2. Buka `/akademik`.
3. Payload dieksekusi.

### Rekomendasi fix (after - contoh)

```blade
{{ $page->content }}
```

atau sanitasi HTML whitelist.

---

## F-09 - DOM XSS pada preview halaman admin

Severity: High  
OWASP: A03 Injection

### Bukti kode (before)

- `resources/views/admin/halaman/index.blade.php:146`

```javascript
document.getElementById("preview-content").innerHTML = btn.dataset.content;
```

### Kenapa ini vuln

Data dari `dataset.content` (berasal dari DB) dimasukkan ke `innerHTML` tanpa sanitasi.

### PoC (testing)

1. Buat halaman dengan content:

```html
<img src=x onerror=alert('DOM-XSS-HALAMAN')>
```

2. Klik tombol preview di admin halaman.
3. Alert muncul di panel admin.

### Rekomendasi fix (after - contoh)

```javascript
document.getElementById("preview-content").textContent = btn.dataset.content;
```

Jika butuh rich HTML, lakukan sanitasi client/server terlebih dahulu.

---

## F-10 - DOM XSS pada preview guru/staff admin

Severity: High  
OWASP: A03 Injection

### Bukti kode (before)

- `resources/views/admin/guru/index.blade.php:32`

```javascript
document.getElementById("preview-body").innerHTML = html;
```

### Kenapa ini vuln

`html` dibangun dari `btn.dataset.*` (name, position, email, dll) yang berasal dari input user dan langsung dirender sebagai HTML.

### PoC (testing)

1. Isi field `name` atau `position` dengan payload:

```html
<svg/onload=alert('DOM-XSS-GURU')>
```

2. Klik preview pada data tersebut.
3. Payload berjalan di browser admin.

### Rekomendasi fix (after - contoh)

- Bangun elemen DOM dengan `textContent`, bukan template string HTML mentah.
- Atau escape semua data sebelum interpolasi.

---

## F-11 - DOM XSS pada preview fitur fasilitas admin

Severity: Medium  
OWASP: A03 Injection

### Bukti kode (before)

- `resources/views/admin/fasilitas/index.blade.php:162`

```javascript
document.getElementById("preview-features").innerHTML = f
    .map(
        (x) =>
            `<p class='mb-0'><i class='fas fa-check text-success me-1'></i>${x.trim()}</p>`,
    )
    .join("");
```

### Kenapa ini vuln

`features` di-split lalu setiap nilai dimasukkan ke `innerHTML` tanpa escaping.

### PoC (testing)

Isi features dengan:

```text
lab, <img src=x onerror=alert('DOM-XSS-FASILITAS')>
```

Klik preview -> payload dieksekusi.

### Rekomendasi fix (after - contoh)

Gunakan `createElement` + `textContent`:

```javascript
const container = document.getElementById("preview-features");
container.innerHTML = "";
f.forEach((x) => {
    const p = document.createElement("p");
    p.className = "mb-0";
    p.textContent = x.trim();
    container.appendChild(p);
});
```

---

## F-12 - Security Misconfiguration: APP_DEBUG=true

Severity: Medium  
OWASP: A05 Security Misconfiguration

### Bukti kode (before)

- `.env:4`

```env
APP_DEBUG=true
```

### Kenapa ini vuln

Saat error terjadi, stack trace dan detail internal app bisa terekspos (path, class, query, environment detail).

### PoC (testing)

1. Trigger error (misalnya akses endpoint dengan kondisi invalid yang melempar exception).
2. Amati halaman debug stack trace muncul.

### Rekomendasi fix (after - contoh)

```env
APP_ENV=production
APP_DEBUG=false
```

---

## Bonus hardening (tambahan untuk bab mitigasi skripsi)

1. Tambahkan security headers global (CSP, X-Frame-Options/frame-ancestors, X-Content-Type-Options, Referrer-Policy).
2. Terapkan policy/authorization per aksi (`Gate`/`Policy`) bukan hanya middleware umum.
3. Gunakan sanitasi HTML whitelist untuk konten CMS jika memang butuh rich text.
4. Audit semua endpoint state-changing agar bukan GET dan wajib CSRF token.
5. Aktifkan cookie secure pada deployment HTTPS (`SESSION_SECURE_COOKIE=true`).

---

## Template bukti screenshot (siap dipakai)

Untuk tiap finding, ambil 4 screenshot:

1. Before code snippet (baris bukti file).
2. Request PoC (Burp/Postman/cURL/browser).
3. Result/impact (alert XSS, data berubah, akses terbuka, dll).
4. Proposed fix snippet (bagian "after - contoh" di report ini).

Dengan pola ini kamu bisa bikin bab: Temuan -> Validasi PoC -> Dampak -> Mitigasi.

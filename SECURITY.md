# Dokumentasi Keamanan Sistem Login

## Ringkasan Implementasi Keamanan

Sistem login telah diimplementasikan dengan berbagai lapisan keamanan untuk melindungi dari serangan umum seperti SQL Injection, XSS, CSRF, dan brute force attacks.

## Fitur Keamanan yang Diimplementasikan

### 1. **JWT (JSON Web Token) Authentication**
- ✅ Menggunakan library `firebase/php-jwt` untuk generate dan verify token
- ✅ Token disimpan di cookie dengan flag `HttpOnly` (mencegah XSS)
- ✅ Token juga disimpan di session sebagai fallback
- ✅ Token memiliki expiration time (24 jam)
- ✅ Support refresh token untuk memperpanjang sesi

**File terkait:**
- `app/Libraries/JWTService.php` - Service untuk generate dan verify JWT
- `app/Controllers/Auth.php` - Controller yang menggunakan JWT
- `app/Filters/AuthFilter.php` - Filter yang verify JWT token

### 2. **SQL Injection Protection**
- ✅ Semua query menggunakan CodeIgniter Query Builder (parameterized queries)
- ✅ Tidak ada raw SQL query yang menggunakan user input langsung
- ✅ Input sudah di-sanitize sebelum digunakan

**Contoh:**
```php
// AMAN - Menggunakan Query Builder
$user = $model->where('username', $username)->first();

// TIDAK AMAN (tidak digunakan)
// $user = $db->query("SELECT * FROM users WHERE username = '$username'");
```

### 3. **XSS (Cross-Site Scripting) Protection**
- ✅ Input di-sanitize menggunakan fungsi `esc()` sebelum ditampilkan
- ✅ Output di-escape di view menggunakan `esc()` helper
- ✅ Cookie dengan flag `HttpOnly` mencegah JavaScript mengakses token

### 4. **CSRF (Cross-Site Request Forgery) Protection**
- ✅ CSRF filter diaktifkan di global filters
- ✅ Form login sudah menggunakan `csrf_field()`
- ✅ Token CSRF divalidasi otomatis oleh CodeIgniter

**File terkait:**
- `app/Config/Filters.php` - CSRF filter diaktifkan
- `app/Views/auth/login.php` - Form dengan CSRF token

### 5. **Rate Limiting (Brute Force Protection)**
- ✅ Maksimal 5 percobaan login per IP + username
- ✅ Lockout selama 15 menit setelah 5 percobaan gagal
- ✅ Menggunakan cache untuk menyimpan jumlah percobaan
- ✅ Reset otomatis setelah login berhasil

**File terkait:**
- `app/Libraries/RateLimiter.php` - Library untuk rate limiting

### 6. **Password Security**
- ✅ Password di-hash menggunakan `password_hash()` (bcrypt)
- ✅ Password verification menggunakan `password_verify()` (constant-time comparison)
- ✅ Password tidak pernah disimpan dalam plain text

### 7. **Input Validation**
- ✅ Validasi username: required, min 3 karakter, max 50 karakter
- ✅ Validasi password: required, min 6 karakter
- ✅ Validasi dilakukan di server-side

**File terkait:**
- `app/Config/Validation.php` - Custom validation rules untuk login

### 8. **Session Security**
- ✅ Session menggunakan secure configuration
- ✅ Session expiration: 7200 detik (2 jam)
- ✅ Session data tidak mengandung informasi sensitif

## Cara Menggunakan

### Login
1. User mengisi form login dengan username dan password
2. Sistem memvalidasi input
3. Sistem mengecek rate limiting
4. Sistem verify password menggunakan `password_verify()`
5. Jika berhasil, sistem generate JWT token
6. Token disimpan di cookie (HttpOnly) dan session
7. User di-redirect ke dashboard sesuai role

### Logout
1. JWT token dihapus dari cookie
2. Session di-destroy
3. User di-redirect ke halaman login

### Refresh Token
- Endpoint: `POST /auth/refresh-token`
- Menggunakan token lama untuk generate token baru
- Berguna untuk memperpanjang sesi tanpa login ulang

## Konfigurasi

### JWT Secret Key
Untuk production, set JWT secret key di file `.env`:
```
JWT_SECRET_KEY=your-very-secure-secret-key-here-min-32-characters
```

Jika tidak di-set, sistem akan menggunakan default key (tidak disarankan untuk production).

### Rate Limiting Configuration
Konfigurasi dapat diubah di `app/Libraries/RateLimiter.php`:
- `$maxAttempts` - Maksimal percobaan (default: 5)
- `$lockoutTime` - Waktu lockout dalam detik (default: 900 = 15 menit)

## Testing Keamanan

### Test SQL Injection
Coba input berikut di form login:
```
Username: admin' OR '1'='1
Password: anything
```
**Hasil yang diharapkan:** Login gagal, tidak ada SQL injection yang terjadi.

### Test XSS
Coba input berikut:
```
Username: <script>alert('XSS')</script>
Password: test123
```
**Hasil yang diharapkan:** Input di-escape, tidak ada script yang dieksekusi.

### Test Rate Limiting
Coba login dengan password salah 5 kali berturut-turut.
**Hasil yang diharapkan:** Setelah 5 kali gagal, sistem akan lockout selama 15 menit.

### Test CSRF
Coba submit form login tanpa CSRF token atau dengan token yang tidak valid.
**Hasil yang diharapkan:** Request ditolak dengan error CSRF.

## Catatan Penting

1. **JWT Secret Key**: Pastikan untuk mengubah secret key di production environment
2. **HTTPS**: Disarankan menggunakan HTTPS di production untuk keamanan cookie
3. **Session Storage**: Pastikan session storage (file/database) memiliki permission yang tepat
4. **Cache**: Rate limiting menggunakan cache, pastikan cache service berfungsi dengan baik

## Dependencies

- `firebase/php-jwt` - Library untuk JWT token
- CodeIgniter 4 Framework - Sudah termasuk Query Builder dan Security features

## Update Log

- **2026-02-08**: Implementasi JWT authentication, rate limiting, dan peningkatan keamanan lainnya

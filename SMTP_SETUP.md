# Panduan Konfigurasi SMTP untuk Email Verification

## Langkah-langkah Setup Email Verification

### 1. Konfigurasi SMTP di file .env

Anda perlu mengupdate konfigurasi SMTP di file `.env` dengan kredensial email yang valid:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Opsi Konfigurasi SMTP

#### A. Menggunakan Gmail
Untuk Gmail, Anda perlu:
1. Aktifkan 2-Step Verification di akun Google Anda
2. Generate App Password:
   - Buka https://myaccount.google.com/security
   - Klik "2-Step Verification"
   - Scroll ke bawah dan klik "App passwords"
   - Generate password untuk "Mail"
   - Gunakan password ini di `MAIL_PASSWORD`

#### B. Menggunakan Mailtrap (untuk development)
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
```

#### C. Menggunakan Mailgun
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-mailgun-api-key
MAILGUN_ENDPOINT=api.mailgun.net
```

### 3. Jalankan Queue Worker (Opsional)

Jika Anda menggunakan queue untuk mengirim email:

```bash
php artisan queue:work
```

### 4. Clear Cache Setelah Update .env

```bash
php artisan config:clear
php artisan cache:clear
```

### 5. Test Email Configuration

Anda bisa test konfigurasi email dengan:

```bash
php artisan tinker
```

Kemudian jalankan:
```php
Mail::raw('Test email', function ($message) {
    $message->to('test@example.com')->subject('Test Email');
});
```

## Fitur yang Sudah Diimplementasikan

1. ✅ User model implements `MustVerifyEmail`
2. ✅ Custom email verification notification dalam bahasa Indonesia
3. ✅ Redirect ke halaman verifikasi setelah registrasi
4. ✅ Halaman verifikasi email dalam bahasa Indonesia
5. ✅ Middleware `verified` sudah ada di routes untuk proteksi halaman

## Cara Kerja

1. User melakukan registrasi
2. Sistem otomatis mengirim email verifikasi
3. User diarahkan ke halaman verifikasi (`/verify-email`)
4. User membuka email dan klik link verifikasi
5. Setelah verifikasi berhasil, user dapat mengakses semua fitur

## Troubleshooting

### Email tidak terkirim
- Pastikan konfigurasi SMTP sudah benar
- Check Laravel log di `storage/logs/laravel.log`
- Pastikan port 587 tidak diblokir firewall

### Gmail blocked
- Aktifkan "Less secure app access" (tidak direkomendasikan)
- Atau gunakan App Password (direkomendasikan)

### Development Environment
Untuk development, gunakan `MAIL_MAILER=log` untuk melihat email di log file, atau gunakan Mailtrap untuk testing.

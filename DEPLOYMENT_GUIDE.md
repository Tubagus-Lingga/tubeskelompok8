# Panduan Deployment ke Hosting (cPanel / Shared Hosting)

Panduan ini berasumsi Anda menggunakan **Shared Hosting** (seperti Niagahoster, Domainesia, Idcloudhost, dll) yang menggunakan cPanel.

> **Tips Hemat (Gratis):**
> Jika Anda mencari hosting **GRATIS** untuk tugas sekolah/kuliah, saya merekomendasikan:
> 1. **InfinityFree** (https://infinityfree.net) - Paling stabil untuk gratisan, Unlimited Bandwidth.
> 2. **000webhost** (https://id.000webhost.com) - Populer, tapi ada limitasi tidur (sleep mode).
>
> Cara uploadnya **SAMA PERSIS** dengan panduan di bawah ini (sama-sama pakai "File Manager" dan "phpMyAdmin").

---

## 1. Persiapan di Komputer Lokal

Sebelum upload, kita harus "merapikan" aplikasi dulu.

### 1.1 Build Aset Frontend (Vite)
Karena hosting biasanya tidak punya Node.js, kita harus compile CSS/JS dulu.
Jalankan perintah ini di terminal VS Code:
```powershell
npm run build
```
*Pastikan muncul folder `public/build`.*

### 1.2 Export Database
1. Buka **XAMPP Control Panel** -> Klik **Admin** pada MySQL (buka phpMyAdmin).
2. Pilih database `tubesbrand`.
3. Klik menu **Export** di bagian atas.
4. Klik **Go**.
5. Simpan file `.sql` yang terunduh (misal: `tubesbrand.sql`).

### 1.3 Bersihkan Cache (Opsional tapi Penting)
Supaya tidak ada konfigurasi lokal yang terbawa:
```powershell
php artisan optimize:clear
```

---

## 2. Persiapan File (ZIP)

Kita akan mengupload semua file **KECUALI** folder yang berat/tidak perlu.

1. **ZIP** semua file & folder proyek Anda (`app`, `bootstrap`, `config`, `database`, `public`, `resources`, `routes`, `storage`, `vendor`, `.env`, `composer.json`, dll).
2. **PENTING**: Folder `node_modules` **JANGAN** ikut di-zip (ukurannya besar dan tidak dipakai di production).
3. Pastikan folder `vendor` IKUT di-zip (karena kita tidak bisa run `composer install` di hosting murah).

---

## 3. Upload ke Hosting (File Manager)

### 3.1 Upload File

**Khusus Pengguna cPanel (Niagahoster, dll):**
Folder utama website adalah `public_html`.
1. Upload file ZIP ke folder DI LUAR `public_html` (buat folder baru `laravel_app`).
2. LANJUT KE LANGKAH 3.2.

**Khusus InfinityFree (htdocs):**
Folder utama website adalah `htdocs`. InfinityFree tidak mengizinkan buat folder di luar htdocs.
1. Buka "Online File Manager".
2. Masuk ke folder `htdocs`.
3. Buat folder baru di dalamnya, beri nama: `my_app`.
4. Upload file ZIP ke dalam folder `my_app` tersebut.
5. Klik kanan ZIP -> **Extract**.
6. **PENTING (KEAMANAN):** Masuk ke folder `my_app`, buat file baru bernama `.htaccess` (titik di depan), isi dengan kode ini lalu simpan:
   ```apache
   Deny from all
   ```
   *(Ini supaya orang tidak bisa mencuri kodingan/database Anda karena foldernya ada di area publik)*

---

### 3.2 Pindahkan Public File (Agar Website Tampil)

Tujuannya: Mengeluarkan isi folder `public` Laravel ke halaman depan hosting (`public_html` atau `htdocs`).

1. Masuk ke folder aplikasi Anda tadi (misal: `laravel_app` atau `htdocs/my_app`).
2. Masuk ke folder `public`.
3. **Select All** semua isinya (`index.php`, `robots.txt`, folder `build`, dll).
4. Klik **Move** (Pindahkan).
5. Ubah tujuannya ke folder utama hosting:
   - cPanel: `/public_html`
   - InfinityFree: `/htdocs`
6. Sekarang folder utama hosting Anda sudah berisi file index dan aset, bukan folder kosong.

### 3.3 Edit index.php

Karena file `index.php` sudah dipindah ke folder utama, kita harus perbaiki "peta" lokasinya.

1. Buka file `index.php` yang sekarang ada di folder utama (`public_html` atau `htdocs`).
2. Cari baris `require` (biasanya baris 19 dan 34).
3. Ubah arahnya menuju folder aplikasi yang Anda buat tadi.

**Contoh (Jika folder aplikasi Anda bernama `my_app`):**

```php
// Baris ~19: Arahkan ke maintenance mode (jika ada)
if (file_exists(__DIR__.'/my_app/storage/framework/maintenance.php')) {
    require __DIR__.'/my_app/storage/framework/maintenance.php';
}

// Baris ~34: Autoload Vendor
require __DIR__.'/my_app/vendor/autoload.php';

// Baris ~47: Bootstrap App
$app = require __DIR__.'/my_app/bootstrap/app.php';
```

*(Sesuaikan `my_app` dengan nama folder tempat Anda mengekstrak ZIP tadi)*

---

## 4. Setup Database di Hosting

1. Di cPanel, cari menu **MySQL Database Wizard**.
2. Buat Database baru (misal: `u12345_tubes`).
3. Buat User Database baru (misal: `u12345_user`) dan Password. **CATAT PASSWORDNYA!**
4. Add User to Database -> Centang **ALL PRIVILEGES**.
5. Buka menu **phpMyAdmin** di cPanel.
6. Pilih database baru tadi.
7. Klik **Import** -> Upload file `tubesbrand.sql` yang dari komputer lokal tadi.

---

## 5. Konfigurasi Akhir (.env)

1. Kembali ke File Manager, buka folder `laravel_app`.
2. Edit file `.env`.
3. Sesuaikan bagian ini:

```ini
APP_NAME="Vibrant Tubes"
APP_ENV=production
APP_DEBUG=false  <-- MATIKAN DEBUG DEMI KEAMANAN
APP_URL=https://namadomainanda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u12345_tubes  (Nama DB di hosting)
DB_USERNAME=u12345_user   (User DB di hosting)
DB_PASSWORD=password_tadi (Password user DB)
```

4. Simpan.

---

## 6. Selesai!

Coba buka domain Anda. Seharusnya website sudah jalan.

### Troubleshooting (Jika Error)
- **403 Forbidden / 500 Error**: Cek permission folder. Folder `storage` dan `bootstrap/cache` harus memiliki permission **775** atau **755**.
- **Symlink Error (Gambar tidak muncul)**: Di hosting shared, kadang `php artisan storage:link` susah dijalankan. Anda bisa membuat route khusus di `routes/web.php` untuk sekali jalan:
  ```php
  Route::get('/link', function () {
      Artisan::call('storage:link');
      return 'Linked!';
  });
  ```
  Buka `domain.com/link`, lalu hapus route itu setelah selesai.

restoran_fix_v2 - Full PHP + MySQL restaurant ordering system (ready to run)

Cara pakai:
1. Ekstrak folder 'restoran_fix_v2' ke folder webserver kamu (htdocs atau www).
2. Import file db_restoran.sql ke MySQL (phpMyAdmin atau CLI).
   - Database akan dibuat bernama 'restoran' ketika import.
3. Sesuaikan inc/config.php jika perlu (DB user/password).
4. Pastikan folder uploads/ writable oleh webserver (chmod 775 atau 777 jika perlu).
5. Akses: http://localhost/restoran_fix_v2/auth/login.php
   - Default akun: admin/admin123, kasir/kasir123, pelanggan/pelanggan123
6. Kamu bisa mendaftar akun baru lewat register.php (password akan di-hash).
7. Admin dapat mengelola users, menu (dengan upload gambar), kategori, dan laporan.
8. Kasir dapat mengelola pesanan; Pelanggan dapat memesan via UI.

Catatan keamanan & pengembangan:
- Ini versi pembelajaran: tambahkan CSRF protection, input sanitization tambahan, dan validasi file upload di produksi.
- Ekspor laporan (CSV/PDF) belum otomatis.

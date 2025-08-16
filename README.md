# Sistem Informasi Dashboard Visualisasi Data Inflasi Berbasis Web

**NIM:** 222111869  
**Nama:** Aisyah Devyta Maharani
**Judul Skripsi:** Pembangunan Sistem Informasi Dashboard Visualisasi Data Inflasi Berbasis Web (Studi Kasus BPS Provinsi Jawa Timur)  
**Dosen Pembimbing:** Yunarso Anang, Ph.D.

## Deskripsi Singkat Skripsi
Sistem informasi dashboard visualisasi data berbasis web yang memvisualisasikan data inflasi di wilayah Provinsi Jawa Timur.

## Struktur Folder Project

```
dashboard-inflasi/
├── app/              # Logika aplikasi utama (controller, model, dll)
├── bootstrap/        # File bootstrap dan konfigurasi awal aplikasi
├── config/           # File konfigurasi aplikasi
├── database/         # Migrasi, seeder, dan file terkait database
├── public/           # File statis yang dapat diakses langsung (gambar, favicon, dll)
├── resources/        # Resource aplikasi (view, asset, dll)
├── routes/           # Definisi routing aplikasi
├── src/              # Source code utama aplikasi (misal frontend)
├── storage/          # File hasil upload, cache, log, dll (selain yang di-ignore)
├── test/             # File dan folder untuk testing aplikasi
├── data/             # Dataset inflasi dan data pendukung
├── README.md         # Dokumentasi dan penjelasan project
├── package.json      # Konfigurasi dan dependensi project
└── ...               # File dan folder lain yang relevan
```

### Penjelasan Struktur
- **app/**: Berisi logika utama aplikasi seperti controller, model, dan middleware.
- **bootstrap/**: File bootstrap dan konfigurasi awal aplikasi.
- **config/**: File konfigurasi aplikasi (database, mail, dll).
- **database/**: Berisi migrasi, seeder, dan file terkait database.
- **public/**: Menyimpan aset statis yang dapat diakses langsung oleh pengguna (selain build, hot, storage).
- **resources/**: Resource aplikasi seperti view, asset, dan file lokal.
- **routes/**: Definisi routing aplikasi (web, API, dll).
- **src/**: Source code utama aplikasi, biasanya untuk frontend (React/Vue).
- **storage/**: File hasil upload, cache, log, dan file sementara (selain yang di-ignore).
- **test/**: Berisi file dan folder untuk testing aplikasi.
- **data/**: Dataset inflasi dan file pendukung analisis.
- **README.md**: Dokumentasi project.
- **package.json**: File konfigurasi untuk manajemen dependensi dan script project.

Catatan: Folder/file yang tercantum di .gitignore seperti node_modules, vendor, public/build, public/hot, public/storage, storage/*.key, storage/pail, dan file konfigurasi tidak dijelaskan di sini.

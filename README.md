Technical Documentation

GPM Sales Finance System

PT. Gangsar Purnama Mandiri (GPM)

1. Overview

Sistem ini adalah aplikasi berbasis web yang dibangun menggunakan PHP Native dan MySQL, digunakan untuk membantu PT. Gangsar Purnama Mandiri dalam:

Mengelola invoice penjualan barang.

Mencetak dan mengekspor invoice dan surat jalan.

Melacak laba rugi dari penjualan.

Mengelola pengeluaran perusahaan yang terkait dengan invoice.

Mengekspor data ke Excel.

2. Fitur Utama

2.1. Modul Invoice

Input data invoice penjualan dengan beberapa produk.

Detail invoice mencakup:

Nomor invoice, tanggal, perusahaan, alamat, no PO, no surat jalan, tanggal pengiriman, sales, dan jatuh tempo.

Daftar produk (nama, jumlah, satuan, harga jual).

Export dan cetak invoice dalam format yang mengikuti template resmi perusahaan.

Invoice dapat dicetak dan diekspor ke Excel menggunakan PhpSpreadsheet.

2.2. Modul Surat Jalan

Surat jalan berdasarkan data invoice.

Format cetak rapi dan sesuai template resmi.

Menyediakan fitur cetak dan export Excel.

2.3. Modul Rugi Laba

Menampilkan daftar penjualan dalam periode tertentu.

Menghitung:

Jumlah beli: qty x harga beli

Jumlah jual: qty x harga jual

Laba = jual - beli

Persentase laba

Tersedia filter berdasarkan:

Perusahaan

Tanggal awal dan akhir

Data dapat diekspor ke Excel.

2.4. Modul Pengeluaran

Input data pengeluaran terkait invoice.

Jenis pengeluaran bisa lebih dari satu (misal: transportasi, packing, dll).

Setiap jenis pengeluaran dibagi merata ke invoice yang terlibat.

Export laporan pengeluaran ke Excel.

2.5. Fitur Pendukung

Autocomplete nama perusahaan berdasarkan histori input sebelumnya.

Tampilan print-friendly untuk invoice dan surat jalan.

Integrasi DataTables untuk pencarian dan filter data.

Desain branding sesuai dengan PT. Gangsar Purnama Mandiri (logo GPM, alamat, dll).

3. Teknologi yang Digunakan

PHP 7+

MySQL

PhpSpreadsheet (library export Excel)

DataTables (plugin tabel interaktif)

HTML/CSS/Bootstrap

JavaScript (jQuery, Chart.js bila diperlukan)

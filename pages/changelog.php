<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Changelog GPM Sales Finance System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
  <style>
    body {
      background-color: #f8f9fa;
    }
    .changelog-container {
      max-width: 800px;
      margin: 40px auto;
    }
    .changelog-card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
      padding: 30px;
    }
    .version-title {
      font-size: 1.5rem;
      font-weight: bold;
    }
    .changelog-item {
      margin-bottom: 12px;
    }
    .changelog-item i {
      color: #0d6efd;
      margin-right: 8px;
    }
  </style>
</head>
<body>

<div class="container changelog-container">
  <div class="changelog-card">
    <h2 class="version-title mb-3">
      <i class="fas fa-code-branch"></i> ChangeLog GPM Sales Finance System v1.1.0
    </h2>
    <ul class="list-group list-group-flush">
      <li class="list-group-item changelog-item"><i class="fas fa-magnifying-glass"></i> Peningkatan desain tabel pada setiap tabel yang terdapat pada <i>GPM Sales and Finance System</i>.</li>
    <h2 class="version-title mb-3">
      <i class="fas fa-code-branch"></i> ChangeLog GPM Sales Finance System v1.2.0
    </h2>
    <ul class="list-group list-group-flush">
      <li class="list-group-item changelog-item"><i class="fas fa-circle-info"></i> Menambahkan fitur keterangan tombol di setiap halaman yang terdapat tombol.</li>
      <li class="list-group-item changelog-item"><i class="fas fa-file-invoice-dollar"></i> Menambahkan status pembayaran pada invoice.</li>
      <li class="list-group-item changelog-item"><i class="fas fa-table-list"></i> Mengupdate tampilan data invoice dengan beberapa tambahan kondisi.</li>
      <li class="list-group-item changelog-item"><i class="fas fa-exclamation-triangle text-danger"></i> Menambahkan fitur alert untuk invoice yang sudah overdue.</li>
      <li class="list-group-item changelog-item"><i class="fas fa-clock text-danger"></i> Menambahkan halaman khusus untuk Overdue Invoice.</li>
      <li class="list-group-item changelog-item"><i class="fas fa-clipboard-list"></i> Menambahkan fitur catatan pengeluaran.</li>
      <li class="list-group-item changelog-item"><i class="fas fa-chart-line text-success"></i> Menambahkan fitur rekap pengeluaran secara real time (bulan, minggu, hari).</li>
      <li class="list-group-item changelog-item"><i class="fas fa-coins text-warning"></i> Menambahkan fitur rekap laba secara real time (bulan, minggu, hari).</li>
      <li class="list-group-item changelog-item"><i class="fas fa-scale-balanced"></i> Menambahkan fitur rekapan rugi laba terbaru.</li>
    </ul>

    <hr class="my-4"/>

    <h5>Perubahan Tambahan:</h5>
    <ul class="list-group list-group-flush">
      <li class="list-group-item changelog-item"><i class="fas fa-filter"></i> Penambahan filter berdasarkan tanggal dan perusahaan pada halaman laporan rugi laba.</li>
      <li class="list-group-item changelog-item"><i class="fas fa-table"></i> Penambahan fitur DataTables pada beberapa halaman untuk pencarian dan navigasi data yang lebih mudah.</li>
      <li class="list-group-item changelog-item"><i class="fas fa-file-excel text-success"></i> Fitur ekspor ke Excel untuk laporan pengeluaran dan laba rugi.</li>
      <li class="list-group-item changelog-item"><i class="fas fa-pen-to-square"></i> Penambahan kemampuan untuk menambahkan produk baru saat proses edit invoice.</li>
      <li class="list-group-item changelog-item"><i class="fas fa-magnifying-glass"></i> Peningkatan desain form input agar lebih intuitif dan responsif.</li>
    </ul>

    <div class="text-end mt-4">
      <small class="text-muted">Terakhir diperbarui: <?= date('d F Y') ?></small>
    </div>
  </div>
</div>

</body>
</html>

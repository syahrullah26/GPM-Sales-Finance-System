<?php
include '../includes/koneksi.php';

$perusahaan = $_GET['perusahaan'] ?? '';
$escapedPerusahaan = mysqli_real_escape_string($konek, $perusahaan);

$query = mysqli_query($konek, "
    SELECT no_sj 
    FROM invoices 
    WHERE perusahaan = '$escapedPerusahaan' 
    ORDER BY id DESC
");

$items = [];
while ($row = mysqli_fetch_assoc($query)) {
    $items[] = $row['no_sj'];
}

if (empty($items)) {
    echo '<li class="list-group-item text-muted">Belum ada No Surat Jalan untuk perusahaan ini.</li>';
    exit;
}

$last = $items[0];
$parts = explode('/', $last);

// Siapkan data waktu saat ini
$bulanRomawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
$bulan = $bulanRomawi[(int)date('n') - 1];
$tahunPendek = date('y');

$rekomendasi = '';

if (count($parts) >= 5 && is_numeric($parts[0])) {
    $nextNumber = (int)$parts[0] + 1;
    $rekomendasi = sprintf('%d/SJ/GPM/%s/%s', $nextNumber, $bulan, $tahunPendek);

    // Cek apakah rekomendasi sudah digunakan
    $check = mysqli_query($konek, "SELECT 1 FROM invoices WHERE no_sj = '$rekomendasi' LIMIT 1");
    if (!mysqli_num_rows($check)) {
        echo "<li class='list-group-item bg-success bg-opacity-10 border-success d-flex justify-content-between align-items-center'>
                <div>
                    <i class='fas fa-lightbulb text-success me-2'></i>
                    <strong class='text-success'>Rekomendasi No SJ Baru:</strong> $rekomendasi
                </div>
                <button class='btn btn-sm btn-outline-success' onclick=\"isiNoSJ('$rekomendasi')\">Gunakan</button>
              </li>";
    }
}

// Tampilkan riwayat No SJ
foreach ($items as $no) {
    $escapedNo = htmlspecialchars($no);
    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
            <span>$escapedNo</span>
          </li>";
}
?>

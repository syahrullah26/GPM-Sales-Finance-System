<?php
include '../includes/koneksi.php';

$perusahaan = isset($_GET['perusahaan']) ? trim($_GET['perusahaan']) : '';

if ($perusahaan === '') {
  echo "<li class='list-group-item text-center text-muted'>Silakan pilih perusahaan terlebih dahulu.</li>";
  exit;
}

$perusahaan = mysqli_real_escape_string($konek, $perusahaan);

$query = mysqli_query($konek, "SELECT no_sp FROM penawaran WHERE nama_perusahaan = '$perusahaan' ORDER BY id DESC LIMIT 1");

$rekomendasi = '';
if ($row = mysqli_fetch_assoc($query)) {
    $last_no_sp = $row['no_sp'];

    if (preg_match('/^(\d+)\/GPM\/[IVXLCDM]+\/\d{2}$/', $last_no_sp, $matches)) {
        $next_number = (int)$matches[1] + 1;
        $bulanRomawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
        $bulan = (int)date('n');
        $tahunPendek = date('y'); 
        $rekomendasi = sprintf("%d/GPM/%s/%s", $next_number, $bulanRomawi[$bulan-1], $tahunPendek);
    }
}


$exists = false;
if ($rekomendasi !== '') {
    $check = mysqli_query($konek, "SELECT 1 FROM penawaran WHERE no_sp = '$rekomendasi' LIMIT 1");
    $exists = mysqli_num_rows($check) > 0;
}


if ($rekomendasi !== '' && !$exists) {
    echo "<li class='list-group-item bg-info bg-opacity-10 border-info d-flex justify-content-between align-items-center'>
            <div>
              <i class='fas fa-lightbulb text-warning me-2'></i>
              <strong class='text-primary'>Rekomendasi No SP Baru:</strong> $rekomendasi
            </div>
            <button class='btn btn-sm btn-outline-success' onclick=\"isiNoSP('$rekomendasi')\">Gunakan</button>
          </li>";
}


$result = mysqli_query($konek, "SELECT no_sp, tanggal FROM penawaran WHERE nama_perusahaan = '$perusahaan' ORDER BY id DESC LIMIT 10");

if (mysqli_num_rows($result) === 0 && $rekomendasi === '') {
  echo "<li class='list-group-item text-center text-muted'>Belum ada data SP untuk perusahaan ini.</li>";
}

while ($row = mysqli_fetch_assoc($result)) {
    $no_sp = htmlspecialchars($row['no_sp']);
    $tanggal = htmlspecialchars($row['tanggal']);
    echo "
      <li class='list-group-item d-flex justify-content-between align-items-center'>
        <div>
          <strong>$no_sp</strong> <small class='text-muted'>($tanggal)</small>
        </div>
      </li>";
}
?>

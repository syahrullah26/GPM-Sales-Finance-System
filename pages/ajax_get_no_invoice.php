<?php
include '../includes/koneksi.php';

$perusahaan = $_GET['perusahaan'] ?? '';
$escapedPerusahaan = mysqli_real_escape_string($konek, $perusahaan);

$query = mysqli_query($konek, "
    SELECT no_invoice 
    FROM invoices 
    WHERE perusahaan = '$escapedPerusahaan' 
    ORDER BY id DESC
");

$items = [];
while ($row = mysqli_fetch_assoc($query)) {
    $items[] = $row['no_invoice'];
}

if (empty($items)) {
    echo '<li class="list-group-item text-muted">Belum ada No Invoice untuk perusahaan ini.</li>';
    exit;
}

$last = $items[0];
$parts = explode('/', $last);


$bulanRomawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
$bulan = $bulanRomawi[(int)date('n') - 1];
$tahunPendek = date('y');

$rekomendasi = '';


if (count($parts) >= 5 && is_numeric($parts[0])) {
    $nextNumber = (int)$parts[0] + 1;
    $rekomendasi = sprintf('%d/INV/GPM/%s/%s', $nextNumber, $bulan, $tahunPendek);


    $check = mysqli_query($konek, "SELECT 1 FROM invoices WHERE no_invoice = '$rekomendasi' LIMIT 1");
    if (!mysqli_num_rows($check)) {
        echo "<li class='list-group-item bg-warning bg-opacity-10 border-warning d-flex justify-content-between align-items-center'>
                <div>
                    <i class='fas fa-lightbulb text-warning me-2'></i>
                    <strong class='text-warning'>Rekomendasi No Invoice Baru:</strong> $rekomendasi
                </div>
                <button class='btn btn-sm btn-outline-success' onclick=\"isiNoInvoice('$rekomendasi')\">Gunakan</button>
              </li>";
    }
}


foreach ($items as $no) {
    $escapedNo = htmlspecialchars($no);
    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
            <span>$escapedNo</span>
          </li>";
}
?>

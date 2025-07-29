<?php
include '../includes/koneksi.php';

$search = $_GET['search'] ?? '';

// Query untuk mengambil invoice yang belum ada di pengeluaran_items
$query = mysqli_query($konek, "
    SELECT i.id, i.no_invoice, i.perusahaan 
    FROM invoices i
    LEFT JOIN pengeluaran_items pi ON pi.invoice_id = i.id
    WHERE pi.invoice_id IS NULL
      AND (i.no_invoice LIKE '%$search%' OR i.perusahaan LIKE '%$search%')
    LIMIT 10
");

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = [
        "id" => $row['id'],
        "text" => $row['no_invoice'] . " - " . $row['perusahaan']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);

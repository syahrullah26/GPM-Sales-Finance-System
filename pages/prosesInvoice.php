<?php
include_once "../includes/koneksi.php";

$perusahaan = $_POST['perusahaan'];
$alamat = $_POST['alamat'];
$no_invoice = $_POST['no_invoice'];
$no_po = $_POST['no_po'];
$no_sj = $_POST['no_sj'];
$tanggal_invoice = $_POST['tanggal_invoice'];
$jatuh_tempo = $_POST['jatuh_tempo'];

$pajak = $_POST['pajak']; 


$nama_barang = $_POST['nama_barang'];
$quantity = $_POST['quantity'];
$satuan = $_POST['satuan'];
$harga_beli = $_POST['harga_beli'];
$harga_jual = $_POST['harga_jual'];

$total_beli = 0;
$total_jual = 0;
$total_laba = 0;
$item_data = [];

for ($i = 0; $i < count($nama_barang); $i++) {
    $subtotal_beli = $harga_beli[$i] * $quantity[$i];
    $subtotal_jual = $harga_jual[$i] * $quantity[$i];
    $laba = $subtotal_jual - $subtotal_beli;
    $persentase = ($subtotal_beli > 0) ? ($laba / $subtotal_beli) * 100 : 0;

    $total_beli += $subtotal_beli;
    $total_jual += $subtotal_jual;
    $total_laba += $laba;

    $item_data[] = [
        'nama_barang' => $nama_barang[$i],
        'quantity' => $quantity[$i],
        'satuan' => $satuan[$i],
        'harga_beli' => $harga_beli[$i],
        'harga_jual' => $harga_jual[$i],
        'subtotal_beli' => $subtotal_beli,
        'subtotal_jual' => $subtotal_jual,
        'laba' => $laba,
        'persentase' => $persentase
    ];
}

$total_persen = ($total_beli > 0) ? ($total_laba / $total_beli) * 100 : 0;

$ppn = 0;
if ($pajak === 'ya') {
    $ppn = round($total_jual * 0.11); 
}


$status = "belum bayar";

$stmt = $konek->prepare("INSERT INTO invoices 
(perusahaan, alamat, no_invoice, no_po, no_sj, tanggal_invoice, jatuh_tempo, total_beli, total_jual, total_laba, total_persen, pajak, ppn, status) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "sssssssddddsds",
    $perusahaan,
    $alamat,
    $no_invoice,
    $no_po,
    $no_sj,
    $tanggal_invoice,
    $jatuh_tempo,
    $total_beli,
    $total_jual,
    $total_laba,
    $total_persen,
    $pajak,
    $ppn,
    $status
);

$stmt->execute();
$invoice_id = $stmt->insert_id;
$stmt->close();

foreach ($item_data as $item) {
    $stmt = $konek->prepare("INSERT INTO invoice_items 
    (invoice_id, nama_barang, quantity, satuan, harga_beli, harga_jual, subtotal_beli, subtotal_jual, laba, persentase)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "isissddddd",
        $invoice_id,
        $item['nama_barang'],
        $item['quantity'],
        $item['satuan'],
        $item['harga_beli'],
        $item['harga_jual'],
        $item['subtotal_beli'],
        $item['subtotal_jual'],
        $item['laba'],
        $item['persentase']
    );

    $stmt->execute();
    $stmt->close();
}

echo "<script>alert('Data invoice berhasil disimpan!'); window.location.href='../index.php?page=rugiLaba';</script>";

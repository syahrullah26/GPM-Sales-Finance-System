<?php
include '../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_pengeluaran = $_POST['no_pengeluaran'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';
    $keterangan = mysqli_real_escape_string($konek, $_POST['keterangan'] ?? '');
    $invoice_ids = $_POST['invoice_ids'] ?? [];
    $jenis_pengeluaran = $_POST['jenis_pengeluaran'] ?? [];
    $nominal_jenis = $_POST['nominal_jenis'] ?? [];

    if (empty($no_pengeluaran) || empty($tanggal) || empty($invoice_ids) || empty($jenis_pengeluaran) || empty($nominal_jenis)) {
        echo "<script>alert('Pastikan semua field telah diisi!'); window.history.back();</script>";
        exit;
    }

    // Hitung total pengeluaran
    $total_pengeluaran = 0;
    foreach ($nominal_jenis as $n) {
        $total_pengeluaran += floatval($n);
    }

    // Simpan ke tabel `pengeluaran`
    $query_pengeluaran = "INSERT INTO pengeluaran (no_pengeluaran, tanggal, total_pengeluaran, keterangan)
                          VALUES ('$no_pengeluaran', '$tanggal', '$total_pengeluaran', '$keterangan')";
    $result = mysqli_query($konek, $query_pengeluaran);

    if ($result) {
        $pengeluaran_id = mysqli_insert_id($konek);
        $jumlah_invoice = count($invoice_ids);
        $nominal_per_invoice = $total_pengeluaran / $jumlah_invoice;

        // Simpan ke `pengeluaran_items`
        foreach ($invoice_ids as $invoice_id) {
            $invoice_id = intval($invoice_id);
            $query_item = "INSERT INTO pengeluaran_items (pengeluaran_id, invoice_id, nominal)
                           VALUES ('$pengeluaran_id', '$invoice_id', '$nominal_per_invoice')";
            mysqli_query($konek, $query_item);
        }

        // Simpan ke `pengeluaran_jenis`
        for ($i = 0; $i < count($jenis_pengeluaran); $i++) {
            $jenis = mysqli_real_escape_string($konek, $jenis_pengeluaran[$i]);
            $nominal = floatval($nominal_jenis[$i]);
            $query_jenis = "INSERT INTO pengeluaran_jenis (pengeluaran_id, jenis_pengeluaran, nominal)
                            VALUES ('$pengeluaran_id', '$jenis', '$nominal')";
            mysqli_query($konek, $query_jenis);
        }

        echo "<script>alert('Data pengeluaran berhasil disimpan!'); window.location.href='../index.php?page=pengeluaran';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data pengeluaran utama.'); window.history.back();</script>";
    }
} else {
    echo "Metode tidak diizinkan.";
}

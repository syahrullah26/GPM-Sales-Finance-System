<?php
include '../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $penawaran_id = $_POST['penawaran_id'];
    $perusahaan = $_POST['perusahaan'];
    $alamat = $_POST['alamat'];
    $no_invoice = $_POST['no_invoice'];
    $no_po = $_POST['no_po'];
    $no_sj = $_POST['no_sj'];
    $tanggal_invoice = $_POST['tanggal_invoice'];
    $jatuh_tempo = $_POST['tanggal_jatuh_tempo'];

    $nama_barang = $_POST['nama_barang'];
    $quantity = $_POST['quantity'];
    $satuan = $_POST['satuan'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $keterangan = $_POST['keterangan'];

    $total_beli = 0;
    $total_jual = 0;
    $total_laba = 0;

    $jumlah_item = count($nama_barang);

    // Perhitungan total sebelum insert
    for ($i = 0; $i < $jumlah_item; $i++) {
        $subtotal_beli = $harga_beli[$i] * $quantity[$i];
        $subtotal_jual = $harga_jual[$i] * $quantity[$i];
        $laba = $subtotal_jual - $subtotal_beli;

        $total_beli += $subtotal_beli;
        $total_jual += $subtotal_jual;
        $total_laba += $laba;
    }

    $total_persen = $total_beli > 0 ? ($total_laba / $total_beli) * 100 : 0;

    // Simpan ke tabel invoices
    $query = "INSERT INTO invoices 
        (perusahaan, alamat, no_invoice, no_po, no_sj, tanggal_invoice, jatuh_tempo, total_beli, total_jual, total_laba, total_persen) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $konek->prepare($query);
    $stmt->bind_param(
        "sssssssdddd",
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
        $total_persen
    );

    if ($stmt->execute()) {
        $invoice_id = $stmt->insert_id;

        // Simpan ke invoice_items
        for ($i = 0; $i < $jumlah_item; $i++) {
            $nama = $nama_barang[$i];
            $qty = $quantity[$i];
            $sat = $satuan[$i];
            $beli = $harga_beli[$i];
            $jual = $harga_jual[$i];
            $ket = $keterangan[$i];

            $subtotal_beli = $beli * $qty;
            $subtotal_jual = $jual * $qty;
            $laba = $subtotal_jual - $subtotal_beli;
            $persen = $subtotal_beli > 0 ? ($laba / $subtotal_beli) * 100 : 0;

            $insert_item = "INSERT INTO invoice_items 
                (invoice_id, nama_barang, quantity, satuan, harga_beli, harga_jual, subtotal_beli, subtotal_jual, laba, persentase)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_item = $konek->prepare($insert_item);
            $stmt_item->bind_param(
                "isissdddds",
                $invoice_id,
                $nama,
                $qty,
                $sat,
                $beli,
                $jual,
                $subtotal_beli,
                $subtotal_jual,
                $laba,
                $persen
            );
            $stmt_item->execute();
        }

        // Update status penawaran menjadi 'selesai'
        mysqli_query($konek, "UPDATE penawaran SET status='selesai' WHERE id=$penawaran_id");

        echo "<script>alert('Invoice berhasil dibuat!'); window.location.href='../index.php?page=rugi_laba';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan invoice!'); window.history.back();</script>";
    }

    $stmt->close();
    $konek->close();
} else {
    echo "Akses tidak sah.";
}

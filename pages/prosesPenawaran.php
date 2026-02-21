<?php
include '../includes/koneksi.php';

if (isset($_POST['submit'])) {
    $perusahaan = $_POST['perusahaan'];
    $no_sp = $_POST['no_sp'];
    $penerima =$_POST ['penerima'];
    $alamat = $_POST['alamat'];
    $tanggal_penawaran = $_POST['tanggal_penawaran'];

    // Hitung total dari semua produk
    $total = 0;
    $jumlah_produk = count($_POST['nama_barang']);
    for ($i = 0; $i < $jumlah_produk; $i++) {
        $qty = $_POST['quantity'][$i];
        $harga_jual = $_POST['harga_jual'][$i];
        $total += ($qty * $harga_jual);
    }

    // Status default
    $status = 'menunggu';

    // Simpan ke tabel penawaran
    $query_penawaran = "INSERT INTO penawaran (no_sp, nama_perusahaan,penerima, alamat, tanggal, total, status) VALUES (?,?,?, ?, ?, ?, ?)";
    $stmt = $konek->prepare($query_penawaran);
    $stmt->bind_param("sssssds", $no_sp, $perusahaan,$penerima, $alamat, $tanggal_penawaran, $total, $status);

    if ($stmt->execute()) {
        $penawaran_id = $stmt->insert_id;

        // Simpan detail produk ke penawaran_items
        for ($i = 0; $i < $jumlah_produk; $i++) {
            $nama_barang = $_POST['nama_barang'][$i];
            $quantity = $_POST['quantity'][$i];
            $satuan = $_POST['satuan'][$i];
            $harga_beli = $_POST['harga_beli'][$i];
            $harga_jual = $_POST['harga_jual'][$i];
            $keterangan = $_POST['keterangan'][$i];

            $jumlah = $quantity * $harga_jual;
            $laba = ($harga_jual - $harga_beli) * $quantity;
            $persentase_laba = $harga_beli > 0 ? ($laba / ($harga_beli * $quantity)) * 100 : 0;

            $query_item = "INSERT INTO penawaran_items 
                (penawaran_id, nama_barang, quantity, satuan, harga_beli, harga_jual, keterangan, jumlah, laba, persentase_laba)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_item = $konek->prepare($query_item);
            $stmt_item->bind_param(
                "isisddsddd", 
                $penawaran_id,
                $nama_barang,
                $quantity,
                $satuan,
                $harga_beli,
                $harga_jual,
                $keterangan,
                $jumlah,
                $laba,
                $persentase_laba
            );
            $stmt_item->execute();
        }

        // Sukses simpan
        echo "<script>alert('Data penawaran berhasil disimpan'); window.location.href='../index.php?page=penawaran';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data penawaran.'); window.history.back();</script>";
    }

    // Tutup koneksi
    $stmt->close();
    $konek->close();
}

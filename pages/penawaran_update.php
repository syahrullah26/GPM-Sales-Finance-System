<?php
include '../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['penawaran_id']);
    $no_sp = $_POST['no_sp'];
    $perusahaan = $_POST['perusahaan'];
    $alamat = $_POST['alamat'];
    $tanggal = $_POST['tanggal'];

    // Hitung ulang total
    $total = 0;
    $jumlah_produk = count($_POST['nama_barang']);
    for ($i = 0; $i < $jumlah_produk; $i++) {
        $total += $_POST['harga_jual'][$i] * $_POST['quantity'][$i];
    }

    // Update penawaran
    $update = mysqli_query($konek, "UPDATE penawaran SET no_sp = '$no_sp', nama_perusahaan='$perusahaan', alamat='$alamat', tanggal='$tanggal', total='$total' WHERE id=$id");

    if ($update) {
        // Update/Insert penawaran_items
        for ($i = 0; $i < $jumlah_produk; $i++) {
            $item_id = $_POST['item_id'][$i] ?? null;
            $nama_barang = $_POST['nama_barang'][$i];
            $quantity = $_POST['quantity'][$i];
            $satuan = $_POST['satuan'][$i];
            $harga_beli = $_POST['harga_beli'][$i];
            $harga_jual = $_POST['harga_jual'][$i];
            $keterangan = $_POST['keterangan'][$i];

            $jumlah = $quantity * $harga_jual;
            $laba = ($harga_jual - $harga_beli) * $quantity;
            $persentase_laba = $harga_beli > 0 ? ($laba / ($harga_beli * $quantity)) * 100 : 0;

            if ($item_id) {
                // Update existing
                mysqli_query($konek, "UPDATE penawaran_items SET
                    nama_barang='$nama_barang',
                    quantity='$quantity',
                    satuan='$satuan',
                    harga_beli='$harga_beli',
                    harga_jual='$harga_jual',
                    keterangan='$keterangan',
                    jumlah='$jumlah',
                    laba='$laba',
                    persentase_laba='$persentase_laba'
                    WHERE id=$item_id");
            } else {
                // Insert new
                mysqli_query($konek, "INSERT INTO penawaran_items
                    (penawaran_id, nama_barang, quantity, satuan, harga_beli, harga_jual, keterangan, jumlah, laba, persentase_laba)
                    VALUES
                    ('$id', '$nama_barang', '$quantity', '$satuan', '$harga_beli', '$harga_jual', '$keterangan', '$jumlah', '$laba', '$persentase_laba')");
            }
        }

        echo "<script>alert('Data penawaran berhasil diperbarui'); window.location.href='../index.php?page=penawaran';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data penawaran'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}

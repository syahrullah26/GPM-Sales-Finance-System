<?php
include '../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_id = (int)$_POST['invoice_id'];
    $perusahaan = mysqli_real_escape_string($konek, $_POST['perusahaan']);
    $alamat = mysqli_real_escape_string($konek, $_POST['alamat']);
    $no_invoice = mysqli_real_escape_string($konek, $_POST['no_invoice']);
    $no_po = mysqli_real_escape_string($konek, $_POST['no_po']);
    $no_sj = mysqli_real_escape_string($konek, $_POST['no_sj']);
    $tanggal_invoice = $_POST['tanggal_invoice'];
    $jatuh_tempo = $_POST['jatuh_tempo'];
    $pajak = $_POST['pajak'] === 'ya' ? 'ya' : 'tidak'; 

    $item_ids     = $_POST['item_id'] ?? [];
    $nama_barangs = $_POST['nama_barang'] ?? [];
    $quantities   = $_POST['quantity'] ?? [];
    $satuans      = $_POST['satuan'] ?? [];
    $harga_belis  = $_POST['harga_beli'] ?? [];
    $harga_juals  = $_POST['harga_jual'] ?? [];

    $subtotal = 0;
    for ($i = 0; $i < count($nama_barangs); $i++) {
        $qty = (int)$quantities[$i];
        $harga_jual = (int)$harga_juals[$i];
        $subtotal += $qty * $harga_jual;
    }

    $ppn = $pajak === 'ya' ? round($subtotal * 0.11) : 0;


    mysqli_query($konek, "UPDATE invoices SET 
        perusahaan = '$perusahaan',
        alamat = '$alamat',
        no_invoice = '$no_invoice',
        no_po = '$no_po',
        no_sj = '$no_sj',
        tanggal_invoice = '$tanggal_invoice',
        jatuh_tempo = '$jatuh_tempo',
        pajak = '$pajak',
        ppn = $ppn
        WHERE id = $invoice_id
    ");

    $ids_terpakai = [];
    foreach ($item_ids as $id) {
        if (is_numeric($id) && (int)$id > 0) {
            $ids_terpakai[] = (int)$id;
        }
    }


    if (!empty($ids_terpakai)) {
        $id_string = implode(',', $ids_terpakai);
        mysqli_query($konek, "DELETE FROM invoice_items WHERE invoice_id = $invoice_id AND id NOT IN ($id_string)");
    } else {
        mysqli_query($konek, "DELETE FROM invoice_items WHERE invoice_id = $invoice_id");
    }


    for ($i = 0; $i < count($nama_barangs); $i++) {
        $item_id_raw = $item_ids[$i];
        $item_id = is_numeric($item_id_raw) && $item_id_raw > 0 ? (int)$item_id_raw : 0;

        $nama_barang = mysqli_real_escape_string($konek, $nama_barangs[$i]);
        $qty         = (int)$quantities[$i];
        $satuan      = mysqli_real_escape_string($konek, $satuans[$i]);
        $harga_beli  = (int)$harga_belis[$i];
        $harga_jual  = (int)$harga_juals[$i];

        if ($item_id > 0) {
            mysqli_query($konek, "UPDATE invoice_items SET
                nama_barang = '$nama_barang',
                quantity = $qty,
                satuan = '$satuan',
                harga_beli = $harga_beli,
                harga_jual = $harga_jual
                WHERE id = $item_id AND invoice_id = $invoice_id
            ");
        } else {
            mysqli_query($konek, "INSERT INTO invoice_items 
                (invoice_id, nama_barang, quantity, satuan, harga_beli, harga_jual)
                VALUES ($invoice_id, '$nama_barang', $qty, '$satuan', $harga_beli, $harga_jual)
            ");
        }
    }

    header("Location: ../index.php?page=rugiLaba&updated=$invoice_id");
    exit;
}
?>

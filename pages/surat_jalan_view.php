<?php
include '../includes/koneksi.php';

if (!isset($_GET['id'])) {
    die("ID invoice tidak ditemukan.");
}

$id = intval($_GET['id']);
$invoice = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM invoices WHERE id = $id"));
$items = mysqli_query($konek, "SELECT * FROM invoice_items WHERE invoice_id = $id");

if (!$invoice) die("Data invoice tidak ditemukan.");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Jalan - <?= $invoice['no_invoice'] ?></title>
    <link rel="stylesheet" href="assets/css/print.css" media="print">
    <link rel="stylesheet" href="../assets/css/print.css" media="print">
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="../assets/images/purnama.png" rel="icon">
    <link href="../assets/images/purnama.png" rel="apple-touch-icon">
    <link rel="icon" href="../assets/images/purnama.png" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/purnama.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="../assets/images/purnama.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul {
            font-weight: bold;
            font-size: 18px;
        }

        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }

        .bordered {
            border: 1px solid #000;
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        .bordered th,
        .bordered td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .signature {
            margin-top: 40px;
        }

        .signature td {
            padding-top: 40px;
            text-align: center;
        }

        .justify-text {
            text-align: justify;
        }
    </style>
</head>

<body>
    <a class="btn btn-primary mt-2 " href='surat_jalan_print.php?id=<?= $id ?>'><i class="fas fa-print"></i>
        print</a>
    <a class='btn btn-success mt-2 ' href="surat_jalan_export_excel.php?id=<?= $id ?>" class="btn btn-success" target="_blank">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
    <hr>
    <div class="header" style="text-align: center;">
        <div style="display: inline-flex; align-items: center; gap: 15px;">
            <img src="../assets/images/purnama.png" alt="Logo" style="height: 60px;">
            <div style="text-align: left;">
                <div style="font-weight: bold; font-size: 18px;">PT. GANGSAR PURNAMA MANDIRI</div>
                <div>Jl. Jalak Bali II Bekasi Timur Regensi Blok J1/63, Cimuning, Bekasi - 17310</div>
                <div>Contact: 0852-105-32929 | Email: purnama.mandiri77@gmail.com</div>
            </div>
        </div>
        <hr>
    </div>



    <table class="info-table" width="100%">
        <tr>
            <td><strong>Kepada Yth:</strong></td>
            <td><?= htmlspecialchars($invoice['perusahaan']) ?></td>
        </tr>
        <tr>
            <td><strong>Alamat:</strong></td>
            <td><?= isset($invoice['alamat']) ? htmlspecialchars($invoice['alamat']) : '-' ?></td>
        </tr>
    </table>

    <h3 style="text-align:center; margin-top: 20px;">SURAT JALAN</h3>

    <table class="info-table" width="100%">
        <tr>
            <td><strong>No Surat Jalan</strong></td>
            <td>: <?= $invoice['no_sj'] ?>/SJ</td>
            <td><strong>Tanggal</strong></td>
            <td>: ( ___________________________ )</td>
        </tr>
        <tr>
            <td><strong>PO. No</strong></td>
            <td>: <?= $invoice['no_po'] ?></td>
        </tr>
    </table>

    <div class="justify-text" style="margin-left:8px;">
        <div>Dengan Hormat,</div>
        <div>Mohon diterima barang di bawah ini :</div>
    </div>

    <table class="bordered">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Banyaknya</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = mysqli_fetch_assoc($items)): ?>
                <tr>
                    <td><?= $item['nama_barang'] ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?> <?= htmlspecialchars($item['satuan']) ?></td>
                    <td></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <table class="signature" width="100%">
        <tr>
            <td>Penerima</td>
            <td>Hormat Kami</td>
        </tr>
        <tr>
            <td>( __________________ )</td>
            <td>( __________________ )</td>
        </tr>
    </table>
</body>

</html>
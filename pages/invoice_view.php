<?php
include '../includes/koneksi.php';

$id = intval($_GET['id']);
$invoice = mysqli_query($konek, "SELECT * FROM invoices WHERE id = $id");
$data = mysqli_fetch_assoc($invoice);

$items = mysqli_query($konek, "SELECT * FROM invoice_items WHERE invoice_id = $id");

$total = 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice <?= htmlspecialchars($data['no_invoice']) ?></title>
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
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .no-border td,
        .no-border th {
            border: none;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .logo {
            width: 80px;
        }

        .header-table td {
            vertical-align: top;
        }

        .item-table th,
        .item-table td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }

        .total-row td {
            font-weight: bold;
            background: #E5E5E5;
        }
    </style>
</head>

<body>
    <a class="btn btn-primary" href='invoice_print.php?id=<?= $data['id'] ?>'><i class="fas fa-print"></i>
        print</a>
    <a class='btn btn-success' href="invoice_export_excel.php?id=<?= $data['id'] ?>" class="btn btn-success" target="_blank">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
    <hr>

    <!-- Header Perusahaan Sesuai Gambar -->
<div style="
    display: grid;
    grid-template-columns: auto 1fr auto; 
    align-items: flex-start; /* Membuat semua kolom rata atas */
    border-bottom: 2px solid #ccc; 
    padding-bottom: 10px; 
    width: 100%;
    font-family: Arial, sans-serif;
">
    <div style="padding-right: 15px;">
        <img src="../assets/images/purnama.png" alt="Logo" style="height: 70px; display: block;">
    </div>

    <div style="padding-right: 50px;"> 
        <div style="
            font-weight: bold; 
            font-size: 30px; /* Ukuran diperbesar agar lebih tinggi */
            color: #B36E1E; 
            margin-top: -5px; /* Menarik teks ke arah atas agar lebih 'naik' */
            margin-bottom: 4px;
            white-space: nowrap;
            line-height: 1; /* Mengurangi ruang kosong di atas teks */
        ">
            PT. GANGSAR PURNAMA MANDIRI
        </div>
        <div style="font-size: 13px; line-height: 1.4;">
            Jl. Jalak Bali II Bekasi Timur Regensi Blok J1/63, Cimuning Kotamadya Bekasi - 17310<br>
            Telp: 021 82521962 | Contact person: 0852-105-39299
        </div>
    </div>

    <div style="
        text-align: right; 
        font-size: 12px; /* Ukuran sedikit diperkecil agar tidak berebut perhatian */
        line-height: 1.4;
        white-space: nowrap;
        padding-top: 8px; /* Memberikan jarak dari atas agar judul PT terlihat jauh lebih tinggi */
    ">
        <strong>BANK ACC:</strong><br>
        KCP BEKASI RUKO D GREEN SQUARE<br>
        PT. GANGSAR PURNAMA MANDIRI<br>
        <strong>NO REK : 156-00-2000590-8</strong><br>
        NPWP : 061-953.570-1-407.000<br>
        e-mail : purnama.mandiri77@gmail.com
    </div>
</div>


    <!-- Tujuan -->
    <table class="no-border" style="margin-top: 20px;">
        <tr>
            <td width="50%">
                <strong>Kepada Yth :</strong><br>
                <?= nl2br($data['perusahaan']) ?><br>
                <?= nl2br($data['alamat']) ?>
            </td>
            <td class="right">
                <table class="no-border">
                    <tr>
                        <td class="bold">INVOICE</td>
                    </tr>
                    <tr>
                        <td>No: <?= $data['no_invoice'] ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal: <?= strtoupper(date('d F Y', strtotime($data['tanggal_invoice']))) ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>


    <!-- Info Kirim -->
    <table class="item-table" style="margin-top: 20px;">
        <tr>
            <th style ="background-color:#ff8c00; 
">SALES</th>
            <th style ="background-color:#ff8c00; ">NO PO</th>
            <th style ="background-color:#ff8c00; ">TGL PENGIRIMAN</th>
            <th style ="background-color:#ff8c00;  ">NO SURAT JALAN</th>
            <th style ="background-color:#ff8c00;">JATUH TEMPO</th>
        </tr>
        <tr>
            <td><?= $data['sales'] ?? 'RENI PURNAMA' ?></td>
            <td><?= $data['no_po'] ?? '-' ?></td>
            <td><?= date('d-M-y', strtotime($data['tanggal_invoice'])) ?></td>
            <td><?= $data['no_sj'] ?? '-' ?></td>
            <td><?= date('l, d F Y', strtotime($data['jatuh_tempo'])) ?></td>
        </tr>
    </table>

    <!-- Produk -->
    <table class="item-table" style="margin-top: 20px;">
        <thead>
            <tr>
                <th>JUMLAH</th>
                <th>UNIT</th>
                <th>NAMA BARANG</th>
                <th>HARGA (Rp)</th>
                <th>JUMLAH (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($items)):
                $sub = $row['quantity'] * $row['harga_jual'];
                $total += $sub;
            ?>
                <tr>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['satuan'] ?></td>
                    <td><?= $row['nama_barang'] ?></td>
                    <td class="right"><?= number_format($row['harga_jual'], 2, ',', '.') ?></td>
                    <td class="right"><?= number_format($sub, 2, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
            <tr class="total-row" style="background-color:#E5E5E5;">
                <td colspan="4" class="right">SUB TOTAL</td>
                <td class="right"><?= number_format($total, 2, ',', '.') ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="4" class="right">PPN</td>
                <td class="right">
                    <?= (!empty($data['ppn']) || $data['ppn'] === 0)
                        ? number_format($data['ppn'], 2, ',', '.')
                        : '-' ?>
                </td>
            </tr>
            <tr class="total-row">
                <td colspan="4" class="right">TOTAL</td>
                <td class="right"><?= number_format($total + $data['ppn'], 2, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>
<div style="text-align: left; width: fit-content; margin-top: 20px;">
    <p style="margin-bottom: 0;">Hormat Kami,</p>
    
    <div style="height: 130px;"></div> 
    
    <p style="margin-top: 0; font-size: 16px;">
        <strong style="text-decoration: underline;">Reni Purnama Sari</strong>
    </p>
</div>

</body>

</html>
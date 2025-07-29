<?php
include '../includes/koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($konek, "SELECT * FROM penawaran WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}

$items = mysqli_query($konek, "SELECT * FROM penawaran_items WHERE penawaran_id = $id");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Penawaran</title>
    <link rel="stylesheet" href="../assets/css/print.css" media="print">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        h2,
        h3,
        h5 {
            text-align: center;
            margin-bottom: 20px;
        }

        .table-custom {
            border-collapse: collapse;
            width: 100%;
            margin-top: 25px;
        }

        .table-custom th,
        .table-custom td {
            padding: 10px;
            border: 1px solid #333;
            font-size: 14px;
        }

        .no-border {
            border: none;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .label-cell {
            padding-right: 20px;
            /* Ubah nilai ini sesuai kebutuhan */
            width: 120px;
        }
    </style>
</head>

<body>
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

    <?php $formattedDate = date('d F Y', strtotime($data['tanggal'])); ?>
    <p class="mb-2 text-end">Bekasi, <?= $formattedDate ?></p>

    <table>
        <tbody>
            <tr>
                <td class="label-cell">Nomor</td>
                <td>:</td>
                <td> <?= htmlspecialchars($data['no_sp']) ?></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td class="label-cell">Hal</td>
                <td>:</td>
                <td> PENAWARAN</td>
            </tr>
        </tbody>
    </table>

    <p class="mb-2"><strong>Kepada</strong></p>
    <p class="mb-2">Yth. <?= htmlspecialchars($data['nama_perusahaan']) ?></p>
    <p class="mb-2"><?= $data['alamat'] ?></p><br><br>

    <p class="mb-2">Dengan Hormat,</p>
    <p class="mb-2">Bersama dengan datangnya surat ini, kami dari PT. Gangsar Purnama Mandiri ingin mengajukan penawaran pengadaan barang kepada <?= htmlspecialchars($data['nama_perusahaan']) ?>. Di bawah ini kami sertakan produk yang siap dipesan beserta hagranya :</p>

    <table class="table-custom">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Unit</th>
                <th>Total</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $grand_total = 0;
            while ($row = mysqli_fetch_assoc($items)):
                $jumlah = $row['quantity'] * $row['harga_jual'];
                $grand_total += $jumlah;
            ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td class="text-center"><?= $row['quantity'] ?></td>
                    <td class="text-center"><?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
                    <td class="text-center"><?= $row['satuan'] ?></td>
                    <td class="text-center"><?= number_format($jumlah, 0, ',', '.') ?></td>
                    <td><?= !empty($item['keterangan']) ? htmlspecialchars($item['keterangan']) : ' - ' ?></td>

                </tr>
            <?php endwhile; ?>
            <tr>
                <td colspan="5" class="text-end"><strong>Grand Total</strong></td>
                <td class="text-center"><strong><?= number_format($grand_total, 0, ',', '.') ?></strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <br>
    <p class="mb-2"> Demikianlah Surat Penawaran harga ini kami buat, kami tunggu kabar baiknya.
        <br>Atas perhatian dan kerja samanya kami ucapkan terima kasih.
    </p>

    <br><br>
    <div style="text-align: left;">
        <p style="margin-bottom: 5px;">Hormat Kami,</p>
        <img src="../assets/images/ttd.png" alt="TTD" style="height: 100px; display: block; margin-right: 0;">
        <p style="margin-top: 5px;"><strong>Reni Purnama Sari</strong></p>
    </div>


</body>
<script>
    window.print()
</script>

</html>
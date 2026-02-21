<?php
require '../vendor/autoload.php';
include '../includes/koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$tanggalAwal = $_GET['tanggal_awal'] ?? date('Y-m-01');
$tanggalAkhir = $_GET['tanggal_akhir'] ?? date('Y-m-t');

$where = ["status = 'sudah bayar'"];

if (!empty($tanggalAwal)) {
    $where[] = "tanggal_invoice >= '$tanggalAwal'";
}
if (!empty($tanggalAkhir)) {
    $where[] = "tanggal_invoice <= '$tanggalAkhir'";
}

$sql = "SELECT * FROM invoices";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY tanggal_invoice DESC";

$invoices = mysqli_query($konek, $sql);


$formattedDateAwal = !empty($tanggalAwal) ? date('d F Y', strtotime($tanggalAwal)) : '';
$formattedDateAkhir = !empty($tanggalAkhir) ? date('d F Y', strtotime($tanggalAkhir)) : '';

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Laporan Rugi Laba - PT. GANGSAR PURNAMA MANDIRI');
$sheet->mergeCells('A1:S1');
$sheet->setCellValue('A2', "Periode: $formattedDateAwal s.d. $formattedDateAkhir");
$sheet->mergeCells('A2:S2');
$sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$header = [
    'No',
    'Perusahaan',
    'No Invoice',
    'Tanggal Invoice',
    'Tanggal Bayar',
    'Pajak',
    'Nama Barang',
    'Qty',
    'Satuan',
    'Harga Beli',
    'Jumlah Beli',
    'Harga Jual',
    'Jumlah Jual',
    'Total Jumlah Jual',
    'Laba',
    'Total Laba',
    'Persentase',
    'PPN',
    'TOTAL JUAL + PPN 11%'
];
$sheet->fromArray($header, NULL, 'A3');
$sheet->getStyle('A3:S3')->getFont()->setBold(true);
$sheet->getStyle('A3:S3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFBFBFBF');

$rowNum = 4;
$no = 1;

$grandTotalAll = 0;
$grandTotaljual = 0;
$grandTotallaba = 0;
$grandTotalBeli = 0;

while ($inv = mysqli_fetch_assoc($invoices)) {
    $items = mysqli_query($konek, "SELECT * FROM invoice_items WHERE invoice_id = " . $inv['id']);

    $rowStart = $rowNum;
    $total_beli = 0;
    $total_jual = 0;
    $total_laba = 0;

    while ($item = mysqli_fetch_assoc($items)) {
        $jumlah_beli = $item['quantity'] * $item['harga_beli'];
        $jumlah_jual = $item['quantity'] * $item['harga_jual'];
        $laba = $jumlah_jual - $jumlah_beli;
        $persentase = $jumlah_beli > 0 ? ($laba / $jumlah_beli) * 100 : 0;

        $sheet->fromArray([
            '',
            '',
            '',
            '',
            '',
            '',
            $item['nama_barang'],
            $item['quantity'],
            $item['satuan'],
            $item['harga_beli'],
            $jumlah_beli,
            $item['harga_jual'],
            $jumlah_jual,
            '',
            $laba,
            '',
            $persentase / 100,
            '',
            ''
        ], NULL, 'A' . $rowNum);

        $sheet->getStyle("J{$rowNum}:M{$rowNum}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getStyle("O{$rowNum}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getStyle("Q{$rowNum}")->getNumberFormat()->setFormatCode('0.00%');

        $total_beli += $jumlah_beli;
        $total_jual += $jumlah_jual;
        $total_laba += $laba;

        $rowNum++;
    }

    $ppn = strtolower($inv['pajak']) === 'dengan pajak' ? $total_jual * 0.11 : 0;
    $grand_total = $total_jual + $ppn;

    $grandTotalAll += $grand_total;
    $grandTotaljual += $total_jual;
    $grandTotallaba += $total_laba;
    $grandTotalBeli += $total_beli;

    $sheet->setCellValue("A{$rowStart}", $no++);
    $sheet->setCellValue("B{$rowStart}", $inv['perusahaan']);
    $sheet->setCellValue("C{$rowStart}", $inv['no_invoice']);
    $sheet->setCellValue("D{$rowStart}", $inv['tanggal_invoice']);
    $sheet->setCellValue("E{$rowStart}", $inv['tanggal_bayar']);
    $sheet->setCellValue("F{$rowStart}", ucfirst($inv['pajak']));

    $sheet->setCellValue("N{$rowStart}", $total_jual);
    $sheet->setCellValue("P{$rowStart}", $total_laba);
    $sheet->setCellValue("R{$rowStart}", $ppn);
    $sheet->setCellValue("S{$rowStart}", $grand_total);

    $sheet->getStyle("N{$rowStart}:S{$rowStart}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


    $sheet->getStyle("N{$rowStart}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDDEBF7');

    $sheet->getStyle("P{$rowStart}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFCE4D6');

    $sheet->getStyle("S{$rowStart}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2EFDA');

    foreach (['N', 'P', 'S'] as $col) {
        $sheet->getStyle("{$col}{$rowStart}")->getFont()->setBold(true);
    }
}


$summaryRow = $sheet->getHighestRow() + 2;

// Tampilkan Grand Total
$sheet->setCellValue("R{$summaryRow}", 'Grand Total');
$sheet->setCellValue("S{$summaryRow}", $grandTotalAll);
$sheet->setCellValue("M{$summaryRow}", 'Grand Total Jual');
$sheet->setCellValue("N{$summaryRow}", $grandTotaljual);
$sheet->setCellValue("O{$summaryRow}", 'Grand Total Laba');
$sheet->setCellValue("P{$summaryRow}", $grandTotallaba);


$sheet->getStyle("N{$summaryRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$sheet->getStyle("N{$summaryRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFBDD7EE');
$sheet->getStyle("N{$summaryRow}")->getFont()->setBold(true);

$sheet->getStyle("P{$summaryRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$sheet->getStyle("P{$summaryRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFCD5B5');
$sheet->getStyle("P{$summaryRow}")->getFont()->setBold(true);

$sheet->getStyle("S{$summaryRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$sheet->getStyle("S{$summaryRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD6EAD3');
$sheet->getStyle("S{$summaryRow}")->getFont()->setBold(true);


$sheet->getStyle("A3:S{$summaryRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle("A{$summaryRow}:S{$summaryRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);


foreach (range('A', 'S') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$cleanName = preg_replace('/[^a-zA-Z0-9]/', ' ', $formattedDateAwal . ' sampai ' . $formattedDateAkhir);
$cleanName = preg_replace('/\s+/', ' ', $cleanName);
$cleanName = trim($cleanName);
$namaFile = 'Rugi Laba PT. GANGSAR PURNAMA MANDIRI ' . $cleanName . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$namaFile\"");

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

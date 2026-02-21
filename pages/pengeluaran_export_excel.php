<?php
require '../vendor/autoload.php';
include '../includes/koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

// Cek filter
$start = $_GET['start_date'] ?? '';
$end = $_GET['end_date'] ?? '';
$tampilkanSemua = isset($_GET['tampilkan_semua']);

$filterQuery = "SELECT * FROM pengeluaran";
$conditions = [];

if (!$tampilkanSemua) {
    if (!empty($start) && !empty($end)) {
        $conditions[] = "tanggal BETWEEN '$start' AND '$end'";
        $periodeTitle = "Periode " . date('d M Y', strtotime($start)) . " s.d. " . date('d M Y', strtotime($end));
    } else {
        $month = date('m');
        $year = date('Y');
        $conditions[] = "MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year'";
        $periodeTitle = "Periode " . date('F Y');
    }
} else {
    $periodeTitle = "Semua Data";
}

if (!empty($conditions)) {
    $filterQuery .= " WHERE " . implode(" AND ", $conditions);
}
$filterQuery .= " ORDER BY tanggal DESC";

$query = mysqli_query($konek, $filterQuery);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Pengeluaran PT. Gangsar Purnama Mandiri');
$sheet->mergeCells('A1:I1');
$sheet->setCellValue('A2', $periodeTitle);
$sheet->mergeCells('A2:I2');

$sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Header
$headers = ['No', 'No Pengeluaran', 'Tanggal', 'Keterangan', 'Total', 'Jenis Pengeluaran', 'No Invoice', 'Perusahaan', 'Nominal/Invoice'];
$sheet->fromArray($headers, null, 'A4');
$sheet->getStyle('A4:I4')->getFont()->setBold(true);

// Data Rows
$row = 5;
$no = 1;
$grandTotal = 0;

while ($pgl = mysqli_fetch_assoc($query)) {
    $jenis_list = [];
    $jenis_query = mysqli_query($konek, "SELECT * FROM pengeluaran_jenis WHERE pengeluaran_id = {$pgl['id']}");
    while ($jenis = mysqli_fetch_assoc($jenis_query)) {
        $jenis_list[] = $jenis['jenis_pengeluaran'] . ' - Rp ' . number_format($jenis['nominal'], 0, ',', '.');
    }
    $jenis_text = implode("; ", $jenis_list);

    $items = mysqli_query($konek, "
        SELECT pi.*, inv.no_invoice, inv.perusahaan 
        FROM pengeluaran_items pi 
        LEFT JOIN invoices inv ON pi.invoice_id = inv.id 
        WHERE pi.pengeluaran_id = {$pgl['id']}
    ");

    $total = (float) $pgl['total_pengeluaran'];
    $grandTotal += $total;

    if (mysqli_num_rows($items) == 0) {
        $sheet->setCellValue("A{$row}", $no++);
        $sheet->setCellValue("B{$row}", $pgl['no_pengeluaran']);
        $sheet->setCellValue("C{$row}", date('d-m-Y', strtotime($pgl['tanggal'])));
        $sheet->setCellValue("D{$row}", $pgl['keterangan']);
        $sheet->setCellValue("E{$row}", $total);
        $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->setCellValue("F{$row}", $jenis_text);
        $sheet->setCellValue("G{$row}", '-');
        $sheet->setCellValue("H{$row}", '-');
        $sheet->setCellValue("I{$row}", '-');
        $row++;
    } else {
        $first = true;
        while ($item = mysqli_fetch_assoc($items)) {
            $sheet->setCellValue("A{$row}", $first ? $no++ : '');
            $sheet->setCellValue("B{$row}", $first ? $pgl['no_pengeluaran'] : '');
            $sheet->setCellValue("C{$row}", $first ? date('d-m-Y', strtotime($pgl['tanggal'])) : '');
            $sheet->setCellValue("D{$row}", $first ? $pgl['keterangan'] : '');
            if ($first) {
                $sheet->setCellValue("E{$row}", $total);
                $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            }
            $sheet->setCellValue("F{$row}", $first ? $jenis_text : '');
            $sheet->setCellValue("G{$row}", $item['no_invoice']);
            $sheet->setCellValue("H{$row}", $item['perusahaan']);
            $nominal = (float) $item['nominal'];
            $sheet->setCellValue("I{$row}", $nominal);
            $sheet->getStyle("I{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $first = false;
            $row++;
        }
    }
}

// Grand Total
$sheet->setCellValue("A{$row}", 'GRAND TOTAL');
$sheet->mergeCells("A{$row}:D{$row}");
$sheet->setCellValue("E{$row}", $grandTotal);
$sheet->getStyle("A{$row}:E{$row}")->getFont()->setBold(true);
$sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

// Auto size
foreach (range('A', 'I') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Border
$sheet->getStyle("A4:I{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Output file
$cleanPeriode = preg_replace('/[^a-zA-Z0-9\s\-]/', '', $periodeTitle);
$cleanPeriode = preg_replace('/\s+/', ' ', $cleanPeriode);
$cleanPeriode = trim($cleanPeriode);

$namaFile = 'Pengeluaran PT Gangsar Purnama Mandiri Periode ' . $cleanPeriode . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$namaFile\"");

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

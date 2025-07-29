<?php
if (!isset($_GET['id'])) {
    echo "ID pengeluaran tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);

// Ambil data utama pengeluaran
$query = mysqli_query($konek, "SELECT * FROM pengeluaran WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data pengeluaran tidak ditemukan.";
    exit;
}

// Ambil invoice terkait
$invoices = mysqli_query($konek, "SELECT * FROM pengeluaran_items WHERE pengeluaran_id = $id");
$invoice_ids_terkait = [];
while ($inv_row = mysqli_fetch_assoc($invoices)) {
    $invoice_ids_terkait[] = $inv_row['invoice_id'];
}
mysqli_data_seek($invoices, 0); // reset pointer

// Ambil jenis pengeluaran
$jenis = mysqli_query($konek, "SELECT * FROM pengeluaran_jenis WHERE pengeluaran_id = $id");

// Ambil semua invoice yang belum dipakai di pengeluaran_items ATAU yang sudah dipakai oleh pengeluaran ini
$invoice_query = "
    SELECT id, no_invoice FROM invoices
    WHERE id NOT IN (
        SELECT invoice_id FROM pengeluaran_items WHERE pengeluaran_id != $id
    )
    OR id IN (" . implode(',', array_map('intval', $invoice_ids_terkait)) . ")
";
$invoice_list = mysqli_query($konek, $invoice_query);
$all_invoices = [];
while ($row = mysqli_fetch_assoc($invoice_list)) {
    $all_invoices[] = $row;
}
?>


<main id="full-width-main" class="full-width-main">
    <div class="pagetitle">
        <h1>Edit Pengeluaran</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=beranda">Home</a></li>
                <li class="breadcrumb-item active">Edit Pengeluaran</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit Pengeluaran</h5>
                            <form action="pages/pengeluaran_update.php" method="POST">
                                <input type="hidden" name="pengeluaran_id" value="<?= $data['id'] ?>">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>No. Pengeluaran:</label>
                                        <input class="form-control mt-2 mb-2" type="text" name="no_pengeluaran" value="<?= htmlspecialchars($data['no_pengeluaran']) ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Tanggal:</label>
                                        <input class="form-control mt-2 mb-2" type="date" name="tanggal" value="<?= $data['tanggal'] ?>" required>
                                    </div>
                                </div>



                                <label>Keterangan:</label>
                                <textarea class="form-control mt-2 mb-2" name="keterangan" rows="3" cols="50" ><?= htmlspecialchars($data['keterangan']) ?></textarea>

                                <h4>Invoice Terkait:</h4>
                                <div id="invoiceContainer">
                                    <p class="card-text"><span>pada bagian ini ubah dapat mengubah atau menambahkan invoice terkait</span></p>
                                    <?php
                                    while ($inv = mysqli_fetch_assoc($invoices)):
                                    ?>
                                        <div class="invoice-row">
                                            <select class="form-control" name="invoice_id[]">
                                                <?php foreach ($all_invoices as $opt): ?>
                                                    <option class="form-control" value="<?= $opt['id'] ?>" <?= $opt['id'] == $inv['invoice_id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($opt['no_invoice']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                                <button class="btn btn-primary mt-2 mb-2" type="button" onclick="addInvoice()">+ Tambah Invoice</button>
                                <h4>Jenis Pengeluaran:</h4>
                                <p class="card-text"><span>pada bagian ini ubah dapat mengubah atau menambahkan Jenis Pengeluaran</span></p>
                                <div id="jenisContainer mb-2">
                                    <?php while ($j = mysqli_fetch_assoc($jenis)): ?>
                                        <div class="row mb-2 jenis-row">
                                            <div class="col-md-6">
                                                <input class="form-control" type="text" name="jenis_pengeluaran[]" placeholder="Jenis Pengeluaran" value="<?= htmlspecialchars($j['jenis_pengeluaran']) ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control" type="number" name="nominal_jenis[]" placeholder="Nominal" value="<?= $j['nominal'] ?>" step="0.01" required>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                                <button class="btn btn-primary mt-2 mb-2" type="button" onclick="addJenis()">+ Tambah Jenis Pengeluaran</button>

                                <br><br>
                                <button class="btn btn-success" type="submit">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    function addInvoice() {
        const container = document.getElementById('invoiceContainer');
        const div = document.createElement('div');
        div.classList.add('invoice-row');
        div.innerHTML = `
        <select class="form-control mt-2" name="invoice_id[]">
                <option class="form-control" value="" disable selected>--Pilih No Invoice--</option>
            <?php foreach ($all_invoices as $opt): ?>
                <option class="form-control" value="<?= $opt['id'] ?>"><?= htmlspecialchars($opt['no_invoice']) ?></option>
            <?php endforeach; ?>
        </select>
    `;
        container.appendChild(div);
    }

    function addJenis() {
        const container = document.getElementById('jenisContainer');
        const div = document.createElement('div');
        div.classList.add('jenis-row');
        div.innerHTML = `
    <div class="row mb-2">
        <div class="col-md-6">
            <input class="form-control" type="text" name="jenis_pengeluaran[]" placeholder="Jenis Pengeluaran" required>
        </div>
        <div class="col-md-6">
            <input class="form-control" type="number" name="nominal_jenis[]" placeholder="Nominal" step="0.01" required>
        </div>
    </div>
`;

        container.appendChild(div);
    }
</script>
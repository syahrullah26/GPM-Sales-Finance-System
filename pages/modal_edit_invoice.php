<?php
$items = mysqli_query($konek, "SELECT * FROM invoice_items WHERE invoice_id = " . $inv['id']);
?>
<div class="modal fade" id="editInvoiceModal<?= $inv['id'] ?>" tabindex="-1" aria-labelledby="editInvoiceLabel<?= $inv['id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="invoice_update.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Invoice: <?= htmlspecialchars($inv['no_invoice']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="invoice_id" value="<?= $inv['id'] ?>">
                    <div class="mb-3">
                        <label>Perusahaan</label>
                        <input type="text" name="perusahaan" class="form-control" value="<?= $inv['perusahaan'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" value="<?= $inv['alamat'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>No Invoice</label>
                        <input type="text" name="no_invoice" class="form-control" value="<?= $inv['no_invoice'] ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Tanggal Invoice</label>
                            <input type="date" name="tanggal_invoice" class="form-control" value="<?= $inv['tanggal_invoice'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jatuh Tempo</label>
                            <input type="date" name="jatuh_tempo" class="form-control" value="<?= $inv['jatuh_tempo'] ?>" required>
                        </div>
                    </div>

                    <h5>Produk</h5>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Satuan</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = mysqli_fetch_assoc($items)): ?>
                                <tr>
                                    <input type="hidden" name="item_id[]" value="<?= $item['id'] ?>">
                                    <td><input type="text" name="nama_barang[]" class="form-control" value="<?= $item['nama_barang'] ?>" required></td>
                                    <td><input type="number" name="quantity[]" class="form-control" value="<?= $item['quantity'] ?>" required></td>
                                    <td><input type="text" name="satuan[]" class="form-control" value="<?= $item['satuan'] ?>" required></td>
                                    <td><input type="number" step="0.01" name="harga_beli[]" class="form-control" value="<?= $item['harga_beli'] ?>" required></td>
                                    <td><input type="number" step="0.01" name="harga_jual[]" class="form-control" value="<?= $item['harga_jual'] ?>" required></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

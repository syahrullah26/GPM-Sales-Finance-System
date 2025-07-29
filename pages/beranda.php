<?php
$perusahaanList = [];
$result = mysqli_query($konek, "SELECT DISTINCT perusahaan FROM invoices");
while ($row = mysqli_fetch_assoc($result)) {
  $perusahaanList[] = $row['perusahaan'];
}

?>
<link rel="stylesheet" href="assets/css/Beranda.css">

<body>
  <main id="full-width-main" class="full-width-main">
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php?page=beranda">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row g-3">
            <!-- Sales Card -->
            <div class="col-lg-12 ">
              <div class="row">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Catatan Perusahaan</h5>
                    <p class="card-text"> Sistem Management data <b>PT. Gangsar Purnama Mandiri</b>
                      <!-- Default Tabs -->
                    <ul class="nav nav-tabs d-flex" id="myTabjustified" role="tablist">
                      <li class="nav-item flex-fill" role="presentation">
                        <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-justified" type="button" role="tab" aria-controls="home" aria-selected="true">Invoice & Surat Jalan</button>
                      </li>
                      <li class="nav-item flex-fill" role="presentation">
                        <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-justified" type="button" role="tab" aria-controls="profile" aria-selected="false">Penawaran</button>
                      </li>
                    </ul>
                    <div class="tab-content pt-2" id="myTabjustifiedContent">
                      <div class="tab-pane fade show active" id="home-justified" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card-body">
                          <h5 class="card-title">Create <span> Invoice & Surat Jalan</span></h5>
                          <p class="card-title"> <span>Form untuk membuat invoice dan surat jalan</span></p>
                          <div class="d-flex align-items-center">
                            <div class="col-md-12">
                              <form class="row g-3" role="form" action="pages/prosesInvoice.php" method="POST">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <label>Perusahaan:</label>
                                    <input class="form-control" type="text" list="daftarPerusahaan" placeholder="Masukan Nama Perusahaan" name="perusahaan" required>
                                    <datalist id="daftarPerusahaan">
                                      <?php foreach ($perusahaanList as $nama): ?>
                                        <option value="<?= htmlspecialchars($nama) ?>">
                                        <?php endforeach; ?>
                                    </datalist>
                                  </div>
                                  <div class="form-group">
                                    <label>Alamat Perusahaan:</label>
                                    <input class="form-control" type="text" placeholder="Masukan Alamat Perusahaan" name="alamat" required>
                                  </div>

                                  <div class="row">
                                    <div class="form-group col-md-4">
                                      <label>No Invoice:</label>
                                      <input class="form-control" placeholder="Masukan No Invoice" type="text" name="no_invoice" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                      <label>No PO:</label>
                                      <input class="form-control" placeholder="Masukan No PO" type="text" name="no_po" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                      <label>No Surat Jalan:</label>
                                      <input class="form-control" placeholder="Masukan No Surat Jalan" type="text" name="no_sj" required>
                                    </div>
                                  </div>

                                  <div class="row">
                                    <div class="form-group col-md-6">
                                      <label>Tanggal Invoice:</label>
                                      <input class="form-control" type="date" name="tanggal_invoice" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                      <label>Jatuh Tempo:</label>
                                      <input class="form-control" type="date" name="jatuh_tempo" required>
                                    </div>
                                  </div>

                                  <h4 class="mt-4">Detail Produk</h4>
                                  <div class="table-responsive">
                                    <table class="table table-bordered">
                                      <thead class="thead-light">
                                        <tr>
                                          <th>Nama Barang</th>
                                          <th>Quantity</th>
                                          <th>Satuan</th>
                                          <th>Harga Beli</th>
                                          <th>Harga Jual</th>
                                          <th>Aksi</th>
                                        </tr>
                                      </thead>
                                      <tbody id="produk-body-invoice">
                                        <tr>
                                          <td><input type="text" class="form-control" name="nama_barang[]" placeholder="Nama Barang" required></td>
                                          <td><input type="number" class="form-control" name="quantity[]" placeholder="Qty" min="1" required></td>
                                          <td><input type="text" class="form-control" name="satuan[]" placeholder="Satuan" required></td>
                                          <td><input type="number" class="form-control" name="harga_beli[]" step="0.01" placeholder="Harga Beli" required></td>
                                          <td><input type="number" class="form-control" name="harga_jual[]" step="0.01" placeholder="Harga Jual" required></td>
                                          <td><button type="button" class="btn btn-danger" onclick="hapusBaris(this)"><i class="fas fa-trash-alt"></i> Hapus</button></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>

                                  <button type="button" onclick="tambahBaris()" class="btn btn-success mb-3">+ Tambah Produk</button>

                                  <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                  </div>
                                </div>
                              </form>

                              </form>
                              <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                              <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                              <script>
                                $(document).ready(function() {
                                  $('#inputName5').select2({
                                    placeholder: "Pilih Produk",
                                    allowClear: true
                                  });
                                });
                              </script>

                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="profile-justified" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card-body">
                          <h5 class="card-title mt-5">Create <span> surat Penwaran</span></h5>
                          <p class="card-title"> <span>Form untuk membuat surat jalan</span></p>
                          <p class="card-text">
                          <div class="col-md-12">
                            <form class="row g-3" role="form" action="pages/prosesPenawaran.php" method="POST">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Perusahaan:</label>
                                  <input class="form-control" type="text" list="daftarPerusahaan" placeholder="Masukan Nama Perusahaan" name="perusahaan" required>
                                  <datalist id="daftarPerusahaan">
                                    <?php foreach ($perusahaanList as $nama): ?>
                                      <option value="<?= htmlspecialchars($nama) ?>">
                                      <?php endforeach; ?>
                                  </datalist>
                              </div>

                              <div class="row">
                                <div class="form-group col-md-4">
                                  <label>Alamat :</label>
                                  <input class="form-control" type="text" name="alamat" placeholder="Masukan Alamat Perusahaan" required>
                                </div>
                                <div class="form-group col-md-4">
                                  <label>No Surat Penawran</label>
                                  <input class="form-control" type="text" name="no_sp" placeholder="Masukan Nomor Surat Penawaran" required>
                                </div>
                                <div class="form-group col-md-4">
                                  <label>Tanggal Penawaran :</label>
                                  <input class="form-control" type="date" name="tanggal_penawaran" required>
                                </div>
                              </div>

                              <h4 class="mt-4">Detail Produk</h4>
                              <div class="table-responsive">
                                <table class="table table-bordered">
                                  <thead class="thead-light">
                                    <tr>
                                      <th>Nama Barang</th>
                                      <th>Quantity</th>
                                      <th>Satuan</th>
                                      <th>Harga Beli</th>
                                      <th>Harga Jual</th>
                                      <th>Keterangan</th>
                                      <th>Aksi</th>
                                    </tr>
                                  </thead>
                                  <tbody id="produk-body-penawaran">
                                    <tr>
                                      <td><input type="text" class="form-control" name="nama_barang[]" placeholder="Nama Barang" required></td>
                                      <td><input type="number" class="form-control" name="quantity[]" placeholder="Qty" min="1" required></td>
                                      <td><input type="text" class="form-control" name="satuan[]" placeholder="Satuan" required></td>
                                      <td><input type="number" class="form-control" name="harga_beli[]" step="0.01" placeholder="Harga Beli" required></td>
                                      <td><input type="number" class="form-control" name="harga_jual[]" step="0.01" placeholder="Harga Jual" required></td>
                                      <td><input type="text" class="form-control" name="keterangan[]" placeholder="keterangan"></td>
                                      <td><button type="button" class="btn btn-danger" onclick="hapusBaris(this)"><i class="fas fa-trash-alt"></i> Hapus</button></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>

                              <button type="button" onclick="addRow()" class="btn btn-success mb-3">+ Tambah Produk</button>

                              <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                <button type="reset" class="btn btn-danger">Reset</button>
                              </div>
                          </div>
                          </form>

                        </div>
                      </div>


    </section>
  </main><!-- End #main -->
  <script>
    function tambahBaris() {
      const tbody = document.getElementById("produk-body-invoice");
      const row = tbody.insertRow();
      row.innerHTML = `
      <td><input type="text" class="form-control" name="nama_barang[]" required></td>
      <td><input type="number" class="form-control" name="quantity[]" min="1" required></td>
      <td><input type="text" class="form-control" name="satuan[]" required></td>
      <td><input type="number" class="form-control" name="harga_beli[]" step="0.01" required></td>
      <td><input type="number" class="form-control" name="harga_jual[]" step="0.01" required></td>
      <td><button type="button" class="btn btn-danger" onclick="hapusBaris(this)"><i class="fas fa-trash-alt"></i> Hapus</button></td>
    `;
    }

    function addRow() {
      const tbody = document.getElementById("produk-body-penawaran");
      const newRow = document.createElement("tr");
      newRow.innerHTML = `
      <td><input type="text" class="form-control" name="nama_barang[]" placeholder="Nama Barang" required></td>
      <td><input type="number" class="form-control" name="quantity[]" placeholder="Qty" min="1" required></td>
      <td><input type="text" class="form-control" name="satuan[]" placeholder="Satuan" required></td>
      <td><input type="number" class="form-control" name="harga_beli[]" step="0.01" placeholder="Harga Beli" required></td>
      <td><input type="number" class="form-control" name="harga_jual[]" step="0.01" placeholder="Harga Jual" required></td>
      <td><input type="text" class="form-control" name="keterangan[]" placeholder="keterangan" ></td>
      <td><button type="button" class="btn btn-danger" onclick="hapusBaris(this)"><i class="fas fa-trash-alt"></i> Hapus</button></td>
    `;
      tbody.appendChild(newRow);
    }

    function hapusBaris(button) {
      const row = button.closest("tr");
      row.remove();
    }
  </script>

</body>
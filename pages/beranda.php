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
                              <form class="row gy-4" action="pages/prosesInvoice.php" method="POST">
                                <!-- Informasi Perusahaan -->
                                <div class="col-12">
                                  <div class="border rounded-4 p-4 shadow-sm bg-white">
                                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-building me-2"></i>Informasi Perusahaan</h5>
                                    <div class="row g-3">
                                      <div class="col-md-6">
                                        <input type="text" name="perusahaan" class="form-control" list="daftarPerusahaan" placeholder="🔍 Nama Perusahaan" required>
                                        <datalist id="daftarPerusahaan">
                                          <?php foreach ($perusahaanList as $nama): ?>
                                            <option value="<?= htmlspecialchars($nama) ?>">
                                            <?php endforeach; ?>
                                        </datalist>
                                      </div>
                                      <div class="col-md-6">
                                        <input type="text" name="alamat" class="form-control" placeholder="📍 Alamat Perusahaan" required>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-12">
                                  <div class="border rounded-4 p-4 shadow-sm bg-white">
                                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-file-alt me-2"></i>Informasi Dokumen</h5>
                                    <div class="row g-3">
                                      <div class="col-md-4">
                                        <div class="input-group">
                                          <input type="text" name="no_invoice" class="form-control" placeholder="🧾 No Invoice" required>
                                          <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalNoInvoice"><i class="fas fa-search"></i>
                                          </button>
                                        </div>
                                        <small class="form-text text-muted">Contoh Format : <b>9089/INV/GPM/VIII/25</b>.</small><br>
                                        <small class="form-text text-muted">Tekan tombol "<i class="fas fa-search"></i>" untuk melihat nomor invoice yang sudah digunakan.</small>
                                      </div>
                                      <div class="col-md-4"><input type="text" name="no_po" class="form-control" placeholder="📑 No PO" required></div>
                                      <div class="col-md-4">
                                        <div class="input-group">
                                          <input type="text" name="no_sj" class="form-control" placeholder="🚚 No Surat Jalan" required>
                                          <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalNoSJ"><i class="fas fa-search"></i>
                                          </button>
                                        </div>
                                        <small class="form-text text-muted">Contoh Format : <b>9089/SJ/GPM/VIII/25</b>.</small><br>
                                        <small class="form-text text-muted">Tekan tombol "<i class="fas fa-search"></i>" untuk melihat nomor Surat Jalan yang sudah digunakan.</small>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-12">
                                  <div class="border rounded-4 p-4 shadow-sm bg-white">
                                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-calendar-alt me-2"></i>Tanggal</h5>
                                    <div class="row g-3">
                                      <div class="col-md-6"><input type="date" name="tanggal_invoice" class="form-control" required></div>
                                      <div class="col-md-6"><input type="date" name="jatuh_tempo" class="form-control" required></div>
                                    </div>
                                  </div>
                                </div>


                                <div class="col-12">
                                  <div class="border rounded-4 p-4 shadow-sm bg-white">
                                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-coins me-2"></i>Pajak</h5>
                                    <div class="btn-group" role="group" aria-label="Pajak Options">
                                      <input type="radio" class="btn-check" name="pajak" id="pajakYa" value="ya" required>
                                      <label class="btn btn-outline-success" for="pajakYa"><i class="fas fa-check-circle me-1"></i> Dengan Pajak</label>

                                      <input type="radio" class="btn-check" name="pajak" id="pajakTidak" value="tidak" checked>
                                      <label class="btn btn-outline-danger" for="pajakTidak"><i class="fas fa-times-circle me-1"></i> Tanpa Pajak</label>
                                    </div>
                                  </div>
                                </div>


                                <div class="col-12">
                                  <div class="border rounded-4 p-4 shadow-sm bg-white">
                                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-box-open me-2"></i>Detail Produk</h5>
                                    <div class="table-responsive">
                                      <table class="table table-bordered align-middle table-sm">
                                        <thead class="table-light text-center">
                                          <tr>
                                            <th>Barang</th>
                                            <th>Qty</th>
                                            <th>Satuan</th>
                                            <th>Harga Beli</th>
                                            <th>Harga Jual</th>
                                            <th>Aksi</th>
                                          </tr>
                                        </thead>
                                        <tbody id="produk-body-invoice">
                                          <tr>
                                            <td><input type="text" name="nama_barang[]" class="form-control form-control-sm" required></td>
                                            <td><input type="number" name="quantity[]" class="form-control form-control-sm" min="1" required></td>
                                            <td><input type="text" name="satuan[]" class="form-control form-control-sm" required></td>
                                            <td><input type="number" name="harga_beli[]" step="0.01" class="form-control form-control-sm" required></td>
                                            <td><input type="number" name="harga_jual[]" step="0.01" class="form-control form-control-sm" required></td>
                                            <td class="text-center">
                                              <button type="button" class="btn btn-sm btn-danger" onclick="hapusBaris(this)">
                                                <i class="fas fa-trash-alt"></i>
                                              </button>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                    <button type="button" onclick="tambahBaris()" class="btn btn-outline-primary btn-sm mt-2">
                                      <i class="fas fa-plus me-1"></i> Tambah Produk
                                    </button>
                                  </div>
                                </div>

                                <!-- Tombol -->
                                <div class="col-12 text-end">
                                  <button type="submit" class="btn btn-primary px-4" name="submit">
                                    <i class="fas fa-paper-plane me-1"></i> Submit
                                  </button>
                                  <button type="reset" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-undo me-1"></i> Reset
                                  </button>
                                </div>
                              </form>


                              <div class="modal fade" id="modalNoInvoice" tabindex="-1" aria-labelledby="modalNoInvoiceLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable">
                                  <div class="modal-content rounded-4">
                                    <div class="modal-header bg-info text-white">
                                      <h5 class="modal-title" id="modalNoInvoiceLabel"><i class="fas fa-history me-2"></i>Riwayat No Invoice Sebelumnya</h5>
                                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <label class="form-label">Filter berdasarkan Perusahaan:</label>
                                      <select class="form-select mb-3" id="filterPerusahaanInvoice" onchange="filterNoInvoice()">
                                        <option value="">-- Pilih Perusahaan --</option>
                                        <?php foreach ($perusahaanList as $nama): ?>
                                          <option value="<?= htmlspecialchars($nama) ?>"><?= htmlspecialchars($nama) ?></option>
                                        <?php endforeach; ?>
                                      </select>

                                      <div id="rekomendasiInvoice" class="alert alert-info d-none">
                                        💡 <strong>Rekomendasi No Invoice Baru:</strong> <span id="rekomendasiNoInvoice"></span>
                                        <button class="btn btn-sm btn-outline-success float-end" onclick="isiNoInvoice(document.getElementById('rekomendasiNoInvoice').textContent)">Gunakan</button>
                                      </div>

                                      <ul class="list-group small" id="listNoInvoice">
                                        <li class="list-group-item text-center text-muted">Silakan pilih perusahaan</li>
                                      </ul>
                                    </div>
                                  </div>
                                </div>
                              </div>


                              <div class="modal fade" id="modalNoSJ" tabindex="-1" aria-labelledby="modalNoSJLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable">
                                  <div class="modal-content rounded-4">
                                    <div class="modal-header bg-warning text-dark">
                                      <h5 class="modal-title" id="modalNoSJLabel"><i class="fas fa-history me-2"></i>Riwayat No Surat Jalan Sebelumnya</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                      <label class="form-label">Filter berdasarkan Perusahaan:</label>
                                      <select class="form-select mb-3" id="filterPerusahaanSJ" onchange="filterNoSJ()">
                                        <option value="">-- Pilih Perusahaan --</option>
                                        <?php foreach ($perusahaanList as $nama): ?>
                                          <option value="<?= htmlspecialchars($nama) ?>"><?= htmlspecialchars($nama) ?></option>
                                        <?php endforeach; ?>
                                      </select>

                                      <div id="rekomendasiSJ" class="alert alert-warning d-none">
                                        💡 <strong>Rekomendasi No SJ Baru:</strong> <span id="rekomendasiNoSJ"></span>
                                        <button class="btn btn-sm btn-outline-dark float-end" onclick="isiNoSJ(document.getElementById('rekomendasiNoSJ').textContent)">Gunakan</button>
                                      </div>

                                      <ul class="list-group small" id="listNoSJ">
                                        <li class="list-group-item text-center text-muted">Silakan pilih perusahaan</li>
                                      </ul>
                                    </div>
                                  </div>
                                </div>
                              </div>


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
                            <form class="row g-4" role="form" action="pages/prosesPenawaran.php" method="POST">
                              <!-- Informasi Umum -->
                              <div class="col-12">
                                <div class="bg-light rounded p-3 shadow-sm">
                                  <h6 class="fw-semibold text-primary mb-3">📁 Informasi Umum</h6>
                                  <div class="row g-3">
                                    <div class="col-md-6">
                                      <input class="form-control" type="text" name="perusahaan" list="daftarPerusahaan" placeholder="Nama Perusahaan" required>
                                      <datalist id="daftarPerusahaan">
                                        <?php foreach ($perusahaanList as $nama): ?>
                                          <option value="<?= htmlspecialchars($nama) ?>">
                                          <?php endforeach; ?>
                                      </datalist>
                                    </div>
                                    <div class="col-md-6">
                                      <input class="form-control" type="text" name="penerima" placeholder="Nama Penerima" required>
                                    </div>
                                    <div class="col-md-4">
                                      <input class="form-control" type="text" name="alamat" placeholder="Alamat Perusahaan" required>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="input-group">
                                        <input type="text" class="form-control" name="no_sp" placeholder="No Surat Penawaran" required>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalNoSP">
                                          <i class="fas fa-search"></i>
                                        </button>
                                      </div>
                                      <small class="form-text text-muted">Contoh Format : <b>9089/GPM/VIII/25</b>.</small><br>
                                      <small class="form-text text-muted">Tekan tombol "<i class="fas fa-search"></i>" untuk melihat nomor penawaran yang sudah digunakan.</small>
                                    </div>

                                    <div class="col-md-4">
                                      <input class="form-control" type="date" name="tanggal_penawaran" required>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <!-- Detail Produk -->
                              <div class="col-12">
                                <div class="bg-light rounded p-3 shadow-sm">
                                  <h6 class="fw-semibold text-primary mb-3">📦 Detail Produk</h6>
                                  <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle text-center">
                                      <thead class="table-light">
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
                                          <td><input type="text" class="form-control form-control-sm" name="nama_barang[]" required></td>
                                          <td><input type="number" class="form-control form-control-sm" name="quantity[]" min="1" required></td>
                                          <td><input type="text" class="form-control form-control-sm" name="satuan[]" required></td>
                                          <td><input type="number" step="0.01" class="form-control form-control-sm" name="harga_beli[]" required></td>
                                          <td><input type="number" step="0.01" class="form-control form-control-sm" name="harga_jual[]" required></td>
                                          <td><input type="text" class="form-control form-control-sm" name="keterangan[]"></td>
                                          <td>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="hapusBaris(this)">
                                              <i class="fas fa-trash-alt"></i>
                                            </button>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                  <button type="button" onclick="addRow()" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-plus-circle me-1"></i> Tambah Produk
                                  </button>
                                </div>
                              </div>

                              <!-- Tombol Submit -->
                              <div class="col-12 text-end">
                                <button type="submit" name="submit" class="btn btn-primary">
                                  <i class="fas fa-paper-plane me-1"></i> Submit
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                  <i class="fas fa-undo me-1"></i> Reset
                                </button>
                              </div>
                            </form>
                            <!-- MODAL -->
                            <div class="modal fade" id="modalNoSP" tabindex="-1" aria-labelledby="modalNoSPLabel" aria-hidden="true">
                              <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                  <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="modalNoSPLabel"><i class="fas fa-history me-2"></i> Riwayat No SP Sebelumnya</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="mb-3">
                                      <label for="filterPerusahaan" class="form-label">Filter berdasarkan Perusahaan:</label>
                                      <select id="filterPerusahaan" class="form-select" onchange="filterNoSP()">
                                        <option value="">-- Pilih Perusahaan --</option>
                                        <?php
                                        $perusahaanQuery = mysqli_query($konek, "SELECT DISTINCT nama_perusahaan FROM penawaran ORDER BY nama_perusahaan ASC");
                                        while ($perusahaan = mysqli_fetch_assoc($perusahaanQuery)) {
                                          $nama = htmlspecialchars($perusahaan['nama_perusahaan']);
                                          echo "<option value=\"$nama\">$nama</option>";
                                        }
                                        ?>
                                      </select>
                                    </div>
                                    <ul class="list-group" id="listNoSP">
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </div>



                          </div>
                        </div>


    </section>
  </main><!-- End #main -->
  <script>
    function tambahBaris() {
      const tbody = document.getElementById("produk-body-invoice");
      const row = tbody.insertRow();
      row.innerHTML = `
      <td><input type="text" class="form-control" name="nama_barang[]" placeholder="Masukan Nama Barang" required></td>
      <td><input type="number" class="form-control" name="quantity[]" placeholder="Qty" min="1" required></td>
      <td><input type="text" class="form-control" name="satuan[]"  placeholder="Satuan" required></td>
      <td><input type="number" class="form-control" name="harga_beli[]"  placeholder="Masukan Harga Beli" step="0.01" required></td>
      <td><input type="number" class="form-control" name="harga_jual[]" placeholder="Masukan harga Jual" step="0.01" required></td>
      <td><button type="button" class="btn btn-danger" onclick="hapusBaris(this)"><i class="fas fa-trash-alt"></i></button></td>
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
      <td><button type="button" class="btn btn-danger" onclick="hapusBaris(this)"><i class="fas fa-trash-alt"></i></button></td>
    `;
      tbody.appendChild(newRow);
    }

    function hapusBaris(button) {
      const row = button.closest("tr");
      row.remove();
    }

    function filterNoSP() {
      const perusahaan = document.getElementById('filterPerusahaan').value;
      const list = document.getElementById('listNoSP');
      list.innerHTML = '<li class="list-group-item text-center text-muted">Memuat data...</li>';

      fetch(`pages/ajax_get_no_sp.php?perusahaan=${encodeURIComponent(perusahaan)}`)
        .then(res => res.text())
        .then(html => {
          list.innerHTML = html;
        })
        .catch(err => {
          list.innerHTML = '<li class="list-group-item text-danger">Gagal memuat data</li>';
          console.error(err);
        });
    }

    function isiNoSP(no_sp) {
      const input = document.querySelector('input[name="no_sp"]');
      if (input) input.value = no_sp;
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalNoSP'));
      modal.hide();
    }

    function filterNoInvoice() {
      const perusahaan = document.getElementById('filterPerusahaanInvoice').value;
      const list = document.getElementById('listNoInvoice');
      list.innerHTML = '<li class="list-group-item text-center text-muted">Memuat data...</li>';

      fetch(`pages/ajax_get_no_invoice.php?perusahaan=${encodeURIComponent(perusahaan)}`)
        .then(res => res.text())
        .then(html => {
          list.innerHTML = html;
        })
        .catch(() => {
          list.innerHTML = '<li class="list-group-item text-danger">Gagal memuat data</li>';
        });
    }

    function filterNoSJ() {
      const perusahaan = document.getElementById('filterPerusahaanSJ').value;
      const list = document.getElementById('listNoSJ');
      list.innerHTML = '<li class="list-group-item text-center text-muted">Memuat data...</li>';

      fetch(`pages/ajax_get_no_sj.php?perusahaan=${encodeURIComponent(perusahaan)}`)
        .then(res => res.text())
        .then(html => {
          list.innerHTML = html;
        })
        .catch(() => {
          list.innerHTML = '<li class="list-group-item text-danger">Gagal memuat data</li>';
        });
    }

    function isiNoInvoice(no) {
      const input = document.querySelector('input[name="no_invoice"]');
      if (input) input.value = no;
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalNoInvoice'));
      modal.hide();
    }

    function isiNoSJ(no) {
      const input = document.querySelector('input[name="no_sj"]');
      if (input) input.value = no;
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalNoSJ'));
      modal.hide();
    }
  </script>


</body>
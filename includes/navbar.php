<link href="assets/css/styleNav.css" rel="stylesheet">
<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between w-100">
    <a href="index.php?page=beranda" class="logo d-flex align-items-center">
      <img src="assets/images/purnama.png" alt="">
      <span class="d-none d-lg-block">PURNAMA MANDIRI</span>
    </a>
    <button class="nav-toggle" aria-label="toggle navigation">
      <span class="hamburger"></span>
    </button>
  </div><!-- End Logo -->

  <nav class="nav">
    <ul>
      <li class="nav-link">
        <a href="index.php?page=beranda">Home</a>
      </li>
      <li class="nav-link">
        <a href="index.php?page=penawaran">Penawaran</a>
      </li>
      <li class="nav-link">
        <a href="index.php?page=rugiLaba">Invoices & Surat Jalan</a>
      </li>
      <li class="nav-link">
        <a href="index.php?page=pengeluaran">Pengeluaran</a>
      </li>
      <li class="nav-link">
        <a href="index.php?page=rugiLabaBersih">Rugi Laba</a>
      </li>
    </ul>
  </nav>
  <script>
    document.querySelector('.nav-toggle').addEventListener('click', function() {
      this.classList.toggle('active');
      document.querySelector('.nav').classList.toggle('active');
    });
  </script>
</header>

<!-- Vendor JS Files -->
<script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/chart.js/chart.umd.js"></script>
<script src="../assets/vendor/echarts/echarts.min.js"></script>
<script src="../assets/vendor/quill/quill.min.js"></script>
<script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="../assets/vendor/tinymce/tinymce.min.js"></script>
<script src="../assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="../assets/js/main.js"></script>
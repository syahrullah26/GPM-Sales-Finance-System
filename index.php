<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>PT. Gangsar Purnama Mandiri</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/images/purnama.png" rel="icon">
  <link href="assets/images/purnama.png" rel="apple-touch-icon">
  <link rel="icon" href="assets/images/purnama.png" type="image/x-icon">
  <link rel="shortcut icon" href="assets/images/purnama.png" type="image/x-icon">
  <link rel="apple-touch-icon" href="assets/images/purnama.png">
  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styleProduct.css">
  <link rel="stylesheet" href="assets/css/loader.css">

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.5.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<style>
  #full-width-main {
    margin-top: 60px;
    /* Adjust top margin if needed */
    padding: 20px;
    width: 100%;
    /* Ensure main takes full width */
  }

  @media (max-width: 1199px) {
    #full-width-main {
      padding: 20px;
      margin-left: 0;
      /* Ensure full width on smaller screens */
    }
  }

  @media (min-width: 1200px) {
    #full-width-main {
      margin-left: 0;
      /* Ensure full width on larger screens as well */
    }
  }
</style>

<body>
  <!-- Loader -->
  <!-- <div id="loader" class="loader">
        <div class="loader-content">
          <div class="loader-icon">
            <img src="assets/images/purnama.png" alt="Bastami Logo">
          </div>
          <div class="loader-progress">
            <div class="progress-bar"></div>
          </div>
        </div>
      </div> -->

  <!-- Your existing content here -->

  <?php include "includes/navbar.php"; ?>
  <?php include "includes/koneksi.php"; ?>
  <?php
  if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
      case 'beranda':
        include "pages/beranda.php";
        break;
      case 'rugiLaba':
        include "pages/rugiLaba.php";
        break;
      case 'penawaran':
        include "pages/penawaran.php";
        break;
      case 'acceptPenawaran':
        include "pages/penawaran_accept.php";
        break;
      case 'editPenawaran':
        include "pages/penawaran_edit.php";
        break;
      case 'invoice_edit':
        include "pages/invoice_edit.php";
        break;
      case 'pengeluaran':
        include "pages/pengeluaran.php";
        break;
      case 'pengeluaran_edit':
        include "pages/pengeluaran_edit.php";
        break;
      case 'overdue_invoice':
        include "pages/invoice_jatuh_tempo.php";
        break;
      case 'rugiLabaBersih':
        include "pages/rugi_laba_bersih.php";
        break;
      case 'laba_bersih':
        include "pages/laba_bersih.php";
        break;
      case 'laporan_pengeluaran':
        include "pages/pengeluaran_laporan.php";
        break;
      default:
        include "pages/beranda.php";
        break;
    }
  } else {
    include "pages/beranda.php";
  }
  ?>

  <?php include "includes/footer.php"; ?>


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <!-- Skrip Bootstrap JavaScript -->
  <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->


  <!-- Custom JS for Loader -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const loader = document.getElementById('loader');
      // Remove loader when the page is fully loaded
      window.addEventListener('load', function() {
        loader.classList.add('loader-hidden');
      });
    });
  </script>
</body>


</html>
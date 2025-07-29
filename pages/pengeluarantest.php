
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Pengeluaran</title>

  <!-- Select2 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- Select2 JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
    }
    select, input[type="text"] {
      width: 100%;
      padding: 8px;
      box-sizing: border-box;
    }
  </style>
</head>

<body>
  <h2>Form Pengeluaran</h2>

  <form action="proses_pengeluaran.php" method="POST">
    <div class="form-group">
      <label for="invoice_id">No. Invoice</label>
      <select id="invoice_id" name="invoice_id" style="width:100%;"></select>
    </div>

    <div class="form-group">
      <label for="jumlah">Jumlah</label>
      <input type="text" id="jumlah" name="jumlah" required>
    </div>

    <button type="submit">Simpan</button>
  </form>

  <script>
    $(document).ready(function () {
      $('#invoice_id').select2({
        placeholder: "Cari Nomor Invoice",
        allowClear: true,
        ajax: {
          url: '/purnama/ajax/fetch_invoices.php',
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              search: params.term
            };
          },
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        },
        minimumInputLength: 1
      });
    });
  </script>
</body>

</html>

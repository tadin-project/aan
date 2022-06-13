<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $this->renderSection('title'); ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>/node_modules/admin-lte/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>/node_modules/admin-lte/dist/css/adminlte.min.css">
  <!-- Sweetalert 2 -->
  <link rel="stylesheet" href="<?= base_url() ?>/node_modules/sweetalert2/dist/sweetalert2.min.css">
  <!-- Jstree -->
  <link rel="stylesheet" href="<?= base_url() ?>/node_modules/jstree/dist/themes/default/style.min.css">
  <!-- DataTables Bootstrap 4 -->
  <link href="<?= base_url() ?>/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
  <!-- DataTables Col Reorder Bootstrap 4 -->
  <link href="<?= base_url() ?>/node_modules/datatables.net-colreorder-bs4/css/colReorder.bootstrap4.min.css" rel="stylesheet" />
  <!-- DataTables Fixed Columns Bootstrap 4 -->
  <link href="<?= base_url() ?>/node_modules/datatables.net-fixedcolumns-bs4/css/fixedColumns.bootstrap4.min.css" rel="stylesheet" />
  <!-- DataTables Scroller Bootstrap 4 -->
  <link href="<?= base_url() ?>/node_modules/datatables.net-scroller-bs4/css/scroller.bootstrap4.min.css" rel="stylesheet" />
  <!-- DataTables Responsive Bootstrap 4 -->
  <link href="<?= base_url() ?>/node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
  <!-- Bootstrap datepicker -->
  <link href="<?= base_url() ?>/node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" />

  <style>
    .has-error {
      color: #dc3545;
    }

    .has-error .form-control {
      border-color: #dc3545;
    }

    .datepicker {
      z-index: 1040 !important;
    }
  </style>

  <!-- jQuery -->
  <script src="<?= base_url() ?>/node_modules/admin-lte/plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="<?= base_url() ?>/node_modules/admin-lte/plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url() ?>/node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url() ?>/node_modules/admin-lte/dist/js/adminlte.js"></script>
  <!-- Sweetalert2 -->
  <script src="<?= base_url() ?>/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <!-- Jquery Validation -->
  <script src="<?= base_url() ?>/node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
  <!-- Jquery Validation Plugin -->
  <script src="<?= base_url() ?>/node_modules/jquery-validation/dist/localization/messages_id.min.js"></script> <!-- DataTables -->
  <script src="<?= base_url() ?>/node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
  <!-- DataTables Bootstrap 4 -->
  <script src="<?= base_url() ?>/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
  <!-- DataTables Col Reorder -->
  <script src="<?= base_url() ?>/node_modules/datatables.net-colreorder/js/dataTables.colReorder.min.js"></script>
  <!-- DataTables Fixed Columns -->
  <script src="<?= base_url() ?>/node_modules/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js"></script>
  <!-- DataTables Responsive -->
  <script src="<?= base_url() ?>/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
  <!-- DataTables Responsive Bootstrap 4 -->
  <script src="<?= base_url() ?>/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
  <!-- DataTables Scroller -->
  <script src="<?= base_url() ?>/node_modules/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
  <!-- Jstree -->
  <script src="<?= base_url() ?>/node_modules/jstree/dist/jstree.min.js"></script>
  <!-- Chartjs -->
  <script src="<?= base_url() ?>/node_modules/chart.js/dist/chart.min.js"></script>
  <!-- Bootstrap Datepicker -->
  <script src="<?= base_url() ?>/node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="<?= base_url() ?>/node_modules/admin-lte/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <?= $this->renderSection('navbar'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= base_url() ?>/dashboard" class="brand-link">
        <img src="<?= base_url() ?>/node_modules/admin-lte/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">
          <?= $this->renderSection('title-website'); ?>
        </span>
      </a>

      <!-- Sidebar -->
      <?= $this->renderSection('sidebar'); ?>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0"><?= $this->renderSection('title-menu'); ?></h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Main row -->
          <?= $this->renderSection('content'); ?>
          <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <?= $this->renderSection('footer'); ?>
    </footer>
  </div>
  <!-- ./wrapper -->

  <script>
    function confirmLogout() {
      Swal.fire({
        title: "Peringatan",
        text: "Apakah Anda yakin logout?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Iya",
        cancelButtonText: "Tidak",
      }).then(function(result) {
        if (result.value) {
          window.location.replace('<?= base_url() ?>/logout')
        }
      });
    }
  </script>
</body>

</html>
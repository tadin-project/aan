<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?> | Forgot Password</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>/node_modules/admin-lte/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url() ?>/node_modules/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>/node_modules/admin-lte/dist/css/adminlte.min.css">
  <style type="text/css">
    .has-error {
      color: #dc3545;
    }

    .has-error .form-control {
      border-color: #dc3545;
    }

    .error-block {
      color: #dc3545;
    }
  </style>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="<?= base_url() ?>"><b><?= $title ?></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

        <form method="post" id="form_vendor">
          <div class="input-group mb-3">
            <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="error-block mb-3"></div>
          <div class="row">
            <div class="col-12">
              <button type="submit" id="btnSubmit" class="btn btn-primary btn-block">Request new password</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <p class="mt-3 mb-1">
          <a href="<?= base_url() ?>">Login</a>
        </p>
        <p class="mb-0">
          <a href="<?= base_url() . '/register' ?>" class="text-center">Register a new membership</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= base_url() ?>/node_modules/admin-lte/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url() ?>/node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url() ?>/node_modules/admin-lte/dist/js/adminlte.min.js"></script>
  <!-- Sweetalert2 -->
  <script src="<?= base_url() ?>/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <!-- JQuery Validate -->
  <script src="<?= base_url() ?>/node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
  <!-- JQuery Validate Plugin -->
  <script src="<?= base_url() ?>/node_modules/jquery-validation/dist/localization/messages_id.min.js"></script>
  <script>
    var AdvancedPage = function() {

      var initForm = function() {
        const btnSubmit = $('#btnSubmit');
        const formVendor = $("#form_vendor")

        btnSubmit.click(function() {
          formVendor.submit();
        })

        // Validation Rules
        formVendor.validate({
          rules: {
            user_email: {
              required: true,
            },
          },
          errorClass: 'help-block',
          errorElement: 'span',
          ignore: 'input[type=hidden]',
          highlight: function(el, errorClass) {
            const parentEl = $(el).closest('.input-group').first()
            parentEl.addClass('has-error');
            parentEl.next('.error-block').show();
            parentEl.next('.error-block').css('margin-top', '-15px');
          },
          unhighlight: function(el, errorClass) {
            const parentEl = $(el).closest('.input-group').first();
            parentEl.removeClass('has-error');
            parentEl.next('.error-block').hide();
            parentEl.next('.error-block').css('margin-top', '0');
          },
          errorPlacement: function(error, el) {
            error.appendTo(el.closest('.input-group').next(".error-block"));
          },
          submitHandler: function(form) {
            btnSubmit.attr('disabled', 'disabled').text('Loading...');
            let data = formVendor.serialize()

            $.ajax({
              url: '<?= base_url() ?>/forgot-password',
              data: data,
              type: 'post',
              dataType: 'json',
              complete: function() {
                btnSubmit.removeAttr('disabled', 'disabled').text('Login');
              },
              error: function() {
                btnSubmit.removeAttr('disabled', 'disabled').text('Login');
              },
              success: res => {
                if (res.status) {
                  Swal.fire({
                    icon: "success",
                    title: "Success",
                    html: "Berhasil memasukkan email",
                    showConfirmButton: false,
                    timer: 1500
                  })
                  window.location.replace(res.url)
                } else {
                  Swal.fire({
                    icon: "error",
                    title: "Error",
                    html: res.message,
                  })
                }
              }
            })
          }
        });
      }

      return {

        //main function to initiate the module
        init: function() {
          initForm();
        },

      };

    }();

    $(document).ready(function() {
      AdvancedPage.init()
    })
  </script>
</body>

</html>
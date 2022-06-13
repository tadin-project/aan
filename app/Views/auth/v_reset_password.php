<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SB Admin 2 - Reset Password</title>
  <!-- Custom fonts for this template-->
  <link href="<?= base_url() ?>/node_modules/startbootstrap-sb-admin-2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?= base_url() ?>/node_modules/startbootstrap-sb-admin-2/css/sb-admin-2.min.css" rel="stylesheet">
  <style type="text/css">
    .has-error {
      color: #dc3545;
    }

    .has-error .form-control {
      border-color: #dc3545;
    }
  </style>
</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-6 col-lg-6 col-md-8">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-2">Reset Password</h1>
                  </div>
                  <form class="user" id="form_vendor" method="POST"> <input type="hidden" name="user_id" id="user_id" value="<?= $user_id ?>">

                    <div class="form-group">
                      <input class="form-control form-control-user" name="password" id="password" type="password" placeholder="Enter New Password...">
                      <div class="text-error"></div>
                    </div>
                    <div class="form-group">
                      <input class="form-control form-control-user" name="password2" id="password2" type="password" placeholder="Confirm Password">
                      <div class="text-error"></div>
                    </div>
                    <button class="btn btn-primary btn-user btn-block" type="submit" id="btnSubmit">Reset Password</button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="<?= base_url() . '/auth' ?>">Already have an account? Login!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?= base_url() ?>/node_modules/startbootstrap-sb-admin-2/vendor/jquery/jquery.min.js"></script>
  <script src="<?= base_url() ?>/node_modules/startbootstrap-sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?= base_url() ?>/node_modules/startbootstrap-sb-admin-2/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="<?= base_url() ?>/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script src="<?= base_url() ?>/node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
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
            password: {
              required: true,
            },
            password2: {
              required: true,
              equalTo: '#password',
            }
          },
          errorClass: 'help-block',
          errorElement: 'span',
          ignore: 'input[type=hidden]',
          highlight: function(el, errorClass) {
            $(el).closest('.form-floating').first().addClass('has-error');
          },
          unhighlight: function(el, errorClass) {
            var $parent = $(el).closest('.form-floating').first();
            $parent.removeClass('has-error');
            $parent.find('.help-block').hide();
          },
          errorPlacement: function(error, el) {
            error.appendTo(el.closest('.form-floating').find('.text-error'));
          },
          submitHandler: function(form) {
            btnSubmit.attr('disabled', 'disabled').text('Loading...');
            let data = formVendor.serialize()

            $.ajax({
              url: '<?= base_url() ?>/auth/reset_proses',
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
                    html: "Berhasil perbarui password",
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
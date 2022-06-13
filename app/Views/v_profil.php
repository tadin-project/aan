<h1 class="mt-4"><?= $title ?></h1>
<div class="row" id="rowList">
  <div class="col-xl-12">
    <div class="card mb-4">
      <div class="card-header">
        <div class="row">
          <div class="col-sm-6 align-self-center">
            <h4 class="card-title">
              <?= $title ?>
            </h4>
          </div>
          <div class="col-sm-6">
          </div>
        </div>
      </div>
      <div class="card-body">
        <input type="hidden" name="user_id" id="user_id" value="<?= $user->user_id ?>">
        <div class="row">
          <div class="col-md-6">
            <!--begin::Form-->
            <div class="row mb-3">
              <h4 class="col-md-12 text-center">Detail Data</h4>
            </div>
            <div class="row mb-3">
              <label class="col-md-3 col-form-label">Username</label>
              <div class="col-md-5">
                <input type="text" class="form-control" id="user_name" disabled name="user_name" value="<?= $user->user_name ?>" />
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-md-3 col-form-label">Email</label>
              <div class="col-md-5">
                <input type="text" class="form-control" id="user_email" disabled name="user_email" value="<?= $user->user_email ?>" />
              </div>
            </div>
            <form id="form_update_user">
              <div class="row mb-3">
                <label class="col-md-3 col-form-label">Nama Lengkap</label>
                <div class="col-md-5">
                  <input type="text" class="form-control" id="user_fullname" name="user_fullname" value="<?= $user->user_fullname ?>" />
                </div>
              </div>
              <div class="row mb-3 text-center">
                <div class="col-md-12">
                  <button class="btn btn-primary" type="button" id="btnUpdateUser">Perbarui</button>
                </div>
              </div>
            </form>
            <!--end::Form-->
          </div>
          <div class="col-md-6">
            <div class="row mb-3">
              <h4 class="col-md-12 text-center">Ganti Password</h4>
            </div>
            <form class="form" id="form_ganti_pass">
              <div class="row mb-3">
                <label class="col-md-4 col-form-label">Password</label>
                <div class="col-md-8">
                  <input type="password" class="form-control" placeholder="Password" id="password" name="password" />
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-md-4 col-form-label">Konfirmasi Password</label>
                <div class="col-md-8">
                  <input type="password" class="form-control" placeholder="Konfirmasi Password" id="password2" name="password2" />
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12 text-center">
                  <button class="btn btn-primary" id="btnUpdtPass" name="btnUpdtPass">Ganti Password</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var AdvancedPage = function() {

    var initFormPass = function() {
      const btnSave = $('#btnUpdtPass');
      const formVendor = $("#form_ganti_pass")

      btnSave.click(function() {
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
            equalTo: '#password'
          },
        },
        errorClass: 'help-block',
        errorElement: 'span',
        ignore: 'input[type=hidden]',
        highlight: function(el, errorClass) {
          $(el).closest('.row').first().addClass('has-error');
        },
        unhighlight: function(el, errorClass) {
          var $parent = $(el).closest('.row').first();
          $parent.removeClass('has-error');
          $parent.find('.help-block').hide();
        },
        errorPlacement: function(error, el) {
          error.appendTo(el.closest('.row').find('div:first'));
        },
        submitHandler: function(form) {
          btnSave.attr('disabled', 'disabled').text('Loading...');
          let data = formVendor.serialize() + '&user_id=' + $("#user_id").val()

          $.ajax({
            url: '<?= base_url() ?>/profil/update_pass',
            data: data,
            type: 'post',
            dataType: 'json',
            complete: function() {
              btnSave.removeAttr('disabled', 'disabled').text('Save');
            },
            error: function() {
              btnSave.removeAttr('disabled', 'disabled').text('Save');
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

    var initFormUser = function() {
      const btnSave = $('#btnUpdateUser');
      const formVendor = $("#form_update_user")

      btnSave.click(function() {
        formVendor.submit();
      })

      // Validation Rules
      formVendor.validate({
        rules: {
          user_fullname: {
            required: true,
          },
        },
        errorClass: 'help-block',
        errorElement: 'span',
        ignore: 'input[type=hidden]',
        highlight: function(el, errorClass) {
          $(el).closest('.row').first().addClass('has-error');
        },
        unhighlight: function(el, errorClass) {
          var $parent = $(el).closest('.row').first();
          $parent.removeClass('has-error');
          $parent.find('.help-block').hide();
        },
        errorPlacement: function(error, el) {
          error.appendTo(el.closest('.row').find('div:first'));
        },
        submitHandler: function(form) {
          btnSave.attr('disabled', 'disabled').text('Loading...');
          let data = formVendor.serialize() + '&user_id=' + $("#user_id").val()

          $.ajax({
            url: '<?= base_url() ?>/profil/update_user',
            data: data,
            type: 'post',
            dataType: 'json',
            complete: function() {
              btnSave.removeAttr('disabled', 'disabled').text('Perbarui');
            },
            error: function() {
              btnSave.removeAttr('disabled', 'disabled').text('Perbarui');
            },
            success: res => {
              if (res.status) {
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  html: "Berhasil perbarui data user",
                  showConfirmButton: false,
                  timer: 1500
                })
                window.location.href = window.location.href;
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
        initFormPass();
        initFormUser();
      },

    };

  }();
  $(document).ready(function() {
    AdvancedPage.init();
  })
</script>
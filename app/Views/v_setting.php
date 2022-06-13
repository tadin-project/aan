<div class="row" id="rowForm" style="display: none;">
  <div class="col-xl-12">
    <div class="card mb-4">
      <div class="card-header">
        <div class="row">
          <div class="col-sm-6 align-self-center">
            <h4 class="card-title">
              Form <?= $title ?>
            </h4>
          </div>
          <div class="col-sm-8">
            <button class="btn btn-primary float-right" type="button" onclick="$('#btnCancel').click()"><i class="fa fa-database"></i> List</button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <!--begin::Form-->
        <form class="form" id="form_vendor">
          <input type="hidden" name="act" id="act" value="edit">
          <input type="hidden" name="setting_id" id="setting_id">
          <div class="row mb-3">
            <label class="col-md-3 col-form-label">Nama Lengkap</label>
            <div class="col-md-5">
              <input type="text" disabled class="form-control" placeholder="Kode" id="setting_kode" name="setting_kode" />
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-md-3 col-form-label">Nama</label>
            <div class="col-md-5">
              <input type="text" disabled class="form-control" placeholder="Nama" id="setting_nama" name="setting_nama" />
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-md-3 col-form-label">Nilai</label>
            <div class="col-md-7">
              <textarea class="form-control" placeholder="Nilai" id="setting_value" name="setting_value"></textarea>
            </div>
          </div>
        </form>
        <!--end::Form-->
      </div>
      <div class="card-footer text-center">
        <div class="d-grid gap-2 d-md-block">
          <button type="button" id="btnSave" class="btn btn-primary">Save</button>
          <button type="button" id="btnCancel" class="btn btn-secondary">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row" id="rowList">
  <div class="col-xl-12">
    <div class="card mb-4">
      <div class="card-header">
        <div class="row">
          <div class="col-sm-6 align-self-center">
            <h4 class="card-title">
              Data <?= $title ?>
            </h4>
          </div>
        </div>
      </div>
      <div class="card-body">
        <!--begin::DataTable-->
        <table class="table table-bordered table-hover table-checkable" id="tbl_vendor" style="margin-top: 13px !important; width: 100%;">
          <thead>
            <tr>
              <th class="text-center">No.</th>
              <th class="text-center">Kode</th>
              <th class="text-center">Nama</th>
              <th class="text-center">Nilai</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
        </table>
        <!--end::DataTable-->
      </div>
    </div>
  </div>
</div>

<script>
  var TableAdvanced = function() {

    var initTable1 = function() {
      var table = $('#tbl_vendor');

      // begin first table
      table.DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: true,
        ajax: {
          url: '<?= base_url() ?>/setting/get-data',
          type: 'POST',
        },
        columnDefs: [{
          targets: [0, -1],
          orderable: false,
          className: 'text-center',
        }, ],
        "order": [
          [1, 'asc']
        ]
      });
    };

    var initForm = function() {
      const btnSave = $('#btnSave');
      const formVendor = $("#form_vendor")

      btnSave.click(function() {
        formVendor.submit();
      })

      // Validation Rules
      formVendor.validate({
        rules: {
          setting_id: {
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
          let data = formVendor.serialize()

          $.ajax({
            url: '<?= base_url() ?>/setting',
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
                $("#btnCancel").click()
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  html: res.message,
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

    return {

      //main function to initiate the module
      init: function() {
        initTable1();
        initForm();
      },

    };

  }();

  function resetForm() {
    $("#form_vendor")[0].reset()
    $("#form_vendor").validate().resetForm()
    $(".has-error").removeClass('has-error')
    $("#act").val('edit')
    $("#setting_id").val('')
    $('#btnSave').removeAttr('disabled', 'disabled').text('Save');
  }

  function reload_tbl() {
    $("#tbl_vendor").dataTable().fnDraw();
  }

  function set_val(data) {
    const isi = JSON.parse(decodeURIComponent(data))

    $("#act").val('edit')
    $("#setting_id").val(isi.setting_id)
    $("#setting_kode").val(isi.setting_kode)
    $("#setting_nama").val(isi.setting_nama)
    $("#setting_value").val(isi.setting_value)
    $("#rowForm").slideDown(500)
    $("#rowList").slideUp(500)
  }

  jQuery(document).ready(function() {
    TableAdvanced.init();

    $("#btnCancel").click(function() {
      resetForm()
      reload_tbl()
      $("#rowForm").slideUp(500)
      $("#rowList").slideDown(500)
    })
  });
</script>
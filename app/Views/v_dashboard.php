<!-- <script src="<?= base_url() ?>/node_modules/highcharts/highcharts.js"></script>
<script src="<?= base_url() ?>/node_modules/highcharts/highcharts-more.js"></script>
<script src="<?= base_url() ?>/node_modules/highcharts/modules/solid-gauge.js"></script>
<script src="<?= base_url() ?>/node_modules/highcharts/modules/exporting.js"></script>
<script src="<?= base_url() ?>/node_modules/highcharts/modules/export-data.js"></script>
<script src="<?= base_url() ?>/node_modules/highcharts/modules/accessibility.js"></script> -->

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
          <div class="col-sm-6">
          </div>
        </div>
      </div>
      <div class="card-body">
        <!-- <div class="row mb-3">
          <div class="col-md-9">
            <div class="row">
              <label class="col-md-3 col-form-label">Periode</label>
              <div class="col-md-9">
                <div class="input-group">
                  <input type="text" readonly class="form-control tgl" id="tgl_mulai" name="tgl_mulai" value="<?= date('d-m-Y') ?>">
                  <span class="input-group-text">s/d</span>
                  <input type="text" readonly class="form-control tgl" id="tgl_selesai" name="tgl_selesai" value="<?= date('d-m-Y') ?>">
                </div>
              </div>
            </div>
          </div>
        </div> -->

        <div class="row mb-3">
          <div class="col-md-12">
            <table class="table table-hover table-bordered table-striped" id="tbl_vendor">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">Magnitude</th>
                  <th class="text-center">Timer</th>
                  <th class="text-center">Waktu</th>
                  <th class="text-center">Output</th>
                  <th class="text-center">Kondisi</th>
                  <th class="text-center">Gangguan</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var PageAdvanced = function() {

    // var initTableLogger = function() {
    //   var table = $('#tbl_vendor');

    //   // begin first table
    //   table.DataTable({
    //     responsive: true,
    //     searchDelay: 500,
    //     processing: true,
    //     serverSide: true,
    //     ajax: {
    //       url: '<?= base_url() ?>/dashboard/get_data',
    //       type: 'POST',
    //       data: function(d) {
    //         d.ld_id = $("#ld_id").val()
    //       },
    //     },
    //     columnDefs: [{
    //       targets: [0],
    //       orderable: false,
    //     }, {
    //       targets: [0],
    //       className: 'text-center',
    //     }, ],
    //     "order": [
    //       [1, 'asc']
    //     ]
    //   });
    // };

    return {

      //main function to initiate the module
      init: function() {
        // initTableLogger();
      },

    };

  }();

  function updateData() {
    $.ajax({
      url: '<?= base_url() ?>/dashboard/get_data_grafik',
      cache: false,
      data: {
        tgl_mulai: $("#tgl_mulai").val(),
        tgl_selesai: $("#tgl_selesai").val(),
      },
      dataType: 'json',
      success: res => {
        doChart.data.datasets[0].data = res.do.data;
        doChart.data.labels = res.do.labels;
        doChart.update();
      }
    })
  }

  function reload_tbl() {
    $("#tbl_vendor").dataTable().fnDraw();
  }

  $(document).ready(function() {
    PageAdvanced.init();

    // $('.tgl').datepicker({
    //   format: 'dd-mm-yyyy',
    //   autoclose: true,
    // })

    // updateData()

    // setInterval(() => {
    //   updateData()
    // }, 10000);

  })
</script>
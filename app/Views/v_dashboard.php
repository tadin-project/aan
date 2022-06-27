<!-- Leaflet CSS -->
<link href="<?= base_url() ?>/node_modules/leaflet/dist/leaflet.css" rel="stylesheet" />
<style>
  #map {
    width: 100%;
    height: 45vh;
  }
</style>

<!-- Leaflet JS -->
<script src="<?= base_url() ?>/node_modules/leaflet/dist/leaflet.js"></script>

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
        <div class="row mb-3">
          <div class="col-md-8">
            <div id="map"></div>
          </div>
          <div class="col-md-4 d-flex justify-content-center">
            <img src="<?= base_url() ?>/assets/img/kapal.png" alt="Kapal tampilan atas" style="max-height: 250px;">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let map, drd_lat, drd_lang, drd_accuracy, osm;

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

    const initMapLeaflet = function() {
      map = L.map("map").setView({
        lat: 0.7893,
        lon: 113.9213
      }, 5);
      osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);
    }

    return {

      //main function to initiate the module
      init: function() {
        // initTableLogger();
        initMapLeaflet();
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
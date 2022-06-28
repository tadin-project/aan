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
            <div class="row mb-3">
              <div class="col-md-12">
                <div id="map"></div>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-12">
                <iframe src="" frameborder="0" id="monitorCamera"></iframe>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-12 d-flex justify-content-center">
                <img src="<?= base_url() ?>/assets/img/kapal.png" alt="Kapal tampilan atas" style="max-height: 250px; transform:rotate(0deg);" id="kapalKompas">
              </div>
            </div>
            <div class="row d-flex mx-auto" style="max-width: 230px;">
              <div class="col-12 text-center mb-1">
                <button class="btn btn-kontrol btn-danger" data-value="0" data-position="up">
                  <i class="fas fa-arrow-alt-circle-up fa-2x"></i>
                </button>
              </div>
              <div class="col-12 mb-1">
                <div class="row">
                  <div class="col-6 align-self-start text-center">
                    <button class="btn btn-kontrol btn-danger" data-value="0" data-position="left">
                      <i class="fas fa-arrow-alt-circle-left fa-2x"></i>
                    </button>
                  </div>
                  <div class="col-6 align-self-end text-center">
                    <button class="btn btn-kontrol btn-danger" data-value="0" data-position="right">
                      <i class="fas fa-arrow-alt-circle-right fa-2x"></i>
                    </button>
                  </div>
                </div>
              </div>
              <div class="col-12 text-center">
                <button class="btn btn-kontrol btn-danger" data-value="0" data-position="bottom">
                  <i class="fas fa-arrow-alt-circle-down fa-2x"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let map, drd_lat, drd_lang, drd_accuracy, osm, intervalGetData, markerKapal;
  let ld_id = <?= $ld_id ?>

  var PageAdvanced = function() {

    var initBtnKontrol = function() {
      $('.btn-kontrol').click(function() {
        const btn = $(this);

        const ld_position = btn.data('position');
        const ld_value = btn.data('value') == 0 ? 1 : 0;

        let statusError = false;
        let pesanError = "";

        if ($('.btn-kontrol[data-position=up]').data('value') == 1 && ld_position == 'bottom' && ld_value == 1) {
          statusError = true;
          pesanError = "Matikan dulu tombol majunya";
          console.log('disini');
        }

        if ($('.btn-kontrol[data-position=bottom]').data('value') == 1 && ld_position == 'up' && ld_value == 1) {
          statusError = true;
          pesanError = "Matikan dulu tombol mundurnya";
        }

        if ($('.btn-kontrol[data-position=left]').data('value') == 1 && ld_position == 'right' && ld_value == 1) {
          statusError = true;
          pesanError = "Matikan dulu tombol belok kirinya";
        }

        if ($('.btn-kontrol[data-position=right]').data('value') == 1 && ld_position == 'left' && ld_value == 1) {
          statusError = true;
          pesanError = "Matikan dulu tombol belok kanannya";
        }

        if (statusError) {
          Swal.fire({
            icon: "error",
            title: "Error",
            html: pesanError,
          })
          return;
        } else {
          $.ajax({
            url: '<?= base_url() ?>/api/device',
            type: 'post',
            cache: false,
            dataType: 'json',
            data: {
              ld_id,
              ld_position,
              ld_value
            },
            error: err => {
              Swal.fire({
                icon: "error",
                title: "Error",
                html: JSON.stringify(err),
              })
            },
            success: res => {
              if (res.status) {
                const ldValue = res.data.ld_value;
                btn.data('value', ldValue);
                btn.removeClass(ldValue == 1 ? 'btn-danger' : 'btn-success')
                  .addClass(ldValue == 1 ? 'btn-success' : 'btn-danger');
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  html: res.msg,
                })
              }
            },
          })
        }

      })
    };

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
        initBtnKontrol();
      },

    };

  }();

  function updateData() {
    $.ajax({
      url: '<?= base_url() ?>/api/get_data_dashboard',
      cache: false,
      data: {
        ld_id,
      },
      dataType: 'json',
      error: err => {
        Swal.fire({
          icon: "error",
          title: "Error",
          html: JSON.stringify(err),
          // showConfirmButton: false,
          timer: 1500
        })
      },
      success: res => {
        if (!res.status) {
          Swal.fire({
            icon: "error",
            title: "Error",
            html: res.msg,
            // showConfirmButton: false,
            timer: 1500
          })
        } else {
          const dataRes = res.data;

          $("#kapalKompas").css('transform', `rotate(${dataRes.ld_kompas}deg)`);

          if (markerKapal) map.removeLayer(markerKapal);

          markerKapal = L.marker([dataRes.dd_lat, dataRes.dd_long], 5).addTo(map);

          map.setView([dataRes.dd_lat, dataRes.dd_long], 5);
        }
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

    updateData();

    intervalGetData = setInterval(() => {
      updateData();
    }, 8000);

  })
</script>
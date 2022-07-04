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
                <iframe width="100%" height="315" src="https://101.50.3.181/WebRTCAppEE/play.html?name=tugasakhir" frameborder="0" allowfullscreen></iframe>
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
              <div class="col-12 text-center mb-3">
                <button class="btn btn-kontrol btn-danger" data-value="0" data-position="motor">
                  <i class="fas fa-arrow-alt-circle-up fa-2x"></i>
                </button>
              </div>
              <div class="col-12 mb-1">
                <div class="row">
                  <div class="col-4 align-self-start text-center">
                    <button class="btn btn-kontrol btn-danger btn-kemudi" data-value="1" data-position="kemudi">
                      <i class="fas fa-arrow-alt-circle-left fa-2x"></i>
                    </button>
                  </div>
                  <div class="col-4 align-self-center text-center">
                    <button class="btn btn-kontrol btn-danger btn-kemudi" data-value="0" data-position="kemudi">
                      <i class="fas fa-circle fa-2x"></i>
                    </button>
                  </div>
                  <div class="col-4 align-self-end text-center">
                    <button class="btn btn-kontrol btn-danger btn-kemudi" data-value="2" data-position="kemudi">
                      <i class="fas fa-arrow-alt-circle-right fa-2x"></i>
                    </button>
                  </div>
                </div>
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
        const ld_value = ld_position == 'kemudi' ? btn.data('value') : (btn.data('value') == 1 ? 0 : 1);

        let statusError = false;
        let pesanError = "";

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
                if (ld_position == 'motor') {
                  btn.data('value', ld_value)
                  btn.removeClass(ld_value == 1 ? 'btn-danger' : 'btn-success')
                    .addClass(ld_value == 1 ? 'btn-success' : 'btn-danger');
                } else {
                  $('.btn-kemudi').removeClass('btn-success').addClass('btn-danger');
                  btn.removeClass('btn-danger')
                    .addClass('btn-success');
                }
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
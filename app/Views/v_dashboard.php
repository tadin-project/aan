<script src="<?= base_url() ?>/node_modules/highcharts/highcharts.js"></script>
<script src="<?= base_url() ?>/node_modules/highcharts/highcharts-more.js"></script>
<script src="<?= base_url() ?>/node_modules/highcharts/modules/solid-gauge.js"></script>
<script src="<?= base_url() ?>/node_modules/highcharts/modules/exporting.js"></script>
<script src="<?= base_url() ?>/node_modules/highcharts/modules/export-data.js"></script>
<script src="<?= base_url() ?>/node_modules/highcharts/modules/accessibility.js"></script>

<h1 class="mt-4"><?= $title ?></h1>
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
          <div class="col-md-6">
            <div class="row">
              <label class="col-md-3 col-form-label">Jenis Device</label>
              <div class="col-md-5">
                <select class="form-control" id="device_id" name="device_id">
                  <?php foreach ($opt_ms_device as $v) : ?>
                    <option value="<?= $v->device_id ?>"><?= $v->device_nama ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row">
              <label class="col-md-3 col-form-label">Nama Device</label>
              <div class="col-md-5">
                <select class="form-control" id="ld_id" name="ld_id">
                  <option value="0">Pilih Nama Device</option>
                </select>
              </div>
            </div>
          </div>
        </div>
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
          <div class="col-sm-4">
            <label class="col-form-label">V1</label>
            <div id="v1Chart"></div>
          </div>
          <div class="col-sm-4">
            <label class="col-form-label">V2</label>
            <div id="v2Chart"></div>
          </div>
          <div class="col-sm-4">
            <label class="col-form-label">V3</label>
            <div id="v3Chart"></div>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-sm-4">
            <label class="col-form-label">I1</label>
            <div id="i1Chart"></div>
          </div>
          <div class="col-sm-4">
            <label class="col-form-label">I2</label>
            <div id="i2Chart"></div>
          </div>
          <div class="col-sm-4">
            <label class="col-form-label">I3</label>
            <div id="i3Chart"></div>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="col-form-label">Voltage</label>
            <canvas id="lcVolt" height="400"></canvas>
          </div>
          <div class="col-md-6">
            <label class="col-form-label">Current</label>
            <canvas id="lcCurrent" height="400"></canvas>
          </div>
        </div>

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
  let vC1, vC2, vC3, iC1, iC2, iC3, lcVolt, lcCurrent;

  var PageAdvanced = function() {

    var initTableLogger = function() {
      var table = $('#tbl_vendor');

      // begin first table
      table.DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: true,
        ajax: {
          url: '<?= base_url() ?>/dashboard/get_data',
          type: 'POST',
          data: function(d) {
            d.ld_id = $("#ld_id").val()
          },
        },
        columnDefs: [{
          targets: [0],
          orderable: false,
        }, {
          targets: [0],
          className: 'text-center',
        }, ],
        "order": [
          [1, 'asc']
        ]
      });
    };

    var initGaugeChart = function() {

      var gaugeOptions = {
        chart: {
          type: 'solidgauge'
        },

        title: null,

        pane: {
          startAngle: -90,
          endAngle: 90,
          background: {
            backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#EEE',
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
          }
        },

        exporting: {
          enabled: false
        },

        tooltip: {
          enabled: false
        },

        // the value axis
        yAxis: {
          stops: [
            [0.1, '#55BF3B'], // green
            [0.5, '#DDDF0D'], // yellow
            [0.9, '#DF5353'] // red
          ],
          lineWidth: 0,
          tickWidth: 0,
          minorTickInterval: null,
          tickAmount: 2,
          title: {
            y: -70
          },
          labels: {
            y: 16
          }
        },

        plotOptions: {
          solidgauge: {
            dataLabels: {
              y: 5,
              borderWidth: 0,
              useHTML: true
            }
          }
        }
      };

      function chartGauge(id, nama = '', satuan = '', maxValue = 400) {
        // The speed gauge
        return Highcharts.chart(id, Highcharts.merge(gaugeOptions, {
          yAxis: {
            min: 0,
            max: maxValue,
            title: {
              text: nama
            }
          },

          credits: {
            enabled: false
          },

          series: [{
            name: nama,
            data: [0],
            dataLabels: {
              format: '<div style="text-align:center">' +
                '<span style="font-size:25px">{y}</span><br/>' +
                '<span style="font-size:12px;opacity:0.4">Volt</span>' +
                '</div>'
            },
            tooltip: {
              valueSuffix: ' ' + satuan
            }
          }]

        }));
      }

      vC1 = chartGauge('v1Chart', 'V1', 'Volt');
      vC2 = chartGauge('v2Chart', 'V2', 'Volt');
      vC3 = chartGauge('v3Chart', 'V3', 'Volt');
      iC1 = chartGauge('i1Chart', 'I1', 'Ampere', 15);
      iC2 = chartGauge('i2Chart', 'I2', 'Ampere', 15);
      iC3 = chartGauge('i3Chart', 'I3', 'Ampere', 15);
    }

    var initInterpolationChart = function() {

      async function lineChart(id, nama = '', satuan = '', maxValue = 400) {
        const labels = [];

        const data = {
          labels: labels,
          datasets: [{
            label: (id == "lcCurrent" ? 'I' : 'V') + 1,
            data: [],
            borderColor: 'red',
            fill: false,
            tension: 0.4
          }, {
            label: (id == "lcCurrent" ? 'I' : 'V') + 2,
            data: [],
            borderColor: 'yellow',
            fill: false,
            tension: 0.4
          }, {
            label: (id == "lcCurrent" ? 'I' : 'V') + 3,
            data: [],
            borderColor: 'black',
            fill: false,
            tension: 0.4
          }]
        };

        const config = {
          type: 'line',
          data: await data,
          options: {
            responsive: true,
            plugins: {
              title: {
                display: true,
                text: 'Chart.js Line Chart - Cubic interpolation mode'
              },
            },
            interaction: {
              intersect: false,
            },
            scales: {
              x: {
                display: true,
                title: {
                  display: true
                }
              },
              y: {
                display: true,
                title: {
                  display: true,
                  text: satuan
                },
                suggestedMin: 0,
                suggestedMax: maxValue
              }
            }
          },
        };

        return new Chart(document.getElementById(id), config);
      }

      lcVolt = lineChart('lcVolt', 'Voltage', 'Volt', 400);

      lcCurrent = lineChart('lcCurrent', 'Current', 'Ampere', 15);
    }

    return {

      //main function to initiate the module
      init: function() {
        initTableLogger();
        initGaugeChart();
        initInterpolationChart();
      },

    };

  }();

  function getListDevice() {
    const device_id = $("#device_id").val()
    $.ajax({
      url: '<?= base_url() ?>/dashboard/get_device',
      data: {
        device_id: device_id
      },
      cache: false,
      dataType: 'json',
      success: res => {
        $("#ld_id option[value!=0]").remove()
        if (res.length > 0) {
          let opt = ''
          $.each(res, function(index, item) {
            opt += `<option value="${item.ld_id}">${item.ld_kode}</option>`
          })

          $("#ld_id").append(opt)
        }
      }
    })
  }

  function updateData() {
    $.ajax({
      url: '<?= base_url() ?>/dashboard/get_data_grafik',
      cache: false,
      data: {
        device_id: $("#device_id").val(),
        ld_id: $("#ld_id").val(),
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
    $("#device_id").change(function() {
      getListDevice();
    })

    $("#device_id").trigger('change')

    // $('.tgl').datepicker({
    //   format: 'dd-mm-yyyy',
    //   autoclose: true,
    // })

    // updateData()

    setInterval(() => {
      // updateData()
    }, 10000);

    $("#ld_id").change(function() {
      reload_tbl();
    })

    vC1.setSize($("#v1Chart").width(), $("#v1Chart").height() * 0.5, false)
    vC2.setSize($("#v2Chart").width(), $("#v2Chart").height() * 0.5, false)
    vC3.setSize($("#v3Chart").width(), $("#v3Chart").height() * 0.5, false)
    iC1.setSize($("#i1Chart").width(), $("#i1Chart").height() * 0.5, false)
    iC2.setSize($("#i2Chart").width(), $("#i2Chart").height() * 0.5, false)
    iC3.setSize($("#i3Chart").width(), $("#i3Chart").height() * 0.5, false)
  })
</script>
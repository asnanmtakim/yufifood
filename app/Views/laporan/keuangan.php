<?= $this->extend('template/template'); ?>

<?= $this->section('css_libraries'); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<?php
$sort = 'week';
if (session()->get('sort')) {
   $sort = session()->get('sort');
}
if ($sort == 'week') {
   $nama = 'Minggu ini';
} else if ($sort == 'month') {
   $nama = 'Bulan ini';
} else if ($sort == 'year') {
   $nama = 'Tahun ini';
}
?>
<div class="main-content">
   <section class="section">
      <div class="section-header">
         <h1>Laporan Keuangan</h1>
         <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= base_url(); ?>">Dashboard</a></div>
            <div class="breadcrumb-item">Laporan Keuangan</div>
         </div>
      </div>
      <div class="section-body">
         <div class="print-area">
            <div class="row">
               <div class="col-12">
                  <div class="card">
                     <div class="card-header">
                        <h4>Laporan Keuangan</h4>
                        <div class="card-header-action dropdown">
                           <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><?= $nama; ?></a>
                           <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                              <li class="dropdown-title">Pilih periode</li>
                              <li><a href="#" data-sort="week" class="dropdown-item <?= $sort == 'week' ? 'active' : '' ?>">Minggu ini</a></li>
                              <li><a href="#" data-sort="month" class="dropdown-item <?= $sort == 'month' ? 'active' : '' ?>">Bulan ini</a></li>
                              <li><a href="#" data-sort="year" class="dropdown-item <?= $sort == 'year' ? 'active' : '' ?>">Tahun ini</a></li>
                           </ul>
                        </div>
                     </div>
                     <div class="card-body">
                        <canvas id="myChart" height="150"></canvas>
                     </div>
                  </div>
               </div>
               <div class="col-12">
                  <div class="card">
                     <div class="card-header">
                        <h4>Penjualan Bahan Jadi</h4>
                     </div>
                     <div class="card-body">
                        <canvas id="myChart4" height="150"></canvas>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- <div class="text-right">
            <button type="button" class="btn btn-warning btn-icon icon-left" id="print-laporan"><i class="fas fa-print"></i> Print</button>
         </div> -->
      </div>
   </section>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js_libraries'); ?>
<script src="<?= base_url(); ?>/assets/modules/chart.min.js"></script>
<script src="<?= base_url(); ?>/assets/js/printThis.js"></script>

<script>
   var myChart4;
   var myChart;
   $(document).ready(function() {
      buildLineChart();
      buildLineChart2();
   });

   // $(document).off("click", "button#print-laporan")
   //    .on("click", "button#print-laporan", function(e) {
   //       $(".print-area").printThis({
   //          // debug: false,
   //          // importCSS: true,
   //          // importStyle: true,
   //          // printContainer: true,
   //          // pageTitle: "Pembelian",
   //          // removeInline: false,
   //          // printDelay: 333,
   //          // header: null,
   //          // formValues: true
   //       });
   //    });

   $(document).off("click", ".dropdown-menu .dropdown-item")
      .on("click", ".dropdown-menu .dropdown-item", function(e) {
         e.preventDefault();
         var sort = $(this).attr("data-sort");
         let nama = '';
         if (sort == 'today') {
            nama = 'Hari ini';
         } else if (sort == 'week') {
            nama = 'Minggu ini';
         } else if (sort == 'month') {
            nama = 'Bulan ini';
         } else {
            nama = 'Tahun ini';
         }
         $('.card-header .dropdown a.dropdown-toggle').html(nama);
         $('.dropdown-menu .dropdown-item').removeClass('active');
         $(this).addClass('active');
         $.ajax({
            type: "POST",
            url: BASE_URL + "/laporan_keuangan/setSession",
            data: {
               name: 'sort',
               value: sort
            },
            dataType: "json",
            success: function(res) {}
         }).then(() => {
            myChart.destroy();
            myChart4.destroy();
            buildLineChart();
            buildLineChart2();
         });
      });

   function buildLineChart() {
      $.ajax({
         type: "GET",
         url: BASE_URL + "/laporan_keuangan/getKeuangan",
         dataType: "json",
         success: function(res) {
            var keuangan_chart = document.getElementById("myChart").getContext('2d');
            myChart = new Chart(keuangan_chart, {
               type: 'line',
               data: {
                  labels: res.data.data_x,
                  datasets: [{
                     label: 'Penjualan',
                     data: res.data.data_y,
                     borderWidth: 5,
                     borderColor: '#6777ef',
                     pointBackgroundColor: '#fff',
                     pointBorderColor: '#6777ef',
                     pointRadius: 4
                  }]
               },
               options: {
                  legend: {
                     display: false
                  },
                  scales: {
                     yAxes: [{
                        gridLines: {
                           display: true,
                           drawBorder: true,
                           color: '#f2f2f2',
                        },
                        ticks: {
                           stepSize: 100000,
                           callback: function(value, index, values) {
                              return 'Rp. ' + value;
                           }
                        }
                     }],
                     xAxes: [{
                        gridLines: {
                           color: '#fbfbfb',
                           lineWidth: 2
                        }
                     }]
                  },
               }
            });
         }
      });
   }

   function buildLineChart2() {
      $.ajax({
         type: "GET",
         url: BASE_URL + "/laporan_pembelian/getPembelian",
         dataType: "json",
         success: function(res) {
            $.ajax({
               type: "GET",
               url: BASE_URL + "/laporan_penjualan/getPenjualan",
               dataType: "json",
               success: function(res2) {
                  var ctx = document.getElementById("myChart4").getContext('2d');
                  myChart4 = new Chart(ctx, {
                     type: 'line',
                     data: {
                        labels: res.data.data_x,
                        datasets: [{
                              label: 'Pejualan',
                              data: res2.data.data_y,
                              borderWidth: 3,
                              backgroundColor: 'rgba(63,82,227,.8)',
                              borderColor: 'transparent',
                              pointBorderColor: 'rgba(63,82,227,.8)',
                              pointRadius: 4,
                              pointBackgroundColor: '#fff',
                              pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
                           },
                           {
                              label: 'Pembelian',
                              data: res.data.data_y,
                              borderWidth: 3,
                              backgroundColor: 'rgba(254,86,83,.7)',
                              borderColor: 'transparent',
                              pointBorderColor: 'rgba(254,86,83,.8)',
                              pointRadius: 4,
                              pointBackgroundColor: '#fff',
                              pointHoverBackgroundColor: 'rgba(254,86,83,.8)',
                           }
                        ]
                     },
                     options: {
                        legend: {
                           display: false
                        },
                        scales: {
                           yAxes: [{
                              gridLines: {
                                 // display: false,
                                 drawBorder: false,
                                 color: '#f2f2f2',
                              },
                              ticks: {
                                 beginAtZero: true,
                                 stepSize: 100000,
                                 callback: function(value, index, values) {
                                    return '$' + value;
                                 }
                              }
                           }],
                           xAxes: [{
                              gridLines: {
                                 display: false,
                                 tickMarkLength: 15,
                              }
                           }]
                        },
                     }
                  });
               }
            });
         }
      });
   }
</script>
<?= $this->endSection(); ?>
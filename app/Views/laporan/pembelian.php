<?= $this->extend('template/template'); ?>

<?= $this->section('css_libraries'); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<?php
$sort = 'week';
if (session()->get('sort')) {
   $sort = session()->get('sort');
}
if ($sort == 'today') {
   $nama = 'Hari ini';
} else if ($sort == 'week') {
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
         <h1>Laporan Pembelian</h1>
         <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= base_url(); ?>">Dashboard</a></div>
            <div class="breadcrumb-item">Laporan Pembelian</div>
         </div>
      </div>
      <div class="section-body">
         <div class="print-area">
            <div class="row">
               <div class="col-lg-7 col-md-7 col-12 col-sm-12">
                  <div class="card">
                     <div class="card-header">
                        <h4>Laporan Pembelian</h4>
                        <div class="card-header-action dropdown">
                           <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><?= $nama; ?></a>
                           <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                              <li class="dropdown-title">Pilih periode</li>
                              <li><a href="#" data-sort="today" class="dropdown-item <?= $sort == 'today' ? 'active' : '' ?>">Hari ini</a></li>
                              <li><a href="#" data-sort="week" class="dropdown-item <?= $sort == 'week' ? 'active' : '' ?>">Minggu ini</a></li>
                              <li><a href="#" data-sort="month" class="dropdown-item <?= $sort == 'month' ? 'active' : '' ?>">Bulan ini</a></li>
                              <li><a href="#" data-sort="year" class="dropdown-item <?= $sort == 'year' ? 'active' : '' ?>">Tahun ini</a></li>
                           </ul>
                        </div>
                     </div>
                     <div class="card-body">
                        <canvas id="myChart" height="182"></canvas>
                     </div>
                  </div>
               </div>
               <div class="col-lg-5 col-md-5 col-12 col-sm-12">
                  <div class="card">
                     <div class="card-header">
                        <h4>Pembelian Bahan Mentah</h4>
                     </div>
                     <div class="card-body">
                        <canvas id="myChart4"></canvas>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-12">
                  <div class="card">
                     <div class="card-body">
                        <div class="statistic-details">
                           <div class="statistic-details-item">
                              <span class="text-muted"><span class="text-primary"></span><?= $today['jumlah']; ?> pembelian</span>
                              <div class="detail-value">Rp. <?= number_format($today['uang'], 0, ',', '.'); ?></div>
                              <div class="detail-name">Pembelian Hari Ini</div>
                           </div>
                           <div class="statistic-details-item">
                              <span class="text-muted"><span class="text-primary"></span><?= $week['jumlah']; ?> pembelian</span>
                              <div class="detail-value">Rp. <?= number_format($week['uang'], 0, ',', '.'); ?></div>
                              <div class="detail-name">Pembelian Minggu Ini</div>
                           </div>
                           <div class="statistic-details-item">
                              <span class="text-muted"><span class="text-primary"></span><?= $month['jumlah']; ?> pembelian</span>
                              <div class="detail-value">Rp. <?= number_format($month['uang'], 0, ',', '.'); ?></div>
                              <div class="detail-name">Pembelian Bulan Ini</div>
                           </div>
                           <div class="statistic-details-item">
                              <span class="text-muted"><span class="text-primary"></span><?= $year['jumlah']; ?> pembelian</span>
                              <div class="detail-value">Rp. <?= number_format($year['uang'], 0, ',', '.'); ?></div>
                              <div class="detail-name">Pembelian Tahun Ini</div>
                           </div>
                        </div>
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
      buildPieChart();
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
            url: BASE_URL + "/laporan_pembelian/setSession",
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
            buildPieChart();
         });
      });

   function buildLineChart() {
      $.ajax({
         type: "GET",
         url: BASE_URL + "/laporan_pembelian/getPembelian",
         dataType: "json",
         success: function(res) {
            var pembelian_chart = document.getElementById("myChart").getContext('2d');
            myChart = new Chart(pembelian_chart, {
               type: 'line',
               data: {
                  labels: res.data.data_x,
                  datasets: [{
                     label: 'Pembelian',
                     data: res.data.data_y,
                     borderWidth: 5,
                     borderColor: '#6777ef',
                     backgroundColor: '',
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

   function buildPieChart() {
      $.ajax({
         type: "GET",
         url: BASE_URL + "/laporan_pembelian/getDetailPembelian",
         dataType: "json",
         success: function(res) {
            var randomColor = [];
            var dynamicColors = function() {
               var r = Math.floor(Math.random() * 255);
               var g = Math.floor(Math.random() * 255);
               var b = Math.floor(Math.random() * 255);
               return "rgb(" + r + "," + g + "," + b + ")";
            };
            for (var i in res.data.data_x) {
               randomColor.push(dynamicColors());
            }
            var ctx = document.getElementById("myChart4").getContext('2d');
            myChart4 = new Chart(ctx, {
               type: 'pie',
               data: {
                  datasets: [{
                     data: res.data.data_y,
                     backgroundColor: randomColor,
                     label: 'Pembelian Bahan Mentah'
                  }],
                  labels: res.data.data_x,
               },
               options: {
                  responsive: true,
                  legend: {
                     position: 'right',
                  },
               }
            });
         }
      });
   }
</script>
<?= $this->endSection(); ?>
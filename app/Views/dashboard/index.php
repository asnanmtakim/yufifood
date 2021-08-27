<?= $this->extend('template/template'); ?>

<?= $this->section('content'); ?>
<div class="main-content">
   <section class="section">
      <div class="section-header">
         <h1>Dashboard</h1>
         <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">Dashboard</div>
         </div>
      </div>
      <div class="section-body">
         <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
               <div class="card card-statistic-1">
                  <div class="card-icon bg-primary">
                     <i class="fas fa-fish"></i>
                  </div>
                  <div class="card-wrap">
                     <div class="card-header">
                        <h4>Jumlah Bahan Mentah</h4>
                     </div>
                     <div class="card-body">
                        <?= $total['mentah']; ?> item
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
               <div class="card card-statistic-1">
                  <div class="card-icon bg-danger">
                     <i class="fas fa-stroopwafel"></i>
                  </div>
                  <div class="card-wrap">
                     <div class="card-header">
                        <h4>Jumlah Bahan Jadi</h4>
                     </div>
                     <div class="card-body">
                        <?= $total['jadi']; ?> item
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
               <div class="card card-statistic-1">
                  <div class="card-icon bg-warning">
                     <i class="fas fa-shopping-cart"></i>
                  </div>
                  <div class="card-wrap">
                     <div class="card-header">
                        <h4>Total Pembelian</h4>
                     </div>
                     <div class="card-body">
                        <?= $total['beli']; ?>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
               <div class="card card-statistic-1">
                  <div class="card-icon bg-success">
                     <i class="fas fa-store-alt"></i>
                  </div>
                  <div class="card-wrap">
                     <div class="card-header">
                        <h4>Total Penjualan</h4>
                     </div>
                     <div class="card-body">
                        <?= $total['jual']; ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6">
               <div class="card">
                  <div class="card-header">
                     <h4>Pembelian Terakhir</h4>
                     <div class="card-header-action">
                        <a href="<?= base_url(); ?>/pembelian" class="btn btn-primary">Lihat semua <i class="fas fa-chevron-right"></i></a>
                     </div>
                  </div>
                  <div class="card-body p-0">
                     <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="tb-beli">
                           <tr>
                              <th>Tanggal Beli</th>
                              <th>Deskripsi</th>
                              <th>Total Pembelian</th>
                              <th>Aksi</th>
                           </tr>
                           <tbody>
                              <?php foreach ($pembelian as $beli) : ?>
                                 <tr>
                                    <td><?= date("d-m-Y", strtotime($beli['beli_tgl'])); ?></td>
                                    <td><?= $beli['beli_desc']; ?></td>
                                    <td><?= number_format($beli['beli_total'], 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                       <div class="btn-group">
                                          <button class="btn btn-sm btn-info item_detail" title="Detail" data-id="<?= $beli['beli_id']; ?>"><i class="fas fa-info"></i> Detail</button>
                                       </div>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-6">
               <div class="card">
                  <div class="card-header">
                     <h4>Penjualan Terakhir</h4>
                     <div class="card-header-action">
                        <a href="<?= base_url(); ?>/penjualan" class="btn btn-primary">Lihat semua <i class="fas fa-chevron-right"></i></a>
                     </div>
                  </div>
                  <div class="card-body p-0">
                     <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="tb-jual">
                           <tr>
                              <th>Tanggal Jual</th>
                              <th>Deskripsi</th>
                              <th>Total Penjualan</th>
                              <th>Aksi</th>
                           </tr>
                           <tbody>
                              <?php foreach ($penjualan as $jual) : ?>
                                 <tr>
                                    <td><?= date("d-m-Y", strtotime($jual['jual_tgl'])); ?></td>
                                    <td><?= $jual['jual_desc']; ?></td>
                                    <td><?= number_format($jual['jual_total'], 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                       <div class="btn-group">
                                          <button class="btn btn-sm btn-info item_detail" title="Detail" data-id="<?= $jual['jual_id']; ?>"><i class="fas fa-info"></i> Detail</button>
                                       </div>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<div class="modal fade text-left" id="modal-detail-beli" tabindex="-1" role="dialog" aria-labelledby="modal-detail-beli" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body pb-0">
            <div class="print-area">
               <div class="invoice p-3">
                  <div class="invoice-print">
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="invoice-title mb-0 mt-0">
                              <h4>Pembelian bahan mentah</h4>
                              <div class="invoice-number"></div>
                           </div>
                           <hr class="mb-2 mt-0">
                           <div class="row tgl-detail">
                              <div class="col-md-12">
                                 <address>
                                    <strong>Order Date:</strong><br>
                                    <p></p>
                                 </address>
                              </div>
                           </div>
                           <div class="row desc-detail">
                              <div class="col-md-12">
                                 <address>
                                    <strong>Deskripsi:</strong><br>
                                    <p></p>
                                 </address>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row mt-1">
                        <div class="col-md-12">
                           <div class="table-responsive">
                              <table class="table table-striped table-hover table-md" id="tb-detail">
                                 <thead>
                                    <tr>
                                       <th class="text-center">#</th>
                                       <th>Nama bahan</th>
                                       <th>Harga Satuan</th>
                                       <th class="text-center">Jumlah</th>
                                       <th class="text-right">Subtotal</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                 </tbody>
                              </table>
                           </div>
                           <div class="row mt-1">
                              <div class="col-lg-12 text-right">
                                 <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">Total</div>
                                    <div class="invoice-detail-value"></div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-outline-danger">Close</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade text-left" id="modal-detail-jual" tabindex="-1" role="dialog" aria-labelledby="modal-detail-jual" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body pb-0">
            <div class="print-area">
               <div class="invoice p-3">
                  <div class="invoice-print">
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="invoice-title mb-0 mt-0">
                              <h4>Penjualan bahan jadi</h4>
                              <div class="invoice-number"></div>
                           </div>
                           <hr class="mb-2 mt-0">
                           <div class="row tgl-detail">
                              <div class="col-md-12">
                                 <address>
                                    <strong>Order Date:</strong><br>
                                    <p></p>
                                 </address>
                              </div>
                           </div>
                           <div class="row desc-detail">
                              <div class="col-md-12">
                                 <address>
                                    <strong>Deskripsi:</strong><br>
                                    <p></p>
                                 </address>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row mt-1">
                        <div class="col-md-12">
                           <div class="table-responsive">
                              <table class="table table-striped table-hover table-md" id="tb-detail">
                                 <thead>
                                    <tr>
                                       <th class="text-center">#</th>
                                       <th>Nama bahan</th>
                                       <th>Harga Satuan</th>
                                       <th class="text-center">Jumlah</th>
                                       <th class="text-right">Subtotal</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                 </tbody>
                              </table>
                           </div>
                           <div class="row mt-1">
                              <div class="col-lg-12 text-right">
                                 <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">Total</div>
                                    <div class="invoice-detail-value"></div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-outline-danger">Close</button>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js_libraries'); ?>
<script>
   $(document).off("click", "table#tb-beli button.item_detail")
      .on("click", "table#tb-beli button.item_detail", function(e) {
         e.preventDefault();
         let id = $(this).attr("data-id");
         $.ajax({
            type: "POST",
            url: BASE_URL + "/pembelian/getOnepembelian",
            data: {
               id: id
            },
            dataType: "json",
            success: function(res) {
               if (res.status == 200) {
                  $("#modal-detail-beli").modal({
                     backdrop: false
                  });
                  $("#modal-detail-beli div.modal-header h4.modal-title").html("Detail Pembelian");
                  $("#modal-detail-beli div.invoice-number").html(`<p>` + res.data.beli_tgl + `</p>`);
                  $("#modal-detail-beli div.tgl-detail p").html(tglIndo(res.data.beli_tgl));
                  $("#modal-detail-beli div.desc-detail p").html(res.data.beli_desc);
                  $("#modal-detail-beli div.invoice-detail-value").html(formatRupiah(res.data.beli_total));
                  var no = 1;
                  $.each(res.detail, function(i, val) {
                     var t = '<tr>';
                     t += '<td class="text-center">' + no + '</td>';
                     t += '<td>' + val.mentah_nama + '</td>';
                     t += '<td>' + formatRupiah(val.detbeli_harga) + '</td>';
                     t += '<td class="text-center">' + val.detbeli_jumlah + '</td>';
                     t += '<td class="text-right">' + formatRupiah(val.detbeli_subtotal) + '</td>';
                     t += '</tr>';
                     $('#tb-detail tbody').append(t);
                     no++;
                  });
               } else {
                  swal({
                     title: "Error",
                     text: res.pesan,
                     icon: "error",
                     confirmButtonClass: "btn btn-danger",
                     buttonsStyling: false,
                  });
               }
            }
         });
      });
   $(document).off("click", "table#tb-jual button.item_detail")
      .on("click", "table#tb-jual button.item_detail", function(e) {
         e.preventDefault();
         let id = $(this).attr("data-id");
         $.ajax({
            type: "POST",
            url: BASE_URL + "/penjualan/getOnePenjualan",
            data: {
               id: id
            },
            dataType: "json",
            success: function(res) {
               if (res.status == 200) {
                  $("#modal-detail-jual").modal({
                     backdrop: false
                  });
                  $("#modal-detail-jual div.modal-header h4.modal-title").html("Detail Penjualan");
                  $("#modal-detail-jual div.invoice-number").html(`<p>` + res.data.jual_tgl + `</p>`);
                  $("#modal-detail-jual div.tgl-detail p").html(tglIndo(res.data.jual_tgl));
                  $("#modal-detail-jual div.desc-detail p").html(res.data.jual_desc);
                  $("#modal-detail-jual div.invoice-detail-value").html(formatRupiah(res.data.jual_total));
                  var no = 1;
                  $.each(res.detail, function(i, val) {
                     var t = '<tr>';
                     t += '<td class="text-center">' + no + '</td>';
                     t += '<td>' + val.jadi_nama + '</td>';
                     t += '<td>' + formatRupiah(val.detjual_harga) + '</td>';
                     t += '<td class="text-center">' + val.detjual_jumlah + '</td>';
                     t += '<td class="text-right">' + formatRupiah(val.detjual_subtotal) + '</td>';
                     t += '</tr>';
                     $('#tb-detail tbody').append(t);
                     no++;
                  });
               } else {
                  swal({
                     title: "Error",
                     text: res.pesan,
                     icon: "error",
                     confirmButtonClass: "btn btn-danger",
                     buttonsStyling: false,
                  });
               }
            }
         });
      });
</script>
<?= $this->endSection(); ?>
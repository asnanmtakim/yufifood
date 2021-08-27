<?= $this->extend('template/template'); ?>

<?= $this->section('css_libraries'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/modules/datatables/datatables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="main-content">
   <section class="section">
      <div class="section-header">
         <h1>Pembelian</h1>
         <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= base_url(); ?>">Dashboard</a></div>
            <div class="breadcrumb-item">Pembelian</div>
         </div>
      </div>
      <div class="section-body">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-header">
                     <div class="col-12 col-md-6 col-sm-6">
                        <h4>Data Pembelian</h4>
                     </div>
                     <div class="col-12 col-md-6 col-sm-6 text-right my-auto">
                        <button class="btn btn-primary item_tambah"><i class="fas fa-plus-circle mr-2"></i>Tambah Data</button>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped" id="tb-data">
                           <thead>
                              <tr>
                                 <th class="text-center">
                                    #
                                 </th>
                                 <th>Tanggal Beli</th>
                                 <th>Deskripsi</th>
                                 <th>Total Pembelian</th>
                                 <th>Aksi</th>
                              </tr>
                           </thead>
                           <tbody>
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

<div class="modal fade text-left" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail" aria-hidden="true">
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
            <button type="button" class="btn btn-warning btn-icon icon-left" id="print-detail"><i class="fas fa-print"></i> Print</button>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js_libraries'); ?>
<script src="<?= base_url(); ?>/assets/modules/datatables/datatables.min.js"></script>
<script src="<?= base_url(); ?>/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>/assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
<script src="<?= base_url(); ?>/assets/modules/jquery-ui/jquery-ui.min.js"></script>
<script src="<?= base_url(); ?>/assets/js/printThis.js"></script>

<!-- Page Specific JS File -->
<script>
   $(document).ready(function() {
      tableLoad();
   });

   function tableLoad() {
      $("#tb-data").dataTable().fnDestroy();
      $('#tb-data tbody').html(`<tr><td colspan="6" class="text-center"><i class="fas fa-circle-notch fa-spin"></i> Loading ...</td></tr>`);
      $.ajax({
         type: "GET",
         url: BASE_URL + "/pembelian/readPembelian",
         success: function(data) {
            data = JSON.parse(data);
            data = data.data;
            $('#tb-data tbody').html('');
            var no = 1;
            $.each(data, function(i, val) {
               var t = '<tr>';
               t += '<td class="text-center">' + no + '</td>';
               t += '<td>' + tglIndo(val.beli_tgl) + '</td>';
               t += '<td>' + val.beli_desc + '</td>';
               t += '<td>' + formatRupiah(val.beli_total) + '</td>';
               t += `<td class="text-center">
                     <div class="btn-group">
                           <button class="btn btn-sm btn-info item_detail" title="Detail" data-id="` + val.beli_id + `"><i class="fas fa-info"></i> Detail</button>
                           <button class="btn btn-sm btn-warning item_edit" title="Edit" data-id="` + val.beli_id + `"><i class="fas fa-edit"></i></button>
                           <button class="btn btn-sm btn-danger item_hapus" title="Hapus" data-id="` + val.beli_id + `" data-name="` + val.beli_desc + `"><i class="fas fa-trash"></i></button>
                        </div>
                     </td>`;
               t += '</tr>';
               $('#tb-data tbody').append(t);
               no++;
            });
            var tb_data = $("#tb-data").dataTable({
               paging: true,
               lengthChange: true,
               ordering: true,
               info: true,
               autoWidth: false,
               responsive: true,
               bInfo: true,
               bLengthChange: true,
               searching: true,
               processing: true,
               language: table_language(),
               iDisplayLength: 10,
               order: [
                  [0, 'asc']
               ],
               columnDefs: [{
                  orderable: false,
                  targets: 0
               }]
            });
         }
      });
   }

   $(document).off("click", "table#tb-data button.item_detail")
      .on("click", "table#tb-data button.item_detail", function(e) {
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
                  $("#modal-detail").modal({
                     backdrop: false
                  });
                  $("#modal-detail div.modal-header h4.modal-title").html("Detail Pembelian");
                  $("#modal-detail div.invoice-number").html(`<p>` + res.data.beli_tgl + `</p>`);
                  $("#modal-detail div.tgl-detail p").html(tglIndo(res.data.beli_tgl));
                  $("#modal-detail div.desc-detail p").html(res.data.beli_desc);
                  $("#modal-detail div.invoice-detail-value").html(formatRupiah(res.data.beli_total));
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
   $(document).off("click", "#modal-detail button#print-detail")
      .on("click", "#modal-detail button#print-detail", function(e) {
         $(".print-area").printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            printContainer: true,
            pageTitle: "Pembelian",
            removeInline: false,
            printDelay: 111,
            header: null,
            formValues: true
         });
      });

   $(document).off("click", "table#tb-data button.item_edit")
      .on("click", "table#tb-data button.item_edit", function(e) {
         e.preventDefault();
         let url = BASE_URL + "/pembelian/edit/" + $(this).attr("data-id");
         location.href = url;
      });

   $(document).off("click", "table#tb-data button.item_hapus")
      .on("click", "table#tb-data button.item_hapus", function(e) {
         e.preventDefault();
         var id = $(this).attr("data-id");
         var name = $(this).attr("data-name");
         swal({
            title: "Hapus Data ?",
            text: name,
            icon: "warning",
            buttons: true,
            dangerMode: true,
         }).then((result) => {
            if (result) {
               $.ajax({
                  type: "POST",
                  url: BASE_URL + "/pembelian/deletePembelian",
                  data: {
                     id: id
                  },
                  dataType: "json",
                  success: function(res) {
                     swal({
                        icon: (res.status == 200) ? "success" : "error",
                        title: res.pesan,
                     }).then(function(ress) {
                        tableLoad();
                     })
                  }
               });
            }
         })
      });

   $(document).off("click", "button.item_tambah")
      .on("click", "button.item_tambah", function(e) {
         e.preventDefault();
         let url = BASE_URL + "/pembelian/add";
         location.href = url;
      });

   $(document).off("hidden.bs.modal", "#modal-detail")
      .on("hidden.bs.modal", "#modal-detail", function(e) {
         $("#modal-detail div.modal-header h4.modal-title").html('');
         $("#modal-detail div.invoice-number").html('');
         $("#modal-detail div.tgl-detail p").html('');
         $("#modal-detail div.desc-detail p").html('');
         $("#modal-detail div.invoice-detail-value").html('');
         $('#tb-detail tbody').html('');
      })
</script>

<?= $this->endSection(); ?>
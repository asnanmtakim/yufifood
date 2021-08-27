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
         <h1>Bahan Jadi</h1>
         <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= base_url(); ?>">Dashboard</a></div>
            <div class="breadcrumb-item active">Bahan Jadi</div>
         </div>
      </div>
      <div class="section-body">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-header">
                     <div class="col-12 col-md-6 col-sm-6">
                        <h4>Data Bahan Jadi</h4>
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
                                 <th>Nama</th>
                                 <th>Satuan</th>
                                 <th>Harga Satuan</th>
                                 <th>Deskripsi</th>
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

<div class="modal fade text-left" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form-data" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body pb-0">
            <form id="form-data" class="form form-horizontal">
               <div class="form-body">
                  <input type="hidden" name="jadi_id" id="jadi_id">
                  <div class="form-row">
                     <div class="form-group col-md-12">
                        <label for="jadi_nama">Nama bahan jadi</label>
                        <input type="text" class="form-control" id="jadi_nama" name="jadi_nama" placeholder="Nama bahan jadi">
                     </div>
                  </div>
                  <div class="form-row">
                     <div class="form-group col-md-6">
                        <label for="jadi_satuan">Satuan bahan jadi</label>
                        <input type="text" class="form-control" id="jadi_satuan" name="jadi_satuan" placeholder="Satuan bahan jadi">
                     </div>
                     <div class="form-group col-md-6">
                        <label for="jadi_harga">Harga satuan (Rp)</label>
                        <input type="number" class="form-control" id="jadi_harga" name="jadi_harga" placeholder="Harga satuan (Rp)">
                     </div>
                  </div>
                  <div class="form-row">
                     <div class="form-group col-md-12">
                        <label for="jadi_desc">Deskripsi bahan jadi</label>
                        <textarea class="form-control" name="jadi_desc" id="jadi_desc" placeholder="Deskripsi bahan jadi"></textarea>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-outline-danger">Close</button>
            <button type="button" class="btn btn-outline-success" id="save-form">Simpan</button>
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
         url: BASE_URL + "/bahanjadi/readBahanjadi",
         success: function(data) {
            data = JSON.parse(data);
            data = data.data;
            $('#tb-data tbody').html('');
            var no = 1;
            $.each(data, function(i, val) {
               var t = '<tr>';
               t += '<td class="text-center">' + no + '</td>';
               t += '<td>' + val.jadi_nama + '</td>';
               t += '<td>' + val.jadi_satuan + '</td>';
               t += '<td>' + formatRupiah(val.jadi_harga) + '</td>';
               t += '<td>' + val.jadi_desc + '</td>';
               t += `<td class="text-center">
                        <div class="btn-group">
                           <button class="btn btn-sm btn-warning item_edit" title="Edit" data-id="` + val.id + `"><i class="fas fa-edit"></i></button>
                           <button class="btn btn-sm btn-danger item_hapus" title="Hapus" data-id="` + val.id + `" data-name="` + val.jadi_nama + `"><i class="fas fa-trash"></i></button>
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

   $(document).off("click", "table#tb-data button.item_edit")
      .on("click", "table#tb-data button.item_edit", function(e) {
         e.preventDefault();
         $.ajax({
            type: "POST",
            url: BASE_URL + "/bahanjadi/getOneBahanjadi",
            data: {
               id: $(this).attr("data-id")
            },
            dataType: "json",
            success: function(res) {
               if (res.status == 200) {
                  $("#modal-form").modal({
                     backdrop: false
                  });
                  $("#modal-form div.modal-header h4.modal-title").html("Ubah Data Bahan Jadi");
                  $("#modal-form form#form-data #jadi_id").val(res.data.id);
                  $("#modal-form form#form-data #jadi_nama").val(res.data.jadi_nama);
                  $("#modal-form form#form-data #jadi_satuan").val(res.data.jadi_satuan);
                  $("#modal-form form#form-data #jadi_harga").val(res.data.jadi_harga);
                  $("#modal-form form#form-data #jadi_desc").html(res.data.jadi_desc);
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
                  url: BASE_URL + "/bahanjadi/deleteBahanjadi",
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
         $("#modal-form").modal({
            backdrop: false
         });
         $("#modal-form div.modal-header h4.modal-title").html("Tambah Data Bahan Jadi");
         $("#modal-form form#form-data input").val(null);
         $("#modal-form form#form-data #jadi_desc").html(null);
      });
   $(document).off("click", "#modal-form button#save-form")
      .on("click", "#modal-form button#save-form", function(e) {
         simpan()
      });

   $(document).off("hidden.bs.modal", "#modal-form")
      .on("hidden.bs.modal", "#modal-form", function(e) {
         $("#modal-form div.modal-header h4.modal-title").html(null);
         $("#modal-form form#form-data input").val(null);
         $("#modal-form form#form-data #jadi_desc").html(null);
         $("#modal-form form#form-data select").val(null).trigger("change");
         $("form#form-data input").removeClass("is-invalid");
         $("form#form-data input").removeClass("is-valid");
         $("form#form-data #jadi_desc").removeClass("is-invalid");
         $("form#form-data #jadi_desc").removeClass("is-valid");
         $("form#form-data select").removeClass("is-invalid");
         $("form#form-data select").removeClass("is-valid");
      })

   function simpan() {
      var datas = new FormData($("form#form-data")[0]);
      $.ajax({
         type: "POST",
         url: BASE_URL + "/bahanjadi/saveBahanjadi",
         data: datas,
         dataType: "json",
         cache: false,
         contentType: false,
         processData: false,
         success: function(res) {
            if (res.status == 200) {
               swal({
                  title: "Sukses",
                  text: res.pesan,
                  icon: "success",
                  confirmButtonClass: "btn btn-info",
                  buttonsStyling: false,
               }).then(function(_res_) {
                  $("#modal-form").modal("hide");
                  tableLoad();
               });
            } else {
               if (res.status == 400) {
                  var frm = Object.keys(res.pesan);
                  var val = Object.values(res.pesan);
                  $('form#form-data .invalid-feedback').remove();
                  frm.forEach(function(el, ind) {
                     if (val[ind] != '') {
                        $('form#form-data #' + el).removeClass('is-invalid').addClass("is-invalid");
                        var app = '<div id="' + el + '-error" class="invalid-feedback" for="' + el + '">' + val[ind] + '</div>';
                        $('form#form-data #' + el).closest('.form-group').append(app);
                     } else {
                        $('form#form-data #' + el).removeClass('is-invalid').addClass("is-valid");
                     }
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
         }
      });
   }
</script>

<?= $this->endSection(); ?>

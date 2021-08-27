<?= $this->extend('template/template'); ?>

<?= $this->section('css_libraries'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/modules/bootstrap-daterangepicker/daterangepicker.css">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="main-content">
   <section class="section">
      <div class="section-header">
         <div class="section-header-back">
            <a href="<?= base_url(); ?>/penjualan" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
         </div>
         <h1>Tambah Penjualan</h1>
         <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= base_url(); ?>">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="<?= base_url(); ?>/penjualan">Penjualan</a></div>
            <div class="breadcrumb-item">Tambah Penjualan</div>
         </div>
      </div>
      <div class="section-body">
         <div class="row">
            <div class="col-12">
               <div class="card card-success">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-12">
                           <div class="form-group mb-0">
                              <label class="float-right">Cari bahan jadi</label>
                              <div class="input-group">
                                 <select class="form-control select2" id="select-jual">
                                    <option value="">Option 1</option>
                                    <option>Option 2</option>
                                    <option>Option 3</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-12">
                           <div class="table-responsive">
                              <table class="table table-hover table-md" id="tb-jual">
                                 <thead>
                                    <th>Nama bahan</th>
                                    <th>Harga Satuan</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-center">Aksi</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                 </tbody>
                                 <tfoot class="bg-secondary">
                                    <tr>
                                       <th colspan="2" class="text-center align-middle">Total Penjualan</th>
                                       <th class="text-center align-middle jumlah-jual"></th>
                                       <th class="text-right align-middle total-jual"></th>
                                       <th class="text-center">
                                          <button type="button" class="btn btn-sm btn-outline-danger" id="reset-jual">Reset</button>
                                       </th>
                                    </tr>
                                 </tfoot>
                              </table>
                           </div>
                        </div>
                     </div>
                     <hr>
                     <form id="form-jual">
                        <input type="hidden" name="jual_id">
                        <div class="row">
                           <div class="col-12 col-sm-5 col-md-4">
                              <div class="form-group">
                                 <label>Tanggal Jual</label>
                                 <input type="text" name="jual_tgl" id="jual_tgl" class="form-control datepicker" placeholder="Tangal Jual">
                              </div>
                           </div>
                           <div class="col-12 col-sm-7 col-md-8">
                              <div class="form-group">
                                 <label>Deskripsi</label>
                                 <input type="text" name="jual_desc" id="jual_desc" class="form-control" placeholder="Deskripsi Penjualan">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12 text-right">
                              <button type="button" class="btn btn-primary" id="save-jual">Simpan</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js_libraries'); ?>
<script src="<?= base_url(); ?>/assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- Page Specific JS File -->
<script>
   $(document).ready(function() {
      listJual();
      tableLoad();
   });

   function listJual() {
      $.ajax({
         type: "GET",
         url: BASE_URL + "/penjualan/getBahanJadi",
         success: function(res) {
            res = JSON.parse(res);
            data = res.data;
            $('#select-jual').html('');
            let opt = '<option value="" selected>Pilih bahan jadi</option>';
            $.each(data, function(i, val) {
               opt += '<option value="' + val.id + '">' + val.jadi_nama + '</option>';
            });
            $('#select-jual').html(opt);
         }
      });
   }

   function tableLoad() {
      $('#tb-jual tbody').html(`<tr><td colspan="6" class="text-center"><i class="fas fa-circle-notch fa-spin"></i> Loading ...</td></tr>`);
      $.ajax({
         type: "GET",
         url: BASE_URL + "/penjualan/getCartPenjualan",
         success: function(res) {
            res = JSON.parse(res);
            data = res.data;
            $('#tb-jual tbody').html('');
            $.each(data, function(i, val) {
               var t = '<tr>';
               t += '<td>' + val.name + '</td>';
               t += '<td>';
               t += '<form id="ubah-price" method="post">';
               t += '<input type="hidden" name="rowid" value="' + val.rowid + '">';
               t += 'Rp. <input type="number" style="width: 100px;" name="price" value="' + val.price + '">';
               t += '<input type="submit" style="display: none;">';
               t += '</form>';
               t += '</td>';
               t += '<td class="text-center">';
               t += '<form id="ubah-qty" method="post">';
               t += '<input type="hidden" name="rowid" value="' + val.rowid + '">';
               t += '<input type="number" style="width: 50px;" name="qty" value="' + val.qty + '"> ' + val.satuan + '';
               t += '<input type="submit" style="display: none;">';
               t += '</form>';
               t += '<td class="text-right">' + formatRupiah('' + val.subtotal) + '</td>';
               t += '</td>';
               t += `<td class="text-center">
                        <button class="btn btn-icon btn-sm btn-warning item_hapus" title="Hapus" data-id="` + val.rowid + `" data-name="` + val.name + `"><i class="fas fa-trash"></i></button>
                     </td>`;
               $('#tb-jual tbody').append(t);
            });
            $('#tb-jual tfoot .jumlah-jual').html((res.total ? res.total : 0) + ' item');
            $('#tb-jual tfoot .total-jual').html(formatRupiah('' + res.grand));
         }
      });
   }

   $(document).on('submit', 'form#ubah-qty', function() {
      var datas = new FormData($(this)[0]);
      $.ajax({
         type: "POST",
         url: BASE_URL + "/penjualan/updateQtyPenjualan",
         data: datas,
         dataType: "json",
         cache: false,
         contentType: false,
         processData: false,
         success: function(res) {
            if (res.status == 200) {
               tableLoad();
            }
         }
      });
      return false;
   });

   $(document).on('submit', 'form#ubah-price', function() {
      var datas = new FormData($(this)[0]);
      $.ajax({
         type: "POST",
         url: BASE_URL + "/penjualan/updatePricePenjualan",
         data: datas,
         dataType: "json",
         cache: false,
         contentType: false,
         processData: false,
         success: function(res) {
            if (res.status == 200) {
               tableLoad();
            }
         }
      });
      return false;
   });

   $(document).off("click", "table#tb-jual button.item_hapus")
      .on("click", "table#tb-jual button.item_hapus", function(e) {
         e.preventDefault();
         var id = $(this).attr("data-id");
         var name = $(this).attr("data-name");
         swal({
            title: "Hapus Data Penjualan ?",
            text: name,
            icon: "warning",
            buttons: true,
            dangerMode: true,
         }).then((result) => {
            if (result) {
               $.ajax({
                  type: "POST",
                  url: BASE_URL + "/penjualan/deleteCartPenjualan",
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

   $(document).off("click", "table#tb-jual button#reset-jual")
      .on("click", "table#tb-jual button#reset-jual", function(e) {
         e.preventDefault();
         swal({
            title: "Reset Semua Data Penjualan ?",
            text: "Reset",
            icon: "warning",
            buttons: true,
            dangerMode: true,
         }).then((result) => {
            if (result) {
               $.ajax({
                  type: "GET",
                  url: BASE_URL + "/penjualan/destroyCartPenjualan",
                  dataType: "json",
                  success: function(res) {
                     swal({
                        icon: "success",
                        title: res.pesan,
                     }).then(function(ress) {
                        tableLoad();
                     })
                  }
               });
            }
         })
      });

   $('#select-jual').on('change', function() {
      let value = $(this).val();
      $.ajax({
         type: "POST",
         url: BASE_URL + "/penjualan/addCartPenjualan",
         data: {
            id: value
         },
         dataType: "json",
         success: function(res) {
            listJual();
            tableLoad();
         }
      });
   });

   $(document).off("click", "button#save-jual")
      .on("click", "button#save-jual", function(e) {
         simpanJual()
      });

   function simpanJual() {
      var datas = new FormData($("form#form-jual")[0]);
      $.ajax({
         type: "POST",
         url: BASE_URL + "/penjualan/savePenjualan",
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
                  location.href = BASE_URL + "/penjualan"
               });
            } else {
               if (res.status == 400) {
                  var frm = Object.keys(res.pesan);
                  var val = Object.values(res.pesan);
                  $('form#form-jual .invalid-feedback').remove();
                  frm.forEach(function(el, ind) {
                     if (val[ind] != '') {
                        $('form#form-jual #' + el).removeClass('is-invalid').addClass("is-invalid");
                        var app = '<div id="' + el + '-error" class="invalid-feedback" for="' + el + '">' + val[ind] + '</div>';
                        $('form#form-jual #' + el).closest('.form-group').append(app);
                     } else {
                        $('form#form-jual #' + el).removeClass('is-invalid').addClass("is-valid");
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
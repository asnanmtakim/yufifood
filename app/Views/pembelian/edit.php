<?= $this->extend('template/template'); ?>

<?= $this->section('css_libraries'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/modules/bootstrap-daterangepicker/daterangepicker.css">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="main-content">
   <section class="section">
      <div class="section-header">
         <div class="section-header-back">
            <a href="<?= base_url(); ?>/pembelian" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
         </div>
         <h1>Edit Pembelian</h1>
         <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= base_url(); ?>">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="<?= base_url(); ?>/pembelian">Pembelian</a></div>
            <div class="breadcrumb-item">Edit Pembelian</div>
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
                              <label class="float-right">Cari bahan mentah</label>
                              <div class="input-group">
                                 <select class="form-control select2" id="select-beli">
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
                              <table class="table table-hover table-md" id="tb-beli">
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
                                       <th colspan="2" class="text-center align-middle">Total Pembelian</th>
                                       <th class="text-center align-middle jumlah-beli"></th>
                                       <th class="text-right align-middle total-beli"></th>
                                       <th class="text-center">
                                          <button type="button" class="btn btn-sm btn-outline-danger" id="reset-beli">Reset</button>
                                       </th>
                                    </tr>
                                 </tfoot>
                              </table>
                           </div>
                        </div>
                     </div>
                     <hr>
                     <form id="form-beli">
                        <input type="hidden" name="beli_id" value="<?= $beli['beli_id']; ?>">
                        <div class="row">
                           <div class="col-12 col-sm-5 col-md-4">
                              <div class="form-group">
                                 <label>Tanggal Beli</label>
                                 <input type="text" name="beli_tgl" id="beli_tgl" class="form-control datepicker" placeholder="Tangal Beli" value="<?= date("d-m-Y", strtotime($beli['beli_tgl'])); ?>">
                              </div>
                           </div>
                           <div class="col-12 col-sm-7 col-md-8">
                              <div class="form-group">
                                 <label>Deskripsi</label>
                                 <input type="text" name="beli_desc" id="beli_desc" class="form-control" placeholder="Deskripsi Pembelian" value="<?= $beli['beli_desc']; ?>">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12 text-right">
                              <button type="button" class="btn btn-primary" id="save-beli">Simpan</button>
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
      listBeli();
      tableLoad();
   });

   function listBeli() {
      $.ajax({
         type: "GET",
         url: BASE_URL + "/pembelian/getBahanMentah",
         success: function(res) {
            res = JSON.parse(res);
            data = res.data;
            $('#select-beli').html('');
            let opt = '<option value="" selected>Pilih bahan mentah</option>';
            $.each(data, function(i, val) {
               opt += '<option value="' + val.id + '">' + val.mentah_nama + '</option>';
            });
            $('#select-beli').html(opt);
         }
      });
   }

   function tableLoad() {
      $('#tb-beli tbody').html(`<tr><td colspan="6" class="text-center"><i class="fas fa-circle-notch fa-spin"></i> Loading ...</td></tr>`);
      $.ajax({
         type: "GET",
         url: BASE_URL + "/pembelian/getCartPembelian",
         success: function(res) {
            res = JSON.parse(res);
            data = res.data;
            $('#tb-beli tbody').html('');
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
               $('#tb-beli tbody').append(t);
            });
            $('#tb-beli tfoot .jumlah-beli').html((res.total ? res.total : 0) + ' item');
            $('#tb-beli tfoot .total-beli').html(formatRupiah('' + res.grand));
         }
      });
   }

   $(document).on('submit', 'form#ubah-qty', function() {
      var datas = new FormData($(this)[0]);
      $.ajax({
         type: "POST",
         url: BASE_URL + "/pembelian/updateQtyPembelian",
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
         url: BASE_URL + "/pembelian/updatePricePembelian",
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

   $(document).off("click", "table#tb-beli button.item_hapus")
      .on("click", "table#tb-beli button.item_hapus", function(e) {
         e.preventDefault();
         var id = $(this).attr("data-id");
         var name = $(this).attr("data-name");
         swal({
            title: "Hapus Data Pembelian ?",
            text: name,
            icon: "warning",
            buttons: true,
            dangerMode: true,
         }).then((result) => {
            if (result) {
               $.ajax({
                  type: "POST",
                  url: BASE_URL + "/pembelian/deleteCartPembelian",
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

   $(document).off("click", "table#tb-beli button#reset-beli")
      .on("click", "table#tb-beli button#reset-beli", function(e) {
         e.preventDefault();
         swal({
            title: "Reset Data Pembelian ?",
            text: "Mengembalikan ke semula",
            icon: "warning",
            buttons: true,
            dangerMode: true,
         }).then((result) => {
            if (result) {
               location.reload();
               // $.ajax({
               //    type: "GET",
               //    url: BASE_URL + "/pembelian/destroyCartPembelian",
               //    dataType: "json",
               //    success: function(res) {
               //       swal({
               //          icon: "success",
               //          title: res.pesan,
               //       }).then(function(ress) {
               //          tableLoad();
               //       })
               //    }
               // });
            }
         })
      });

   $('#select-beli').on('change', function() {
      let value = $(this).val();
      $.ajax({
         type: "POST",
         url: BASE_URL + "/pembelian/addCartPembelian",
         data: {
            id: value
         },
         dataType: "json",
         success: function(res) {
            listBeli();
            tableLoad();
         }
      });
   });

   $(document).off("click", "button#save-beli")
      .on("click", "button#save-beli", function(e) {
         simpanBeli()
      });

   function simpanBeli() {
      var datas = new FormData($("form#form-beli")[0]);
      $.ajax({
         type: "POST",
         url: BASE_URL + "/pembelian/savePembelian",
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
                  location.href = BASE_URL + "/pembelian"
               });
            } else {
               if (res.status == 400) {
                  var frm = Object.keys(res.pesan);
                  var val = Object.values(res.pesan);
                  $('form#form-beli .invalid-feedback').remove();
                  frm.forEach(function(el, ind) {
                     if (val[ind] != '') {
                        $('form#form-beli #' + el).removeClass('is-invalid').addClass("is-invalid");
                        var app = '<div id="' + el + '-error" class="invalid-feedback" for="' + el + '">' + val[ind] + '</div>';
                        $('form#form-beli #' + el).closest('.form-group').append(app);
                     } else {
                        $('form#form-beli #' + el).removeClass('is-invalid').addClass("is-valid");
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
function table_language() {
   var _language = {
      sLengthMenu: "_MENU_",
      sSearch: "",
      sInfo: "_START_ to _END_ from _TOTAL_",
      infoEmpty: "",
      infoFiltered: "",
      sZeroRecords: "<b>Data Tidak Ditemukan</b>",
      processing: '<span class="fa fa-refresh" aria-hidden="true"></span> Sedang memuat data',
      decimal: ",",
      thousands: ".",
      sSearchPlaceholder: "Cari ...",
      paginate: {
         previous: '<span class="fa fa-chevron-left" aria-hidden="true"></span>',
         next: '<span class="fa fa-chevron-right" aria-hidden="true"></span>',
      },
   };
   return _language;
}

function tglIndo(tgl) {
   var days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
   var months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

   var tanggal = new Date(tgl).getDate();
   var jam = new Date(tgl).getHours();
   var menit = new Date(tgl).getMinutes();
   var detik = new Date(tgl).getSeconds();
   var xhari = new Date(tgl).getDay();
   var xbulan = new Date(tgl).getMonth();
   var xtahun = new Date(tgl).getYear();

   var hari = days[xhari];
   var bulan = months[xbulan];
   var tahun = xtahun < 1000 ? xtahun + 1900 : xtahun;

   if (jam < 10) {
      jam = "0" + jam;
   }
   if (menit < 10) {
      menit = "0" + menit;
   }
   if (detik < 10) {
      detik = "0" + detik;
   }

   return hari + ", " + tanggal + " " + bulan + " " + tahun;
}

function tglIndoJam(tgl) {
   var days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
   var months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

   var tanggal = new Date(tgl).getDate();
   var jam = new Date(tgl).getHours();
   var menit = new Date(tgl).getMinutes();
   var detik = new Date(tgl).getSeconds();
   var xhari = new Date(tgl).getDay();
   var xbulan = new Date(tgl).getMonth();
   var xtahun = new Date(tgl).getYear();

   var hari = days[xhari];
   var bulan = months[xbulan];
   var tahun = xtahun < 1000 ? xtahun + 1900 : xtahun;

   if (jam < 10) {
      jam = "0" + jam;
   }
   if (menit < 10) {
      menit = "0" + menit;
   }
   if (detik < 10) {
      detik = "0" + detik;
   }

   return hari + ", " + tanggal + " " + bulan + " " + tahun + " " + jam + ":" + menit + ":" + detik;
}

function formatRupiah(angka) {
   var number_string = angka.replace(/[^,\d]/g, "").toString(),
      split = number_string.split(","),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

   // tambahkan titik jika yang di input sudah menjadi angka ribuan
   if (ribuan) {
      separator = sisa ? "." : "";
      rupiah += separator + ribuan.join(".");
   }

   rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
   return "Rp. " + rupiah;
}

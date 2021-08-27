<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
   <title><?= $title; ?> &mdash; Yufifood</title>
   <link rel="shortcut icon" href="<?= base_url(); ?>/assets/img/logo.png">

   <!-- General CSS Files -->
   <link rel="stylesheet" href="<?= base_url(); ?>/assets/modules/bootstrap/css/bootstrap.min.css">
   <link rel="stylesheet" href="<?= base_url(); ?>/assets/modules/fontawesome/css/all.min.css">

   <!-- CSS Libraries -->
   <link rel="stylesheet" href="<?= base_url(); ?>/assets/modules/izitoast/css/iziToast.min.css">
   <link rel="stylesheet" href="<?= base_url(); ?>/assets/modules/select2/dist/css/select2.min.css">
   <?= $this->renderSection('css_libraries'); ?>

   <!-- Template CSS -->
   <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/style.css">
   <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/components.css">

   <!-- Start GA -->
   <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
   <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
         dataLayer.push(arguments);
      }
      gtag('js', new Date());

      gtag('config', 'UA-94034622-3');
   </script>
   <!-- /END GA -->
</head>

<body>
   <div id="app">
      <div class="main-wrapper main-wrapper-1">

         <?= $this->include('template/navbar'); ?>

         <?= $this->include('template/sidebar'); ?>

         <!-- Main Content -->
         <?= $this->renderSection('content'); ?>

         <footer class="main-footer">
            <div class="footer-left">
            </div>
            <div class="footer-right">
               Copyright &copy; 2021 <div class="bullet"></div> Design By <a href="https://instagram.com/asnanmtakim/">Asnanmtakim</a>
            </div>
         </footer>
      </div>
   </div>
   <!-- General JS Scripts -->
   <script src="<?= base_url(); ?>/assets/modules/jquery.min.js"></script>
   <script src="<?= base_url(); ?>/assets/modules/popper.js"></script>
   <script src="<?= base_url(); ?>/assets/modules/tooltip.js"></script>
   <script src="<?= base_url(); ?>/assets/modules/bootstrap/js/bootstrap.min.js"></script>
   <script src="<?= base_url(); ?>/assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
   <script src="<?= base_url(); ?>/assets/modules/moment.min.js"></script>
   <script src="<?= base_url(); ?>/assets/js/stisla.js"></script>

   <!-- JS Libraies -->
   <script>
      var BASE_URL = "<?= base_url() ?>";
   </script>
   <script src="<?= base_url(); ?>/assets/modules/sweetalert/sweetalert.min.js"></script>
   <script src="<?= base_url(); ?>/assets/modules/izitoast/js/iziToast.min.js"></script>
   <script src="<?= base_url(); ?>/assets/modules/select2/dist/js/select2.full.min.js"></script>
   <?= $this->renderSection('js_libraries'); ?>

   <!-- Template JS File -->
   <script src="<?= base_url(); ?>/assets/js/scripts.js"></script>
   <script src="<?= base_url(); ?>/assets/js/custom.js"></script>
</body>

</html>
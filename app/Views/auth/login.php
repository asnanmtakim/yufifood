<?= $this->extend('auth/template'); ?>

<?= $this->section('content'); ?>
<div class="card card-primary">
   <div class="card-header">
      <h4><?= lang('Auth.loginTitle') ?></h4>
   </div>

   <div class="card-body">
      <?= view('Myth\Auth\Views\_message_block') ?>
      <form action="<?= route_to('login') ?>" method="post">
         <?= csrf_field() ?>

         <?php if ($config->validFields === ['email']) : ?>
            <div class="form-group">
               <label for="login"><?= lang('Auth.email') ?></label>
               <input id="login" type="email" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" placeholder="<?= lang('Auth.email') ?>">
               <div class="invalid-feedback">
                  <?= session('errors.login') ?>
               </div>
            </div>
         <?php else : ?>
            <div class="form-group">
               <label for="login"><?= lang('Auth.emailOrUsername') ?></label>
               <input id="login" type="text" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" placeholder="<?= lang('Auth.emailOrUsername') ?>">
               <div class="invalid-feedback">
                  <?= session('errors.login') ?>
               </div>
            </div>
         <?php endif; ?>

         <div class="form-group">
            <div class="d-block">
               <label for="password"><?= lang('Auth.password') ?></label>
               <div class="float-right">
                  <?php if ($config->activeResetter) : ?>
                     <a href="<?= route_to('forgot') ?>" class="text-small">
                        <?= lang('Auth.forgotYourPassword') ?>
                     </a>
                  <?php endif; ?>
               </div>
            </div>
            <input type="password" name="password" class="form-control  <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>">
            <div class="invalid-feedback">
               <?= session('errors.password') ?>
            </div>
         </div>

         <?php if ($config->allowRemembering) : ?>
            <div class="form-group">
               <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="remember" class="custom-control-input" <?php if (old('remember')) : ?> checked <?php endif ?> id="remember-me">
                  <label class="custom-control-label" for="remember-me"><?= lang('Auth.rememberMe') ?></label>
               </div>
            </div>
         <?php endif; ?>

         <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block">
               <?= lang('Auth.loginAction') ?>
            </button>
         </div>
      </form>
   </div>
</div>

<?php if ($config->allowRegistration) : ?>
   <div class="mt-5 text-muted text-center">
      Don't have an account? <a href="<?= route_to('register') ?>"><?= lang('Auth.needAnAccount') ?></a>
   </div>
<?php endif; ?>
<?= $this->endSection(); ?>
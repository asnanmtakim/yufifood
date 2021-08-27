<div class="main-sidebar sidebar-style-2">
   <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
         <a href="<?= base_url(); ?>">YUFIFOOD</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
         <a href="<?= base_url(); ?>">YF</a>
      </div>
      <ul class="sidebar-menu">
         <li class="menu-header">Dashboard</li>
         <li class="<?= $page == 'dashboard' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url(); ?>/dashboard">
               <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
            </a>
         </li>
         <li class="dropdown <?= $page == 'bahanmentah' || $page == 'bahanjadi' ? 'active' : ''; ?>">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
               <i class="fas fa-list-ol"></i> <span>Bahan-bahan</span>
            </a>
            <ul class="dropdown-menu">
               <li class="<?= $page == 'bahanmentah' ? 'active' : ''; ?>">
                  <a class="nav-link" href="<?= base_url(); ?>/bahanmentah">
                     <i class="fas fa-dot-circle"></i><span>Bahan Mentah</span>
                  </a>
               </li>
               <li class="<?= $page == 'bahanjadi' ? 'active' : ''; ?>">
                  <a class="nav-link" href="<?= base_url(); ?>/bahanjadi">
                     <i class="fas fa-dot-circle"></i><span>Bahan Jadi</span>
                  </a>
               </li>
            </ul>
         </li>
         <li class="<?= $page == 'pembelian' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url(); ?>/pembelian">
               <i class="fas fa-shopping-cart"></i> <span>Pembelian</span>
            </a>
         </li>
         <li class="<?= $page == 'penjualan' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url(); ?>/penjualan">
               <i class="fas fa-store-alt"></i> <span>Penjualan</span>
            </a>
         </li>
         <li class="menu-header">Laporan</li>
         <li class="dropdown <?= $page == 'laporan_pembelian' || $page == 'laporan_penjualan' || $page == 'laporan_keuangan' ? 'active' : ''; ?>">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
               <i class="fas fa-file-alt"></i> <span>Laporan-laporan</span>
            </a>
            <ul class="dropdown-menu">
               <li class="<?= $page == 'laporan_pembelian' ? 'active' : ''; ?>">
                  <a class="nav-link" href="<?= base_url(); ?>/laporan_pembelian">
                     <i class="fas fa-dot-circle"></i><span>Laporan Pembelian</span>
                  </a>
               </li>
               <li class="<?= $page == 'laporan_penjualan' ? 'active' : ''; ?>">
                  <a class="nav-link" href="<?= base_url(); ?>/laporan_penjualan">
                     <i class="fas fa-dot-circle"></i><span>Laporan Penjualan</span>
                  </a>
               </li>
               <li class="<?= $page == 'laporan_keuangan' ? 'active' : ''; ?>">
                  <a class="nav-link" href="<?= base_url(); ?>/laporan_keuangan">
                     <i class="fas fa-dot-circle"></i><span>Laporan Keuangan</span>
                  </a>
               </li>
            </ul>
         </li>
      </ul>
   </aside>
</div>

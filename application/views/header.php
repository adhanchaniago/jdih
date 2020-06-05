<?php $jenis_produk_hukums = $this->Jenis_produk_hukum_model->get_jenis_produk_hukum(); ?>
<header id="header">
    <!-- NAV -->
    <div id="nav">
      <!-- Top Nav -->
      <div id="nav-top">
        <div class="container">
          <!-- social -->
          <ul class="nav-social">
            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
          </ul>
          <!-- /social -->

          <!-- logo -->
          <div class="nav-logo">
            <a href="<?=base_url()?>" class="logo">JDIH</a>
          </div>
          <!-- /logo -->

          <!-- search & aside toggle -->
          <div class="nav-btns">
            <button class="aside-btn"><i class="fa fa-bars"></i></button>
            <button class="search-btn"><i class="fa fa-search"></i></button>
            <div id="nav-search">
              <form>
                <input class="input" name="search" placeholder="Enter your search...">
              </form>
              <button class="nav-close search-close">
                <span></span>
              </button>
            </div>
          </div>
          <!-- /search & aside toggle -->
        </div>
      </div>
      <!-- /Top Nav -->

      <!-- Main Nav -->
      <div id="nav-bottom">
        <div class="container">
          <!-- nav -->
          <ul class="nav-menu">
            <li><a href="<?=base_url()?>">Beranda</a></li>
            <li class="has-dropdown">
              <a href="<?=base_url('profil/list_profil')?>">Profil</a>
              <div class="dropdown">
                <div class="dropdown-body">
                  <ul class="dropdown-list">
                    <li><a href="<?=base_url()?>profil/detail/<?=decode(1)?>/visi-dan-misi">Visi dan Misi</a></li>
                    <li><a href="<?=base_url()?>profil/detail/<?=decode(2)?>/struktur-organisasi">Struktur Organisasi</a></li>
                  </ul>
                </div>
              </div>
            </li>
            <li class="has-dropdown">
              <a href="<?=base_url('produk_hukum/list_produk_hukum')?>">Produk Hukum</a>
              <div class="dropdown">
                <div class="dropdown-body">
                  <ul class="dropdown-list">
                    <?php 
                    foreach($jenis_produk_hukums as $jenis_produk_hukum) 
                    {
                    ?>
                    <li><a href="<?=base_url()?>produk_hukum/list_produk_hukum/<?=decode($jenis_produk_hukum->id_jenis_produk_hukum)?>/<?=url_title($jenis_produk_hukum->nama_jenis_produk_hukum)?>"><?=$jenis_produk_hukum->nama_jenis_produk_hukum?></a></li>
                  <?php 
                    } 
                  ?>
                  </ul>
                </div>
              </div>
            </li>
            <li><a href="<?=base_url('berita/list_berita')?>">Berita</a></li>
            <li><a href="<?=base_url('kontak')?>">Kontak</a></li>
            <li><a href="<?=base_url('site_map')?>">Site Map</a></li>
          </ul>
          <!-- /nav -->
        </div>
      </div>
      <!-- /Main Nav -->

      <!-- Aside Nav -->
      <div id="nav-aside">
        <ul class="nav-aside-menu">
          <li><a href="<?=base_url()?>">Beranda</a></li>
          <li class="has-dropdown"><a>Profil</a>
            <ul class="dropdown">
              <li><a href="<?=base_url()?>/profil/detail/<?=decode(1)?>/visi-dan-misi">Visi dan Misi</a></li>
              <li><a href="<?=base_url()?>/profil/detail/<?=decode(2)?>/struktur-organisasi">Struktur Organisasi</a></li>
            </ul>
          </li>
          <li class="has-dropdown"><a>Produk Hukum</a>
            <ul class="dropdown">
              <?php
              foreach ($jenis_produk_hukums as $jenis_produk_hukum) 
              {
              ?>
              <li><a href="<?=base_url()?>produk_hukum/list_produk_hukum/<?=decode($jenis_produk_hukum->id_jenis_produk_hukum)?>/<?=url_title($jenis_produk_hukum->nama_jenis_produk_hukum)?>"><?=$jenis_produk_hukum->nama_jenis_produk_hukum?></a></li>
            <?php 
              } 
            ?>
            </ul>
          </li>
          <li><a href="<?=base_url('berita/list_berita')?>">Berita</a></li>
          <li><a href="<?=base_url('kontak')?>">Kontak</a></li>
          <li><a href="<?=base_url('site_map')?>">Site Map</a></li>
        </ul>
        <button class="nav-close nav-aside-close"><span></span></button>
      </div>
      <!-- /Aside Nav -->
    </div>
    <!-- /NAV -->
  </header>
  
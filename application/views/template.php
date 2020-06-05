<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

  <title><?=isset($title) ? $title : ''?></title>

  <!-- Google font -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7CMuli:400,700" rel="stylesheet">

  <!-- Bootstrap -->
  <link type="text/css" rel="stylesheet" href="<?=base_url()?>callie/css/bootstrap.min.css" />

  <!-- Font Awesome Icon -->
  <link rel="stylesheet" href="<?=base_url()?>callie/css/font-awesome.min.css">

  <!-- Custom stlylesheet -->
  <link type="text/css" rel="stylesheet" href="<?=base_url()?>callie/css/style.css" />

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
  <!-- HEADER -->
  <?php $this->load->view('header'); ?>
  <!-- /HEADER -->

  <!-- SECTION -->
  <?php $this->load->view('section_banner'); ?>
  <!-- /SECTION -->

  <!-- SECTION -->
  <?php $this->load->view('section_produk_hukum'); ?>
  <!-- /SECTION -->

  <!-- SECTION -->
  <div class="section">
    <!-- container -->
    <div class="container">
      <!-- row -->
      <div class="row">
        <div class="col-md-8">
          <!-- row -->
          <div class="row">
            <div class="col-md-12">
              <div class="section-title">
                <h2 class="title">Telusuri Produk Hukum Kabupaten Bolaang Mongondow</h2>
              </div>
            </div>

            <!-- post -->
            <div class="col-md-12">
              <div class="newsletter-widget">
                <?=form_open()?>
                <?=form_dropdown('id_jenis_produk_hukun', $opt_jenis_produk_hukum,'', 'id="id_jenis_produk_hukum" class="form-control input"')?>
                <?=form_input(array('name' => 'nomor', 'id' => 'nomor', 'class' => 'input form-control', 'placeholder' => 'Nomor'))?>
                <?=form_input(array('name' => 'tahun', 'id' => 'tahun', 'class' => 'input form-control', 'placeholder' => 'Tahun'))?>
                <?=form_textarea(array('name' => 'judul', 'id' => 'judul', 'class' => 'input form-control', 'placeholder' => 'Judul/Tentang'))?>
                <?=form_submit('mysubmit', 'Cari', 'class="primary-button"')?>
                <?=form_close()?>
              </div>
            </div>
            <!-- /post -->

            <div class="clearfix visible-md visible-lg"></div>
                  <!-- row -->
      <div class="row">
        <br />
        <div class="col-md-12">
            <div class="section-title">
                <h2 class="title">Update Produk Hukum Terbaru</h2>
              </div>
            </div>

            <div class="col-md-12">
              <!-- post -->
              <div class="post post-row">
                <a class="post-img" href="blog-post.html"><img src="<?=base_url()?>callie/img/post-13.jpg" alt=""></a>
                <div class="post-body">
                  <div class="post-category">
                    <a href="category.html">Travel</a>
                    <a href="category.html">Lifestyle</a>
                  </div>
                  <h3 class="post-title"><a href="blog-post.html">Mel ut impetus suscipit tincidunt. Cum id ullum laboramus persequeris.</a></h3>
                  <ul class="post-meta">
                    <li><a href="author.html">John Doe</a></li>
                    <li>20 April 2018</li>
                  </ul>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam...</p>
                </div>
              </div>
              <!-- /post -->

              <!-- post -->
              <div class="post post-row">
                <a class="post-img" href="blog-post.html"><img src="<?=base_url()?>callie/img/post-1.jpg" alt=""></a>
                <div class="post-body">
                  <div class="post-category">
                    <a href="category.html">Travel</a>
                  </div>
                  <h3 class="post-title"><a href="blog-post.html">Sed ut perspiciatis, unde omnis iste natus error sit</a></h3>
                  <ul class="post-meta">
                    <li><a href="author.html">John Doe</a></li>
                    <li>20 April 2018</li>
                  </ul>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam...</p>
                </div>
              </div>
              <!-- /post -->

              <!-- post -->
              <div class="post post-row">
                <a class="post-img" href="blog-post.html"><img src="<?=base_url()?>callie/img/post-5.jpg" alt=""></a>
                <div class="post-body">
                  <div class="post-category">
                    <a href="category.html">Lifestyle</a>
                  </div>
                  <h3 class="post-title"><a href="blog-post.html">Postea senserit id eos, vivendo periculis ei qui</a></h3>
                  <ul class="post-meta">
                    <li><a href="author.html">John Doe</a></li>
                    <li>20 April 2018</li>
                  </ul>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam...</p>
                </div>
              </div>
              <!-- /post -->

              <!-- post -->
              <div class="post post-row">
                <a class="post-img" href="blog-post.html"><img src="<?=base_url()?>callie/img/post-6.jpg" alt=""></a>
                <div class="post-body">
                  <div class="post-category">
                    <a href="category.html">Fashion</a>
                    <a href="category.html">Lifestyle</a>
                  </div>
                  <h3 class="post-title"><a href="blog-post.html">Sed ut perspiciatis, unde omnis iste natus error sit</a></h3>
                  <ul class="post-meta">
                    <li><a href="author.html">John Doe</a></li>
                    <li>20 April 2018</li>
                  </ul>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam...</p>
                </div>
              </div>
              <!-- /post -->

              <!-- post -->
              <div class="post post-row">
                <a class="post-img" href="blog-post.html"><img src="<?=base_url()?>callie/img/post-7.jpg" alt=""></a>
                <div class="post-body">
                  <div class="post-category">
                    <a href="category.html">Health</a>
                    <a href="category.html">Lifestyle</a>
                  </div>
                  <h3 class="post-title"><a href="blog-post.html">Ne bonorum praesent cum, labitur persequeris definitionem quo cu?</a></h3>
                  <ul class="post-meta">
                    <li><a href="author.html">John Doe</a></li>
                    <li>20 April 2018</li>
                  </ul>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam...</p>
                </div>
              </div>
              <!-- /post -->

              <div class="section-row loadmore text-center">
                <a href="#" class="primary-button">Load More</a>
              </div>
            </div>
        
      </div>
      <!-- /row -->
          </div>
          <!-- /row -->
        </div>

        <div class="col-md-4">
         
          <!-- post widget -->
          <div class="aside-widget">
            <div class="section-title">
              <h2 class="title">Berita Terkini</h2>
            </div>
            <!-- post -->
            <div class="post post-widget">
              <a class="post-img" href="blog-post.html"><img src="<?=base_url()?>callie/img/widget-3.jpg" alt=""></a>
              <div class="post-body">
                <div class="post-category">
                  <a href="category.html">Lifestyle</a>
                </div>
                <h3 class="post-title"><a href="blog-post.html">Ne bonorum praesent cum, labitur persequeris definitionem quo cu?</a></h3>
              </div>
            </div>
            <!-- /post -->

            <!-- post -->
            <div class="post post-widget">
              <a class="post-img" href="blog-post.html"><img src="<?=base_url()?>callie/img/widget-2.jpg" alt=""></a>
              <div class="post-body">
                <div class="post-category">
                  <a href="category.html">Technology</a>
                  <a href="category.html">Lifestyle</a>
                </div>
                <h3 class="post-title"><a href="blog-post.html">Mel ut impetus suscipit tincidunt. Cum id ullum laboramus persequeris.</a></h3>
              </div>
            </div>
            <!-- /post -->

            <!-- post -->
            <div class="post post-widget">
              <a class="post-img" href="blog-post.html"><img src="<?=base_url()?>callie/img/widget-4.jpg" alt=""></a>
              <div class="post-body">
                <div class="post-category">
                  <a href="category.html">Health</a>
                </div>
                <h3 class="post-title"><a href="blog-post.html">Postea senserit id eos, vivendo periculis ei qui</a></h3>
              </div>
            </div>
            <!-- /post -->

            <!-- post -->
            <div class="post post-widget">
              <a class="post-img" href="blog-post.html"><img src="<?=base_url()?>callie/img/widget-5.jpg" alt=""></a>
              <div class="post-body">
                <div class="post-category">
                  <a href="category.html">Health</a>
                  <a href="category.html">Lifestyle</a>
                </div>
                <h3 class="post-title"><a href="blog-post.html">Sed ut perspiciatis, unde omnis iste natus error sit</a></h3>
              </div>
            </div>
            <!-- /post -->
          </div>
          <!-- /post widget -->
        </div>
      </div>
      <!-- /row -->
    </div>
    <!-- /container -->
  </div>
  <!-- /SECTION -->

  <!-- SECTION -->
  <div class="section">
    <!-- container -->
    <div class="container">

    </div>
    <!-- /container -->
  </div>
  <!-- /SECTION -->

  <!-- FOOTER -->
  <?php $this->load->view('footer'); ?>
  <!-- /FOOTER -->

  <!-- jQuery Plugins -->
  <script src="<?=base_url()?>callie/js/jquery.min.js"></script>
  <script src="<?=base_url()?>callie/js/bootstrap.min.js"></script>
  <script src="<?=base_url()?>callie/js/jquery.stellar.min.js"></script>
  <script src="<?=base_url()?>callie/js/main.js"></script>

</body>

</html>

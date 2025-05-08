
<!DOCTYPE html>
<html lang="en">
<head>
	<title>MS-Aplikasi</title>

	<!-- meta -->
  <?php $this->load->view('_layout/_meta'); ?>
  
  <!-- css --> 
  <?php $this->load->view('_layout/_css'); ?>
  
  <!-- jstop --> 
  <?php $this->load->view('_layout/_jstop'); ?>

</head>
<body class="navbar-top">

  <!-- header -->
  <?php $this->load->view('_layout/_header'); ?>
  
	<div class="page-content">

      <!-- sidebar -->
      <?php $this->load->view('_layout/_sidebar'); ?>

      <!-- Main content -->
      <div class="content-wrapper">
      
      <!-- content -->
      <?php $this->load->view('_layout/_contents'); ?> 
        
      <!-- footer -->
      <?php $this->load->view('_layout/_footer'); ?>

		</div>
	</div>
</body>
</html>

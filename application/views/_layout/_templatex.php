<!DOCTYPE html>
<html lang="en">

<head>
  <title>JTE - Retail Apps</title>

  <!-- meta -->
  <?php echo @$_meta; ?>

  <!-- css -->
  <?php echo @$_css; ?>

  <!-- jstop -->
  <?php echo @$_jstop; ?>

</head>
<?php 
  if($this->session->flashdata('message')){
    $flash = $this->session->flashdata('message');
    // sleep(3);
    // $this->session->set_flashdata('message', '');
  }else{
    $flash = "";
  }
?>
<body class="navbar-top" onload="<?= $flash; ?>" onbeforeunload="myFunction()">
  <div class="se-pre-con2"></div>
  <!-- header -->
  <?php echo @$_header; ?>

  <div class="page-content">

    <!-- sidebar -->
    <?php echo @$_sidebar; ?>

    <!-- Main content -->
    <div class="content-wrapper">

      <!-- content -->
      <?php echo @$_content; ?>

      <!-- footer -->
      <?php echo @$_footer; ?>

    </div>
  </div>

  <!-- jstop -->
  <?php echo @$_js; ?>

</body>

</html>
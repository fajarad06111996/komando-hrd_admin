<!DOCTYPE html>
<html>
<head>
    <title id="x"><?= apps_name_title(); ?></title>
    <!-- <script>
        const d = new Date();
        let time = d.getTime();
        document.getElementById('x').innerText = time;
    </script> -->
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
        sleep(3);
        $this->session->set_flashdata('message', '');
    }else{
        $flash = "";
    }
?>
<body class="hold-transition sidebar-mini layout-fixed" onload="<?= $flash; ?>" onbeforeunload="myFunction()">
    <div class="se-pre-con2"></div>
    <div class="wrapper">
        <!-- header -->
        <?php //echo @$_header; ?>
        <!-- navbar -->
        <?php echo @$_navbar; ?>
        <!-- sidebar -->
        <?php echo @$_sidebar; ?>
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- content -->
            <?php echo @$_content; ?>
        </div>
        <!-- footer -->
        <?php echo @$_footer; ?>

        <!-- rightbar -->
        <?php echo @$_rightbar; ?>
    </div>
    <!-- jstop -->
    <?php echo @$_js; ?>
</body>
</html>
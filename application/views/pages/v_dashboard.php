<!-- Main content -->
<?php //print_r('<pre>'); ?>
<?php //print_r($autoRevProduct); ?>
<?php //($getAutoRevProduct); ?>
<?php 
    // $this->load->model('M_office', 'mOffice');
	$offId = $this->secure->dec($this->session->userdata('JToffice_id')); 
	// $office = $this->mOffice->searchMOffice($offId);
?>
<div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-md-2 mt-2">
            <img src="<?= $this->session->JTphoto == "default.png" || $this->session->JTphoto == ""?base_url('assets/images/RIDER.png'):$this->session->JTphoto; ?>" alt="" width="150px" class="rounded">
        </div>
        <div class="col-md-10" style="padding-top:50px;">
            <div class="text-white" style="font-size: 1.5rem;font-weight: 400;">
                <b><span class="text-white text-uppercase"><?= $nowIs; ?></span>, <?= $this->session->JTusername; ?><br><?= $companyData['company_name']; ?></b></br>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            <div class="small-box bg-light onLoader">
                <div class="inner">
                    <h3 class="count0"></h3>
                    <p>Total Karyawan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer bPersonal">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="small-box bg-light onLoader">
                <div class="inner">
                    <h3 class="count1"></h3>
                    <p>Total Departemen</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer bPersonal">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- <div class="col-md-3">
            <div class="small-box bg-light onLoader">
                <div class="inner">
                    <h3 class="count2"></h3>
                    <p>Mangkir Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer bPersonal">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-light onLoader">
                <div class="inner">
                    <h3 class="count3"></h3>
                    <p>Pulang Awal Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer bPersonal">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div> -->
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <div class="row justify-content-center">
        <!-- Left col -->
        <div class="col-md-12 mb-3 onLoader">
            <div id="attendance"></div>
        </div>
        <div class="col-md-6 mb-3 onLoader">
            <div id="department"></div>
        </div>
        <div class="col-md-6 mb-3 onLoader">
            <div id="education"></div>
        </div>
    </div>
    <!-- /.row (main row) -->
</div><!-- /.container-fluid -->
<!-- /.content -->
<script src="<?= base_url(); ?>assets/highcharts/highcharts.js?v=1.1"></script>
<script src="<?= base_url(); ?>assets/highcharts/highcharts-3d.js?v=1.0"></script>
<script src="<?= base_url(); ?>assets/highcharts/exporting.js?v=1.0"></script>
<script src="<?= base_url(); ?>assets/highcharts/accessibility.js"></script>
<script src="<?= base_url(); ?>assets/layout1/js/home/v_home.js?v=0.6" params='<?= $params; ?>'></script>
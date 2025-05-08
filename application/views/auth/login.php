<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= brand_name();?> - Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= apps_name(); ?>" />
    <meta name="keywords" content="<?= apps_name(); ?>">
    <meta name="author" content="">
    <!-- <meta http-equiv="refresh" content="30"> -->
    <meta property="article:section" content="<?= apps_name(); ?>">
    <meta property="article:tag" content="<?= apps_name(); ?>">
    <meta property="article:tag" content="<?= apps_name(); ?>">
    <meta property="article:tag" content="<?= apps_name(); ?>">
    <meta property="article:tag" content="<?= apps_name(); ?>">
    <meta property="article:tag" content="<?= apps_name(); ?>">
    <meta property="article:tag" content="<?= apps_name(); ?>">
    <!--open graph-->
    <meta property="og:site_name" content="<?= apps_name(); ?>.">
    <meta property="og:description" content="<?= apps_name(); ?>">
    <meta property="og:url" content="">
    <meta property="og:image" content="<?= logo_apps_login(); ?>">
    <meta property="og:image" content="<?= logo_apps_login(); ?>">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">
    <meta property="og:title" content="">
    <meta property="og:site_name" content="<?= apps_name(); ?>">
    <!--open graph end-->
    <meta http-equiv="Cache-Control" content="no-store">
    <meta name="google" content="sitelinkssearchbox">
    <meta name="email" content="">
    <meta name="audience" content="all">
    <meta name="robots" content="all">
    <meta name="rating" content="general">
    <meta name="language" content="ID">
    <meta name="geo.country" content="id">
    <meta name="distribution" content="global">
    <!--twitter view-->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:description" content="<?= apps_name(); ?>">
    <meta name="twitter:title" content="<?= apps_name(); ?>">
    <meta name="twitter:site" content="@jte_official">
    <meta name="twitter:image" content="<?= logo_apps_login(); ?>">
    <meta name="twitter:image" content="<?= logo_apps_login(); ?>">
    <meta name="twitter:creator" content="@jte_official">
    <!--twitter view end-->
	<!--<link rel="icon" type="image/png" href="<?=site_url(); ?>assets/images/logo_komando_emas-02.png" sizes="32x32" />-->
	<!--<link rel="icon" type="image/png" href="<?=site_url(); ?>assets/images/logo_komando_emas-02.png" sizes="16x16" />-->
	<link rel="icon" type="image/png" href="<?= logo_apps_login(); ?>" sizes="32x32" />
	<link rel="icon" type="image/png" href="<?= logo_apps_login(); ?>" sizes="16x16" />

    <!-- Icomoon -->
    <link href="<?= base_url(); ?>assets/global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url(); ?>plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?=base_url(); ?>assets/global_assets/js/plugins/sweetalert2/sweetalert2.min.css?v=0.1">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url(); ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>dist/css/adminlte.min.css?v=0.1">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/global_assets/css/jquery_ui/jquery-ui.css">
    <link href="<?= base_url(); ?>assets/layout1/css/components.css?v=0.1" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/layout1/css/colors.min.css?v=0.1" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/global_assets/css/extras/animates.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/layout1/css/style.css?v=0.1" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/layout1/css/timeline/timeline.css?v=0.1" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/layout1/css/custom.css?v=0.1" rel="stylesheet" type="text/css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <script src="<?= base_url(); ?>assets/global_assets/js/cryptojs/cryptojs-aes.min.js"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/cryptojs/cryptojs-aes-format.js"></script>
</head>
<?php
    if($this->session->flashdata('notif')){
        $flash = $this->session->flashdata('notif');
        // sleep(3);
        // $this->session->set_flashdata('notif', '');
    }else{
        $flash = "";
    }
?>

<body class="hold-transition login-page bg-slate-800" onload="<?= $flash; ?>" onbeforeunload="myFunctionx()">
    <div class="se-pre-con2"></div>
    <div id="mDialog" data-backdrop="static" data-keyboard="false" class="modal fade">
        <div class="modal-dialog modal-dialog-centered" style="width:250px;">
            <div class="">
                <div class="theme_xbox theme_xbox_with_text">
                    <div class="pace_progress" data-progress-text="60%" data-progress="60"></div>
                    <div class="pace_activity"></div> <span class="text-center">Loading....</span>
                </div>
            </div>
        </div>
    </div>

    <!--Modal change status-->
    <div id="xForgot" data-backdrop="static" data-keyboard="false" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pt-2 pb-2 bg-primary">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="" id="forgotForm" class="form-horizontal f-validasi" method="post">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-10">
                            <input type="hidden" name="tId" value="" />
                            <input type="hidden" name="tClCode" value="" />
                            <input type="hidden" name="tClId" value="" />
                            <input type="text" name="email" class="form-control" placeholder="Enter Your Email"/>
                            <p class="text-center mt-3 mb-0 tContent"></p>
                        </div>
                        <div class="col-md-12">
                            <p class="text-center text-info"><b class="tName"></b></p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
                            <button type="submit" class="btn btn-danger btn-ladda btn-ladda-spinner tBtn" data-spinner-color="#333" data-style="slide-down">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--End Modal change status-->

    <!-- untuk tampil view form login -->
    <div class="login-box">
        <div class="login-logo">
            <a href="<?= base_url(); ?>" class="text-white"><b>Admin </b><?= brand_name();?></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body" style="padding-top: 0px;">
                <div class="text-center mb-3">
                    <img src="<?= logo_apps_login(); ?>" width="120" height="120" class="" alt="img login">
                    <h5 class="mb-0">Sign in to start your session</h5>
                    <span class="d-block text-muted">Enter your credentials below</span>
                </div>
                <form id="formLogin" method="post" action="<?=base_url('auth/loginApps'); ?>" class="form-horizontal form-validasi">
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <?= csrf_input();?>
                            <input type="text" name="tUname" class="form-control" placeholder="Email" data-msg="username wajib di isi." required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="icon-user"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input id="login-password" name="tPass" type="password" class="form-control" placeholder="Password" data-msg="Password wajib di isi." required>
                            <div class="input-group-append" onclick="myFunction()">
                                <div class="input-group-text">
                                    <span id="icon" class="icon-eye"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block bLogin">Sign In</button>
                        </div>
                    </div>
                </form>

                <p class="mb-1">
                    <a href="javascript:void();" class="bForgot">I forgot my password</a>
                </p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <!-- <script src="<?= base_url(); ?>plugins/jquery/jquery.min.js"></script> -->
    <script src="<?= base_url(); ?>assets/global_assets/js/main/jquery.js"></script>
    <script src="<?= base_url(); ?>plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url(); ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(); ?>dist/js/adminlte.min.js"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/plugins/sweetalert2/sweetalert2.min.js?v=0.1"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/plugins/forms/selects/select2.min.js"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/plugins/forms/styling/switch.min.js"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/plugins/forms/validation/validate.min.js"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/demo_pages/form_validation.js?v=0.1"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/bootstrap-filestyle.min.js"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/plugins/buttons/spin.min.js"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/plugins/buttons/ladda.min.js"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/demo_pages/animations_css3.js"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/plugins/notifications/jgrowl.min.js"></script>
    <script src="<?= base_url(); ?>assets/global_assets/js/plugins/notifications/noty.min.js"></script>
    <script src="<?= base_url(); ?>assets/layout1/js/notifikasi.js?v=0.1"></script>
    <script src="<?= base_url(); ?>assets/layout1/js/custom.js?v=0.1"></script>
    <script src="<?= base_url(); ?>assets/layout1/js/auth/login.js?v=0.1" params='<?= $params; ?>'></script>
</body>
</html>

<?php 
    $page2	= !empty($page)?$page:'NOT FOUND';
    $page3	= !empty($judul)?$judul:'N404';
    // var_dump($cont);
    // var_dump($breadcrumb == false);
    // die;
?>
<?php if(isset($breadcrumb) && $breadcrumb == false){ ?>
<?php }else{ ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><?= $cont == 1?(empty($get2)?ucfirst($this->uri->segment(1)):$get2['menu_alias']):(!empty($get['title2'])?$get['title2']:$page2); ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <?php if($cont == 1){ ?>
                        <a href="#" class="breadcrumb-item">
                            <i class="icon-home2 mr-2"></i> <span class="breadcrumb-item active"><?= empty($get2)?ucfirst($this->uri->segment(1)):$get2['menu_alias']; ?></span>
                        </a>
                    <?php }elseif($cont == 2){ ?>
                        <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?= !empty($get['title2'])?$get['title2']:$page2; ?></a>
                        <span class="breadcrumb-item active"><?= !empty($get['title3'])?$get['title3']:$page3; ?></span>
                    <?php }else{ ?>
                        <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?= !empty($get['title2'])?$get['title2']:$page2; ?></a>
                        <span class="breadcrumb-item active"><?= !empty($get['title3'])?$get['title3']:$page3; ?></span>
                    <?php } ?>
                    <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard v1</li> -->
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <?=!empty($menuadd)?$menuadd:''; ?>
    </div><!-- /.container-fluid -->
</div>
<?php } ?>
<!-- Page header -->
<?php 
$page2	= !empty($page)?$page:'NOT FOUND';
$page3	= !empty($judul)?$judul:'N404';
//var_dump($cont);
?>
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<!-- <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?//= empty($get2['menu_title'])?'EMPTY':$get2['menu_title']; ?></a> -->
				<!-- <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?//= !empty($get['title1'])?$get['title1']:'HOME'; ?></a> -->
				<!-- <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?//= $get['title1']; ?> <?//= $get['title2']; ?> <?//= $get['title3']; ?></a> -->
				<?php if($cont == 1){ ?>
					<a href="#" class="breadcrumb-item">
						<i class="icon-home2 mr-2"></i> <span class="breadcrumb-item active"><?= $get2['menu_alias']; ?></span>
					</a>
				<?php }elseif($cont == 2){ ?>
					<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?= !empty($get['title2'])?$get['title2']:$page2; ?></a>
					<span class="breadcrumb-item active"><?= !empty($get['title3'])?$get['title3']:$page3; ?></span>
				<?php }else{ ?>
					<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?= !empty($get['title2'])?$get['title2']:$page2; ?></a>
					<span class="breadcrumb-item active"><?= !empty($get['title3'])?$get['title3']:$page3; ?></span>
				<?php } ?>
			</div>
		</div>
		<?=!empty($menuadd)?$menuadd:''; ?>
	</div>
</div>
<!-- /page header -->
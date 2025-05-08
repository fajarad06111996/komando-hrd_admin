<div id="mDialogfirst" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-dialog-centered text-center" style="width:250px;">
		<div class="">
			<div class="theme_squares theme_squares_with_text">
				<div class="pace_progress" data-progress-text="60%" data-progress="60"></div>
				<div class="pace_activity"></div> <span class="text-center ml-2">Loading....</span>
			</div>
		</div>
	</div>
</div>
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
<div id="mDialogCorner" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-dialog-centered" style="width:250px;">
		<div class="">
			<div class="theme_corners theme_corners_with_text">
				<div class="pace_progress" data-progress-text="60%" data-progress="60"></div>
				<div class="pace_activity"></div> <span class="text-center">Loading....</span>
			</div>
		</div>
	</div>
</div>
<div id="fDialog" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-dialog-centered" style="width:250px;">
		<div class="pace-demo">
			<div class="theme_xbox theme_xbox_with_text">
				<div class="pace_progress" data-progress-text="60%" data-progress="60"></div>
				<div class="pace_activity"></div> <span class="text-center">Loading....</span>
			</div>
		</div>
	</div>
</div>
<div id="deleteModal" data-backdrop="static" data-keyboard="false" class="modal fade ">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-danger">
				<h3 class="modal-title" id="exampleModalLabel">Alert !!!</h3>
			</div>
			<div class="modal-body text-justify">
        		<h6 class="font-weight-semibold text-center"><span class="infoDelete"></span>, Yakin Mau Dihapus ?</h6>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-default">Tidak</button>
				<button type="button" id="btnDelete" class="btn btn-danger btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Ya, Hapus Data Ini</button>
			</div>
		</div>
	</div>
</div>
<div id="deleteModalmini" data-backdrop="static" data-keyboard="false" class="modal fade ">
	<div class="modal-dialog modal-xs">
		<div class="modal-content">
			<div class="modal-header pt-1 pb-1 bg-danger">
				<h3 class="modal-title" id="exampleModalLabel">Alert !!!</h3>
			</div>
			<div class="modal-body text-justify p-1">
        		<h6 class="font-weight-semibold text-center"><span class="infoDelete"></span>, Yakin Mau Dihapus ?</h6>
			</div>
			<div class="modal-footer p-1">
				<button type="button" data-dismiss="modal" class="btn btn-default">Tidak</button>
				<button type="button" class="btn btn-danger btn-ladda btn-ladda-spinner cDelete" data-spinner-color="#333" data-style="slide-down">Ya, Hapus Data Ini</button>
			</div>
		</div>
	</div>
</div>
<div id="fDialogMini" data-backdrop="static" data-keyboard="false" class="modal fade ">
	<div class="modal-dialog modal-xs">
		<div class="modal-content">
			<div class="modal-header pt-1 pb-1 bg-primary">
				<h3 class="modal-title" id="exampleModalLabel">Alert !!!</h3>
			</div>
			<div class="modal-body text-justify p-1">
        		<p class="font-weight-semibold text-center"><span class="infoDialog"></span></p>
			</div>
			<div class="card-footer p-1">
				<div class="d-flex justify-content-between align-items-center ">
					<button type="button" data-dismiss="modal" class="btn btn-default">No</button>
					<button type="button" id="bYes" class="btn btn-primary  btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Yes</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!--Modal change status-->
<div id="xStatement" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="stateForm" class="form-horizontal f-validasi" method="post">
                <div class="row">
                    <div class="col-md-12 added">
						<?= csrf_input(); ?>
                        <input type="hidden" name="tId" value="" />
                        <input type="hidden" name="tClCode" value="" />
                        <input type="hidden" name="tClId" value="" />
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
<!--Modal choose feature-->
<div id="xFeature" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="row justify-content-center">
					<!-- <div class="col-md-6 d-flex justify-content-center">
						<button class="btn btn-danger btn-ladda btn-ladda-spinner wCity" style="width:100%" data-spinner-color="#333" data-style="slide-down">City Courier</button>
					</div> -->
					<div class="col-md-6 d-flex justify-content-center">
						<button class="btn btn-danger btn-ladda btn-ladda-spinner iCity" style="width:100%" data-spinner-color="#333" data-style="slide-down">Domestics</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--End Modal choose feature-->
<!--Modal change status-->
<div id="xCompany" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<?php //var_dump($origin_office); ?>
			<form action="" id="companyChangeForm" class="form-horizontal" method="post">
				<div class="modal-body">
					<div class="form-group row mb-1">
						<?= csrf_input(); ?>
						<label class="col-form-label col-sm-4 text-dark">SELECT COMPANY<b class="text-danger">*</b></label>
						<div class="col-md-6">
							<!-- <select name="tUname" data-placeholder="Select a Level" class="form-control form-control-sm" required> -->
							<select name="get_company" data-placeholder="Select a Compnay" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc  required>
								<option value=""></option>
								<?php foreach ($companyList as $z) { ?>
								<option value="<?= $this->secure->enc($z->idx); ?>" ><?= $z->company_name; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			    <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
                        <button type="submit" class="btn btn-info btn-ladda btn-ladda-spinner tBtn" data-spinner-color="#333" data-style="slide-down">changes Company</button>
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>
<!--End Modal change status-->
<!--Modal change counter-->
<div id="xCounter" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<?php //var_dump($origin_office); ?>
			<form action="" id="counterForm" class="form-horizontal" method="post">
				<div class="modal-body">
					<div class="form-group row mb-1">
						<?= csrf_input(); ?>
						<label class="col-form-label col-sm-4 text-dark text-right">SELECT COUNTER <b class="text-danger">*</b></label>
						<div class="col-md-6">
							<!-- <select name="tUname" data-placeholder="Select a Level" class="form-control form-control-sm" required> -->
							<select name="get_counter" data-placeholder="Select a Counter" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc  required>
								<option value=""></option>
								<?php //foreach ($counter as $c) { ?>
								<option value="<?//= $this->secure->enc($c->idx); ?>" ><?//= $c->counter_name; ?></option>
								<?php //} ?>
							</select>
						</div>
					</div>
				</div>
			    <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
                        <button type="submit" class="btn btn-info btn-ladda btn-ladda-spinner tBtn" data-spinner-color="#333" data-style="slide-down">changes Counter</button>
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>
<!--End Modal change counter-->
<!--Modal change status-->
<div id="xPicture" class="modal fade">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<center>
				<img id="xData" src="" alt="" width="90%">
			</center>
			<!-- <form action="" id="stateForm" class="form-horizontal f-validasi" method="post">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="tId" value="" />
                        <input type="hidden" name="tClCode" value="" />
                        <input type="hidden" name="tClId" value="" />
                        <input type="hidden" name="csrf" value="<?//= $this->session->csrf_token; ?>" />
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
			</form> -->
		</div>
	</div>
</div>
<!--End Modal change status-->
<!-- The Modal Desktop-->
<div id="myImage" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content" style="background: transparent;background-color: transparent;border: none;box-shadow: none;">
			<div class="modal-header pb-2 pt-0 pr-0 pl-0 m-0" style="border: none;">
				<h4 class="modal-title"></h4>
				<button type="button" class="close text-white" data-dismiss="modal" data-popup="tooltip" title="Close" data-placement="right">&times;</button>
			</div>
			<div class="modal-body p-0 m-0">
				<center>
					<img id="img01" src="" alt="" width="100%">
				</center>
				<div class="caption" id="caption"></div>
			</div>
		</div>
	</div>
</div>
<!-- The Modal Desktop End -->
<div id="publishModal" data-backdrop="static" data-keyboard="false" class="modal fade ">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-danger">
				<h3 class="modal-title" id="exampleModalLabel">Alert !!!</h3>
			</div>
			<div class="modal-body text-justify">
        <h6 class="font-weight-semibold text-center infoPublish"></h6>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-default bDismiss">No</button>
				<button type="button" id="btnPublish" class="btn btn-danger btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Publish</button>
			</div>
		</div>
	</div>
</div>
<!-- modal upload indocator -->
<div id="mUpload" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-dialog-centered modal-lg" style="width: 100%;background-color: #37474f00;">
		<div class="bg-upload" style="padding: 5px 50px 5px 50px;width: 100%;display: flex;">
			<div class="pace-demo" style="width: 100%;margin: 5px;background-color: #37474f00;">
				<div class="theme_xbox theme_xbox_with_text">
					<div class="progress mb-1">
						<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h5 class="text-center text-white">Proccessing Upload...</h5>
					<span class="text-center cText"></span>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- modal upload indocator end -->
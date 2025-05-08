<!--Modal detail user-->
<div class="modal fade" id="mDetail" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg modal-dialog-popin" role="document">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title">Detail User</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="block block-themed block-transparent mb-0">
				<div class="block-content font-size-sm">
					<!-- Team Member -->
					<div class="block">
						<div class="block-content block-content-full text-center bg-image" style="background-image: url('<?= base_url(); ?>assets/media/photos/photo12.jpg');">
							<img id="my_image" class="img-avatar img-avatar96 img-avatar-thumb" src="<?= base_url(); ?>assets/media/avatars/profile/default.png" alt="">
						</div>
						<div class="block-content font-size-sm">
							<div class="row">
								<div class="col-md-6">
									<table class="table">
										<tr>
											<td class="text-right"><strong>Company Name</strong></td>
											<td class="text-left oName"></td>
										</tr>
										<tr>
											<td class="text-right"><strong>Driver Name</strong></td>
											<td class="text-left cName"></td>
										</tr>
										<tr>
											<td class="text-right"><strong>Driver Type</strong></td>
											<td class="text-left cType"></td>
										</tr>
										<tr>
											<td class="text-right"><strong>Vehicle No</strong></td>
											<td class="text-left vno"></td>
										</tr>
										<tr>
											<td class="text-right"><strong>Email</strong></td>
											<td class="text-left email"></td>
										</tr>
										<tr>
											<td class="text-right"><strong>Mobile Phone</strong></td>
											<td class="text-left mPhone"></td>
										</tr>
										<tr>
											<td class="text-right"><strong>Telephone</strong></td>
											<td class="text-left tPhone"></td>
										</tr>
										<tr>
											<td class="text-right"><strong>Tax Number</strong></td>
											<td class="text-left tax"></td>
										</tr>
									</table>
								</div>
								<div class="col-md-6">
									<table class="table">
										<tr>
											<td class="text-right"><strong>Address</strong></td>
											<td class="text-left address"></td>
										</tr>
										<tr>
											<td class="text-right"><strong>Country</strong></td>
											<td class="text-left country"></td>
										</tr>
										<tr>
											<td class="text-right"><strong>Province</strong></td>
											<td class="text-left province"></td>
										</tr>
										<tr>
											<td class="text-right"><strong>City</strong></td>
											<td class="text-left city"></td>
										</tr>
										<tr>
											<td class="text-right"><strong>Postal Code</strong></td>
											<td class="text-left postal-code"></td>
										</tr>
										<tr>
											<td class="text-right"><strong>State Code</strong></td>
											<td class="text-left state-code"></td>
										</tr>
										<tr>
											<td class="text-right"><strong>Attention</strong></td>
											<td class="text-left attention"></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- END Team Member -->
				</div>
			</div>
		</div>
	</div>
</div>
<!--End Modal detail user-->

<div class="panel panel-blue">
	<div class="panel-heading">Master Company
    <?= $write; ?>
  </div>
	<div class="alert alert-success" style="display: none;"></div>
	<div class="panel_body">
		<div class="table-responsive">
			<table id="example1" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
				<tr>
					<th>#</th>
					<th>Company Code</th>
					<th>Company Name</th>
					<th>Address</th>
					<th>Email</th>
					<th>Status</th>
					<th>Aksi</th>
				</tr>
				</thead>
				<tbody>
				
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="insert_form"></div>

<!-- load file js -->
<script src="<?= base_url(); ?>assets/layout1/js/master/v_company.js?v=0.1" params='<?= $params; ?>'></script>
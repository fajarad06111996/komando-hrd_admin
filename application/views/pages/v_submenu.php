<!--Modal Responsive-->
<div id="mForm" tabindex="-1" role="dialog" aria-labelledby="modal-responsive-label" aria-hidden="true" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myForm" class="form-horizontal form-validasi" method="post">
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Sub Menu</label>
						<div class="col-md-9">
							<select id="tSub" name="tSub" class="form-control" required>
								<option value="">Pilih --</option>
								<?php foreach ($tm as $e) { ?>
									<option value="<?php echo $e->id_menu ?>"><?php echo $e->menu; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Nama Sub Menu</label>
						<div class="col-md-9">
							<input type="hidden" name="tId" />
							<input id="tNama" name="tNama" type="text" placeholder="Nama Menu" class="form-control" required/>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Link Sub Menu</label>
						<div class="col-md-9">
							<input id="tLink" name="tLink" type="text" placeholder="Link Sub Menu" class="form-control" />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Status Sub Menu</label>
						<div class="col-md-9">
							<select id="tStatus" name="tStatus" class="form-control" required>
								<option value="">Pilih --</option>
								<option value="1">Aktif</option>
								<option value="0">Non Aktif</option>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
					<button type="submit" id="btnSave" class="btn btn-primary">Save changes</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!--Modal Confirm Delete-->
<div id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="exampleModalLabel">Peringatan !!!</h3>
			</div>
			<div class="modal-body text-justify">
				<h4 style="text-align: center;">HAPUS DATA INI?</h4>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-default">Tidak</button>
				<button type="button" id="btnDelete" class="btn btn-primary">Ya, Hapus Data Ini</button>
			</div>
		</div>
	</div>
</div>
<!--End Modal Confirm Delete-->

<div class="panel panel-green">
	<div class="panel-heading">List Data Sub Menu <a href="#" id="btnAdd" class="pull-right text-white small"><i class="fa fa-plus"></i> New Sub Menu</a></div>
	<div class="alert alert-success" style="display: none;"></div>
	<div class="panel-body">
		<div class="table-responsive">
			<table id="example1" class="table table-striped table-bordered table-hover table-sm3">
				<thead>
					<tr>
						<th style="text-align: center;width: 20px;">#</th>
						<th>Main Menu</th>
						<th>Sub Menu</th>
						<th>Link Sub Menu</th>
						<th>Status</th>
						<th style="width:15.8%;text-align: center;">Aksi</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>
	</div>
</div>
<!--loading bootstrap js-->
<script type="text/javascript">
	$(document).ready(function() {

		//Add New
		$('#btnAdd').click(function() {
			$('#mForm').modal('show');
			$('#mForm').find('.modal-title').text('Tambah Data Sub Menu');
			$('#myForm').attr('action', '<?php echo base_url() ?>submenu/addSubmenu');
			$("#myForm").data('validator').resetForm();
			$('#myForm')[0].reset();
		});


		$('#myForm').submit(function(e) {
			e.preventDefault();
			var url = $('#myForm').attr('action');
			var data = $('#myForm').serialize();
			$.ajax({
				type: 'ajax',
				method: 'post',
				url: url,
				data: data,
				async: false,
				dataType: 'json',
				success: function(response) {
					if (response.error) {

					} else {
						$('#mForm').modal('hide');
						if (response.type == 'add') {
							var type = 'added'
						} else if (response.type == 'update') {
							var type = "updated"
						}
						$('.alert-success').html('Data Sub Menu ' + type + ' successfully').fadeIn().delay(1000).fadeOut('slow');
						dataTable.ajax.reload();
					}
				},
				error: function() {
					alert('Could not add data');
				}
			});
		});

		//Edit Modal Form
		//$(document).on("click", ".item-edi", function(e) {
		$('#example1').on('click', '.item-edit', function() {
			var id = $(this).attr('data');
			$('#mForm').modal('show');

			$('#mForm').find('.modal-title').text('Edit Data Sub Menu');
			//$('.text-warning').modal('show');
			$('#myForm').attr('action', '<?php echo base_url() ?>submenu/updateSubmenu');
			$.ajax({
				type: 'ajax',
				method: 'get',
				url: '<?php echo base_url() ?>submenu/editSubmenu',
				data: {
					id: id
				},
				async: false,
				dataType: 'json',
				success: function(data) {

					$('select[name=tBahasa]').val(data.bahasa);
					$('select[name=tSub]').val(data.id_menu);
					$('select[name=tStatus]').val(data.status);
					$('input[name=tNama]').val(data.submenu);
					$('input[name=tLink]').val(data.link_submenu);
					$('input[name=tId]').val(data.id_submenu);
					$('input[name=tLinkbid]').val(data.link_bind);
					$('input[name=tLinkbig]').val(data.link_bing);
				},
				error: function() {
					alert('Could not Edit Data');
				}
			});
		});


		//delete modal - 
		$('#example1').on('click', '.item-delete', function() {
			var id = $(this).attr('data');
			$('#deleteModal').modal('show');
			//prevent previous handler - unbind()
			$('#btnDelete').unbind().click(function() {
				$.ajax({
					type: 'ajax',
					method: 'get',
					async: false,
					url: '<?php echo base_url() ?>submenu/deleteSubmenu',
					data: {
						id: id
					},
					dataType: 'json',
					success: function(response) {
						if (response.success) {
							$('#deleteModal').modal('hide');
							$('.alert-success').html('Data Sub Menu successfully').fadeIn().delay(1000).fadeOut('slow');
							dataTable.ajax.reload();
						} else {
							alert('Error');
						}
					},
					error: function() {
						alert('Error deleting');
					}
				});
			});
		});

		var dataTable = $('#example1').DataTable({
			"processing": true,
			"serverSide": true,
			"order": [],
			"ajax": {
				"url": "<?php echo base_url() ?>submenu/showAllSubmenu",
				"type": "POST"
			},
			"columnDefs": [{
				"targets": [0],
				"orderable": false,
			}, ],

		});

	});
</script>
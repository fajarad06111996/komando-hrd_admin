<style>
	.top-top {
		top: 4px !important;
	}
</style>
<!--Modal Responsive-->
<div id="mForm" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myForm" class="form-horizontal form-validasi" method="post">
				<div class="modal-body">
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">Menu Modul</label>
						<div class="col-md-9">
							<input name="tModulName" type="text" placeholder="Menu Modul" class="form-control form-control-sm text-uppercase" required/>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">Menu Alias</label>
						<div class="col-md-9">
							<input name="tModulAlias" type="text" placeholder="Menu Alias" class="form-control form-control-sm text-uppercase" required/>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">Media Icon</label>
						<div class="col-md-9">
							<!-- <select name="tUname" data-placeholder="Select a Level" class="form-control form-control-sm" required> -->
							<select name="tIcon" data-placeholder="Select a Icon" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc required>
								<option value=""></option>
								<?php foreach ($icon as $i) { ?>
                				<option value="<?= $i->icon_class; ?>" data-icon="<?= $i->icon_class; ?>"><?= $i->icon_class; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">Menu Access</label>
						<div class="col-md-9">
							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<input type="radio" value="0" class="form-check-input-styled" name="tAccess" id="tNonpublish">No Publish
								</label>
							</div>
							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<input type="radio" value="1" class="form-check-input-styled" name="tAccess" id="tpublish">Publish
								</label>
							</div>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">Menu Parent</label>
						<div class="col-md-9">
							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<input type="radio" value="0" class="form-check-input-styled" name="tParent" id="tPnonaktif">No Aktif
								</label>
							</div>
							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<input type="radio" value="1" class="form-check-input-styled" name="tParent" id="tPaktif">Aktif
								</label>
							</div>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">MENU URUTAN</label>
						<div class="col-sm-9">
							<input name="tUrutan" autocomplete="off" type="number" placeholder="Menu Urutan" class="form-control form-control-sm text-uppercase" required/>
						</div>
					</div>
				</div>
				<div class="card-footer">
          <div class="d-flex justify-content-between align-items-center">
            <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
            <button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Save changes</button>
          </div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="panel panel-blue">
	<div class="panel-heading">List Menu Modul
	<?= $write; ?>
  </div>
	<div class="alert alert-success" style="display: none;"></div>
	<div class="panel_body">
		<div class="table-responsive">
			<table id="example1" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
					<tr>
						<th style="text-align: center;width: 20px;">#</th>
						<th>MENU MODUL</th>
						<th>MENU ALIAS</th>
						<th>MENU ACCESS</th>
						<th>MENU PARENT</th>
						<th>ID PARENT</th>
						<th>NO URUT</th>
						<th>MEDIA ICON</th>
						<th style="width:7%;text-align: center;">Aksi</th>
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
	
		function formatText (icon) {
			return $('<span>' + icon.text + ' <i class="' + $(icon.element).data('icon') + ' float-right top-top"></i></span>');
		};
		<?php if($lock==1): ?>
			$('#btnAdd').click(function() {
				$('#mForm').modal('show');
				rbAktif();
				$('#mForm').find('.modal-title').text('Add New Data Modul');
				$('#myForm').attr('action', '<?= $link;?>/addmenuparents');
				$("#myForm").data('validator').resetForm();
				$('#myForm')[0].reset();
				$('#btnAdd').tooltip('hide');
				$('.select-search').select2({
					width: "100%",
					templateSelection: formatText,
					templateResult: formatText
				});
			});
    	<?php endif; ?>

		$('#myForm').submit(function(e) {
			e.preventDefault();
			var url = $('#myForm').attr('action');
			var data = $('#myForm').serialize();
			$.ajax({
				type: 'ajax',
				method: 'post',
				url: url,
				data: data,
				async: true,
				dataType: 'json',
				success: function(response) {
					console.log(response);
					if (response.error) {
            			notiferror_a('Error Update Modul To Database');
					} else {
						$('#mForm').modal('hide');
						if (response.type == 'add') {
							var type = 'Added'
						} else if (response.type == 'update') {
							var type = "Updated"
						}
						notifsukses('Data Modul ' + type + ' Successfully');
						dataTable.ajax.reload();
						// setTimeout(function () { location.reload(1); }, 2000);
					}
				},
				error: function(error) {
					console.log(error);
					$('#mForm').modal('hide');
					notiferror('Error Update To Database');
				}
			});

		});

		<?php if(!empty($this->security_function->permissions($filename . "-c"))): ?>
			$('#example1').on('click', '.bEdit', function() {
				var id    = $(this).attr('id');
				$('.bEdit').tooltip('hide');
				rbAktif();
				start();
				$.ajax({
					type: 'ajax',
					method: 'get',
					url: '<?= $link; ?>/editmenuparents',
					data: {
						id: id
					},
					async: true,
					dataType: 'json',
					success: function(data) {
						if(data===false){
							errordatabase();
						}else{
							// notiferror_a('Sukses Get Database');
							// $('#mForm').modal('show');
							function formatText2 (icon) {
								return $('<span>' + icon.text + ' <i class="' + $(icon.element).data('icon') + ' float-right top-top"></i></span>');
							};
							$('#mForm').find('.modal-title').text('Edit Data Modul');
							$('#myForm').attr('action', '<?= $link; ?>/updatemenuparents/'+id);
							$('input[name=tModulName]').val(data.menu_title);
							$('input[name=tModulAlias]').val(data.menu_alias);
							$("select[name=tIcon]").select2({
								width: "100%",
								templateSelection: formatText2,
								templateResult: formatText2
							}).val(data.menu_icon).trigger('change.select2');
							$('input[name=tUrutan]').val(data.menu_urutan);
							if(data.menu_access == 1) {
								$('#tpublish').prop('checked', true).uniform();
							}else{
								$('#tNonpublish').prop('checked', true).uniform();
							}
							if(data.menu_parent_active == 1) {
								$('#tPaktif').prop('checked', true).uniform();
							}else{
								$('#tPnonaktif').prop('checked', true).uniform();
							}
							end();
							setTimeout(function() {$('#mForm').modal('show');}, lama_akses+500);
						}
					},
					error: function() {
						errordatabase();
					}
				});
			});
		<?php endif; ?>

		<?php if(!empty($this->security_function->permissions($filename . "-x"))): ?>
		$('#example1').on('click', '.bDelete', function() {
			var id    = $(this).attr('id');
			var judul = $(this).attr('data');
      		$('#deleteModal').modal('show');
			$('#deleteModal').find('.infoDelete').text('Menu Modul : '+judul);
			//prevent previous handler - unbind()
			$('#btnDelete').unbind().click(function() {
				$.ajax({
					type: 'ajax',
					method: 'get',
					async: true,
					url: '<?= $link; ?>/deletemenuparents',
					data: {
						id: id
					},
					dataType: 'json',
					success: function(response) {
            			$('#deleteModal').modal('hide');
						if(response.success) {
							notifsukses('Data Modul Delete Successfully');
							dataTable.ajax.reload();
							// setTimeout(function () { location.reload(1); }, 2000);
						} else {
							// console.log(response);
              				notiferror('Failed! <br>'+response.text);
						}
					},
					error: function(error) {
						// console.log(error);
						$('#deleteModal').modal('hide');
						notiferror('Error Delete To Database');
					}
				});
			});
		});
		<?php endif; ?>

		var dataTable = $('#example1').DataTable({ 
			"processing": true, 
			"serverSide": true,
			// "scrollx"   : true,
			"responsive": false,
			"ajax": {
				"url": "<?= $link; ?>/get_ajax",
				"type": "POST",
				"data": function(data){
					data.CSRFToken = "<?= $this->session->csrf_token; ?>";
					// data.CSRFToken = $('input[name=token]').val();
				}
			},
			"columnDefs": [
				{"targets": [0,-1], "orderable": false,},
				{"className": "text-center", "targets":[0,4,5,-1]}
			],
			"fnDrawCallback": function( oSettings ) {
                $('.bEdit').each(function () {
                    $(this).tooltip({
                        html: true
                    });
                });
                $('.bDelete').each(function () {
                    $(this).tooltip({
                        html: true
                    });
                });
            }
				
		});
  });
</script>
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
						<label class="col-form-label col-sm-3 text-uppercase">NAMA MENU</label>
						<div class="col-md-5">
							<select name="tMenu" id="tMenu" class="form-control form-control-sm select" data-container-css-class="select-sm" data-fouc required>
								<option value="">Pilih --</option>
								<?php foreach($parent AS $p){ ?>
								<option value="<?= $p->menu_id; ?>"><?= ucfirst($p->menu_title); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">NAMA SUB MODUL</label>
						<div class="col-md-9">
							<input name="tSubModul" type="text" placeholder="Nama Sub Modul" class="form-control form-control-sm text-uppercase" required/>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">NAMA SUB ALIAS</label>
						<div class="col-md-9">
							<input name="tSubAlias" type="text" placeholder="Nama Sub Alias" class="form-control form-control-sm text-uppercase" required/>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">MENU URUTAN</label>
						<div class="col-sm-5">
							<input name="tUrutan" type="number" placeholder="Menu Urutan" class="form-control form-control-sm text-uppercase" required/>
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
						<label class="col-form-label col-sm-3 text-uppercase">Media Icon</label>
						<div class="col-md-9">
							<input type="text" name="tIcon" placeholder="Media Icon" class="form-control form-control-sm" />
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
	<div class="panel-heading">List Sub Modul
	<?= $write; ?>
  </div>
	<div class="alert alert-success" style="display: none;"></div>
	<div class="panel_body">
		<div class="table-responsive">
			<table id="example1" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
					<tr>
						<th style="text-align: center;width: 20px;">#</th>
						<th>MODUL</th>
						<th>SUB MODUL</th>
						<th>SUB ALIAS</th>
						<th>MENU ACCESS</th>
						<th>MENU PARENT</th>
						<th>ORDER</th>
						<th>ICON</th>
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

		<?php if($lock==1): ?>
		$('#btnAdd').click(function() {
			$('#mForm').modal('show');
			rbAktif();
			$('#mForm').find('.modal-title').text('Add New Sub Modul');
			$('#myForm').attr('action', '<?= $link;?>/addmenuchild');
			$("#myForm").data('validator').resetForm();
			$("#tMenu").select().val('').trigger('change.select2');
			$('#myForm')[0].reset();
			$('#btnAdd').tooltip('hide');
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
					if (response.error) {
            			// notiferror_a('Error Update Sub Modul To Database');
					}else{
						$('#mForm').modal('hide');
						if (response.type == 'add') {
							var type = 'Added'
						} else if (response.type == 'update') {
							var type = "Updated"
						}
						notifsukses('Data Sub Modul ' + type + ' Successfully');
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
			start();
			rbAktif();
			$.ajax({
				type: 'ajax',
				method: 'get',
				url: '<?= $link; ?>/editMenuChild',
				data: {
					id: id
				},
				async: true,
				dataType: 'json',
				success: function(data) {
					if(data===false){
						console.log(data);
						errordatabase();
					}else{
						// notiferror_a('Sukses Get Database');
						// console.log(id);
						$('#mForm').find('.modal-title').text('Edit Sub Modul');
						$('#myForm').attr('action', '<?= $link;?>/updatemenuchild/'+id);
						$("#myForm").data('validator').resetForm();
						$('#tMenu').select().val(data.menu_parent_id).trigger('change.select2');
						$('input[name=tSubModul]').val(data.menu_title);
						$('input[name=tSubAlias]').val(data.menu_alias);
						$('input[name=tUrutan]').val(data.menu_urutan);
						$('input[name=tIcon]').val(data.menu_icon);
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
						// console.log(data.menu_parent_id);
					}
				},
				error: function(error) {
					console.log(error);
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
			$('#deleteModal').find('.infoDelete').text('Sub Modul : '+judul);
			//prevent previous handler - unbind()
			$('#btnDelete').unbind().click(function() {
				$.ajax({
					type: 'ajax',
					method: 'get',
					async: true,
					url: '<?= $link; ?>/deleteMenuParents/'+id,
					// data: {
					// 	id: id
					// },
					dataType: 'json',
					success: function(response) {
            			$('#deleteModal').modal('hide');
						if(response.success) {
							notifsukses('Data Sub Modul Delete Successfully');
							dataTable.ajax.reload();
							// setTimeout(function () { location.reload(1); }, 2000);
						} else {
              				notiferror('Failed! <br>'+response.text);
						}
					},
					error: function() {
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
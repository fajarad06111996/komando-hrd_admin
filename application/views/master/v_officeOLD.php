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
											<td class="text-right"><strong>Office Name</strong></td>
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
	<div class="panel-heading">Master Office
    <?= $write; ?>
  </div>
	<div class="alert alert-success" style="display: none;"></div>
	<div class="panel_body">
		<div class="table-responsive">
			<table id="example1" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
				<tr>
					<th>#</th>
					<th>Office Code</th>
					<th>Office Name</th>
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

<script type="text/javascript">
	$(document).ready(function() {
		document.body.classList.add("sidebar-collapse");
        $('#example1').on('click', '.bInfo', function() {
            var id = $(this).attr('id');
            startfirst();
            endfirst();
            setTimeout(function () { window.location.replace('<?= $link; ?>/formInfo/'+id); }, lama_akses+500);
        });
    <?php if(!empty($this->security_function->permissions($filename . "-c"))): ?>
        $('#example1').on('click', '.bCreate', function() {
            var id = $(this).attr('id');
            var data = $(this).attr('data');
            var code = $(this).attr('code');
			$('#xStatement').modal('show');
			$('#xStatement').find('.modal-title').text('Create API CLIENT');
			$('#xStatement').find('.tBtn').text('Create API Key ?');
			$('#xStatement').find('.tContent').text('Create API Key for,');
			$('#xStatement').find('.tName').text(data);
			$('#stateForm').attr('action', '<?= $link; ?>/created_api_key');
            $('input[name=tId]').val(id);
            $('input[name=tClCode]').val(code);
			$("#stateForm").data('validator').resetForm();
			// $('#response_result').html('');
			$('#stateForm')[0].reset();
			// $(".select-search").select2({width: '100%'}).val('').trigger('change.select2');
			// document.getElementById("tUsername").readOnly = false;
			// xFalse();
		});

        $('#example1').on('click', '.bSend', function() {
            var id = $(this).attr('id');
            var data = $(this).attr('data');
            var code = $(this).attr('code');
            var key = $(this).attr('key');
			$('#xStatement').modal('show');
			$('#xStatement').find('.modal-title').text('API KEY CLIENT');
			$('#xStatement').find('.tBtn').text('Send API Key ?');
			$('#xStatement').find('.tContent').text(key+' This API Code For,');
			$('#xStatement').find('.tName').text(data);
			$('#stateForm').attr('action', '<?= $link; ?>/send_api_client_email');
            $('input[name=tId]').val(id);
            $('input[name=tClCode]').val(key);
			$("#stateForm").data('validator').resetForm();
			// $('#response_result').html('');
			$('#stateForm')[0].reset();
			// $(".select-search").select2({width: '100%'}).val('').trigger('change.select2');
			// document.getElementById("tUsername").readOnly = false;
			// xFalse();
		});

        $('#example1').on('click', '.bCreate2', function() {
            var id = $(this).attr('id');
            var data = $(this).attr('data');
            var code = $(this).attr('code');
			$('#xStatement').modal('show');
			$('#xStatement').find('.modal-title').text('Create DRIVER as USER');
			$('#xStatement').find('.tBtn').text('Create ?');
			$('#xStatement').find('.tContent').text('Create DRIVER as USER,');
			$('#xStatement').find('.tName').text(data);
			$('#stateForm').attr('action', '<?= $link; ?>/createAsUser');
            $('input[name=tId]').val(id);
            $('input[name=tClCode]').val(code);
			$("#stateForm").data('validator').resetForm();
			// $('#response_result').html('');
			$('#stateForm')[0].reset();
			// $(".select-search").select2({width: '100%'}).val('').trigger('change.select2');
			// document.getElementById("tUsername").readOnly = false;
			// xFalse();
		});

		$('#example1').on('click', '.bStatus', function() {
            var id = $(this).attr('id');
            var data = $(this).attr('data');
            var code = $(this).attr('code');
			$('#xStatement').modal('show');
			$('#xStatement').find('.modal-title').text('Change Status HUB');
			if(code == 1){
				$('#xStatement').find('.tBtn').text('Non Aktif ?');
				$('#xStatement').find('.tContent').text('Change status to Non Aktif,');
			}else{
				$('#xStatement').find('.tBtn').text('Aktif ?');
				$('#xStatement').find('.tContent').text('Change status to Aktif,');
			}
			$('#xStatement').find('.tName').text(data);
			$('#stateForm').attr('action', '<?= $link; ?>/changeStatus');
            $('input[name=tId]').val(id);
			$('input[name=tClCode]').val(code);
			$("#stateForm").data('validator').resetForm();
			// $('#response_result').html('');
			$('#stateForm')[0].reset();
			// $(".select-search").select2({width: '100%'}).val('').trigger('change.select2');
			// document.getElementById("tUsername").readOnly = false;
			// xFalse();
		});

        $('#stateForm').submit(function(e) {
			e.preventDefault();
			var url = $('#stateForm').attr('action');
			var data = $('#stateForm').serialize();
			$.ajax({
				type: 'ajax',
				method: 'post',
				url: url,
				data: data,
				async: true,
				dataType: 'json',
				success: function(response) {
					// console.log(response);
					if (response.error) {
						$('#xStatement').modal('hide');
						notiferror_a('Error Update User To Database1');
					}else if(response.status){
						$('#xStatement').modal('hide');
						notiferror_a(response.message);
					}else{
            			$('#xStatement').modal('hide');
						if (response.type == 'add') {
							var type = 'Added'
						} else if (response.type == 'update') {
							var type = "Updated"
            			} else if (response.type == 'create') {
                            var type = "Created"
            			} else if (response.type == 'change') {
                            var type = "Changed"
                        }
						notifsukses('Data ' + type + ' Successfully');
						dataTable.ajax.reload();
						// setTimeout(function () { location.reload(1); }, 2000);
						$("#stateForm").data('validator').resetForm();
						$('#stateForm')[0].reset();
					}
				},
				error: function(error) {
					console.log(error);
					$('#xStatement').modal('hide');
          			// notiferror('KNP ERROR?');
          			notiferror('Error Update To Database');
				}
			});

		});
		
        $('#example1').on('click', '.bEdit', function() {
            var id = $(this).attr('id');
            startfirst();
            endfirst();
            setTimeout(function () { window.location.replace('<?= $link; ?>/formData/'+id); }, lama_akses+500);
        });
	<?php endif; ?>

		<?php if(!empty($this->security_function->permissions($filename . "-x"))): ?>
		    $('#example1').on('click', '.bDelete', function(){
			var id    = $(this).attr('id');
			var Name = $(this).attr('data');
            $('#deleteModal').modal('show');
			$('#deleteModal').find('.infoDelete').text('Master Hub : '+Name);
			//prevent previous handler - unbind()
			$('#btnDelete').unbind().click(function() {
				$.ajax({
					type: 'ajax',
					method: 'get',
					async: true,
					url: '<?= $link; ?>/deleteHub',
					data: {
						id: id
					},
					dataType: 'json',
					success: function(response) {
                        $('#deleteModal').modal('hide');
						if(response.success){
                            notifsukses('Master Hub <strong>'+Name+'</strong> Delete Successfully');
                            // setTimeout(function () { location.reload(1); }, 2000);
                            dataTable.ajax.reload();
						}else{
                            notiferror('Error Delete Hub To Database');
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
        "scrollx"   : true,
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
            {"className": "text-center", "targets":[0,5,-1]}
        ],
            
    });
            
	});
</script>
<!--Modal detail user-->
<div id="mForm" data-backdrop="static" data-keyboard="false" class="modal fade">

	<div class="modal-dialog modal-xl">

		<div class="modal-content">

			<div class="modal-header pt-2 pb-2 bg-goblin">

				<h4 class="modal-title"></h4>

				<button type="button" class="close" data-dismiss="modal">&times;</button>

			</div>

			<form action="" id="myForm" class="form-horizontal form-validasi" method="post">

				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6">
		
							<div class="form-group row mb-1">
		
								<label class="col-form-label col-sm-3 text-uppercase">Menu Code<sup><b class="text-danger">*</b></sup></label>
		
								<div class="col-md-4">
		
									<input type="hidden" name="clear_quote" value="0" />
									<input type="hidden" name="csrf" value="<?= $this->session->csrf_token; ?>" />

									<input type="hidden" name="tId" />
		
									<input id="menu_code" name="menu_code" type="text" placeholder="Menu Code" class="form-control form-control-sm" required/>
		
								</div>
								<div class="col-md-5">
		
									<span id="response_result" class="mt-1"></span>
		
								</div>
		
							</div>
							<div class="form-group row mb-1">
		
								<label class="col-form-label col-sm-3 text-uppercase">Menu Name<sup><b class="text-danger">*</b></sup></label>
		
								<div class="col-md-9">
		
									<input id="menu_name" name="menu_name" type="text" placeholder="Menu Name" class="form-control form-control-sm" required/>
		
								</div>
		
							</div>
							<div class="form-group row mb-1">
	
								<label class="col-form-label col-sm-3 text-uppercase">Remarks</label>
	
								<div class="col-md-9">
	
									<textarea rows="4" id="remarks" name="remarks" type="text" placeholder="Remarks" class="form-control form-control-sm" required></textarea>
	
								</div>
	
							</div>
							<div class="form-group row mb-1">
	
								<label class="col-form-label col-sm-3">STATUS</label>
	
								<div class="col-md-9">
	
									<div class="form-check form-check-inline">
	
										<label class="form-check-label">	<!-- tBnaktif -->
	
											<input type="radio" value="1" class="form-check-input-styled" name="tStatus" id="xTrue">Aktif
	
										</label>
	
									</div>
	
									<div class="form-check form-check-inline">
	
										<label class="form-check-label">	<!-- tBaktif -->
	
											<input type="radio" value="0" class="form-check-input-styled" name="tStatus" id="xFalse">Non-Aktif
	
										</label>
	
									</div>
	
								</div>
	
							</div>
		
						</div>
						<div class="col-sm-6">
						
							<div class="form-group row mb-1">

								<label class="col-sm-2" for="attention">Photo</label>

								<div class="col-sm-3">

									<button type="button" class="btn btn-sm btn-warning bCapture"><i class="fa fa-camera fa-lg"></i> Capture</button>

								</div>

								<div class="col-sm-7 text-left p-3">

									<input type="file" id="cmd_browse" class="cmd_browse" name="cmd_browse" accept="image/*" style="display:none;">
									<input type="hidden" id="url_image" name="url_image" value="">
									<input type="hidden" id="temp_image" name="temp_image" value="0">
									<img src="<?= base_url('assets/media/photos/DEFAULT.png'); ?>" id="img_photo" class="rounded" style="width:250px;">

								</div>

							</div>
						</div>
					</div>

				</div>

				<div class="card-footer">

                    <div class="d-flex justify-content-between align-items-center">

                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>

                        <a class="btn bg-blue btn-ladda btn-ladda-spinner bSbmit" data-spinner-color="#333" data-style="slide-down">Save changes</a>

						<button type="submit" id="bSubmit" style="display: none;"></button>

                    </div>

				</div>

			</form>

		</div>

	</div>

</div>
<!--End Modal detail user-->

<div class="panel panel-green">
	<div class="panel-heading">Menu Apps Client
    <?= $write; ?>
  </div>
	<div class="alert alert-success" style="display: none;"></div>
	<div class="panel_body">
		<div class="table-responsive">
			<table id="example1" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
				<tr>
					<th>#</th>
					<th>Menu Code</th>
					<th>Menu Name</th>
					<th>Icon</th>
					<th>Remarks</th>
					<th>Status</th>
					<th>Created</th>
					<th>Aksi</th>
				</tr>
				</thead>
				<tbody>
				
				</tbody>
			</table>
		</div>
	</div>
</div>
<script src="https://www.gstatic.com/firebasejs/live/3.0/firebase.js"></script>
<script type="text/javascript">
	// jQuery(function($){
	// 	// console.log
	// 	$("#admin_fee").mask("999,999,999");
	// });
	$(document).ready(function() {
		$('.bCapture').click(function(){
			console.log('oke');
			document.getElementById('cmd_browse').click();
		})
		$('input[type=file]').on('change',function(){
			const file = this.files[0];
			console.log(file);
			if (file){
				let reader = new FileReader();
				reader.onload = function(event){
					console.log(event.target.result);
					$('#img_photo').attr('src', event.target.result);
					$('#temp_image').val(1);
				}
				reader.readAsDataURL(file);
			}
		});
		$('.bSbmit').click(function(e){
			e.preventDefault();
			if($('#menu_name').val() == ''){
				notiferror_a('Menu Name is required !');
			}else{
				if($('#temp_image').val()==1){

					var file = document.getElementById('cmd_browse').files[0];
					// console.log(file);
					// Your web app's Firebase configuration
					// For Firebase JS SDK v7.20.0 and later, measurementId is optional
					var firebaseConfig = {
						apiKey: "AIzaSyDayM745l9bzQrGOgaQIsEV8TLMLuid9uA",
						authDomain: "angelic-center-280009.firebaseapp.com",
						projectId: "angelic-center-280009",
						storageBucket: "angelic-center-280009.appspot.com",
						messagingSenderId: "665915076805",
						appId: "1:665915076805:web:276f4b6a6a9fecf74d80ac",
						measurementId: "G-317C6Y6SB5"
					};
					// Initialize Firebase
					firebase.initializeApp(firebaseConfig);
					var filen     = document.getElementById("menu_code").value;
					var filena    = filen.split(' ').join('_');
					var filename  = filena + '.svg';
					var storageRef = firebase.storage().ref('menu_photo/' + filename);
					var task = storageRef.put(file);
					
					// Listen for state changes, errors, and completion of the upload.
					task.on(firebase.storage.TaskEvent.STATE_CHANGED, // or 'state_changed'
						function(snapshot) {
							// Get task progress, including the number of bytes uploaded and the total number of bytes to be uploaded
							var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
							console.log('Upload is ' + progress + '% done');
							switch (snapshot.state) {
							case firebase.storage.TaskState.PAUSED: // or 'paused'
								console.log('Upload is paused');
								break;
							case firebase.storage.TaskState.RUNNING: // or 'running'
								console.log('Upload is running');
								// Swal.fire({
								//           imageUrl: '<?//= base_url(); ?>assets/images/JONI_TAPA.png',
								//           imageHeight: 100,
								//           imageAlt: 'Joni',
								//           title: "Sorry",
								//           text: 'Upload is running',
								//           allowOutsideClick: false
								// });
								break;
							}
						},
						function(error) {
						
						// A full list of error codes is available at
						// https://firebase.google.com/docs/storage/web/handle-errors
						switch (error.code) {
							case 'storage/unauthorized':
							console.log(error);
							console.log('storage/unauthorized');
							// User doesn't have permission to access the object
							break;
						
							case 'storage/canceled':
							console.log(error);
							console.log('storage/canceled');
							// User canceled the upload
							break;
						
							case 'storage/unknown':
							console.log(error);
							console.log('storage/unknown');
							// Unknown error occurred, inspect error.serverResponse
							break;
						}
						},
						function() {
							// Upload completed successfully, now we can get the download URL
							task.snapshot.ref.getDownloadURL().then(function(downloadURL) {
								// console.log('File available at', downloadURL);
								document.getElementById("url_image").value = downloadURL;
								document.getElementById('bSubmit').click();
							});
						}
					);
				}else{
				    document.getElementById('bSubmit').click();
				}
			}
		});
        $('#btnAdd').click(function() {
            $('#mForm').modal('show');
            $('#btnAdd').tooltip('hide');
            $('#mForm').find('.modal-title').text('Add New Menu Apps Client');

            $('#myForm').attr('action', '<?= $link; ?>/addMenuapps');
            $('#img_photo').attr('src', '<?= base_url('assets/images/no_image.png'); ?>');

            $("#myForm").data('validator').resetForm();

            // $('#myForm').validate().resetForm();

            $('#response_result').html('');

            $('#myForm')[0].reset();

            $(".select-search").select2({width: '100%'}).val('').trigger('change.select2');

            document.getElementById("menu_code").readOnly = false;

            xFalse();

        });
		<?php if($lock==1): ?>

		$('#menu_code').keyup(function() {

			var resname = $('#menu_code').val();

			// console.log(username);

			if (resname != '') {

				$.ajax({

					url: '<?= $link; ?>/checkResponse',

					method: 'POST',

					data: {

						resname: resname

					},

					success: function(data) {

						// console.log(data);

						if (data == 0) {
							$('#response_result').html('<span class="help-block text-danger"><span><i class="ic ic_db_x" aria-hidden="true"></i>Product Code is Already Exist</span></span>');

						} else {
							$('#response_result').html('<span class="help-block text-success"><span><i class="ic ic_db_accept" aria-hidden="true"></i>Product Code is Available</span></span>');

						}

					}

				});

			}

		});
		<?php endif; ?>
        $('#example1').on('click', '.bInfo', function() {
            var id = $(this).attr('id');
            startfirst();
            endfirst();
            setTimeout(function () { window.location.replace('<?= $link; ?>/formInfo/'+id); }, lama_akses+500);
        });
		$('#myForm').submit(function(e) {
			e.preventDefault();
			var url = $('#myForm').attr('action');
			var data = $('#myForm').serialize();
			$.ajax({
    			type: 'ajax',
    			method: 'post',
    			url: url,
    			data: JSON.stringify(data),
    			async: true,
    			dataType: 'json',
    			success: function(response) {
    				console.log(response);
    				if (response.error) {
    					// notiferror_a('Error Data is not complete');
    					notiferror_a(response.message);
    				}else if(response.status){
    					$('#mForm').modal('hide');
    					notiferror_a(response.text);
    					// notiferror_a('Error Update Customer To Database');
    				}else{
    					$('#mForm').modal('hide');
    					notifsukses(response.text);
    					setTimeout(function () { window.location.replace(response.ledit); }, 2000);
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
// 			document.getElementById("menu_code").readOnly = false;
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
			$('#xStatement').find('.modal-title').text('Create AGENT as USER');
			$('#xStatement').find('.tBtn').text('Create ?');
			$('#xStatement').find('.tContent').text('Create AGENT as USER,');
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
			$('#xStatement').find('.modal-title').text('Change Status  Menu Apps Client');
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
			$('.bEdit').tooltip('hide');
			xFalse();
			// startfirst();
			$.ajax({
				type: 'ajax',
				method: 'get',
				url: '<?= $link; ?>/editMenuapps',
				data: {
					id: id
				},
				async: true,
				dataType: 'json',
				success: function(data) {
					if(data===false){
						errordatabase();
					}else{
						console.log(data);
						$('#mForm').find('.modal-title').text('Edit Menu Apps Client');
						$('#myForm').attr('action', '<?= $link; ?>/updateMenuapps/'+id);
						$('#response_result').html('');
						$("#myForm").validate().resetForm();
						document.getElementById("menu_code").readOnly = true;
						$('input[name=menu_code]').val(data.menu_code);
						$('input[name=menu_name]').val(data.menu_name);
						// $('input[name=product_title]').val(data.product_title);
						$('textarea[name=remarks]').val(data.remarks);
						if(data.image_menu == ""){
							$('#img_photo').attr('src','<?= base_url('assets/images/no_image.png'); ?>');
						}else{
							$('input[name=url_image]').val(data.image_menu);
							$('#img_photo').attr('src',data.image_menu);
						}
						if(data.status == 1) {
							$('#xTrue').prop('checked', true).uniform();
							$('#xFalse').prop('checked', false).uniform();
						}else{
							$('#xTrue').prop('checked', false).uniform();
							$('#xFalse').prop('checked', true).uniform();
						}
						// endfirst();
						// dataTable.ajax.reload();
						$('#mForm').modal('show');
						// setTimeout(function() {$('#mForm').modal('show');}, lama_akses+500);
					}
				},
				error: function() {
					$('#mForm').modal('hide');
					notiferror('Error Update To Database');
				}
			});
		});
	<?php endif; ?>

		<?php if(!empty($this->security_function->permissions($filename . "-x"))): ?>
		    $('#example1').on('click', '.bDelete', function(){
			var id    = $(this).attr('id');
			var Name = $(this).attr('data');
            $('#deleteModal').modal('show');
			$('#deleteModal').find('.infoDelete').text('Menu Apps Client : '+Name);
			//prevent previous handler - unbind()
			$('#btnDelete').unbind().click(function() {
				$.ajax({
					type: 'ajax',
					method: 'get',
					async: true,
					url: '<?= $link; ?>/deleteMenuapps',
					data: {
						id: id
					},
					dataType: 'json',
					success: function(response) {
                        $('#deleteModal').modal('hide');
						if(response.success){
                            notifsukses(response.text);
                            // setTimeout(function () { location.reload(1); }, 2000);
                            dataTable.ajax.reload();
						}else{
                            notiferror_a(response.text);
						}
					},
					error: function(error) {
						console.log(error);
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
				},
				"error": function(error){
					console.log(error);
				}
			},
			"columnDefs": [
				{"targets": [0,-1], "orderable": false,},
				{"className": "text-center", "targets":[0,3,5,-1]}
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
				$('.bStatus').each(function () {
					$(this).tooltip({
						html: true
					});
				});
				$('.bLockStatus').each(function () {
					$(this).tooltip({
						html: true
					});
				});
				$('.bInfo').each(function () {
					$(this).tooltip({
						html: true
					});
				});
				$('.bCreate').each(function () {
					$(this).tooltip({
						html: true
					});
				});
				$('.bCreate2').each(function () {
					$(this).tooltip({
						html: true
					});
				});
				$('.bSend').each(function () {
					$(this).tooltip({
						html: true
					});
				});
				$('.bImage').each(function () {
					$(this).tooltip({
						html: true
					});
				});
			} 
				
		});

		$('#example1').on('click', '.bImage', function(){
			var urlImage = $(this).attr('src');
			var caption = $(this).attr('caption');
			$('#myImage').modal('show');
			$('#example1').find('.bImage').tooltip('hide');
			$('#myImage').find('#img01').attr('src',urlImage);
			$('#myImage').find('#caption').text(caption);
			$('#myImage').find('#caption').css('font-size','21px');
			$('#myImage').find('#caption').css('font-weight','700');
			$('#myImage').find('#caption').css('color','#FFF');
			$('#myImage').find('#caption').css('text-align','center');
		});
	});
	function xFalse(){
		$('#xTrue').prop('checked', true).uniform();
		$('#xFalse').prop('checked', false).uniform();
		$('#personal_account').prop('checked', false);
		$('#corporate_account').prop('checked', false);
	}   
	
</script>
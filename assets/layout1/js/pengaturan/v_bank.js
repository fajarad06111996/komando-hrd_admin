let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
// console.log(dp);
document.body.classList.add("sidebar-collapse");
var submit, notif;
$(document).ready(function() {
	$('.bCapture').click(function(){
		// console.log('oke');
		document.getElementById('cmd_browse').click();
	})
	$('input[type=file]').on('change',function(){
		const file = this.files[0];
		// console.log(file);
		if (file){
			let reader = new FileReader();
			reader.onload = function(event){
				// console.log(event.target.result);
				$('#img_photo').attr('src', event.target.result);
				$('#temp_image').val(1);
			}
			reader.readAsDataURL(file);
		}
	});
	if(dp.lock==1){
		$('#product_code').keyup(function() {
			var resname = $('#product_code').val();
			// console.log(username);
			if (resname != '') {
				$.ajax({
					url: dp.link+'/checkResponse',
					method: 'POST',
					data: {
						resname: resname
					},
					success: function(data) {
						// console.log(data);
						if (data == 0) {
							$('#bSave').prop('disabled', true);
							$('#response_result').html('<span class="help-block text-danger"><span><i class="ic ic_db_x" aria-hidden="true"></i>Product Code is Already Exist</span></span>');
						} else {
							$('#bSave').prop('disabled', false);
							$('#response_result').html('<span class="help-block text-success"><span><i class="ic ic_db_accept" aria-hidden="true"></i>Product Code is Available</span></span>');
						}
					}
				});
			}
		});
		$('#btnAdd').click(function() {
			$('#mForm').modal('show');
			$('#btnAdd').tooltip('hide');
			$('#mForm').find('.modal-title').text('Add New Data Bank');
			$('#myForm').attr('action', dp.link+'/addBank');
			$("#myForm").data('validator').resetForm();
			$('#response_result').html('');
			$('#myForm')[0].reset();
			xFalse();
			$('#xTrue').prop('checked', true).uniform();
		});
	}
	$('.bSbmitx').click(function(e){
		e.preventDefault();
		// console.log($('#max_distance').val());
		// throw '';
		if($('#product_name').val() == ''){
			notiferror_a('Product Name is required !');
		}else if($('#product_code').val() == ''){
			notiferror_a('Product Code is required !');
		}else if($('#product_title').val() == null){
			notiferror_a('Product Title is required !');
		}else if($('#type_product').val() == null){
			notiferror_a('Type Product is required !');
		}else if($('#max_distance').val() == ''){
			notiferror_a('Max Distance is required !');
		}else{
			if($('#temp_image').val()==1){
				var file = document.getElementById('cmd_browse').files[0];
				// console.log(file);
				// Your web app's Firebase configuration
				// For Firebase JS SDK v7.20.0 and later, measurementId is optional
				var firebaseConfig = dp.key_firebase;
				// Initialize Firebase
				firebase.initializeApp(firebaseConfig);
				var filen     = document.getElementById("product_code").value;
				var filena    = filen.split(' ').join('_');
				var filename  = filena + '.svg';
				var storageRef = firebase.storage().ref('product_photo/' + filename);
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
	$('#myForm').submit(function(e) {
		e.preventDefault();
		var url 		= $('#myForm').attr('action');
		var data 		= $('#myForm').serialize();
		// var form 		= $('#myForm')[0]; // You need to use standard javascript object here
		// var formData 	= new FormData(form);
		// var data 	= $('#myForm')[0];
		// console.log(form);
		// throw '';
		$.ajax({
			type: 'ajax',
			method: 'post',
			processData: false,
			contentType: false,
			url: url,
			data: new FormData(this),
			async: true,
			dataType: 'json',
			success: function(response) {
				// console.log('udah masuk nich');
				// console.log(response);
				if (response.error) {
					console.log(response);
					$('#mForm').modal('hide');
					notiferror_a(response.message);
				}else if(response.status){
					$('#mForm').modal('hide');
					notiferror_a(response.text);
				}else{
					$('#mForm').modal('hide');
					notifsukses(response.text);
					dataTable.ajax.reload();
					// setTimeout(function () { location.reload(1); }, 2000);
					$("#myForm").data('validator').resetForm();
					$('#myForm')[0].reset();
				}
			},
			error: function(error) {
				console.log(error);
				$('#mForm').modal('hide');
				// notiferror('KNP ERROR?');
				notiferror('Error Update To Database');
			}
		});
	});
	$('#stateForm').submit(async function(e) {
		e.preventDefault();
		var url = $('#stateForm').attr('action');
		var data = $('#stateForm').serialize();
		try {
			submit = await submitForm('POST', url, data);
			if (submit.error) {
				$('#xStatement').modal('hide');
				notiferror_a(submit.message);
			}else if(submit.status){
				$('#xStatement').modal('hide');
				notiferror_a(submit.text);
			}else{
				$('#xStatement').modal('hide');
				notifsukses(submit.text);
				dataTable.ajax.reload();
				// setTimeout(function () { location.reload(1); }, 2000);
				$("#stateForm").data('validator').resetForm();
				$('#stateForm')[0].reset();
			}
		} catch (error) {
			console.log(error);
			$('#xStatement').modal('hide');
			notiferror('Error Update To Database');
		}
	});
	if(dp.permC==1){

		$('#example2').on('click', '.bEdit',async function() {
			var id = $(this).attr('id');
			$(this).tooltip('hide');
			xFalse();
			try {
				submit = await submitForm('get', dp.link+'/editBank', {id: id});
				if(submit===false){
					errordatabase();
				}else{
					$('#mForm').find('.modal-title').text('Edit Data Bank');
					$('#myForm').attr('action', dp.link+'/updateBank/'+id);
					$('#response_result').html('');
					$("#myForm").validate().resetForm();
					$('input[name=bank_code]').val(submit.bank_code);
					$('input[name=bank_name]').val(submit.bank_name);
					$('textarea[name=remark]').val(submit.remark);
					if(submit.status == 1) {
						$('#xTrue').prop('checked', true).uniform();
					}else{
						$('#xFalse').prop('checked', true).uniform();
					}
					$('#mForm').modal('show');
				}
			} catch (error) {
				console.log(error);
				$('#mForm').modal('hide');
				notiferror('Error Update To Database');
			}
		});
		$('#example2').on('click', '.bStatus', function() {
			$(this).tooltip('hide');
			var id = $(this).attr('id');
			var data = $(this).attr('data');
			var code = $(this).attr('code');
			$('#xStatement').modal('show');
			$('#xStatement').find('.modal-title').text('Change Status Product');
			if(code == 1){
				$('#xStatement').find('.tBtn').text('Non Aktif ?');
				$('#xStatement').find('.tContent').text('Change status to Non Aktif,');
			}else{
				$('#xStatement').find('.tBtn').text('Aktif ?');
				$('#xStatement').find('.tContent').text('Change status to Aktif,');
			}
			$('#xStatement').find('.tName').text(data);
			$('#stateForm').attr('action', dp.link+'/changeStatus');
			$('input[name=tId]').val(id);
			$('input[name=tClCode]').val(code);
			$("#stateForm").data('validator').resetForm();
			// $('#response_result').html('');
			$('#stateForm')[0].reset();
			// $(".select-search").select2({width: '100%'}).val('').trigger('change.select2');
			// document.getElementById("tUsername").readOnly = false;
			// xFalse();
		});
	}
	if(dp.permX==1){

		$('#example2').on('click', '.bDelete', function() {
			var id   	= $(this).attr('id');
			var Name 	= $(this).attr('data');
			var csrfSes = dp.csrf;
			$('#deleteModal').modal('show');
			$('#deleteModal').find('.infoDelete').text('Username : '+Name);
			$('#btnDelete').unbind().click(function() {
				$.ajax({
					type: 'ajax',
					method: 'get',
					async: true,
					url: dp.link+'/deleteProduct',
					data: {
						id: id,
						csrfsession: csrfSes
					},
					dataType: 'json',
					success: function(response) {
						$('#deleteModal').modal('hide');
						if(response.success){
							notifsukses('User <strong>'+Name+'</strong> Delete Successfully');
							dataTable.ajax.reload();
							// setTimeout(function () { location.reload(1); }, 2000);
						}else{
							notiferror('Error Delete User To Database');
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
	}
	
	// load data ke tabel
	var dataTable = $('#example2').DataTable({
		processing: true,
		serverSide: true,
		// scrollX	: true,
		responsive: false,
		ajax:{
			url: dp.link+"/get_ajax",
			type: "POST",
			data: function(data){
				data.csrfsession = dp.csrf;
				// data.CSRFToken = $('input[name=token]').val();
			},
			error:function(error){
				console.log(error);
			}
		},
		columnDefs: [
			{targets: [0,-1], orderable: false,},
			{className: "text-center", targets:[0,1,2,3]}
		],
		preDrawCallback: function() {
			spinnerdarkDT(this);
		},
		language: {
			processing: ""
		},
		fnDrawCallback: function( oSettings ) {
			spinnerdarkDT(this);
			stopdarkspinnerDT();
			$('.bImage').each(function () {
				$(this).tooltip({
					html: true
				});
			});
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
		}
		// CONTOH LAIN MODEL DATATABLE SERVERSIDE 
	});
	
	$('#example2').on('click', '.bImage', function(){
		$('#myImage').find('#img01').attr('src','');
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
	$('#xFalse').prop('checked', false).uniform();
	$('#xTrue').prop('checked', false).uniform();
}
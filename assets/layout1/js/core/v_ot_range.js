let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
document.body.classList.add("sidebar-collapse");
var notif,submit;
$(document).ready(function() {
    if(dp.lock==1){
        $('#tUsername').keyup(function() {
            var resname = $('#tUsername').val();
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
            location.replace(dp.link+'/formData');
        });
        $('#btnImport').click(function(){
            location.replace(dp.link+'/import');
        });
    }
	$('#stateForm').submit(async function(e) {
		// startfirst();
		e.preventDefault();
		var url = $('#stateForm').attr('action');
		var data = $('#stateForm').serialize();
		try {
			submit = await submitForm('post', url, data);
			if(submit.success){
				$('#xStatement').modal('hide');
				notifsukses(submit.msg);
				dataTable.ajax.reload();
				$('#stateForm')[0].reset();
			}else{
                $('#xStatement').modal('hide');
				notiferror_a(submit.msg);
			}
		} catch (error) {
			console.log(error);
			$('#xStatement').modal('hide');
			notiferror('Error Update To Database');
		}
	});
    if(dp.permC==1){
        $('#example2').on('click', '.bEdit', function() {
            var id = $(this).attr('id');
            location.replace(dp.link+'/formData/'+id);
        });
        $('#example2').on('click', '.bStatus', function() {
            var id = $(this).attr('id');
            var data = $(this).attr('data');
            var code = $(this).attr('code');
            $(this).tooltip('hide');
            $('#xStatement').modal('show');
            $('#xStatement').find('.modal-title').text('Change Status Setup');
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
            var id   = $(this).attr('id');
            var Name = $(this).attr('data');
            $('#deleteModal').modal('show');
            $('#deleteModal').find('.infoDelete').text('Username : '+Name);
            $('#btnDelete').unbind().click(function() {
                $.ajax({
                    type: 'ajax',
                    method: 'get',
                    async: true,
                    url: dp.link+'/deleteTariff',
                    data: {
                        id: id,
                        csrfsession: dp.csrf
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
	// CONTOH LAIN MODEL DATATABLE SERVERSIDE
	var dataTable = $('#example2').DataTable({
		// stateSave: true,
		// processing: true,
		serverSide: true,
		// scrollX	: true,
		responsive: false,
        ordering: false,
		ajax:{
			url: dp.link+"/get_ajax/",
			type: "POST",
			data: function(data){
				data.csrfsession = dp.csrf;
			},
			error: function (error){
				console.log(error);
			}
		},
		dom: 'Blfrtip',
		buttons: [
			{
				className: 'btn btn-outline-success p-1',
				extend: 'excel',
				text: '<i class="fa fa-file-excel-o"></i> Excel',
				titleAttr: 'Excel'
			},
		],
		columnDefs: [
			{targets: [0,1], orderable: false,},
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
			$('.bDelete').each(function () {
				$(this).tooltip({
					html: true
				});
			});
			$('.bEdit').each(function () {
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
	DatatableButtonsHtml5.init();
});
function xFalse(){
	$('#xFalse').prop('checked', false).uniform();
	$('#xTrue').prop('checked', false).uniform();
}
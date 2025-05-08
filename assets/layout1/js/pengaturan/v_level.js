let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
document.body.classList.add("sidebar-collapse");
var notif, submit;
$(document).ready(function() {
    if(dp.lock==1){
        $('#tLevelname').keyup(async function() {
            $('#tLevelname').removeClass('is-valid');
            $('#tLevelname').removeClass('is-invalid');
            $('#response_result').html('');
            var levname = $('#tLevelname').val();
                // console.log(username);
            if (levname != '') {
                try {
                    submit = await submitForm('post', dp.link+'/checkResponse', {
                        levname: levname
                    });
                    if (submit == 0) {
                        $('#bSave').prop('disabled', true);
                        $('#tLevelname').removeClass('is-valid');
                        $('#tLevelname').addClass('is-invalid');
                        $('#response_result').html('<span class="help-block text-danger"><span><i class="ic ic_db_x" aria-hidden="true"></i> This Field Level Name is Already Exist</span></span>');
                    } else {
                        $('#bSave').prop('disabled', false);
                        $('#tLevelname').removeClass('is-invalid');
                        $('#tLevelname').addClass('is-valid');
                        $('#response_result').html('<span class="help-block text-success"><span><i class="ic ic_db_accept" aria-hidden="true"></i>Field Level Name is Available</span></span>');
                    }
                } catch (error) {
                    console.log(error);
                }
            }
        });
        $('#btnAdd').click(function() {
            var csrf_token_add = dp.csrf;
            $('#tLevelname').removeClass('is-valid');
            $('#tLevelname').removeClass('is-invalid');
            $('#mForm').modal('show');
            $('#mForm').find('.modal-title').text('Add New Data Level');
            $('#myForm').attr('action', dp.link+'/addLevel');
            $("#myForm").data('validator').resetForm();
            $('#response_result').html('');
            $('#myForm')[0].reset();
            $(".select-search").select2({width: '100%'}).val('').trigger('change.select2');
            $('input[name=csrftoken]').val(csrf_token_add);
            document.getElementById("tLevelname").readOnly = false;
            xFalse();
        });
    }
	$('#example1').on('click', '.bCounter',async function() {
		var level_id = $(this).attr('id');
		$('.bCounter').tooltip('hide');
		try {
			submit = await submitForm('post', dp.link+'/getAccessCounter', {level_id: level_id});
			$('#mAccCount').find('.modal-title').text('Edit Daftar Access Counter');
			$('#myAccCount').attr('action', dp.link+'/fActAccessCounter');
			$('input[name=tLevel]').val(level_id);
			$('#mAccCount').find('#result').html(submit.res_tr2);
			$('#mAccCount').modal('show');
		} catch (error) {
			console.log(error);
			notiferror('Error Get Modul To Database');
		}
	});

	$('#myAccCount').submit(async function(e) {
		e.preventDefault();
		var url = $(this).attr('action');
		var data = $(this).serialize();
		try {
			submit = await submitForm('post', url, data);
			if (submit.error) {
				$('#mAccCount').modal('hide');
				notiferror(submit.message);
			}else if(submit.status){
				$('#mAccCount').modal('hide');
				notiferror(submit.text);
			}else{
				$('#mAccCount').modal('hide');
				$(this)[0].reset();
				notifsukses(submit.text);
			}
		} catch (error) {
			console.log(error);
			$('#mAccCount').modal('hide');
			notiferror('Error Update To Database');
		}
	});

	$('#example1').on('click', '.bSettings',async function() {
		var level_id = $(this).attr('id');
		$('.bSettings').tooltip('hide');
		try {
			submit = await submitForm('post', dp.link+'/getAccessSettings', {level_id: level_id});
			$('#mSettings').find('.modal-title').text('Edit Daftar Access Settings');
			$('#mySettings').attr('action', dp.link+'/fActAccessSettings');
			$('input[name=tLevel]').val(level_id);
			$('#mSettings').find('#result').html(submit.res_tr);
			$('#mSettings').find('#result2').html(submit.res_tr2);
			$('#mSettings').modal('show');
		} catch (error) {
			console.log(error);
			notiferror('Error Get Modul To Database');
		}
	});

	$('#mySettings').submit(async function(e) {
		e.preventDefault();
		var url = $(this).attr('action');
		var data = $(this).serialize();
		try {
			submit = await submitForm('post', url, data);
			if (submit.error) {
				$('#mSettings').modal('hide');
				notiferror(submit.message);
			}else if(submit.status){
				$('#mSettings').modal('hide');
				notiferror(submit.text);
			}else{
				$('#mSettings').modal('hide');
				$(this)[0].reset();
				notifsukses(submit.text);
			}
		} catch (error) {
			console.log(error);
			$('#mSettings').modal('hide');
			notiferror('Error Update To Database');
		}
	});

	$('#myForm').submit(async function(e) {
		e.preventDefault();
		var url = $('#myForm').attr('action');
		var data = $('#myForm').serialize();
		var valid = $(this).data('validator').form();
		if(valid == false){
			return false;
		}
		try {
			submit = await submitForm('post', url, data);
			if (submit.error) {
				notiferror_a(submit.message);
			}else if(submit.status){
				$('#mForm').modal('hide');
				notiferror_a('Error Update User To Database');
			}else{
				$('#mForm').modal('hide');
				if (submit.type == 'add') {
					var type = 'Added'
				} else if (submit.type == 'update') {
					var type = "Updated"
				}
				notifsukses('Data User ' + type + ' Successfully');
				dataTable.ajax.reload();
				// setTimeout(function () { location.reload(1); }, 2000);
				$("#myForm").data('validator').resetForm();
				$('#myForm')[0].reset();
			}
		} catch (error) {
			console.log(error);
			$('#mForm').modal('hide');
			notiferror('Error Update To Database');
		}
	});
	$('#stateForm').submit(async function(e) {
		e.preventDefault();
		var url = $('#stateForm').attr('action');
		var data = $('#stateForm').serialize();
		try {
			submit = await submitForm('post', url, data);
			if (submit.error) {
				$('#xStatement').modal('hide');
				notiferror_a('Error Update User To Database1');
			}else if(submit.status){
				$('#xStatement').modal('hide');
				notiferror_a('Error Update User To Database');
			}else{
				$('#xStatement').modal('hide');
				if (submit.type == 'add') {
					var type = 'Added'
				} else if (submit.type == 'update') {
					var type = "Updated"
				} else if (submit.type == 'change') {
					var type = "Changed"
					$('#xStatement').modal('hide')
				}
				notifsukses('Data Level ' + type + ' Successfully');
				dataTable.ajax.reload();
				// setTimeout(function () { location.reload(1); }, 2000);
				$("#myForm").data('validator').resetForm();
				$('#stateForm')[0].reset();
			}
		} catch (error) {
			console.log(error);
			$('#xStatement').modal('hide');
			notiferror('Error Update To Database');
		}
	});
    if(dp.permC==1){

        $('#example1').on('click', '.bEdit', function() {
            var id = $(this).attr('id');
            var csrf_token_edit = dp.csrf;
            $('.bEdit').tooltip('hide');
            xFalse();
            // start();
            $.ajax({
                type: 'ajax',
                method: 'get',
                url: dp.link+'/editlevel',
                data: {
                    id: id,
                    CSRFToken: csrf_token_edit
                },
                async: true,
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if(data===false){
                        errordatabase();
                    }else{
                        $('#mForm').modal('show');
                        $('#mForm').find('.modal-title').text('Edit Data Level');
                        $('#myForm').attr('action', dp.link+'/updatelevel/'+id);
                        $("#myForm").data('validator').resetForm();
                        document.getElementById("tLevelname").readOnly = true;
                        $('.uPass').css('display','');
                        $('input[name=csrftoken]').val(csrf_token_edit);
                        $('input[name=level_name]').val(data.level_name);
                        $('input[name=level_name]').val(data.level_name);
                        $('input[name=level_alias]').val(data.level_alias);
                        $("select[name=user_type]").select2({width: '100%'}).val(data.idx).trigger('change.select2');
                        $("select[name=office]").select2({width: '100%'}).val(data.office_enidx).trigger('change.select2');
                        $('textarea[name=level_remark]').val(data.level_remark);
                        if(data.level_active == 1) {
                            $('#xTrue').prop('checked', true).uniform();
                        }else{
                            $('#xFalse').prop('checked', true).uniform();
                        }
                        // end();
                        // setTimeout(function() {$('#mForm').modal('show');}, lama_akses+500);
                    }
                },
                error: function(error) {
                    console.log(error);
                    $('#mForm').modal('hide');
                    notiferror('Error Update To Database');
                }
            });
        });
        $('#example1').on('click', '.bStatus', function() {
            console.log('oke');
            var id = $(this).attr('id');
            var data = $(this).attr('data');
            var code = $(this).attr('code');
            $('#xStatement').modal('show');
            $('#xStatement').find('.modal-title').text('Change Status Level');
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

        $('#example1').on('click', '.bDelete', function() {
            var id    = $(this).attr('id');
            var Name = $(this).attr('data');
            // var csrf_token_delete = $('input[name=token]').val();
            var csrf_token_delete = dp.csrf;
            $('#deleteModal').modal('show');
            $('#deleteModal').find('.infoDelete').text('Level : '+Name);
            $('#btnDelete').unbind().click(function() {
                $.ajax({
                    type: 'ajax',
                    method: 'get',
                    async: true,
                    url: dp.link+'/deleteMenuLevel',
                    data: {
                        id: id,
                        CSRFToken: csrf_token_delete
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        if(response.success){
                            notifsukses('Level <strong>'+Name+'</strong> Delete Successfully');
                            // dataTable.ajax.reload();
                            setTimeout(function () { location.reload(1); }, 2000);
                            // console.log(response);
                        }else{
                            if(response.text){
                                notiferror(response.text);
                            }else{
                                notiferror('Error DeleteLevel To Database');
                            }
                        }
                    },
                    error: function() {
                        $('#deleteModal').modal('hide');
                        notiferror('Error Delete To Database');
                    }
                });
            });
        });
    }
	// CONTOH LAIN MODEL DATATABLE SERVERSIDE
	var dataTable = $('#example1').DataTable({
		processing: true,
		serverSide: true,
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
			{targets: [1,0], orderable: false,},
			{className: "text-center", targets:[0,1,6,7]}
		],
		fnDrawCallback: function( oSettings ) {
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
			$('.bReset').each(function () {
				$(this).tooltip({
					html: true
				});
			});
			$('.bCounter').each(function () {
				$(this).tooltip({
					html: true
				});
			});
		}
		// CONTOH LAIN MODEL DATATABLE SERVERSIDE 
	});
});
function xFalse(){
	$('#xFalse').prop('checked', false).uniform();
	$('#xTrue').prop('checked', true).uniform();
}
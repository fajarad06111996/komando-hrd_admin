let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
var notif, submit;
document.body.classList.add("sidebar-collapse");
$(document).ready(function() {
    if(dp.lock==1){
        $('input#tUsername').on({
            keydown: function(e) {
                if (e.which === 32)
                return false;
            },
            keyup: function() {
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
                                $('#response_result').html('<span class="help-block text-danger"><span><i class="ic ic_db_x" aria-hidden="true"></i> This Field Username is Already Exist</span></span>');
                            } else {
                                $('#bSave').prop('disabled', false);
                                $('#response_result').html('<span class="help-block text-success"><span><i class="ic ic_db_accept" aria-hidden="true"></i>Field Username is Available</span></span>');
                            }
                        }
                    });
                }
            }
              
        });
    
        var company_tag = $('select[name=company_show]');
        var company_id_coy = company_tag.val();
    
        company_tag.change(async function(){
            company_id_coy = await $(this).val();
            // console.log(company_id_coy);
            dataTable.ajax.reload();
        });
    
        $('#btnAdd').click(function() {
            $('#mForm').modal('show');
            $('#btnAdd').tooltip('hide');
            $('#mForm').find('.modal-title').text('Add New Data User');
            $('#myForm').attr('action', dp.link+'/addUser');
            $("#myForm").data('validator').resetForm();
            $('#response_result').html('');
            $('#myForm')[0].reset();
            $("select[name=tLevel]").select2({width: '100%'}).val('').trigger('change.select2');
            $("select[name=tOffice]").select2({width: '100%'}).val('').trigger('change.select2');
            $("select[name=tHub]").select2({width: '100%'}).val('').trigger('change.select2');
            $("select[name=tCounter]").select2({width: '100%'}).val('').trigger('change.select2');
            document.getElementById("tUsername").readOnly = false;
            xFalse();
            $('#xTrue').prop('checked', true).uniform();
        });
    }

    $('#myForm').submit(async function(e) {
        e.preventDefault();
        var url = $('#myForm').attr('action');
        var data = $('#myForm').serialize();
        var valid = $('#myForm').data('validator').form();
        if(valid==false){
            return;
        }
        try {
            submit = await submitForm('post', url, data);
            if (submit.error) {
                $('#mForm').modal('hide');
                notiferror_a(submit.message);
            }else if(submit.status){
                $('#mForm').modal('hide');
                notiferror_a(submit.text);
            }else{
                $('#mForm').modal('hide');
                if (submit.type == 'add') {
                    var type = 'Added'
                } else if (submit.type == 'update') {
                    var type = "Updated"
                }
                notifsukses('Data User ' + type + ' Successfully');
                dataTable.ajax.reload();
                $("#myForm").data('validator').resetForm();
                $('#myForm')[0].reset();
            }
        } catch (error) {
            console.log(error);
            $('#mForm').modal('hide');
            // notiferror('KNP ERROR?');
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
                $('#mForm').modal('hide');
                notiferror_a(submit.message);
            }else if(submit.status){
                $('#xStatement').modal('hide');
                if(submit.text){
                    notiferror_a(submit.text);
                }else{
                    notiferror_a('Error Update User To Database');
                }
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
                notifsukses('Data User ' + type + ' Successfully');
                dataTable.ajax.reload();
                $("#myForm").data('validator').resetForm();
                $('#stateForm')[0].reset();
            }
        } catch (error) {
            console.log(error);
            $('#mForm').modal('hide');
            // notiferror('KNP ERROR?');
            notiferror('Error Update To Database');
        }
    });
    if(dp.permC==1){
        $('#example2').on('click', '.bEdit', function() {
            var id = $(this).attr('id');
            $('.bEdit').tooltip('hide');
            xFalse();
            // start();
            $.ajax({
                type: 'ajax',
                method: 'get',
                url: dp.link+'/edituser',
                data: {
                    id: id
                },
                async: true,
                dataType: 'json',
                success: function(data) {
                    if(data===false){
                        errordatabase();
                    }else{
                        $('#mForm').modal('show')
                        $('.bEdit').tooltip('hide');
                        $('#mForm').find('.modal-title').text('Edit Data User');
                        $('#myForm').attr('action', dp.link+'/updateuser/'+id);
                        $("#myForm").data('validator').resetForm();
                        document.getElementById("tUsername").readOnly = true;
                        $('input[name=tNama]').val(data.user_name);
                        $('input[name=tUsername]').val(data.user_id);
                        $('input[name=tEmail]').val(data.email_id);
                        $('textarea[name=tAddress]').val(data.address);
                        $('input[name=tPhone]').val(data.mobile_phone);
                        $("select[name=tLevel]").select2().val(data.user_level_idx).trigger('change.select2');
                        $("select[name=tOffice]").select2().val(data.office_idx).trigger('change.select2');
                        $("select[name=tHub]").select2().val(data.hub_idx).trigger('change.select2');
                        $("select[name=tCounter]").select2().val(data.counter_idx).trigger('change.select2');
                        if(data.status == 1) {
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
    
        $('#example2').on('click', '.bReset', function() {
            // console.log('oke');
            var id = $(this).attr('id');
            var data = $(this).attr('data');
            var code = $(this).attr('code');
            $('#xStatement').modal('show');
            $('.bStatus').tooltip('hide');
            $('#xStatement').find('.modal-title').text('Reset Password User');
            $('#xStatement').find('.tBtn').text('Reset ?');
            $('#xStatement').find('.tContent').text('Reset Password to default,');
            $('#xStatement').find('.tName').text(data);
            $('#stateForm').attr('action', dp.link+'/resetPassword');
            $('input[name=tId]').val(id);
            $('input[name=tClCode]').val(code);
            $("#stateForm").data('validator').resetForm();
            // $('#response_result').html('');
            $('#stateForm')[0].reset();
            // $(".select-search").select2({width: '100%'}).val('').trigger('change.select2');
            // document.getElementById("tUsername").readOnly = false;
            // xFalse();
        });
    
        $('#example2').on('click', '.bStatus', function() {
            // console.log('oke');
            var id = $(this).attr('id');
            var data = $(this).attr('data');
            var code = $(this).attr('code');
            $('#xStatement').modal('show');
            $('.bStatus').tooltip('hide');
            $('#xStatement').find('.modal-title').text('Change Status User');
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
            $('.bDelete').tooltip('hide');
            $('#deleteModal').find('.infoDelete').text('Username : '+Name);
            $('#btnDelete').unbind().click(function() {
                $.ajax({
                    type: 'ajax',
                    method: 'get',
                    async: true,
                    url: dp.link+'/deleteUser',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
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
        processing: true,
        serverSide: true,
        responsive: false,
        ajax:{
            url: dp.link+"/get_ajax",
            type: "POST",
            data: function(data){
                data.csrfsession = dp.csrf;
                // data.CSRFToken = $('input[name=token]').val();
                data.companyX = $('select[name=company_show]').val();
            },
            error:function(error){
                console.log(error);
            }
        },
        columnDefs: [
            {
                className: "text-center",
                targets: [0, 1], 
                orderable: false, 
            },
            {
                className: "text-center",
                targets: [2,9],
            },
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
        }
        // CONTOH LAIN MODEL DATATABLE SERVERSIDE 

    });
});
function xFalse(){
    $('#xFalse').prop('checked', false).uniform();
    $('#xTrue').prop('checked', false).uniform();
}
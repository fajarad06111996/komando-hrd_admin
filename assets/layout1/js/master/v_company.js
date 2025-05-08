let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
document.body.classList.add("sidebar-collapse");
$(document).ready(function() {
    // $('#example1').on('click', '.bInfo', function() {
    //     var id = $(this).attr('id');
    //     window.location.replace('<?= $link; ?>/formInfo/'+id);
    // });
    $('#example1').on('click', '.bInfo', function() {
        var id = $(this).attr('id');
        start();
        $('#insert_form').html('<form action="'+dp.link+'/formInfo" name="vote" method="post" style="display:none;"><input type="text" name="id_edit" value="' + id + '" /></form>');
        document.forms['vote'].submit();
        end();
    });

    if(dp.permC==1){
        $('#example1').on('click', '.bCreate', function() {
            var id = $(this).attr('id');
            var data = $(this).attr('data');
            var code = $(this).attr('code');
            $('#xStatement').modal('show');
            $('#xStatement').find('.modal-title').text('Create API CLIENT');
            $('#xStatement').find('.tBtn').text('Create API Key ?');
            $('#xStatement').find('.tContent').text('Create API Key for,');
            $('#xStatement').find('.tName').text(data);
            $('#stateForm').attr('action', dp.link+'/created_api_key');
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
            $('#stateForm').attr('action', dp.link+'/send_api_client_email');
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
            $('#stateForm').attr('action', dp.link+'/createAsUser');
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
        
        // $('#example1').on('click', '.bEdit', function() {
        //     var id = $(this).attr('id');
        //     window.location.replace('<?= $link; ?>/formData/'+id);
        // });
        $('#example1').on('click', '.bEdit', function() {
            var id = $(this).attr('id');
            start();
            $('#insert_form').html('<form action="'+dp.link+'/formData" name="vote" method="post" style="display:none;"><input type="text" name="id_edit" value="' + id + '" /></form>');
            document.forms['vote'].submit();
            end();
        });
        $('#example1').on('click', '.bAccount', function(){
            var id    	= $(this).attr('id');
            var Name 	= $(this).attr('data');
            var city 	= $(this).attr('code');
            $('#confirmModal').modal('show');
            $('#confirmModal').find('.infoConfirm').text('Tambah Akun : '+Name);
            //prevent previous handler - unbind()
            $('#btnConfirm').unbind().click(async function() {
                start();
                try {
                    const bAccount = await submitForm('get', dp.link+'/createAccount', {
                        id: id,
                        name: Name,
                        city: city
                    });
                    end();
                    $('#confirmModal').modal('hide');
                    if(bAccount.success){
                        notifsukses(bAccount.text);
                        dataTable.ajax.reload();
                    }else{
                        notiferror(bAccount.text);
                    }
                } catch (error) {
                    end();
                    console.log(error);
                    $('#confirmModal').modal('hide');
                    notiferror('Error Delete To Database');
                }
            });
        });
        $('#example1').on('click', '.bPuAccount', function(){
            var id    	= $(this).attr('id');
            var Name 	= $(this).attr('data');
            var city 	= $(this).attr('code');
            $('#confirmModal').modal('show');
            $('#confirmModal').find('.infoConfirm').text('Tambah Akun Piutang : '+Name);
            //prevent previous handler - unbind()
            $('#btnConfirm').unbind().click(async function() {
                start();
                try {
                    const bPuAccount = await submitForm('get', dp.link+'/createPuAccount', {
                        id: id,
                        name: Name,
                        city: city
                    });
                    end();
                    $('#confirmModal').modal('hide');
                    if(bPuAccount.success){
                        notifsukses(bPuAccount.text);
                        dataTable.ajax.reload();
                    }else{
                        notiferror(bPuAccount.text);
                    }
                } catch (error) {
                    end();
                    console.log(error);
                    $('#confirmModal').modal('hide');
                    notiferror('Error Delete To Database');
                }
            });
        });
    }

    if(dp.permX==1){
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
                    url: dp.link+'/deleteHub',
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
    }

    var dataTable = $('#example1').DataTable({ 
        processing: true, 
        serverSide: true,
        scrollx   : true,
        responsive: false,
        ajax: {
            url: dp.link+"/get_ajax",
            type: "POST",
            data: function(data){
                data.csrfsession = dp.csrf;
                // data.CSRFToken = $('input[name=token]').val();
            },
            error: function(error){
                console.log(error);
            }
        },
        columnDefs: [
            {targets: [0,-1], orderable: false,},
            {className: "text-center", targets:[0,5,-1]}
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
            $('.bAccount').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bPuAccount').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bLock').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bCreated').each(function () {
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
            $('.bInfo').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
        }  
    });
});
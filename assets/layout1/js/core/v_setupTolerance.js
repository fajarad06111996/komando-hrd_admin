let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
document.body.classList.add("sidebar-collapse");
var submit,notif;
$(document).ready(function() {
    $('#btnAdd').click(function() {
        $(this).tooltip('hide');
        $('#mForm').find('.modal-title').text('Input Toleransi');
        $('#myForm').attr('action', dp.link+'/addTolerance');
        $("#myForm").data('validator').resetForm();
        $('#myForm')[0].reset();
        resetRadio();
        $('#xTrue').prop('checked', true).uniform();
        modalDragShow('#mForm');
    });

    if(dp.permC==1){
        $('#example1').on('click', '.bStatus', function() {
            var id = $(this).attr('id');
            var data = $(this).attr('data');
            var code = $(this).attr('code');
            $('#xStatement').modal('show');
            $('#xStatement').find('.modal-title').text('Ubah Status Jabatan');
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
            $('#stateForm')[0].reset();
        });

        $('#example1').on('click', '.bSetup', function() {
            $('#xInvoice').modal('show');
            var id = $(this).attr('id');
            location.replace(dp.link+'/setupTolerance/'+id);
        });

        $('#myForm').submit(async function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();
            var valid = $(this).data('validator').form();
            if(valid==false){
                return;
            }
            try {
                submit = await submitForm('post', url, data);
                if (submit.error) {
                    $('#mForm').modal('hide');
                    notiferror(submit.message);
                }else if(submit.status){
                    $('#mForm').modal('hide');
                    notiferror(submit.text);
                }else{
                    $('#mForm').modal('hide');
                    $('#myForm')[0].reset();
                    dataTable.ajax.reload();
                    notifsukses(submit.text);
                }
            } catch (error) {
                console.log(error);
                $('#mForm').modal('hide');
                notiferror('Error Update To Database');
            }
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
                        notiferror_a(response.text);
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
                    console.log(error.responseText);
                    $('#xStatement').modal('hide');
                    // notiferror('KNP ERROR?');
                    notiferror('Error Update To Database');
                }
            });

        });
        
        $('#example1').on('click', '.bEdit',async function() {
            var id = $(this).attr('id');
            $(this).tooltip('hide');
            try {
                submit = await submitForm('get', dp.link+'/editTolerance',{id: id});
                resetRadio();
                $("#myForm").data('validator').resetForm();
                $('#myForm')[0].reset();
                $('#mForm').find('input[name=in_start]').val(submit.tolerance_in_start);
                $('#mForm').find('input[name=in_end]').val(submit.tolerance_in_end);
                $('#mForm').find('input[name=out_start]').val(submit.tolerance_out_start);
                $('#mForm').find('input[name=out_end]').val(submit.tolerance_out_end);
                $('#mForm').find('textarea[name=description]').val(submit.description);
                $('#mForm').find('.modal-title').text('Update Toleransi');
                $('#myForm').attr('action', dp.link+'/updateTolerance/'+id);
                $('#mForm').modal('show');
                if(submit.status==1){
                    $('#xTrue').prop('checked', true).uniform();
                }else{
                    $('#xFalse').prop('checked', true).uniform();
                }
            } catch (error) {
                console.log(error);
                $('#mForm').modal('hide');
                notiferror('Error Update To Database');
            }
        });
    }

    if(dp.permC==1){
        $('#example1').on('click', '.bDelete', function(){
            var id    = $(this).attr('id');
            var Name = $(this).attr('data');
            $('#deleteModal').modal('show');
            $('#deleteModal').find('.infoDelete').text('Acc Customer : '+Name);
            //prevent previous handler - unbind()
            $('#btnDelete').unbind().click(async function() {
                try {
                    submit = await submitForm('post', dp.link+'/deleteToleran', {
                        csrfsession: dp.csrf,
                        id: id
                    });
                    $('#deleteModal').modal('hide');
                    if(submit.success){
                        notifsukses(submit.text);
                        dataTable.ajax.reload();
                    }else{
                        notiferror(submit.text);
                    }
                } catch (error) {
                    console.log(error);
                    $('#deleteModal').modal('hide');
                    notiferror('Error Delete To Database');
                }
            });
        });    
    }

    var dataTable = $('#example1').DataTable({ 
        processing: true, 
        serverSide: true,
        scrollx   : true,
        responsive: false,
        ajax: {
            url: dp.link+"/get_tolerance",
            type: "POST",
            data: function(data){
                data.csrfsession = dp.csrf;
                data.shift_idx = dp.shift_idx;
            },
            error: function(error){
                console.log(error);
            }
        },
        columnDefs: [
            {targets: [0,1,2,3,4,-1], orderable: false,},
            {className: "text-center", targets:[0,1,2,3,4]}
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
            $('.bSetup').each(function () {
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
        }
    });
        
});
function resetRadio(){
    $('#mForm').find('input[name=tStatus]').prop('checked', false).uniform();
}
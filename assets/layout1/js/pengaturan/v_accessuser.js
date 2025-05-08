let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
// console.log(dp);
document.body.classList.add("sidebar-collapse");
$(document).ready(function() {
    if(dp.lock==1){
        $('#btnAdd').click(function() {
            $('#mForm').modal('show');
            $('#mForm').find('.modal-title').text('Add New Daftar Access Level');
            $("#myForm").data('validator').resetForm();
            $('#myForm')[0].reset();
            $('#btnAdd').tooltip('hide');
            $(".select-search").select2().val('').trigger('change.select2');
            document.getElementById("tGetTable").style.display = "none";
            document.getElementById("bHidden").style.display = "none";
            document.getElementById("tMessage").style.display = "none";
        });
    }
    $('.tMenu').on('change',function() {
        $('select[name=tSubmenu]').val('');
    });

    $('.tSubmenu').on('change',function() {
        $('select[name=tMenu]').val('');
    });

    $('.bGetdata').click(function() {
        getDataModul();
    });

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
                // console.log(response);
                if (response.error) {
                }else if(response.status){
                    $('#mForm').modal('hide');
                    if(response.text){
                        notiferror_a(response.text);
                    }else{
                        notiferror_a('Error Update Access User To Database');
                    }
                }else{
                    $('#mForm').modal('hide');
                    if (response.type == 'add') {
                        var type = 'Added'
                        // console.log(response.message);
                    } else if (response.type == 'update') {
                        var type = "Updated"
                    }
                    notifsukses('Data Access User ' + type + ' Successfully');
                    dataTable.ajax.reload();
                    // setTimeout(function () { location.reload(1); }, 2000);
                    $("#myForm").data('validator').resetForm();
                    $('#myForm')[0].reset();
                }
            },
            error: function(error) {
                console.log(error);
                $('#mForm').modal('hide');
                notiferror('Error Update To Database');
            }
        });

    });
    if(dp.permC==1){
        $('#example2').on('click', '.bEdit', function() {
            var id = $(this).attr('id');
            $('.bEdit').tooltip('hide');
            start();
            $.ajax({
                type: 'ajax',
                method: 'get',
                url: dp.link+'/editaccessuser',
                data: {
                    id: id
                },
                async: true,
                dataType: 'json',
                success: function(data) {
                    if(data===false){
                        errordatabase();
                    }else{
                        $('#mForm').find('.modal-title').text('Edit Daftar Access User');
                        // console.log('<?//= $link; ?>/updateUser/'+id);
                        $("select[name=tUname]").select2().val(data.access_level_id).trigger('change.select2');
                        $("select[name=tMenu]").select2().val(data.access_menu_id).trigger('change.select2');
                        $('select[name=tOffice]').select2().val(data.access_office_id).trigger('change.select2');
                        // if(data.access_rolemenu==1){
                        // 	$('select[name=tMenu]').val(data.access_menu_id);
                        // 	$('select[name=tSubmenu]').val('');
                        // }else{
                        // 	$('select[name=tMenu]').val('');
                        // 	$('select[name=tSubmenu]').val(data.access_menu_id);
                        // }
                        document.getElementById("bHidden").style.display = "";
                        document.getElementById("tGetTable").style.display = "";
                        document.getElementById("tMessage").style.display = "none";
                        getDataModul();
                        end();
                        setTimeout(function() {$('#mForm').modal('show');}, lama_akses+500);
                    }
                },
                error: function() {
                    $('#mForm').modal('hide');
                    notiferror('Error Update To Database');
                }
            });
        });
    }
    if(dp.permX==1){

        $('#example2').on('click', '.bDelete', function() {
            var id    = $(this).attr('id');
            var Name = $(this).attr('data');
            $('#deleteModal').modal('show');
            $('#deleteModal').find('.infoDelete').text('Menu : '+Name);
            $('#btnDelete').unbind().click(function() {
                $.ajax({
                    type: 'ajax',
                    method: 'get',
                    async: true,
                    url: dp.link+'/deleteAccessUser',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        if(response.success){
                            notifsukses('Access User <strong>'+Name+'</strong> Delete Successfully');
                            dataTable.ajax.reload();
                            // setTimeout(function () { location.reload(1); }, 2000);
                        }else{
                            if(response.text){
                                notiferror(response.text);
                            }else{
                                notiferror('Error Delete Access User To Database');
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

    // CONTOH MODEL LAIN DATATABLE SERVERSIDE
    var dataTable = $('#example2').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url: dp.link+"/posts",
            dataType: "json",
            type: "POST",
            data: function(data){
                data.csrfsession = dp.csrf;
                console.log(data);
            },
            error: function(error) {
                console.log(error);
            },
        },
        columns: [
                { data: "level_id",className: "text-center"},
                { data: "level_alias" },
                { data: "menu_alias" },
                { data: "submenu" },
                { data: "permissions" },
                { data: "aksi",className: "text-center",orderable: false },
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
        }	 

    });
    
});

function getDataModul(){
    var data = $('#myForm').serialize();
    // console.log(data);
    $.ajax({
        type: 'ajax',
        method: 'post',
        url: dp.link+'/getAccessModul',
        data: data,
        dataType: 'json',
        success: function(response) {
            // console.log(response);
            if(response.error){
                document.getElementById("tMessage").style.display = "";
                // $('#tFeedbackUname').html('Error This Field Is Required');
                $('#tMessage').html('Failed!! <br>'+response.message);
            }else if(response.status){
                document.getElementById("tMessage").style.display = "";
                $('#tMessage').html('Data Is Not Found');
                $('#result').html('');
                document.getElementById("bHidden").style.display = "none";
                document.getElementById("tGetTable").style.display = "none";
            }else{
                document.getElementById("bHidden").style.display = "";
                document.getElementById("tGetTable").style.display = "";
                document.getElementById("tMessage").style.display = "none";
                $('#result').html(response.res_tr);
            }
            
        },
        error: function(error) {
            console.log(error);
            notiferror('Error Get Modul To Database');
        }
    });
}

function getClick(index) {
    var checkboxes = document.getElementsByClassName($(index).attr('id'));

    if (index.checked) {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox' ) {
                // if(!$(checkboxes).attr('disabled')){
                // 	checkboxes[i].checked = true;
                // }
                checkboxes[i].checked = true;
            }
        }
    }else{
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
            checkboxes[i].checked = false;
            }
        }
    }
}

function get2Click(index) {
    // var checkboxes = document.getElementsById($(index).attr('class'));
    let checkboxes2 = $('#'+$(index).attr('class')).attr('id');
    // let checkboxes = $('#'+$(index).attr('class'));
    // console.log(checkboxes)
    if (index.checked) {
        let ini = document.getElementById(checkboxes2);
        ini.checked = true;
    }
}
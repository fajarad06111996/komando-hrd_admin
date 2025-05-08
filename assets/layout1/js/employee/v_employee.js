let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
document.body.classList.add("sidebar-collapse");
var submit,notif;
$(document).ready(function() {
    // $('#btnAdd').click(function() {
    // 	$('#mForm').modal('show');
    // 	$('#btnAdd').tooltip('hide');
    // 	$('#mForm').find('.modal-title').text('Tambah Jabatan');
    // 	$('#myForm').attr('action', '<?//= $link; ?>/addPosition');
    // 	$("#myForm").data('validator').resetForm();
    // 	$('#response_result').html('');
    // 	$('#myForm')[0].reset();
    //     resetRadio();
    // 	$(".select-search").select2({width: '100%'}).val('').trigger('change.select2');
    // 	$('#xTrue').prop('checked', true).uniform();
    // });
    if(dp.permC==1){
        $('#example1').on('click', '.bStatus', function() {
            // spinnerdarkDT(this);
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
        
        $('#myForm').submit(async function(e) {
            e.preventDefault();
            var url = $('#myForm').attr('action');
            var data = $('#myForm').serialize();
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
        
        $('#stateForm').submit(async function(e) {
            e.preventDefault();
            var url = $('#stateForm').attr('action');
            var data = $('#stateForm').serialize();

            try {
                submit = await submitForm('POST', url, data);
                if (submit.error) {
                    $('#xStatement').modal('hide');
                    notiferror_a('Error Update User To Database1');
                }else if(submit.status){
                    $('#xStatement').modal('hide');
                    notiferror_a(submit.text);
                }else{
                    $('#xStatement').modal('hide');
                    if (submit.type == 'add') {
                        var type = 'Added'
                        notifsukses('Data ' + type + ' Successfully');
                    } else if (submit.type == 'update') {
                        var type = "Updated"
                        notifsukses('Data ' + type + ' Successfully');
                    } else if (submit.type == 'create') {
                        var type = "Created"
                        notifsukses('Data ' + type + ' Successfully');
                    } else if (submit.type == 'change') {
                        var type = "Changed"
                        notifsukses('Data ' + type + ' Successfully');
                    }else{
                        notifsukses(submit.text);
                    }
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
        
        $('#example1').on('click', '.bEdit',async function() {
            var id = $(this).attr('id');
            window.location.replace(dp.link+'/formEdit/'+id);
        });
        
        $('#example1').on('click', '.bDoc',async function() {
            var id = $(this).attr('id');
            window.location.replace(dp.link+'/formDoc/'+id);
        });
        $('#example1').on('click', '.bCreate', function() {
            var id = $(this).attr('id');
            var data = $(this).attr('data');
            $('#xStatement').modal('show');
            $('#xStatement').find('.modal-title').text('Create EMPLOYEE as USER Apps');
            $('#xStatement').find('.tBtn').text('Create ?');
            $('#xStatement').find('.tContent').text('Create EMPLOYEE as USER Apps,');
            $('#xStatement').find('.tName').text(data);
            $('#stateForm').attr('action', dp.link+'/createAsUser');
            $('input[name=tId]').val(id);
            $('input[name=tClCode]').val(data);
            // $("#stateForm").data('validator').resetForm();
            // $('#response_result').html('');
            $('#stateForm')[0].reset();
            // $(".select-search").select2({width: '100%'}).val('').trigger('change.select2');
            // document.getElementById("tUsername").readOnly = false;
            // xFalse();
        });
    }
    if(dp.permX==1){
        $('#example1').on('click', '.bDelete', function(){
            var id    = $(this).attr('id');
            var Name = $(this).attr('data');
            $('#deleteModal').modal('show');
            $('#deleteModal').find('.infoDelete').text('Karyawan : '+Name);
            //prevent previous handler - unbind()
            $('#btnDelete').unbind().click(async function() {
                try {
                    submit = await submitForm('get', dp.link+'/deleteEmployee', {id: id});
                    if(submit.success){
                        $('#deleteModal').modal('hide');
                        notifsukses(submit.text);
                        setTimeout(function () { location.reload(1); }, 2000);
                    }else{
                        $('#deleteModal').modal('hide');
                        notiferror('Error Delete To Database');
                    }
                } catch (error) {
                    console.log(error);
                    $('#deleteModal').modal('hide');
                    notiferror('Error Delete To Database');
                }
            });
        });    
    }

    // untuk show data
    var dataTable = $('#example1').DataTable({ 
        processing: true,       // Tampilkan loading saat proses ambil data
        serverSide: true,       // Data di-load dari server (bukan dari data statis di HTML)
        scrollx: true,          // Aktifkan horizontal scroll jika tabel melebar
        responsive: false,      // Responsiveness dimatikan
        ajax: {
            url: dp.link+"/get_ajax", // URL endpoint untuk ambil data (POST)
            type: "POST",
            data: function(data){
                data.csrfsession = dp.csrf; // Tambahkan CSRF token ke body POST
                // data.CSRFToken = $('input[name=token]').val();
            },
            error: function(error){ 
                console.log(error); // Log error jika gagal ambil data
            }
        },
        columnDefs: [
            {targets: [0,1,2,3,4,-1], orderable: false,},
            {className: "text-center", targets:[0,1,2,3,4,5]}
        ],
        preDrawCallback: function() {
            spinnerdarkDT(this); // Tampilkan loading spinner
        },
        language: {
            processing: ""
        },
        // setelah data ditampilkan
        fnDrawCallback: function() {
            spinnerdarkDT(this);
            stopdarkspinnerDT();
            $('.bEdit').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bDoc').each(function () {
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
            $('.bCreate').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
        }
    });

    Fancybox.bind("[data-fancybox]", {
        // Your custom options
    });   
});
function resetRadio(){
    $('#mForm').find('input[name=tStatus]').prop('checked', false).uniform();
}
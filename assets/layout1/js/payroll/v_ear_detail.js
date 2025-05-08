let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
document.body.classList.add("sidebar-collapse");
var notif, submit, valid, data, url;
$(document).ready(function() {
    getDataHeader();

    $('#myForm').submit(async function(e) {
        e.preventDefault();
        url     = $(this).attr('action');
        data    = $(this).serialize();
        valid   = $(this).data('validator').form();
        if(valid == false){
            stoplightspinner();
            return false;
        }
        try {
            submit = await submitForm('post', url, data);
            if(submit.status==true){
                stoplightspinner();
                notifsukses(submit.msg);
                dataTable2.ajax.reload();
                getDataHeader();
            }else{
                stoplightspinner();
                notiferror_a(submit.msg);
            }
        } catch (error) {
            stoplightspinner();
            console.log(error);
            notiferror('Koneksi error,<br> Cobalagi nanti.');
        }
    });
    $('#stateForm').submit(async function(e) {
        e.preventDefault();
        var url = $('#stateForm').attr('action');
        var data = $('#stateForm').serialize();
        try {
            const stateForm = await submitForm('post', url, data);
            if (stateForm.error) {
                $('#xStatement').modal('hide');
                notiferror_a(stateForm.text);
            }else if(stateForm.status){
                $('#xStatement').modal('hide');
                notiferror_a(stateForm.text);
            }else if(stateForm.success){
                $('#xStatement').modal('hide');
                $('#stateForm')[0].reset();
                notifsukses(stateForm.text);
                dataTable2.ajax.reload();
            }
        } catch (error) {
            console.log(error);
            $('#xStatement').modal('hide');
            notiferror('Koneksi error,<br> Cobalagi nanti.');
        }
    });
    // CONTOH LAIN MODEL DATATABLE SERVERSIDE

    var dataTable2  = $('#example2').DataTable({
        processing: true,
        serverSide: true,
        // scrollX	: true,
        responsive: false,
        ordering: false,
        ajax:{
            url: dp.link+"/get_ajax2",
            type: "POST",
            data: function(data){
                data.csrfsession  = dp.csrf;
                data.all_id  = dp.allowh_id;
            },
            error: function(error){
                console.log(error);
            }
        },
        columnDefs: [
            // {targets: [0,1,2,3,4,5,6,-1], orderable: false,},
            {className: "text-center", targets:[0,2,7,-1]},
            {className: "text-right", targets:[3,4,5,6,-1]}
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
            $('.bRemTo').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bLock').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bEdit').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bPrint').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
        }
        // CONTOH LAIN MODEL DATATABLE SERVERSIDE
    });
    $('div.dataTables_filter input').css('font-size', 10);
    $('div.dataTables_length select').css('font-size', 10);
});
async function getDataHeader(){
    try {
        submit = await submitForm('post', dp.link+'/getDataHeader/'+dp.allowh_id, {});
        if(submit.status==true){
            $('#allowance_code').val(submit.data.allowance_code);
            $('input[name=from]').val(submit.data.start);
            $('input[name=to]').val(submit.data.end);
            $('textarea[name=description]').val(submit.data.description);
        }else{
            notiferror(submit.msg);
        }
    } catch (error) {
        console.log(error);
        notiferror('Koneksi error,<br> Cobalagi nanti.');
    }
}
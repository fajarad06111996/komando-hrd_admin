let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
document.body.classList.add("sidebar-collapse");
var notif, submit, valid, data, url;
$(document).ready(async function() {
    // await getColumnHeader();
    getDataHeader();
    $('#myForm').submit(async function(e) {
        e.preventDefault();
        spinnersdark(this);
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
                notif = await notifsukses(submit.msg);
                if(notif.value==true){
                    location.reload();
                }
                // dataTable2.ajax.reload();
                // getDataHeader();
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
    DatatableButtonsHtml5.init();
    var dataTable2  = $('#umtx').DataTable({
        processing: true,
        serverSide: true,
        // scrollX	: true,
        responsive: false,
        ordering: false,
        search: {
            return: true
        },
        ajax:{
            url:  dp.link+"/get_ajax2",
            type: "POST",
            data: function(data){
                data.csrfsession    = dp.csrf;
                data.all_id         = dp.allowh_id;
                data.from           = dp.from;
                data.to             = dp.to;
            },
            error: function(error){
                console.log(error);
            }
        },
        createdRow: function(row, data, dataIndex) {
            data.forEach((absen, index) => {

                // Populate name cell
                $('td', row).eq(index).html(absen[0]);
                if(absen[1]==2){
                    $('td', row).eq(index).addClass('bg-sakit text-dark '+absen[1]);
                }else if(absen[1]==3 || absen[1]==4){
                    $('td', row).eq(index).addClass('bg-izin-cuti text-dark '+absen[1]);
                }else if(absen[1]==6){
                    $('td', row).eq(index).addClass('bg-telat1 text-dark '+absen[1]);
                }else if(absen[1]==7){
                    $('td', row).eq(index).addClass('bg-telat2 text-dark '+absen[1]);
                }else if(absen[1]==8){
                    $('td', row).eq(index).addClass('bg-cekin text-dark '+absen[1]);
                }else if(absen[1]==10){
                    $('td', row).eq(index).addClass('bg-lembur text-dark '+absen[1]);
                }else if(absen[1]==11){
                    $('td', row).eq(index).addClass('bg-piket text-dark '+absen[1]);
                }else if(absen[1]==0){
                    $('td', row).eq(index).addClass('bg-libur text-dark '+absen[1]);
                }else{
                    $('td', row).eq(index).addClass('text-dark '+absen[1]);
                }

                // Populate status cell with color based on status value
                // const statusCell = $('td', row).eq(statusIndex);
                // statusCell.text(absen[1]);
                // statusCell.css('background-color', absen[1] == 1 ? 'red' : 'blue');
            });
        },
        columnDefs: [
            {className: "text-right", targets: [-1,-2,-3,-4,-5,-6,-7,-8,-9]},
            {className: "wraping-text", targets: "_all"}
        ],
        buttons: {            
            dom: {
                button: {
                    className: 'btn btn-outline-danger p-1'
                }
            },
            buttons: [
                {
                    title: 'Report Absensi',
                    text: '<i class="fa fa-file-pdf"></i> PDF',
                    titleAttr: 'PDF',
                    action: function(e, dt, node, config){
                        window.open(dp.link+'/printUmtDetail/'+dp.allowh_id+'/'+dp.from+'/'+dp.to, '_BLANK');
                    }
                },
                {
                    title: 'Report Absensi 2',
                    text: '<i class="fa fa-file-pdf"></i> PDF2',
                    titleAttr: 'PDF 2',
                    action: function(e, dt, node, config){
                        window.open(dp.link+'/printUmtDetail2/'+dp.allowh_id+'/'+dp.from+'/'+dp.to, '_BLANK');
                    }
                }
            ]
        },
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
        }
        // CONTOH LAIN MODEL DATATABLE SERVERSIDE
    });
    // $('#umtx_filter input').unbind();
    // $('#umtx_filter input').bind('keypress', function (e) {
    //     if (e.keyCode === 13) { // Detect Enter key
    //         dataTable2.search(this.value).draw();
    //     }
    // });
    // $('#umtx_filter input').bind('input', function () {
    //     if (this.value === "") { // Check if input is empty
    //         dataTable2.search('').draw(); // Clear the search and reload data
    //     }
    // });
});
async function getColumnHeader(){
    try {
        submit = await submitForm('post', dp.link+"/get_header", {
            csrfsession: dp.csrf,
            all_id: dp.allowh_id,
            from: dp.from,
            to: dp.to
        });
        console.log(submit);
        var headerRow = '<tr>';
        submit.columns.forEach(function(column) {
            headerRow += '<th style="text-wrap: nowrap;">' + column + '</th>';
        });
        headerRow += '</tr>';
        console.log($('#umtx thead'));
        $('#umtx thead').html(headerRow);
    } catch (error) {
        console.log(error);
        notiferror('Koneksi error,<br> Cobalagi nanti.');
    }
}
async function getDataHeader(){
    try {
        submit = await submitForm('post', dp.link+'/getDataHeader/'+dp.allowh_id, {});
        if(submit.status==true){
            $('#allowance_code').val(submit.data.allowance_code);
            $('input[name=from]').val(submit.data.start);
            $('input[name=to]').val(submit.data.end);
            if(submit.data.status_rapel==1){
                $('#xFalse').prop('checked', false).uniform();
                $('#xTrue').prop('checked', true).uniform();
            }else{
                $('#xFalse').prop('checked', true).uniform();
                $('#xTrue').prop('checked', false).uniform();
            }
            $('textarea[name=description]').val(submit.data.description);
        }else{
            notiferror(submit.msg);
        }
    } catch (error) {
        console.log(error);
        notiferror('Koneksi error,<br> Cobalagi nanti.');
    }
}
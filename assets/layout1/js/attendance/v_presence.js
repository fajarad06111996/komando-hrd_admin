let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
console.log(dp);
document.body.classList.add("sidebar-collapse");
var notif, submit, dataPresence;
$(document).ready(function() {
    xFalseTrue();
    $('#tStartdate').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        formatDate:'Y-m-d',
    });
    $('#tUntildate').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        formatDate:'Y-m-d',
    });
    $('#bSync').click(async function(){
        $('#mSync').find('.modal-title').text('Syncron Data Absensi');
        $('#mySync').attr('action', dp.link+'/syncPresence');
        $("#mySync").data('validator').resetForm();
        $('#mySync')[0].reset();
        // $(".select-search").select2().val().trigger('change.select2');
        $('#bSync').tooltip('hide');
        modalDragShow('#mSync');
    });
    $('.table').on('click', '.kecualiin', function(){
        var attid = $(this).attr('attid');
        var empname = $(this).attr('empname');
        var tgl = $(this).attr('tgl');
        var statusExcept = $(this).attr('sexcept');
        $('#mExcept').find('.modal-title').text('Pengecualian Potongan');
        $('#mExcept').find('#emp_name').text(empname);
        $('#mExcept').find('#tglna').text(tgl);
        $('#myExcept').attr('action', dp.link+'/exceptUm/'+attid);
        $('#myExcept')[0].reset();
        if(statusExcept==1){
            $('#xTrue').prop("checked", true).uniform();
            $('#xFalse').prop("checked", false).uniform();
        }else{
            $('#xTrue').prop("checked", false).uniform();
            $('#xFalse').prop("checked", true).uniform();
        }
        modalDragShow('#mExcept');
    });
    $('#mySync').submit(async function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();
        var valid = $(this).data('validator').form();
        if(valid==false){
            return;
        }
        try {
            submit = await submitForm('post', url, data);
            if(submit.status==true){
                notif = await notifsukses(submit.text);
                if(notif.value==true){
                    location.reload(1);
                }
            }else{
                notiferror(submit.text);
            }
        } catch (error) {
            console.log(error);
            // $('#mSync').modal('hide');
            notiferror('Error Update To Database');
        }
    });
    $('#myExcept').submit(async function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();
        try {
            submit = await submitForm('post', url, data);
            if(submit.status==true){
                $('#mExcept').modal('hide');
                notif = await notifsukses(submit.text);
                if(notif.value==true){
                    location.reload(1);
                }
            }else{
                notiferror(submit.text);
            }
        } catch (error) {
            console.log(error);
            notiferror('Error Update To Database');
        }
    });

    // untuk tampil nama karyawan ketika tombol select search di ketik
    var selectajax = $('.tEmployee').select2({
        ajax: {
            url: dp.link+'/getDataAjaxRemote',
            dataType: 'json',
            type: 'post',
            // delay: 250,
            data: function(params) {
            // console.log(params);
                return {
                    search: params.term,
                    page: params.page
                }
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: data.total_count
                    }
                };
            },
            error: function(error){
                console.log(error);
            },
            cache: true
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        },
        placeholder: 'ketik nama tujuan',
        minimumInputLength: 3,
    });
    if(dp.post==1){
        var selectajax2 = $(".js-data-example-ajax");
        $.Deferred(function(defer) {
            $.ajax({
                url: dp.link+'/getDataAjaxRemoteId/'+dp.employee_id,
                method: 'GET'
            }).then(defer.resolve, defer.reject);
        }).then(function(data){
            var option = new Option(data.items[1].text, data.items[1].id, true, true);
            selectajax2.append(option).trigger('change');
            // manually trigger the `select2:select` event
            selectajax2.trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            });
        }).catch(function(error) {
            // Error handler
            console.error('Error:', error);
        });
    }
    DatatableButtonsHtml5.init();
    $('.customTables-button').dataTable({
        responsive  : false,
        ordering: false,
        iDisplayLength: 25,
        columnDefs  : [
            {className : "text-center", targets:[0]}
        ],
        buttons: {            
            dom: {
                button: {
                    className: 'btn btn-outline-success p-1'
                }
            },
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: 'Report Absensi',
                    text: '<i class="fa fa-copy"></i> Copy',
                    titleAttr: 'Copy',
                    action: function(e, dt, node, config){
                        var that = this;
                        Swal.fire({
                            title: 'Auto close alert!',
                            html: 'I will close after <b></b> download start.',
                            // timer: 2000,
                            timerProgressBar: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        // Swal.showLoading();
                        setTimeout(function() { 
                            $.fn.DataTable.ext.buttons.copyHtml5.action.call(that, e, dt, node, config);
                            Swal.close();
                        }, 1000);
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: 'Report Absensi',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    titleAttr: 'Excel',
                    action: function(e, dt, node, config){
                        var that = this;
                        Swal.fire({
                            title: 'Auto close alert!',
                            html: 'I will close after <b></b> download start.',
                            // timer: 2000,
                            timerProgressBar: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        // Swal.showLoading();
                        setTimeout(function() { 
                            $.fn.DataTable.ext.buttons.excelHtml5.action.call(that, e, dt, node, config);
                            Swal.close();
                        }, 1000);
                    }
                },
                {
                    extend: 'csvHtml5',
                    title: 'Report Absensi',
                    text: '<i class="fa fa-file-excel-o"></i> CSV',
                    titleAttr: 'CSV',
                    action: function(e, dt, node, config){
                        var that = this;
                        Swal.fire({
                            title: 'Auto close alert!',
                            html: 'I will close after <b></b> download start.',
                            // timer: 2000,
                            timerProgressBar: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        // Swal.showLoading();
                        setTimeout(function() { 
                            $.fn.DataTable.ext.buttons.csvHtml5.action.call(that, e, dt, node, config);
                            Swal.close();
                        }, 1000);
                    }
                }
            ]
        }
    });
});

function xFalseTrue(){
    $('#xTrue').prop("checked", false).uniform();
    $('#xFalse').prop("checked", true).uniform();
}
let dataPassSidebar = document.currentScript.getAttribute('params');
let passSideBar = '#*ettkHRD2024*#';
let decSideBar = CryptoJSAesJson.decrypt(dataPassSidebar, passSideBar);
let dps = JSON.parse(decSideBar);
$(document).ready(function(){
    var notif,submit;
    if(dps.office_access==1){
        $('.bCompany').on('click', function() {
            modalDragShow('#xCompany');
            // $('#xCompany').modal('show');
            $('#xCompany').find('.modal-title').text('Change COMPANY');
            $('#xCompany').find('#companyChangeForm').attr('action', dps.link+'/auth/changeCompany');
            $(".select-search").select2({width: '100%'}).val(dps.office_id).trigger('change.select2');
        });
    }
    if(dps.counter_access==1){
        $('.bCounter').on('click', function() {
            $('#xCounter').modal('show');
            $('#xCounter').find('.modal-title').text('Change COUNTER');
            $('#xCounter').find('#counterForm').attr('action', dps.link+'/auth/changeCounter');
            $(".select-search").select2({width: '100%'}).val(dps.counter_id).trigger('change.select2');
        });
    }

    $('#xCompany').submit(async function(e) {
        e.preventDefault();
        spinnersdark($('#companyChangeForm'));
        var url     = $(this).find('#companyChangeForm').attr('action');
        var data    = $(this).find('#companyChangeForm').serialize();
        var url2    = dps.link;
        try {
            submit = await submitForm('post', url, data);
            $(this).modal('hide');
            if (submit.error) {
                stopspinnersdark();
                notiferror(submit.message);
            }else if(submit.status){
                stopspinnersdark();
                if(submit.text){
                    notiferror(submit.text);
                }else{
                    notiferror_a('Error Update Company User To Database');
                }
            }else{
                // $("#officeForm").data('validator').resetForm();
                // $('#officeForm')[0].reset();
                notifsukses(submit.text);
                window.location.replace(url2);
                // dataTable.ajax.reload();
                // setTimeout(function () { window.location.replace(url2); }, lama_akses+500);
            }
        } catch (error) {
            stopspinnersdark();
            console.log(error);
            $('#xOffice').modal('hide');
            notiferror('Error Update To Database');
        }
    });

    $('#xCounter').submit(async function(e) {
        e.preventDefault();
        spinnersdark($('#counterForm'));
        var url     = $('#xCounter').find('#counterForm').attr('action');
        var data    = $('#xCounter').find('#counterForm').serialize();
        var url2    = dps.link;
        try {
            submit = await submitForm('post', url, data);
            $('#xCounter').modal('hide');
            if (submit.error) {
                stopspinnersdark();
            }else if(submit.status){
                if(submit.text){
                    notiferror(submit.text);
                }else{
                    notiferror_a('Error Update Office User To Database');
                }
                stopspinnersdark();
            }else{
                // $("#officeForm").data('validator').resetForm();
                $('#counterForm')[0].reset();
                notifsukses('Counter ' + submit.type + ' Successfully');
                window.location.replace(url2);
                // dataTable.ajax.reload();
                // setTimeout(function () { window.location.replace(url2); }, lama_akses+500);
            }
        } catch (error) {
            stopspinnersdark();
            console.log(error);
            $('#xCounter').modal('hide');
            notiferror('Error Update To Database');
        }
    });
});
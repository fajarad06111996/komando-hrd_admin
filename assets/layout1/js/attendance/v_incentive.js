let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
document.body.classList.add("sidebar-collapse");
var notif, submit, cropper, cropperKtp, cropperNpwp, initData, fileFromInput, fileFromKtp, fileFromNpwp;
$(document).ready(function() {
    var avatar      = document.getElementById('img_photo');
    var inputFile   = document.getElementById('cmd_browse');
    $('#img_photo').click(function(){
        document.getElementById('cmd_browse').click();
    });
    $(inputFile).on('change', function (e) {
        Swal.fire({
            title: 'Please wait',
            imageUrl: dp.base_url+'assets/layout1/css/loader/campur/Flat-hourglass.gif',
            imageHeight: 100,
            showConfirmButton: false,
            allowOutsideClick: false
        });
        const file = this.files[0];
        if (file){
            let reader = new FileReader();
            reader.onload = async function(event){
                await $('#imageCropper').attr('src', event.target.result);
                $('#modalCropper').modal('show');
                Swal.close();
            }
            reader.readAsDataURL(file);
        }else{
            Swal.close();
        }
    });
    $('#modalCropper').on('shown.bs.modal', function () {
        var imageCropper = $('#imageCropper')[0];
        cropper = new Cropper(imageCropper, {
            aspectRatio: 1,
            viewMode: 2,
            scalable: true,
            zoomable: true,
            responsive: true,
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });
    $('#crop').click(async function(){
        Swal.fire({
            title: 'Please wait',
            imageUrl: dp.base_url+'assets/layout1/css/loader/campur/Flat-hourglass.gif',
            imageHeight: 100,
            showConfirmButton: false,
            allowOutsideClick: false
        });
        const worker = await Tesseract.createWorker('eng+ind');
        fileFromInput = inputFile.files[0];
        var initialAvatarURL;
        var canvas;
        if (cropper) {
            $('#modalCropper').modal('hide');
            canvas = cropper.getCroppedCanvas({
                maxWidth: 1000
            });
            initialAvatarURL = avatar.src;
            avatar.src = canvas.toDataURL();
            $('#temp_image').val(1);
            Swal.close();
        }
    });
    $('#detail_incentive_modal').on('click','#btnAdd', function(){
        var incid = $(this).attr('incid');
        var incdate = $(this).attr('incdate');
        $('#incForm').data('validator').resetForm();
        $('#incForm')[0].reset();
        $('#add_detail_incentive_modal').find('.modal-title').text('Add Detail Insentif '+incdate);
        $('#incForm').find('#img_photo').attr('src', dp.base_url+'assets/media/photos/foto_uploadx.png');
        $('#incForm').find('input[name=inc_idx]').val(incid);
        $('#incForm').find('input[name=inc_date]').val(incdate);
        $('#incForm').attr('action', dp.link+"/add_incentive_detail");
        modalDragShow('#add_detail_incentive_modal');
    });

    $('#detail_incentive_modal').on('click','.bEdit',async function(){
        var id = $(this).attr('id');
        var incid = $(this).attr('incid');
        var incdate = $(this).attr('incdate');
        $('#incForm').find('#img_photo').attr('src', '');
        $(this).tooltip('hide');
        try {
            submit = await submitForm('get', dp.link+'/get_incentive_detail', {
                id: id
            });
            if(submit==null){
                notiferror('data not found.');
            }else{
                $('#incForm').data('validator').resetForm();
                $('#incForm')[0].reset();
                $('#add_detail_incentive_modal').find('.modal-title').text('Edit Detail Insentif '+incdate);
                // $('#incForm').find('input[name=inc_idx]').val(incid);
                // $('#incForm').find('input[name=inc_date]').val(submit.incentive_date);
                if(submit.url_prof == null || submit.url_prof == ''){
                    $('#incForm').find('#img_photo').attr('src', dp.base_url+'assets/media/photos/foto_uploadx.png');
                }else{
                    $('#incForm').find('#img_photo').attr('src', submit.url_prof);
                }
                $('#incForm').find('input[name=value]').val(submit.value);
                $('#incForm').find('textarea[name=description]').val(submit.description);
                $('#incForm').find("select[name=employee_id]").select2({width: '100%'}).val(submit.employee_id_en).trigger('change');
                $('#incForm').attr('action', dp.link+"/update_incentive_detail/"+id);
                modalDragShow('#add_detail_incentive_modal');
            }
        } catch (error) {
            console.log(error);
        }
        // $('#incForm').data('validator').resetForm();
        // $('#incForm')[0].reset();
        // $('#add_detail_incentive_modal').find('.tTgl').text(incdate);
        // $('#incForm').find('input[name=inc_idx]').val(incid);
        // $('#incForm').attr('action', dp.link+"/adit_incentive_detail");
        // modalDragShow('#add_detail_incentive_modal');
    });

    $('#tbInsentif').on('click', '.bPublish', function() {
        spinnerdarkDT($('#tbInsentif'));
        var id = $(this).attr('id');
        var data = $(this).attr('data');
        var code = $(this).attr('code');
        $('#xStatement').modal('show');
        $('#xStatement').find('.modal-title').text('Konfirmasi');
        $('#xStatement').find('.tBtn').text('Konfirmasi ?');
        $('#xStatement').find('.tContent').text('Konfirmasi Insentif,');
        $('#xStatement').find('.tName').text(data);
        $('#stateForm').attr('action', dp.link+'/confirmIncentif');
        $('input[name=tId]').val(id);
        $('input[name=tClCode]').val(code);
        $("#stateForm").data('validator').resetForm();
        $('#stateForm')[0].reset();
    });

    $('#detail_incentive_modal').on('click', '.bSbmit', function() {
        spinnerdarkDT($('#tbInsentif'));
        var id = $(this).attr('incid');
        $('#xStatement').modal('show');
        $('#xStatement').find('.modal-title').text('Konfirmasi');
        $('#xStatement').find('.tBtn').text('Konfirmasi ?');
        $('#xStatement').find('.tContent').text('Konfirmasi Semua Insentif');
        // $('#xStatement').find('.tName').text(data);
        $('#stateForm').attr('action', dp.link+'/confirmAllIncentif');
        $('input[name=tId]').val(id);
        $("#stateForm").data('validator').resetForm();
        $('#stateForm')[0].reset();
    });

    $('#xStatement').on('hidden.bs.modal', function () {
        stopdarkspinnerDT();
    });

    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'dayGrid', 'timeGrid', 'interaction' ],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        defaultView: 'dayGridMonth',
        columnHeaderFormat: {
            weekday: 'long'
        }, 
        editable: true,
        selectable: true,
        selectHelper: true,
        editable: false,
        select:async function(info) {
            // console.log(info.startStr);
            // var valDate = moment(info.start).format('YYYY-MM-DD');
            try {
                submit = await submitForm('GET', dp.link+"/checkIncentiveByDate", {
                    now: info.startStr
                });
                if(submit.status==true){
                    $('#incHForm')[0].reset();
                    $('#incHForm').data('validator').resetForm();
                    $('#incHForm').find('input[name=incentive_date]').val(info.startStr);
                    $('#incHForm').attr('action', dp.link+"/save_incentive_header");
                    modalDragShow('#header_incentive_modal');
                }else{
                    console.log(submit.msg);
                    return;
                }
            } catch (error) {
                console.log(error);
                return;
            }
        },
        businessHours: true,
        events: async function(info, successCallback, failureCallback){
            var monthNow = moment(info.start).add(12, 'd').format('M');
            var yearNow = moment(info.start).add(12, 'd').format('YYYY');
            try {
                submit = await submitForm('POST', dp.link+'/getIncentive', {
                    csrf_tokenx: dp.csrf2,
                    month: monthNow,
                    year: yearNow
                });
                // console.log(submit);
                if(submit.length>0){
                    var events = [];
                    $.each(submit, function(i, item) {
                        events.push({
                            id: item.id,
                            title: item.title,
                            start: item.start, // will be parsed
                            end: item.end, // will be parsed
                            color: '#af1d1d'
                        });
                    });
                    successCallback(events);
                }else{
                    failureCallback(submit);
                }
            } catch (error) {
                failureCallback(error);
                console.log(error);
            }
        },
        eventClick: async function (event) {
            $('#event_name').val('');
            $('#event_date').val('');
            // $('#event_start_date').val('');
            // $('#event_end_date').val('');
            var id = event.event.id;
            var dateEv = moment(event.event.start).format('YYYY-MM-DD');
            // var start = moment(event.event.start).format('YYYY-MM-DD hh:mm:ss');
            // var end = moment(event.event.end).format('YYYY-MM-DD hh:mm:ss');

            try {
                const editEventSubmit = await submitForm('get', dp.link+"/get_incentive", {id: id});
                var dateEv2 = moment(editEventSubmit.start).format('YYYY-MM-DD');
                var dateInc = moment(editEventSubmit.start).format('DD-MMM-YYYY');
                $('#description').val(editEventSubmit.description);
                $('#incentive_date').val(dateEv2);
                $('#detail_incentive_modal').find('.bSbmit').attr('incid', id);
                $('#detail_incentive_modal').find('#btnAdd').attr('incid', id);
                $('#detail_incentive_modal').find('#btnAdd').attr('incdate', dateInc);
                // $('#event_start_date').val(editEventSubmit.start);
                // $('#event_end_date').val(editEventSubmit.end);
                $('#detail_incentive_modal').find('.tInsentif').text(dateInc);
                dataTable.ajax.reload();
                modalDragShow('#detail_incentive_modal');
            } catch (error) {
                console.log(error);
            }
        },
        // eventDrop: async function (event) {
        //     var id = event.event.id;
        //     var dateEv3 = moment(event.event.start).format('YYYY-MM-DD');
        //     // var start = moment(event.event.start).format('YYYY-MM-DD HH:mm:ss');
        //     // var end = moment(event.event.end).format('YYYY-MM-DD HH:mm:ss');

        //     // console.log(start);
        //     // console.log(end);
        //     try {
        //         const submitDrop = await submitForm('post', dp.link+'/updateDrop_event', {
        //             id: id,
        //             date: dateEv3
        //             // start: start,
        //             // end: end
        //         });
        //         console.log(submitDrop);
        //     } catch (error) {
        //         console.log(error);
        //         var notifErr = await notiferror('internet error.');
        //         if(notifErr.value==true){
        //             // location.reload(1);
        //             calendar.refetchEvents();
        //         }
        //     }
        //     // {description: "Lecture", department: "BioChemistry"}
        // }
    });

    calendar.render();

    $('#incHForm').submit(async function(e){
        e.preventDefault();
        var validator = $(this).data('validator').form();
        console.log(validator);
        if(validator == false){
            return;
        }

        try {
            var url = $(this).attr('action');
            var data = $(this).serialize();
            submit =  await submitForm('post', url, data);
            console.log(submit);
            $('#header_incentive_modal').modal('hide');
            if(submit.status == true)
            {
                notif = await notifsukses(submit.msg);
                if(notif.value==true){
                    // location.reload();
                    calendar.refetchEvents();
                }
            }
            else
            {
                notiferror(submit.msg);
            }
        } catch (error) {
            console.log(error);
        }
    });

    $('#incForm').submit(async function(e){
        e.preventDefault();
        var validator = $(this).data('validator').form();
        // console.log(validator);
        if(validator == false){
            return;
        }

        try {
            var karyawanUpload = await imgKaryawan();
            // $(".se-pre-con2").css('display', 'block');
            if(karyawanUpload.status==false){
                $(".se-pre-con2").css('display', 'none');
                notiferror(karyawanUpload.data.message);
                return false;
            }
            var url = $(this).attr('action');
            var data = $(this).serialize();
            submit =  await submitForm('post', url, data);
            $('#add_detail_incentive_modal').modal('hide');
            if(submit.status == true)
            {
                notif = await notifsukses(submit.msg);
                if(notif.value==true){
                    $(".se-pre-con2").css('display', 'none');
                    // location.reload();
                    dataTable.ajax.reload();
                }
            } else {
                $(".se-pre-con2").css('display', 'none');
                notiferror(submit.msg);
            }
        } catch (error) {
            $(".se-pre-con2").css('display', 'none');
            console.log(error);
        }
    });

    $('#stateForm').submit(async function(e) {
        e.preventDefault();
        var url = $('#stateForm').attr('action');
        var data = $('#stateForm').serialize();

        try {
            submit = await submitForm('POST', url, data);
            if(submit.status==true){
                $('#xStatement').modal('hide');
                notifsukses(submit.text);
                dataTable.ajax.reload();
                // setTimeout(function () { location.reload(1); }, 2000);
                $("#stateForm").data('validator').resetForm();
                $('#stateForm')[0].reset();
            }else{
                $('#xStatement').modal('hide');
                notiferror_a(submit.text);
            }
        } catch (error) {
            console.log(error);
            $('#xStatement').modal('hide');
            notiferror('Error Update To Database');
        }
    });

    var dataTable = $('#tbInsentif').DataTable({
        processing: true,
        serverSide: true,
        scrollx: true,
        responsive: false,
        ajax: {
            url: dp.link+"/get_tb_incentive",
            type: "POST",
            data: function(data) {
                var incid2 = $('#detail_incentive_modal').find('#btnAdd').attr('incid');
                data.csrfsession = dp.csrf;
                data.incid = incid2;
            },
            error: function(error) {
                console.log(error);
            }
        },
        columnDefs: [{
                targets: [0, 1, 2, 3, 4, -1],
                orderable: false,
            },
            {
                className: "text-center",
                targets: [0, 1, 2, 3, 4, -1]
            }
        ],
        preDrawCallback: function() {
            spinnerdarkDT(this);
        },
        language: {
            processing: ""
        },
        fnDrawCallback: function() {
            spinnerdarkDT(this);
            stopdarkspinnerDT();
            $('.bEdit').each(function() {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bSetup').each(function() {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bDelete').each(function() {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bStatus').each(function() {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bLockStatus').each(function() {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bConfirmed').each(function() {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bPublish').each(function() {
                $(this).tooltip({
                    html: true
                });
            });
        }
    });

    // Load library masMoney
    $('#incForm').on('focus','.moneyx',function(){
        $(this).maskMoney({
            // The symbol to be displayed before the value entered by the user
            prefix:'',
            // The suffix to be displayed after the value entered by the user(example: "1234.23 €").
            suffix:"",
            // Delay formatting of text field until focus leaves the field
            formatOnBlur:false,
            // Prevent users from inputing zero
            allowZero:false,
            // Prevent users from inputing negative values
            allowNegative:true,
            // Allow empty input values, so that when you delete the number it doesn't reset to 0.00.
            allowEmpty:false,
            // Select text in the input on double click
            doubleClickSelection:true,
            // Select all text in the input when the element fires the focus event.
            selectAllOnFocus:false,
            // The thousands separator
            thousands: ',',
            // The decimal separator
            decimal: '.' ,
            // How many decimal places are allowed
            precision: 0,
            // Set if the symbol will stay in the field after the user exits the field.
            affixesStay :false,
            // Place caret at the end of the input on focus
            bringCaretAtEndOnFocus:true
        });
    });

    Fancybox.bind("[data-fancybox]", {
        // Your custom options
    });
    
}); //end document.ready block

async function save_event()
{
    var event_name=$("#event_name").val();
    var event_start_date=$("#event_start_date").val();
    var event_end_date=$("#event_end_date").val();
    if(event_name=="" || event_start_date=="" || event_end_date=="")
    {
        alert("Please enter all required details.");
        return false;
    }
    try {
        submit =  await submitForm('post', dp.link+"/save_event", {event_name:event_name,event_start_date:event_start_date,event_end_date:event_end_date});
        console.log(submit);
        $('#event_entry_modal').modal('hide');
        if(submit.status == true)
        {
            notif = await notifsukses(submit.msg);
            location.reload();
        }
        else
        {
            notiferror(submit.msg);
        }
    } catch (error) {
        console.log(error);
    }
    return false;
}

async function imgKaryawan() {
    var temp_image  = $('#incForm').find('input[name=temp_image]').val();
    var url_image   = $('#incForm').find('input[name=url_image]').val();
    if(url_image==''){
        if(temp_image==1){
            const dSelfie   = new Date();
            let timeSelfie  = dSelfie.getTime().toString();
            var img         = document.getElementById('img_photo').src;
            var imgSplit    = img.split(',');
            if(imgSplit.length>1){
                var file        = document.getElementById('cmd_browse').files[0];
                var extens      = file.name.split(".").splice(-1);
                var filen       = timeSelfie;
                var filename    = filen + '.'+extens[0];
                // var filename    = 'DPTE/foto_pemilih/'+filen + '.'+extens[0];
                var ref         = 'KOMANDO/HRD/bukti_insentif';
                try {
                    upload = await uploadFirebaseBase64(dp.key_firebase, imgSplit[1], ref, filename, 'Upload Bukti');
                    if(upload.status==true){
                        result = upload.data;
                        await $('#incForm').find('input[name=url_image]').val(upload.data);
                        dataError = {
                            status: true,
                            data: {
                                serverResponse: true,
                                message: 'Uploaded'
                            }
                        }
                        return dataError;
                    }
                } catch (error) {
                    console.log(error);
                    dataError = {
                        status: false,
                        data: error
                    }
                    return dataError;
                }
            }else{
                dataError = {
                    status: false,
                    data: {
                        serverResponse: true,
                        message: 'No image set.'
                    }
                }
                return dataError;
            }
        }else{
            dataError = {
                status: true,
                data: {
                    serverResponse: true,
                    message: 'No upload.'
                }
            }
            return dataError;
        }
    }else{
        dataError = {
            status: true,
            data: {
                serverResponse: true,
                message: 'Image was set before.'
            }
        }
        return dataError;
    }
}
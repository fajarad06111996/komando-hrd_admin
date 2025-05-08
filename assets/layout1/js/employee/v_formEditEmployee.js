let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
document.body.classList.add("sidebar-collapse");
var submit,notif,tooltip;
$(document).ready(function() {
    // Setup Function Awal
    $("#designation_idx").chained("#department_idx");
    var selectajax2 = $(".searchBank");
    var avatar      = document.getElementById('img_photo');
    var inputFile   = document.getElementById('cmd_browse');
    var $modal      = $('#modal');
    var cropper, initData, fileFromInput;
    getDokumen();
    $('select[name=ot_id]').on('select2:open', function(e) {
        // Tambahkan tooltip saat hover pada setiap option
        setTimeout(function() {
            $('.select2-results__option').each(function() {
                // console.log($(this).data());
                var title = $(this).data().data.title;
                // var title = $('option[value="'+$(this).data().data.id+'"]').attr('title');
                if (title) {
                    $(this).attr('title', title);
                    $(this).attr('data-popup', 'tooltip');
                    $(this).attr('data-placement', 'right');
                    // Aktifkan Bootstrap Tooltip
                    var _this = this;
                    setTimeout(function() {
                        $(_this).tooltip({
                            html: true
                        });
                    }, 1);
                    
                }

            });
        }, 0);
    });
    $('select[name=ot_id]').on('select2:select', function(e){
        console.log(e.params.data._resultId);
        $('.tooltip.fade.show').remove();
    });
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
                await $('#image').attr('src', event.target.result);
                // cropper = new Cropper(image, {
                //     aspectRatio: 1,
                //     viewMode: 3,
                // });
                $('#modal').modal('show');
                Swal.close();
            }
            reader.readAsDataURL(file);
        }else{
            Swal.close();
        }
    });
    $('#modal').on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
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
            $('#modal').modal('hide');
            canvas = cropper.getCroppedCanvas({
                maxWidth: 800
            });
            initialAvatarURL = avatar.src;
            avatar.src = canvas.toDataURL();
            $('#temp_image').val(1);
            Swal.close();
        }
    });
    $('.date_input').datetimepicker({
        timepicker:false,
        format:'d-m-Y',
        formatDate:'Y-m-d'
    });
    $('.addNewData').click(async function(e){
        e.preventDefault();
        $('.addNewData').tooltip('hide');
        await $('table#itu').append(`
        <tr class="nah">
            <td>
                <select name="tBankName[]" data-placeholder="Bank" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc>
                    <option value="" >- Bank -</option>
                    <option value="1">BANK PERMATA</option>
                    <option value="2">BANK DANAMON</option>
                    <option value="3">BANK SINARMAS</option>
                    <option value="4">PANIN BANK</option>
                    <option value="5">BANK BISNIS</option>
                    <option value="6">BNI SYARIAH</option>
                    <option value="7">BANK BCA SYARIAH</option>
                    <option value="8">BANK MANDIRI</option>
                    <option value="9">BANK BRI</option>
                    <option value="10">BANK BCA</option>
                </select>
            </td>
            <td>
                <input type="text"  tabindex="17" name="tBranch[]" class="form-control form-control-sm" placeholder="Cabang" value="">
            </td>
            <td>
                <input type="text" tabindex="18" name="tOwner[]" class="form-control form-control-sm" placeholder="Nama Pemilik" value="">
            </td>
            <td>
                <input type="text" tabindex="19" name="tAccount[]" class="form-control form-control-sm" placeholder="Nomor Rekening" value="">
            </td>
            <td>
                <div class='btn-group'>
                    <h6><a href='javascript:void(0);' class='text-center badge badge-danger pupus' onclick="pupus(this)" data-popup="tooltip" title="Hapus rekening ini" data-placement="right"><i class="icon-cross2"></i></a></h6>
                </div>
            </td>
        </tr>
        `);
        $('.select-search').select2();
        $('.pupus').each(function () {
            $(this).tooltip({
                html: true
            });
        });
    });
    // Setup Function Awal Berakhir
    // Setup Function Menengah
    $('.bDadd').click(function(){
        $('.bDadd').tooltip('hide');
        $('#mFormDet').find('.modal-title').text('Tambah Rekening');
        $('#myFormDet').attr('action', dp.link+'/addBank');
        $('#myFormDet').find('select[name=tBankName]').select2().val('').trigger('change');
        selectajax2.select2({
            ajax: {
                type: 'post',
                url: dp.link+'/getBank',
                dataType: 'json',
                data: function(params){
                    return {
                        search: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.items
                    };
                },
                error: function(error){
                    console.log(error);
                },
                placeholder: 'ketik nama tujuan',
            }
        });
        // $('#myFormDet').data('validator').resetForm();
        // console.log($('#myFormDet').data('validator'));
        $('#myFormDet')[0].reset();
        $('#mFormDet').modal('show');
    });
    $('.bSaved').click(function(){
        $('#myForm').find('#savingOrder').click();
    });
    $('#mRekening').on('click', '.bEditDetail',async function(){
        $(this).tooltip('hide');
        var id = $(this).attr('id');
        try {
            submit = await submitForm('get', dp.link+'/getDataRekening',{id: id});
            $('#myFormDet').data('validator').resetForm();
            $('#myFormDet')[0].reset();
            $('#mFormDet').find('.modal-title').text('Edit Rekening');
            $('#myFormDet').attr('action', dp.link+'/updateBank/'+id);
            selectajax2.select2({
                ajax: {
                    type: 'post',
                    url: dp.link+'/getBank',
                    dataType: 'json',
                    data: function(params){
                        return {
                            search: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.items
                        };
                    },
                    error: function(error){
                        console.log(error);
                    },
                    placeholder: 'ketik nama tujuan',
                }
            });
            $.ajax({
                type: 'GET',
                url: dp.link+'/getBankId',
                data: {
                    id: submit.bank_enid
                }
            }).then(function (data) {
                // create the option and append to Select2
                var option = new Option(data.items[0].text, data.items[0].id, true, true);
                selectajax2.append(option).trigger('change');
                // manually trigger the `select2:select` event
                selectajax2.trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            }).catch(err => {
                console.log('this will be logged too');
                console.log(err); // bum related error
            });
            $('#mFormDet').find('input[name=tBranch]').val(submit.branch);
            $('#mFormDet').find('input[name=tOwner]').val(submit.account_name);
            $('#mFormDet').find('input[name=tAccount]').val(submit.account_id);
            $('#mFormDet').modal('show');
        } catch (error) {
            console.log(error);
        }
    });
    $('#myForm').submit(async function(e){
        e.preventDefault();
        const d     = new Date();
        let time    = d.getTime().toString();
        let emp_id  = $('input[name=employee_id]').val();
        var tmp     = $('#myForm').find('#temp_image').val();
        var json    = null;
        var valid   = $(this).data('validator').form();
        if(valid==false){
            return;
        }
        
        if(tmp==1){
            var karyawanUpload = await imgKaryawan();
            $(".se-pre-con2").css('display', 'block');
            if(karyawanUpload.status==false){
                $(".se-pre-con2").css('display', 'none');
                notiferror(karyawanUpload.data.message);
                return false;
            }
        }
        try {
            var url     = $(this).attr('action');
            var data    = $(this).serialize();
            submit      = await submitForm('post', url, data);
            if(submit.success){
                $(".se-pre-con2").css('display', 'none');
                notif = await notifsukses(submit.text);
                if(notif.value==true){
                    window.location.replace(submit.url);
                }
            }else if(submit.error){
                $(".se-pre-con2").css('display', 'none');
                notiferror_a(submit.message);
            }else{
                $(".se-pre-con2").css('display', 'none');
                notiferror_a(submit.text);
            }
        } catch (error) {
            $(".se-pre-con2").css('display', 'none');
            console.log(error);
            notiferror_a('Error update to database.');
        }
    });
    $('#myFormDet').submit(async function(e){
        e.preventDefault();
        var url     = $(this).attr('action');
        var data    = $(this).serialize();
        var valid   = $(this).data('validator').form();
        if(valid==false){
            return;
        }
        try {
            submit = await submitForm('POST', url, data);
            if(submit.success){
                $('#mFormDet').modal('hide');
                notif = await notifsukses(submit.text);
                detail.ajax.reload();
            }else if(submit.error){
                notiferror_a(submit.message);
            }else{
                notiferror_a(submit.text);
            }
        } catch (error) {
            console.log(error);
            notiferror_a('Error update to database.');
        }
    })
    // GET DETAIL SERVERSIDE
    var detail = $('#mRekening').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        paging: false,
        bInfo : false,
        autoWidth: true,
        // scrollX	: true,
        responsive: false,
        ajax:{
            url: dp.link+"/get_rekening",
            type: "POST",
            data: function(data){
                data.csrfsession    = dp.csrf;
                data.emp_id         = dp.employee_id;
                // data.CSRFToken = $('input[name=token]').val();
            },
                error:function(error){
                console.log(error);
            }
        },
        columns: [
            {className: "text-center"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,1,2,3,4]}
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
            $('.bEditDetail').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bDeleteDetail').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
        }
    });
    // Setup Function Menengah Berakhir

    // Load library masMoney
    $('#myForm').on('focus','.moneyx',function(){
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
});
jQuery(function($){
    $("input[name=tax_number]").mask("99.999.999.9-999.999");
});
async function getDokumen()
{
    try {
        submit      = await submitForm('get', dp.base_url+'employee/getDokumen2/'+dp.employee_id, {});
        await $('div#appendData').append(submit.data);
        console.log(submit);
    } catch (error) {
        console.log(error);
    }
}
async function imgKaryawan() {
    var temp_image  = $('#myForm').find('#temp_image').val();
    var url_image   = $('#myForm').find('#url_image').val();
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
                var ref         = 'KOMANDO/HRD/foto_karyawan';
                try {
                    upload = await uploadFirebaseBase64(dp.key_firebase, imgSplit[1], ref, filename, 'Upload Pemilih');
                    if(upload.status==true){
                        result = upload.data;
                        await $('input[name=url_image]').val(upload.data);
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
async function pupus(ini)
{
    $('.pupus').tooltip('hide');
    $(ini).closest('.nah').remove();
}
function removeError(e)
{
    $(e).closest('.form-group').find('.validation-invalid-label').remove();
}
function removeError2(e)
{
    $(e).closest('.remover').find('.validation-invalid-label').remove();
}
function removeError3(e)
{
    var idRemove = $(e).attr('id');
    $(e).closest('.remover').find('#'+idRemove+'-error').css('display', 'none');
}
function formatText (icon) {
    // $('input[name=tAccname]').val($(icon.element).data("icon"));
    if($(icon.element).data("icon")){
        return $('<span>'+icon.text+' <svg class="float-right top-top custom-tooltip" viewBox="64 64 896 896" focusable="false" data-icon="question-circle" width="1em" height="1em" fill="currentColor" aria-hidden="true" style="align-self: center; cursor: help;" data-popup="tooltip" title="'+$(icon.element).data("icon")+'" data-placement="right"><path d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448 448-200.6 448-448S759.4 64 512 64zm0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372 372 166.6 372 372-166.6 372-372 372z"></path><path d="M623.6 316.7C593.6 290.4 554 276 512 276s-81.6 14.5-111.6 40.7C369.2 344 352 380.7 352 420v7.6c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V420c0-44.1 43.1-80 96-80s96 35.9 96 80c0 31.1-22 59.6-56.1 72.7-21.2 8.1-39.2 22.3-52.1 40.9-13.1 19-19.9 41.8-19.9 64.9V620c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8v-22.7a48.3 48.3 0 0130.9-44.8c59-22.7 97.1-74.7 97.1-132.5.1-39.3-17.1-76-48.3-103.3zM472 732a40 40 0 1080 0 40 40 0 10-80 0z"></path></svg></span>');
    }else{
        return $('<span class="float-right top-top">-</span>');
    }
};
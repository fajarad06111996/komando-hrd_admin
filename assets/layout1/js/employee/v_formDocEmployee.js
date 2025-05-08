let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
document.body.classList.add("sidebar-collapse");
var submit,notif;
$(document).ready(function() {
    // Setup Function Awal
    var imgDefault  = dp.base_url+"assets/images/no_image.png";
    var selectajax2 = $(".searchBank");
    var avatar      = document.getElementById('img_photo');
    var inputFile   = document.getElementById('cmd_browse');
    var $modal      = $('#modal');
    var cropper, initData, fileFromInput;
    getDokumen();
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
        var imageCropper = $('#image')[0];
        cropper = new Cropper(imageCropper, {
            aspectRatio: NaN,
            viewMode: 3,
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });
    $('#crop').click(async function(){
        var classImg = $('#modal').find('div#savingClass')[0].classList;
        var srcImg      = $('img.'+classImg[0])[0];
        var inputBrowse = $('input[name='+classImg[1]+']')[0];
        var tempVal     = $('input[name='+classImg[3]+']');
        var tempValR    = $('input[name='+classImg[3]+']').closest('.nah').find('.tempR');
        var urlImg      = $('input[name='+classImg[3]+']').closest('.nah').find('.urlX').val();
        var updateFile  = $('input[name='+classImg[3]+']').closest('.nah').find('.updateX');
        Swal.fire({
            title: 'Please wait',
            imageUrl: dp.base_url+'assets/layout1/css/loader/campur/Flat-hourglass.gif',
            imageHeight: 100,
            showConfirmButton: false,
            allowOutsideClick: false
        });
        const worker = await Tesseract.createWorker('eng+ind');
        fileFromInput = inputBrowse.files[0];
        var initialAvatarURL = null;
        var canvas = null;
        if (cropper) {
            $('#modal').modal('hide');
            canvas = cropper.getCroppedCanvas({
                maxWidth: 800
            });
            initialAvatarURL = srcImg.src;
            srcImg.src = canvas.toDataURL();
            tempVal.val(1);
            tempValR.val(1);
            if(urlImg!=''){
                updateFile.val(1);
            }
            Swal.close();
        }
    });
    $('.date_input').datetimepicker({
        timepicker:false,
        format:'d-m-Y',
        formatDate:'Y-m-d'
    });
    $('.bAdd').click(function(){
        $(this).tooltip('hide');
        modalDragShow('#modalDoc');
    });
    $('#docForm').submit(async function(e){
        e.preventDefault();
        var valid   = $(this).data('validator').form();
        var data    = $(this).serializeArray();
        if(valid==false){
            return;
        }
        var inputDoc = data[0].value.replace(/\s/g, '_');
        // console.log(inputDoc);
        var duplicateCek = '';
        var cekEr = $('div#appendData').find('.text-identifier').map(function(){
            var textIdentifier = $(this).text();
            // console.log(textIdentifier);
            if(inputDoc.toLowerCase() == textIdentifier.toLowerCase()){
                // notiferror_a('nama '+inputDoc+' Sudah digunakan.');
                return false;
            }
        });
        if(cekEr.length > 0){
            // console.log(cekEr[0]);
            if(cekEr[0]==false){
                notiferror_a('nama '+data[0].value+' Sudah digunakan.');
                return;
            }
        }
        // if(inputDoc.toLowerCase() == duplicateCek.toLowerCase()){
        //     notiferror_a('nama '+inputDoc+' Sudah digunakan.');
        //     return;
        // }
        // console.log('aman');
        // return;
        await $('div#appendData').append(`
        <div class="col-lg-6 nah">
            <div class="form-group row justify-content-center mb-1">
                <label class="col-form-label text-right text-identifier" style="display: inline-grid;">`+inputDoc.toUpperCase()+`<a href="javascript:void(0);" class="bPupus" title="Hapus dokumen" data-placement="left" data-popup="tooltip" onclick="pupus(this)"><i class="icon-bin text-danger"></i></a></label>
                <div class="col-sm-8 text-left p-3">
                    <input type="file" class="cmdX" name="cmd_`+inputDoc.toLowerCase()+`" accept="image/*" style="display:none;" onchange="browseChange(this)">
                    <input type="hidden" class="nameFileX" name="name_file[]" value="`+inputDoc.toLowerCase()+`">
                    <input type="hidden" class="indexX" name="index_file[]" value="">
                    <input type="hidden" class="updateX" name="update_file[]" value="0">
                    <input type="hidden" class="extensionX" name="name_extension[]" value="">
                    <input type="hidden" class="urlZ" name="url_`+inputDoc.toLowerCase()+`" value="">
                    <input type="hidden" class="urlX" id="url_`+inputDoc.toLowerCase()+`" name="url_img[]" value="">
                    <input type="hidden" class="tempX" name="temp_`+inputDoc.toLowerCase()+`" value="0">
                    <input type="hidden" class="tempR" name="temp_image[]" value="0">
                    <img src="`+imgDefault+`" class="img_`+inputDoc.toLowerCase()+` rounded" style="width: 100%;" onclick="openBrowse(this)">
                </div>
            </div>
        </div>
        `);
        $(this).data('validator').resetForm();
        $(this)[0].reset();
        $('#modalDoc').modal('hide');
        console.log();
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
    $('#myForm').submit(async function(e){
        e.preventDefault();
        const d     = new Date();
        let time    = d.getTime().toString();
        var nah     = $('#myForm').find('.nah');
        // console.log(nah);
        await Promise.all(nah.map(async function(){
            var el = $(this);
            var fromBrowse  = el.find('.cmdX')[0].files[0];
            var fromExtens  = el.find('.extensionX');
            var fromUrl     = el.find('.urlX');
            var fromUrlVal  = el.find('.urlX').val();
            var fromTmp     = el.find('.tempX').val();
            var fromImg     = el.find('.rounded')[0];
            var fromName    = el.find('.text-identifier').text();
            var karyawanUpload = await imgKaryawan(fromBrowse, fromUrl, fromUrlVal, fromTmp, fromImg, fromName, fromExtens);
            $(".se-pre-con2").css('display', 'block');
            console.log(karyawanUpload);
            if(karyawanUpload.status==false){
                $(".se-pre-con2").css('display', 'none');
                notiferror(karyawanUpload.data.message);
                return false;
            }
        })).then(async (responseList) => {
            // console.log('jalan yg benar');
            console.log(responseList);
            // return;
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
        }).catch(error => {
            // console.log('tersesat');
            console.log(error);
            notiferror('Error connection,<br>Please try again later');
        });
    });
});
jQuery(function($){
    $("input[name=tax_number]").mask("99.999.999.9-999.999");
});
async function getDokumen()
{
    try {
        submit      = await submitForm('get', dp.base_url+'employee/getDokumen/'+dp.employee_id, {});
        await $('div#appendData').append(submit.data);
        $('.bPupus').each(function () {
            $(this).tooltip({
                html: true
            });
        });
        // console.log(submit);
    } catch (error) {
        console.log(error);
    }
}
async function openBrowse(ini)
{
    var cek = $(ini).closest('.nah').find('input[type=file]');
    cek[0].click();
    // console.log();
}
function browseChange(ini)
{
    var imgSource   = $(ini).closest('.nah').find('img')[0].classList;
    var browseFile  = $(ini).closest('.nah').find('input[type=file]').attr('name');
    var urlInput    = $(ini).closest('.nah').find('input.urlX').attr('id');
    var tempInput   = $(ini).closest('.nah').find('input.tempX').attr('name');
    // console.log(imgSource);
    Swal.fire({
        title: 'Please wait',
        imageUrl: dp.base_url+'assets/layout1/css/loader/campur/Flat-hourglass.gif',
        imageHeight: 100,
        showConfirmButton: false,
        allowOutsideClick: false
    });
    const file = ini.files[0];
    if (file){
        let reader = new FileReader();
        reader.onload = async function(event){
            await $('#image').attr('src', event.target.result);
            $('div#savingClass').attr('class', '');
            $('div#savingClass').addClass(imgSource[0]+' '+browseFile+' '+urlInput+' '+tempInput);
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
}
async function imgKaryawan(cmd, url, urlVal, temp, img, name, extension) {
    var json        = null;
    if(temp==1){
        const dSelfie   = new Date();
        let timeSelfie  = dSelfie.getTime().toString();
        var imgx        = img.src;
        var imgSplit    = imgx.split(',');
        if(imgSplit.length>1){
            var file        = cmd;
            var extens      = file.name.split(".").splice(-1);
            var filen       = timeSelfie;
            var filename    = filen + '.'+extens[0];
            // var filename    = 'DPTE/foto_pemilih/'+filen + '.'+extens[0];
            var ref         = 'KOMANDO/HRD/dokumen_karyawan';
            try {
                upload = await uploadFirebaseBase64(dp.key_firebase, imgSplit[1], ref, filename, 'Upload '+name);
                if(upload.status==true){
                    result = upload.data;
                    await extension.val(filename);
                    await url.val(upload.data);
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
    // if(urlVal==''){
    // }else{
    //     dataError = {
    //         status: true,
    //         data: {
    //             serverResponse: true,
    //             message: 'Image was set before.'
    //         }
    //     }
    //     return dataError;
    // }
}
async function pupus(ini)
{
    $(ini).closest('.nah').remove();
}
async function pupusx(ini)
{
    var el = $(ini).closest('.nah');
    var id      = el.find('.indexX').val();
    var Name    = el.find('.text-identifier').text();
    $('#deleteModal').modal('show');
    $('#deleteModal').find('.infoDelete').text('Dokumen : '+Name);
    //prevent previous handler - unbind()
    $('#btnDelete').unbind().click(function() {
        $.ajax({
            type: 'ajax',
            method: 'get',
            async: true,
            url: dp.link+'/deleteDocEmployee',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#deleteModal').modal('hide');
                if(response.success){
                    notifsukses(response.text);
                    location.reload(1);
                    // getDokumen();
                }else{
                    notiferror(response.text);
                }
            },
            error: function(error) {
                console.log(error);
                $('#deleteModal').modal('hide');
                notiferror('Error Delete To Database');
            }
        });
    });
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
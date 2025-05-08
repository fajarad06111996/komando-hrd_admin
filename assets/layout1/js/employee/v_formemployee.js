let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
document.body.classList.add("sidebar-collapse");
var submit, notif, cropper, cropperKtp, cropperNpwp, initData, fileFromInput, fileFromKtp, fileFromNpwp;
$(document).ready(function() {
    // Setup Function Awal
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
    $("#designation_idx").chained("#department_idx");
    var avatar      = document.getElementById('img_photo');
    var inputFile   = document.getElementById('cmd_browse');
    // var ktp         = document.getElementById('img_ktp');
    // var inputKtp    = document.getElementById('cmd_ktp');
    // var npwp        = document.getElementById('img_npwp');
    // var inputNpwp   = document.getElementById('cmd_npwp');
    var $modal      = $('#modal');
    $('#img_photo').click(function(){
        document.getElementById('cmd_browse').click();
    });
    // $('#img_ktp').click(function(){
    //     document.getElementById('cmd_ktp').click();
    // });
    // $('#img_npwp').click(function(){
    //     document.getElementById('cmd_npwp').click();
    // });
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
                $('input[name=initialData]').val(1);
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
    // $(inputKtp).on('change', function (e) {
    //     console.log(e);
    //     Swal.fire({
    //         title: 'Please wait',
    //         imageUrl: '<?//= base_url() ?>assets/layout1/css/loader/campur/Flat-hourglass.gif',
    //         imageHeight: 100,
    //         showConfirmButton: false,
    //         allowOutsideClick: false
    //     });
    //     const fileKtp = this.files[0];
    //     if (fileKtp){
    //         let readerKtp = new FileReader();
    //         readerKtp.onload = async function(eventKtp){
    //             await $('#image').attr('src', eventKtp.target.result);
    //             $('input[name=initialData]').val(2);
    //             // cropper = new Cropper(image, {
    //             //     aspectRatio: 1,
    //             //     viewMode: 3,
    //             // });
    //             $('#modal').modal('show');
    //             Swal.close();
    //         }
    //         readerKtp.readAsDataURL(fileKtp);
    //     }else{
    //         Swal.close();
    //     }
    // });
    // $(inputNpwp).on('change', function (e) {
    //     Swal.fire({
    //         title: 'Please wait',
    //         imageUrl: '<?//= base_url() ?>assets/layout1/css/loader/campur/Flat-hourglass.gif',
    //         imageHeight: 100,
    //         showConfirmButton: false,
    //         allowOutsideClick: false
    //     });
    //     const fileNpwp = this.files[0];
    //     if (fileNpwp){
    //         let readerNpwp = new FileReader();
    //         readerNpwp.onload = async function(eventNpwp){
    //             await $('#image').attr('src', eventNpwp.target.result);
    //             $('input[name=initialData]').val(3);
    //             // cropper = new Cropper(image, {
    //             //     aspectRatio: 1,
    //             //     viewMode: 3,
    //             // });
    //             $('#modal').modal('show');
    //             Swal.close();
    //         }
    //         readerNpwp.readAsDataURL(fileNpwp);
    //     }else{
    //         Swal.close();
    //     }
    // });
    $('#modal').on('shown.bs.modal', function () {
        var imageCropper = $('#image')[0];
        initData = $('input[name=initialData]').val();
        if(initData == 2 || initData == 3 ){
            cropper = new Cropper(imageCropper, {
                aspectRatio: 1.6/1,
                viewMode: 3,
            });
        }else{
            cropper = new Cropper(imageCropper, {
                aspectRatio: 1,
                viewMode: 3,
            });
        }
    }).on('hidden.bs.modal', function () {
        $('input[name=initialData]').val('');
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
        initData = $('input[name=initialData]').val();
        if(initData== 1){
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
        }else if(initData== 2){
            if (cropper) {
                $('#modal').modal('hide');
                canvas = cropper.getCroppedCanvas({
                    maxWidth: 800
                });
                initialAvatarURL = ktp.src;
                ktp.src = canvas.toDataURL();
                $('#temp_ktp').val(1);
                Swal.close();
            }
        }else if(initData== 3){
            if (cropper) {
                $('#modal').modal('hide');
                canvas = cropper.getCroppedCanvas({
                    maxWidth: 800
                });
                initialAvatarURL = npwp.src;
                npwp.src = canvas.toDataURL();
                $('#temp_npwp').val(1);
                Swal.close();
            }
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
    $('.bSaved').click(function(){
        $('#myForm').find('#savingOrder').click();
    });
    $('.searchBank').select2({
        ajax: {
            type: 'post',
            url: dp.link+'/getBank',
            dataType: 'json',
            data: function(params){
                console.log(params);
                return {
                    search: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data) {
                console.log(data);
                // params.page = params.page || 1;
                return {
                    results: data.items,
                    // pagination: {
                    //     more: (params.page * 5) < data.total_count
                    // }
                };
            },
            error: function(error){
                console.log(error);
            },
            placeholder: 'ketik nama tujuan',
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

        var karyawanUpload = await imgKaryawan();
        $(".se-pre-con2").css('display', 'block');
        if(karyawanUpload.status==false){
            $(".se-pre-con2").css('display', 'none');
            notiferror(karyawanUpload.data.message);
            return false;
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
});
jQuery(function($){
    $("input[name=tax_number]").mask("99.999.999.9-999.999");
});
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
async function cek()
{
    submit = await submitForm('get', dp.link+'/getBank',{});
    console.log(submit);
}
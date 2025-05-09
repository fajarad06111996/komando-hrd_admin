let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
// console.log(dp);
document.body.classList.add("sidebar-collapse");
var notif, submit;
$(document).ready(function() {
    setHead_startup();
    $('#starting_date').datetimepicker({
    	timepicker:false,
    	format:'Y-m-d H:i:s',
    	formatDate:'Y-m-d'
    });
    $("select[name=tSubAccount]").select2({
        width: "100%",
        templateSelection: formatText,
        templateResult: formatText
    });
    var avatar      = $('#img_photo');
    var inputFile   = $('#cmd_browse');

    avatar.click(function(){
        inputFile.click();
    });
    inputFile.on('change', function (e) {
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
                await $('#image_cropper').attr('src', event.target.result);
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
        var imageCropper = $('#image_cropper')[0];
        cropper = new Cropper(imageCropper, {
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
        fileFromInput = inputFile[0].files[0];
        var initialAvatarURL;
        var canvas;
        if (cropper) {
            $('#modal').modal('hide');
            canvas = cropper.getCroppedCanvas({
                maxWidth: 800
            });
            initialAvatarURL = avatar[0].src;
            avatar[0].src = canvas.toDataURL();
            $('#temp_image').val(1);
            Swal.close();
        }
    });
    $('#tSubAccount').change(async function(){
        console.log(this);
        var tSubAccountDisplay = $('.tSubAccount').css('display');
        $('input[name=accNo2]').val('');
        var segmentx = $('option:selected', this).data('segment');
        var data = $(this).val();
        if(tSubAccountDisplay=='flex'){
            try {
                const tsAcc = await submitForm('post', dp.link+'/getAccNo', {data: data, segment: segmentx, this_idx: dp.idx});
                console.log(tsAcc);
                if(tsAcc == null){
                    $('input[name=accNo2]').val('01');
                }else{
                    var lfPad = tsAcc.part_two.length;
                    console.log(dp.parent_idx);
                    console.log(tsAcc.en_idx);
                    if(dp.parent_idx == tsAcc.en_idx){
                        $('input[name=accNo1]').val(tsAcc.this_number_parent);
                        $('input[name=accNo2]').val(tsAcc.this_number_child);
                    }else{
                        if(tsAcc.part_two=='98' || tsAcc.part_two=='998' || tsAcc.part_two=='9998' || tsAcc.part_two=='99998'){
                            var part_two_extra = ++tsAcc.part_two;
                            $('input[name=accNo1]').val(tsAcc.organization_number);
                            $('input[name=accNo2]').val(leftPad(++part_two_extra, lfPad));
                        }else{
                            $('input[name=accNo1]').val(tsAcc.organization_number);
                            $('input[name=accNo2]').val(leftPad(++tsAcc.part_two, lfPad));
                        }
                    }
                }
            } catch (error) {
                console.log(error);
            }
        }
    });
    if(dp.acc==1){
        xFalse();
        $('#xTrue').prop('checked', true).uniform();
        $('#xOne').prop('checked', true).uniform();
        $('#headA').prop('checked', true).uniform();
        $('.tSubAccount').css('display', 'none');
        // $("#accNo1").prop("readonly",false);
        $('.accNo2').css('display', 'none');
        $('input:radio[name=tPosition]').change(function(){
            $('input[name=accNo2]').val('');
            if(this.checked && this.value == '1'){
                $('.tSubAccount').css('display', 'flex');
                $("select[name=tSubAccount]").select2({
                    width: "100%",
                    templateSelection: formatText,
                    templateResult: formatText
                }).val('').trigger('change.select2');
                // $('.accNo1').css('display', 'block');
                $('.accNo2').css('display', 'block');
                // $("#accNo1").prop("readonly",true);
                $("#accNo2").prop("required",true);
                $("#tSubAccount").prop("required",true);
            }else{
                $('.accNo2').css('display', 'none');
                $('.tSubAccount').css('display', 'none');
                $("#tSubAccount").select2().val('');
                $("#accNo2").prop("required",false);
                $("#tSubAccount").prop("required",false);
            }
        });
        $('input:radio[name=setHead]').change(function(){
            if(this.checked && this.value == '1'){
                $('.headName').css('display', 'none');
                $("input[name=head_name]").val('');
                $('.headOrganization').css('display', 'flex');
                $("select[name=head_organization]").select2().val('').trigger('change.select2');
            }else{
                $('.headName').css('display', 'flex');
                $("input[name=head_name]").val('');
                $('.headOrganization').css('display', 'none');
                $("select[name=head_organization]").select2().val('').trigger('change.select2');
            }
        });
    }else{
        $('input:radio[name=tPosition]').change(function(){
            $('input[name=accNo2]').val('');
            if(this.checked && this.value == '1'){
                $('.tSubAccount').css('display', 'flex');
                $("select[name=tSubAccount]").select2({
                    width: "100%",
                    templateSelection: formatText,
                    templateResult: formatText
                }).val(dp.parent_idx).trigger('change.select2');
                // $('.accNo1').css('display', 'block');
                $('.accNo2').css('display', 'block');
                $('#myForm').find('input[name=accNo2]').val(dp.number_child);
                // $("#accNo1").prop("readonly",true);
                $("#accNo2").prop("required",true);
                $("#accNo2").prop("readonly",true);
                $("#tSubAccount").prop("required",true);
            }else{
                $('.accNo2').css('display', 'none');
                $('.tSubAccount').css('display', 'none');
                $("#tSubAccount").select2().val('');
                $("#accNo2").prop("required",false);
                $("#tSubAccount").prop("required",false);
            }
        });
        $('input:radio[name=setHead]').change(function(){
            if(this.checked && this.value == '1'){
                $('.headName').css('display', 'none');
                $("input[name=head_name]").val('');
                $('.headOrganization').css('display', 'flex');
                $("select[name=head_organization]").select2().val(dp.employee_id).trigger('change.select2');
            }else{
                $('.headName').css('display', 'flex');
                $("input[name=head_name]").val(dp.head_name);
                $('.headOrganization').css('display', 'none');
                $("select[name=head_organization]").select2().val('').trigger('change.select2');
            }
        });
        var segmentx = $('input:radio[name=tPosition]:checked').val();
        console.log(segmentx);
        if (segmentx == 1) {
            $('.tSubAccount').css('display', 'flex');
            $("select[name=tSubAccount]").select2({
                width: "100%",
                templateSelection: formatText,
                templateResult: formatText
            }).val(dp.parent_idx).trigger('change.select2');
            $('.accNo2').css('display', 'block');
            $("#accNo2").prop("readonly",true);
            $("#accNo2").prop("required",true);
            $("#tSubAccount").prop("required",true);
        }else{
            $('.tSubAccount').css('display', 'none');
            $("#tSubAccount").select2().val('');
            $("#accNo2").prop("required",false);
            $("#tSubAccount").prop("required",false);
        }
        $('input:radio[name="tPosition"]').prop("disabled",true);
    }
    if(dp.edit==0){
        $('#start_balance').keyup(function(){
            var start_balance = $('#start_balance').val();
            $('#ending_balance').val(start_balance);
        });
    }
    $('.aClose').click(function(e) {
        $(".notifValidasi").css('display','none');
    });

    $('.bSbmit').click(function(){
        var q     = $('#myForm').get();
        var r     = $(q).find('#bSubmit');
        spinnerlight003(r);
        $('#bSubmit').click();
    });

    $('#myForm').submit(async function(e) {
        e.preventDefault();

        var tempImg = $(this).find('input[name=temp_image]').val();
        if(tempImg == 1){
            var organizationUpload = await imgOrganization();
            $(".se-pre-con2").css('display', 'block');
            if(organizationUpload.status==false){
                $(".se-pre-con2").css('display', 'none');
                notiferror(organizationUpload.data.message);
                return false;
            }
        }

        try {
            var url = $('#myForm').attr('action');
            var data = $('#myForm').serialize();
            const myForm = await submitForm('post', url, data);
            // console.log(myForm);
            stoplightspinner();
            $(".se-pre-con2").css('display', 'none');
            if (myForm.error) {
                $(".notifValidasi").css('display','block');
                $('.print-error-msg').html(myForm.message);
                // notiferror(myForm.message);
            }else if(myForm.status){
                $(".notifValidasi").css('display','block');
                $('.print-error-msg').html(myForm.text);
                // notiferror_a(myForm.text);
            }else{
                $(".notifValidasi").css('display','none');
                notif = await notifsukses(myForm.text);
                if(notif.value==true){
                    location.replace(myForm.link);
                }
            }
        } catch (error) {
            stoplightspinner();
            console.log(error);
            $(".se-pre-con2").css('display', 'none');
            $(".notifValidasi").css('display','block');
            $('.print-error-msg').html(error);
        }
    });
});
function xFalse(){
    $('#xFalse').prop('checked', false).uniform();
    $('#xTrue').prop('checked', false).uniform();
    $('#headA').prop('checked', false).uniform();
    $('#headB').prop('checked', false).uniform();
}
function xSegment(){
    $('#xOne').prop('checked', false).uniform();
    $('#xTwo').prop('checked', false).uniform();
    $('#xThree').prop('checked', false).uniform();
    $('#xFour').prop('checked', false).uniform();
}
function leftPad(number, targetLength) {
    var output = number + '';
    var tLength = targetLength == 1 ? 2 : targetLength;
    while (output.length < tLength) {
        output = '0' + output;
    }
    return output;
}
function setHead_startup()
{
    var setHead = $('#myForm').find('input:radio[name=setHead]:checked');
    if(setHead.val() == '1'){
        $('.headName').css('display', 'none');
        $("input[name=head_name]").val('');
        $('.headOrganization').css('display', 'flex');
        $("select[name=head_organization]").select2().val(dp.employee_id).trigger('change.select2');
    }else{
        $('.headName').css('display', 'flex');
        $("input[name=head_name]").val(dp.head_name);
        $('.headOrganization').css('display', 'none');
        $("select[name=head_organization]").select2().val('').trigger('change.select2');
    }
}
function formatText (props) {
    var propsxyz;
    if("element" in props){
        propsxyz = $('<span>' + props.text + ' <span class="float-right top-top badge badge-danger">Segment ' + $(props.element).data('segment') + '</span></span>')
    }else{
        propsxyz = $('<span>' + props.text + '</span>')
    }
    return propsxyz;
};
async function imgOrganization() {
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
        var ref         = 'KOMANDO/HRD/foto_organization';
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
}
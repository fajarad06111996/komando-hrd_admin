let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
$(document).ready(function(){
    $(".se-pre-con2").css('display', 'none');
    $('.bForgot').click(function(e){{
        e.preventDefault();
        console.log('oke');
        $('#xForgot').modal('show');
        $('#xForgot').find('.modal-title').text('Forgot Password');
        $('#xForgot').find('.tBtn').text('Send ?');
        $('#forgotForm').attr('action',dp.base_url+'auth/forgotPassword');
    }});
    $('#forgotForm').submit(function(e) {
        startforgot();
        e.preventDefault();
        var url = $('#forgotForm').attr('action');
        var data = $('#forgotForm').serialize();
        $.ajax({
            type: 'ajax',
            method: 'post',
            url: url,
            data: data,
            async: true,
            dataType: 'json',
            success: function(response) {
                endforgot();
                console.log(response);
                if (response.error) {
                }else if(response.status){
                    $('#xForgot').modal('hide');
                    if(response.text){
                        notiferror(response.text);
                    }else{
                        notiferror_a('Error Update Access User To Database');
                    }
                }else{
                    $('#xForgot').modal('hide');
                    notifsukses(response.text);
                    // dataTable.ajax.reload();
                    // setTimeout(function () { location.reload(1); }, 2000);
                    $("#forgotForm").data('validator').resetForm();
                    $('#forgotForm')[0].reset();
                }
            },
            error: function(error) {
                console.log(error);
                endforgot();
                $('#xForgot').modal('hide');
                  notiferror('Error Update To Database');
            }
        });
    });
    $('#formLogin').submit(async function(e){
        e.preventDefault();
        // console.log(e);
        var url     = $(this).attr('action');
        var data    = $(this).serialize();
        var valid   = $(this).data('validator').form();
        if(valid==false){
            return;
        }
        try {
            submit = await submitForm('post', url, data);
            
            console.log(submit);
            // return;
            if(submit.status == true){
                $(".se-pre-con2").css('display', 'none');
                notif = await notifsukses(submit.msg);
                if(notif.value==true){
                    location.replace(submit.link);
                }
            }else{
                $(".se-pre-con2").css('display', 'none');
                notiferror(submit.msg);
            }
        } catch (error) {
            $(".se-pre-con2").css('display', 'none');
            console.log(error);
            notiferror('Error Update To Database');
        }
    });
    // $('.bLogin').click(function(){
    // 	// console.log('diklik');
    //     $(".se-pre-con2").css('display', 'block');
    // });
    $('#login-password').on('keyup',function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $('.bLogin').click();
            $(".se-pre-con2").css('display', 'block');
        }
    });
    $('.tUname').on('keyup',function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $('.bLogin').click();
            $(".se-pre-con2").css('display', 'block');
        }
    });
});
function myFunction()
{
    var x = document.getElementById("login-password");
    if (x.type === "password") { x.type = "text"; }
    else { x.type = "password"; }
    var y = document.getElementById("icon");
    if(x.type === "password")
    {
        y.classList.remove("icon-eye-blocked");
        y.classList.add("icon-eye");
    }else{
        y.classList.remove("icon-eye");
        y.classList.add("icon-eye-blocked");
    }
}
function myFunctionx()
{
    $(".se-pre-con2").css('display', 'block');
}
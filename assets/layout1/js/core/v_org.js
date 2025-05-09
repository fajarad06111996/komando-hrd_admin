let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
// console.log(dp);
document.body.classList.add("sidebar-collapse");
var notif;
$(document).ready(function() {
    // console.log($('.tree').treegrid());
    $('.tree').treegrid({
        treeColumn: 1,
        // initialState: 'collapsed',
        expanderExpandedClass: 'glyphicon glyphicon-minus',
        expanderCollapsedClass: 'glyphicon glyphicon-plus'
    });
    if(dp.lock==1){
    
        $('#btnAddx').click(function() {
            $('#mForm').modal('show');
            $('#btnAdd').tooltip('hide');
            $('#mForm').find('.modal-title').text('Add New Account');
            $('#myForm').attr('action', dp.link+'/addAccount');
            $("#myForm").data('validator').resetForm();
            // $('#myForm').validate().resetForm();
            $('#response_result').html('');
            $('#myForm')[0].reset();
            $(".select-search").select2({width: '100%'}).val('').trigger('change.select2');
            xFalse();
            xSegment();
            $('#xTrue').prop('checked', true).uniform();
            $('#xOne').prop('checked', true).uniform();
            $('.tSubAccount').css('display', 'none');
            $('.tSubChildAccount').css('display', 'none');
            $('.tSubSubChildAccount').css('display', 'none');
            // $("#accNo1").prop("readonly",false);
            $('.accNo2').css('display', 'none');
            $('.accNo3').css('display', 'none');
            $('.accNo4').css('display', 'none');
            // $("#tSubAccount").remoteChained({
            // 	parents : "#tAccType",
            // 	url : "<?//= $link; ?>/getTypeAcc",
            // 	data: function (json) {
            // 		return json;
            // 	},
            // 	error: function (error){
            // 		console.log(error);
            // 	}
            // });
    
            $('#tSubAccount').chained('#tAccType');
            $('#tSubChildAccount').chained('#tSubAccount');
            $('#tSubSubChildAccount').chained('#tSubChildAccount');
        });
    
        $('#start_balance').keyup(function(){
            var start_balance = $('#start_balance').val();
            $('#ending_balance').val(start_balance);
        });
    }
    $('#myForm').submit(async function(e) {
        e.preventDefault();
        $(".se-pre-con1").css('display', 'block');
        var url = $('#myForm').attr('action');
        var data = $('#myForm').serialize();
        try {
            const myForm = await submitForm('post', url, data);
            if (myForm.error) {
                $('#mForm').modal('hide');
                notiferror(myForm.message);
                $(".se-pre-con1").css('display', 'none');
            }else if(myForm.status){
                $('#mForm').modal('hide');
                notiferror_a(myForm.text);
                $(".se-pre-con1").css('display', 'none');
            }else{
                $('#mForm').modal('hide');
                $("#myForm").data('validator').resetForm();
                $('#myForm')[0].reset();
                notif = await notifsukses(myForm.text);
                if(notif.value==true){
                    location.reload(1);
                }
            }
        } catch (error) {
            console.log(error);
            $('#mForm').modal('hide');
            notiferror('Error Update To Database');
            $(".se-pre-con1").css('display', 'none');
        }
    });
    if(dp.permC==1){
        $('.bEdit').on('click', function() {
            var id = $(this).attr('id');
            start();
            window.location.replace(dp.link+'/formData/'+id);
        });
    }
    if(dp.permX==1){

        $('.bDelete').on('click',function(){
            var id    = $(this).attr('id');
            var Name = $(this).attr('data');
            $('.bDelete').tooltip('hide');
            $('#deleteModal').modal('show');
            $('#deleteModal').find('.infoDelete').text('Organization : '+Name);
            //prevent previous handler - unbind()
            $('#btnDelete').unbind().click(async function() {
                try {
                    const bDelete = await submitForm('get', dp.link+'/deleteAccount', {id: id});
                    $('#deleteModal').modal('hide');
                    if(bDelete.success){
                        notif = await notifsukses(bDelete.text);
                        if(notif.value==true){
                            location.reload(1);
                        }
                    }else{
                        $('#deleteModal').modal('hide');
                        notiferror(bDelete.text);
                    }
                } catch (error) {
                    console.log(error);
                    $('#deleteModal').modal('hide');
                    notiferror('Error Delete To Database');
                }
            });
        });
    }

    var dataTable = $('#example1').DataTable({
        processing: true,
        serverSide: true,
        scrollx   : true,
        responsive: false,
        paging: false,
        ajax: {
            url: dp.link+"/get_ajax",
            type: "POST",
            data: function(data){
                data.CSRFToken = dp.csrf;
                // data.CSRFToken = $('input[name=token]').val();
            },
            error: function(error){
                console.log(error);
            }
        },
        columnDefs: [
            {targets: [0,-1], orderable: false,},
            {className: "text-center", targets:[0,3,-1]},
            {className: "text-right", targets:[-1]}
        ],
        fnDrawCallback: function( oSettings ) {
            $('.bEdit').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bDelete').each(function () {
                $(this).tooltip({
                    html: true
                });
            });
        }
    });
});
function xFalse(){
    $('#xFalse').prop('checked', false).uniform();
    $('#xTrue').prop('checked', false).uniform();
}
function xSegment(){
    $('#xOne').prop('checked', false).uniform();
    $('#xTwo').prop('checked', false).uniform();
    $('#xThree').prop('checked', false).uniform();
    $('#xFour').prop('checked', false).uniform();
}
function leftPad(number, targetLength) {
    var output = number + '';
    while (output.length < targetLength) {
        output = '0' + output;
    }
    return output;
}
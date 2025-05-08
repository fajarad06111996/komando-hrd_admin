let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
document.body.classList.add("sidebar-collapse");
var notif, submit;
var numLoop = 1;
$(document).ready(function() {
    if(dp.post==1){
        $('#rangeDetail').on('click','.bAddDetail', function(){
            $('.bAddDetail').tooltip('hide');
            $('input[name=tMinx]').val('');
            $('input[name=tMaxx]').val('');
            $('input[name=tValuex]').val('');
            $('#mFormDet').find('.modal-title').text('Add Setup Detail');
            $('#myFormDet').attr('action', dp.link+'/addOtDetail');
            // $('#mFormDet').modal('show');
            modalDragShow($('#mFormDet'));
        });
    
        $('#rangeDetail').on('click', '.bEditDetail',async function() {
            $('.bEditDetail').tooltip('hide');
            var id = $(this).attr('id');
            $('input[name=tMinx]').val('');
            $('input[name=tMaxx]').val('');
            $('input[name=tValuex]').val('');
            try {
                submit = await submitForm('get', dp.link+'/editOtDetail', {id: id});
                if(submit===false){
                    errordatabase();
                }else{
                    $('input[name=tMinx]').val(submit.min_hour);
                    $('input[name=tMaxx]').val(submit.max_hour);
                    $("select[name=tTotx]").val(submit.type_of_value);
                    $('input[name=tValuex]').val(submit.value);
                    $('input[name=tEndHiddenx]').val(submit.status_end);
                    if(submit.status_um==1){
                        $('input[name=tUmx]').prop("checked", true);
                    }else{
                        $('input[name=tUmx]').prop("checked", false);
                    }
                    $('#mFormDet').find('.modal-title').text('Edit Ot Detail');
                    $('#myFormDet').attr('action', dp.link+'/updateOtDetail/'+id);
                    modalDragShow($('#mFormDet'));
                }
            } catch (error) {
                console.log(error);
                notiferror('Error Update To Database');
            }
        });
    
        $('#rangeDetail').on('click','.bDeleteDetail', function(){
            $('.bDeleteDetail').tooltip('hide');
            var id   = $(this).attr('id');
            $('#deleteModal').modal('show');
            $('#deleteModal').find('.infoDelete').text('Delete Data');
            modalDragShow($('#deleteModal'));
            $('#btnDelete').unbind().click(async function() {
                try {
                    submit = await submitForm('get', dp.link+'/deleteOtDetail', {
                        id: id,
                        csrfsession: dp.csrf,
                        ot_id: dp.ot_id
                    });
                    console.log(submit);
                    $('#deleteModal').modal('hide');
                    if(submit.success){
                        notif = await notifsukses(submit.msg);
                        if(notif.value==true){
                            location.reload();
                        }
                    }else{
                        notiferror(submit.msg);
                    }
                } catch (error) {
                    console.log(error);
                    $('#deleteModal').modal('hide');
                    notiferror('Error Delete To Database');
                }
            });
        });
    
        $('#myFormDet').submit(async function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();
            var valid = $(this).data('validator').form();
            if(valid==false){
                return;
            }
            try {
                submit = await submitForm('post', url, data);
                if (submit.error) {
                    // notiferror_a('Error Data is not complete');
                    notiferror_a(submit.msg);
                }else if(submit.status){
                    $(this).modal('hide');
                    notiferror_a(submit.msg);
                    // notiferror_a('Error Update Customer To Database');
                }else{
                    $(this).modal('hide');
                    notif = await notifsukses(submit.msg);
                    if(notif.value==true){
                        window.location.reload();
                    }
                }
            } catch (error) {
                console.log(error);
                $(this).modal('hide');
                notiferror('Error Update To Database');
            }
        });
    
        var rangeDetail = $('#rangeDetail').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            paging: false,
            bInfo : false,
            autoWidth: true,
            // scrollX	: true,
            responsive: false,
            ajax:{
                url: dp.link+"/get_rangeDetail",
                type: "POST",
                data: function(data){
                    data.csrfsession    = dp.csrf;
                    data.ot_id          = dp.ot_id;
                },
                    error:function(error){
                    console.log(error);
                }
            },
            columns: [
                {className: "text-center"}
            ],
            columnDefs: [
                {width: "5%", targets: [0]},
                {width: "18%", targets: [1,2,3]},
                {width: "21%", targets: [4]},
                {width: "20%", targets: [-1]},
                {orderable: false, targets: [0,1,2,3,4,5,-1]}
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
    }

    $('.loopForm').click(async function(e){
        e.preventDefault();
        uncheck();
        numLoop++;
        await $('table#itu').append(`
            <tr class="nah">
                <td>`+numLoop+`</td>
                <td>
                    <input type="number" name="tMin[]" class="textbox-xxs col-sm-11 text-center tMin" placeholder="0" value="">
                </td>
                <td>
                    <input type="number" name="tMax[]" class="textbox-xxs col-sm-11 text-center tMax" placeholder="0" value="">
                </td>
                <td>
                    <select name="tTot[]" class="textbox-xs">
                        <option value="0" >x1</option>
                        <option value="1" >xJam</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="tValue[]" class="textbox-xxs col-sm-6 text-center tValue" placeholder="0" value="">&nbsp;Or&nbsp;
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tUm[]" value="1" id="addcheck`+numLoop+`">
                        <label class="form-check-label" for="addcheck`+numLoop+`">
                            UM
                        </label>
                    </div>
                    <input type="hidden" class="tEndHidden" name="tEndHidden[]" value="1">
                </td>
                <td>
                    <div class='btn-group'>
                        <h6><a href='javascript:void(0);' class='text-center badge badge-danger pupus' onclick="twoExecutePupus(this)"><i class="icon-cross2"></i></a></h6>
                    </div>
                </td>
            </tr>
        `);
    });
    $('#myForm').submit(async function(e) {
        e.preventDefault();
        var url = $('#myForm').attr('action');
        var data = $('#myForm').serialize();
        var valid = $('#myForm').data('validator').form();
        if(valid==false){
            return;
        }
        try {
            submit = await submitForm('post', url, data);
            if (submit.error) {
                // notiferror_a('Error Data is not complete');
                notiferror_a(submit.msg);
            }else if(submit.status){
                $('#mForm').modal('hide');
                notiferror_a(submit.msg);
                // notiferror_a('Error Update Customer To Database');
            }else{
                $('#mForm').modal('hide');
                notif = await notifsukses(submit.msg);
                if(notif.value==true){
                    window.location.replace(submit.link);
                }
            }
        } catch (error) {
            console.log(error);
            $('#mForm').modal('hide');
            notiferror('Error Update To Database');
        }
    });
});
function uncheck()
{
    $('.tEndHidden').val(0);
    // $('.tEnd').prop('checked', false);
    $('.tEnd').prop('disabled', true);
}
function twoExecutePupus(a)
{
    var tEnd        = $('tr.nah').find('.tEnd');
    var tEndHidden  = $('tr.nah').find('.tEndHidden');
    var total       = tEndHidden.length-2;
    if(total<0){
        $('tbody#rangeBody .noh').find('.tEndHidden').val(1);
        // $('tbody#rangeBody .noh').find('.tEnd').prop('checked', true);
    }else{
        $(tEndHidden[total]).val(1);
        // $(tEnd[total]).prop('checked', true);
    }
    pupus(a);
}
async function pupus(ini)
{
    numLoop--;
    $(ini).closest('.nah').remove();
}
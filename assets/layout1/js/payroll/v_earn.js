(function () {
    // decrypt value
    let dataPass = document.currentScript.getAttribute('params');
    let password = '#*ettkHRD2024*#';
    let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
    let dp = JSON.parse(decrypted);

    document.body.classList.add("sidebar-collapse");
    var notif, submit;
    $(document).ready(function() {
        if(dp.lock==1){
            $('#btnAdd').click(function() {
                $('#mForm').find('.modal-title').text('Tarik Data Gaji');
                $('#myForm').attr('action', dp.link+'/selectEarnings');
                $("#myForm").data('validator').resetForm();
                $('#myForm')[0].reset();
                // $(".select-search").select2().val().trigger('change.select2');
                $('#btnAdd').tooltip('hide');
                modalDragShow('#mForm');
            });
            // $('#example2').on('click', '.bPrint', function() {
            //     $('#xInvoice').modal('show');
            //     var inv_id = $(this).attr('invid');
            //     $('.invReceipt').attr('invid', inv_id);
            //     $('.invDetail').attr('invid', inv_id);
            // });
            $('.invReceipt').click(function(){
                $('#xInvoice').modal('hide');
                var inv_id = $(this).attr('invid');
                window.open(dp.link+'/printReceiptInvoice/'+inv_id, '_blank');
            });
            $('.invDetail').click(function(){
                $('#xInvoice').modal('hide');
                var inv_id = $(this).attr('invid');
                window.open(dp.link+'/printInvoice/'+inv_id, '_blank');
            });
        }
    
        $('#myForm').submit(async function(e) {
            e.preventDefault();
            var valid = $('#myForm').data('validator').form();
            if(valid==false){
                return;
            }else{
                e.currentTarget.submit();
            }
        });
        $('#stateForm').submit(async function(e) {
            e.preventDefault();
            var url = $('#stateForm').attr('action');
            var data = $('#stateForm').serialize();
            try {
                submit = await submitForm('post', url, data);
                if (submit.error) {
                    // $('#mForm').modal('hide');
                    // notiferror_a('Error Update User To Database1');
                }else if(submit.status){
                    $('#xStatement').modal('hide');
                    notiferror_a('Error Update User To Database');
                }else{
                    $('#xStatement').modal('hide');
                    if (submit.type == 'add') {
                        var type = 'Added'
                    } else if (submit.type == 'update') {
                        var type = "Updated"
                    } else if (submit.type == 'change') {
                        var type = "Changed"
                        $('#xStatement').modal('hide')
                    }
                    notifsukses('Data Location ' + type + ' Successfully');
                    dataTable.ajax.reload();
                    // setTimeout(function () { location.reload(1); }, 2000);
                    $("#myForm").data('validator').resetForm();
                    $('#stateForm')[0].reset();
                }
            } catch (error) {
                console.log(error);
                $('#xStatement').modal('hide');
                notiferror('Error Update To Database');
            }
        });
    
        if(dp.permW==1){
            $('#example2').on('click', '.bPublish', function() {
                var id 			= $(this).attr('id');
                var data 		= $(this).attr('data');
                var all_id 	    = $(this).attr('allid');
                $('#publishModal').modal('show');
                $('#publishModal').find('.infoPublish').html('Nomor Gaji : '+data+', Publish this Gaji?');
                $('#btnPublish').unbind().click(async function() {
                    try {
                        submit = await submitForm('get', dp.link+'/publishEarnings', {
                            id: id,
                            all_id: all_id
                        });
                        $('#publishModal').modal('hide');
                        if(submit.success){
                            notifsukses(submit.text);
                            dataTable.ajax.reload();
                            // setTimeout(function () { location.reload(1); }, 2000);
                        }else{
                            $('#publishModal').modal('hide');
                            notiferror(submit.text);
                        }
                    } catch (error) {
                        console.log(error);
                        $('#publishModal').modal('hide');
                        notiferror('Error Connect To Database');
                    }
                });
            });
    
            $('#example2').on('click', '.bEdit', function() {
                var id = $(this).attr('id');
                start();
                window.location.replace(dp.link+'/selectEarnings/'+id);
            });
        }
    
        if(dp.permC==1){
            $('#example2').on('click', '.bStatus', function() {
                var id = $(this).attr('id');
                var data = $(this).attr('data');
                var code = $(this).attr('code');
                $('#xStatement').modal('show');
                $('#xStatement').find('.modal-title').text('Change Status Tariff');
                if(code == 1){
                    $('#xStatement').find('.tBtn').text('Non Aktif ?');
                    $('#xStatement').find('.tContent').text('Change status to Non Aktif,');
                }else{
                    $('#xStatement').find('.tBtn').text('Aktif ?');
                    $('#xStatement').find('.tContent').text('Change status to Aktif,');
                }
                $('#xStatement').find('.tName').text(data);
                $('#stateForm').attr('action', dp.link+'/changeStatus');
                $('input[name=tId]').val(id);
                $('input[name=tClCode]').val(code);
                $("#stateForm").data('validator').resetForm();
                $('#stateForm')[0].reset();
            });
    
            $('#example2').on('click', '.bEdit', function() {
                var id = $(this).attr('id');
                start();
                window.location.replace(dp.link+'/selectEarnings/'+id);
            });
        }
    
        if(dp.permX==1){
            $('#example2').on('click', '.bDelete', function() {
                var id   	= $(this).attr('id');
                var No      = $(this).attr('data');
                $('#deleteModal').modal('show');
                $('#deleteModal').find('.infoDelete').text('Invoice No. : '+No);
                $('#btnDelete').unbind().click(async function() {
                    try {
                        submit = await submitForm('get', dp.link+'/deleteInvoice', {id: id});
                        $('#deleteModal').modal('hide');
                        if(submit.success){
                            notifsukses(submit.text);
                            dataTable.ajax.reload();
                            // setTimeout(function () { location.reload(1); }, 2000);
                        }else{
                            $('#deleteModal').modal('hide');
                            notiferror(submit.text);
                        }
                    } catch (error) {
                        console.log(error);
                        $('#deleteModal').modal('hide');
                        notiferror('Error Delete To Database');
                    }
                });
            });
        }
    
        // CONTOH LAIN MODEL DATATABLE SERVERSIDE
    
        var dataTable = $('#example2').DataTable({
            processing: true,
            serverSide: true,
        	// "scrollX"	: true,
        	responsive: false,
        	orderable: false,
            ajax:{
        		url: dp.link+"/get_ajax",
        		type: "POST",
        		data: function(data){
        			data.csrfsession = dp.csrf;
        			// data.CSRFToken = $('input[name=token]').val();
        		},
                error: function (error){
                    console.log(error);
                }
            },
        	columnDefs: [
        		{targets: [0,1,2,3,4,5,6,-1], orderable: false,},
                {className: "text-center", targets:[0,1,2,3,4,5,-1]},
                {className: "text-right", targets:[6]}
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
                $('.bEdit').each(function () {
                    $(this).tooltip({
                        html: true
                    });
                });
                $('.bPrint').each(function () {
                    $(this).tooltip({
                        html: true
                    });
                });
                $('.bLock').each(function () {
                    $(this).tooltip({
                        html: true
                    });
                });
                $('.bDelete').each(function () {
                    $(this).tooltip({
                        html: true
                    });
                });
                $('.bPublish').each(function () {
                    $(this).tooltip({
                        html: true
                    });
                });
                $('.bPublished').each(function () {
                    $(this).tooltip({
                        html: true
                    });
                });
            }
        	// CONTOH LAIN MODEL DATATABLE SERVERSIDE
        });
    
    });
    
    function xFalse(){
        $('#xFalse').prop('checked', false).uniform();
        $('#xTrue').prop('checked', true).uniform();
    }
})()
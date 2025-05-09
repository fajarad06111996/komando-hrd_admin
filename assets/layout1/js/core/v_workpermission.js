let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
// console.log(dp);
// document.body.classList.add("sidebar-collapse");
var submit, notif;
$(document).ready(function() {
    if(dp.permC==1){
        // untuk tombol status permission
        $('#example1').on('click', '.bStatus', function() {
            var id = $(this).attr('id');
            var employeeName = $(this).attr('data-employee_name');
            var date = $(this).attr('data-date');
            var splitDate = date.split('_');
            var remarks = $(this).attr('data-remarks');
            // console.log(remarks);
            var code = $(this).attr('code');

            $('#xWorkPermission').modal('show');
            $('#xWorkPermission').find('.modal-title').text('Status Ijin Kerja');
            if (code == 1) {workPermissionForm
                $('input[name=id]').val(id);
                $('input[name=employee_name]').val(employeeName);
                $('input[name=date]').val(splitDate[0] +' s/d '+ splitDate[1]);
                $('textarea[name=remarks]').val(remarks);
                $('#workPermissionForm').attr('action', dp.link+'/updatePermission');
                // $('#xWorkPermission').find('.tBtn').text('Iya, Beri Ijin');
                // $('#xWorkPermission').find('.tBtn').removeClass('btn-danger').addClass('btn-success');
                // $('#xWorkPermission').find('.tContent').text('Proses Ijin Kerja Karyawan '+employeeName);
                // $('#xWorkPermission').find('.tName').text('Tanggal Ijin '+date);
            } else {
                $('#xStatement').find('.tBtn').text('Aktif ?');
                $('#xStatement').find('.tContent').text('Change status to Aktif,');
            }
            // $('#xStatement').find('.tName').text(data);
            // $('#stateForm').attr('action', dp.link+'/changeStatus');
            // $("#stateForm").data('validator').resetForm();
            // $('#stateForm')[0].reset();
            
        });
    } // end if dp.permC

    // tampil data pake datatable
    $('#example1').DataTable({
        processing: true,
        serverSide: true,
        scrollx: true,
        responsive: false,
        ajax: {
            url: dp.link+"/get_ajax",
            type: "POST",
            data: function(data) {
                // console.log(data);
                data.csrfsession = dp.csrf;
                // data.CSRFToken = $('input[name=token]').val();
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
                targets: [0, 1, 2, 3, 4, 5]
            }
        ],
        preDrawCallback: function() {
            spinnerdarkDT(this);
        },
        language: {
            processing: ""
        },
        fnDrawCallback: function(oSettings) {
            spinnerdarkDT(this);
            stopdarkspinnerDT();
            $('.bEdit').each(function() {
                $(this).tooltip({
                    html: true
                });
            });
            $('.bDelete').each(function() {
                $(this).tooltip({
                    html: true
                });
            });
        }
    }); // end datatable

});

let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
document.body.classList.add("sidebar-collapse");
var notif, submit;
$(document).ready(function() {
    // display_events();
    
}); //end document.ready block

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'dayGrid', 'timeGrid', 'interaction' ],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        defaultView: 'dayGridMonth',
        columnHeaderFormat: {
            weekday: 'long'
        }, 
        editable: true,
        selectable: true,
        selectHelper: true,
        select: function(info) {
            // console.log(info);
            $('#event_name').val('');
            $('#event_date').val('');
            // $('#event_start_date').val('');
            // $('#event_end_date').val('');
            var valDate = moment(info.start).format('YYYY-MM-DD');
            // var valStart = moment(info.start).format('YYYY-MM-DD hh:mm');
            // var valEnd = moment(info.end).format('YYYY-MM-DD hh:mm');
            $('#event_date').val(valDate);
            // $('#event_start_date').val(valStart);
            // $('#event_end_date').val(valEnd);
            $('#calForm').attr('action', dp.link+"/save_event");
            modalDragShow('#event_entry_modal');
        },
        businessHours: true,
        events: function(info, successCallback, failureCallback){
            var monthNow = moment(info.start).add(12, 'd').format('M');
            var yearNow = moment(info.start).add(12, 'd').format('YYYY');
            // console.log(yearNow);
            // console.log(callback);
            // ambil data even di kalender
            $.ajax({
                url: dp.link+'/getCalendar',
                method: 'POST',
                dataType: 'json',
                data: {
                    // our hypothetical feed requires UNIX timestamps
                    // start: start.unix(),
                    // end: end.unix()
                    month: monthNow,
                    year: yearNow
                },
                success: function(doc) {
                    var events = [];
                    $.each(doc, function(i, item) {
                        events.push({
                            id: item.id,
                            title: item.title,
                            start: item.start, // will be parsed
                            // end: item.end, // will be parsed
                            color: '#af1d1d'
                        });
                    });
                    // console.log(events);
                    successCallback(events);
                },
                error: function(error){
                    console.log(error);
                }
            });
        },
        eventClick: async function (event) {
            console.log(event.event);
            $('#event_name').val('');
            $('#event_date').val('');
            // $('#event_start_date').val('');
            // $('#event_end_date').val('');
            var id = event.event.id;
            var dateEv = moment(event.event.start).format('YYYY-MM-DD');
            // var start = moment(event.event.start).format('YYYY-MM-DD hh:mm:ss');
            // var end = moment(event.event.end).format('YYYY-MM-DD hh:mm:ss');
            
            // untuk tampil detail even pada modal master kalender
            try {
                const editEventSubmit = await submitForm('get', dp.link+"/get_event", {id: id});
                var dateEv2 = moment(editEventSubmit.start).format('YYYY-MM-DD');
                $('#event_name').val(editEventSubmit.event_name);
                $('#event_date').val(dateEv2);
                // $('#event_start_date').val(editEventSubmit.start);
                // $('#event_end_date').val(editEventSubmit.end);
                $('#calForm').attr('action', dp.link+"/update_event/"+id);
                modalDragShow('#event_entry_modal');
            } catch (error) {
                console.log(error);
            }
        },
        eventDrop: async function (event) {
            var id = event.event.id;
            var dateEv3 = moment(event.event.start).format('YYYY-MM-DD');
            // var start = moment(event.event.start).format('YYYY-MM-DD HH:mm:ss');
            // var end = moment(event.event.end).format('YYYY-MM-DD HH:mm:ss');

            // console.log(start);
            // console.log(end);
            try {
                const submitDrop = await submitForm('post', dp.link+'/updateDrop_event', {
                    id: id,
                    date: dateEv3
                    // start: start,
                    // end: end
                });
                console.log(submitDrop);
            } catch (error) {
                console.log(error);
                var notifErr = await notiferror('internet error.');
                if(notifErr.value==true){
                    // location.reload(1);
                    calendar.refetchEvents();
                }
            }
            // {description: "Lecture", department: "BioChemistry"}
        }
    });

    calendar.render();

    $('#calForm').submit(async function(e){
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
            $('#event_entry_modal').modal('hide');
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
});

// function display_events() {
//     var events = new Array();
//     $.ajax({
//         url: '<?//= $link; ?>/getCalendar',  
//         dataType: 'json',
//         success: function (response) {
//             // console.log(response);
//             var result=response.data;
//             // $.each(result, function (i, item) {
//             //     events.push({
//             //         event_id: result[i].event_id,
//             //         title: result[i].title,
//             //         start: result[i].start,
//             //         end: result[i].end,
//             //         color: result[i].color,
//             //         url: result[i].url
//             //     }); 	
//             // })
//             // console.log(result);
//             // var calendar = $('#calendar').fullCalendar({
//             //     themeSystem: 'bootstrap4',
//             //     defaultView: 'month',
//             //     header: {
//             //         left: 'prev,next today',
//             //         center: 'title',
//             //         right: 'month,agendaDay'
//             //     },
//             //     timeZone: 'local',
//             //     editable: true,
//             //     selectable: true,
//             //     selectHelper: true,
//             //     select: function(start, end) {
//             //         // alert('select from '+start.format()+' To '+end.format());
//             //         //alert(end);
//             //         $('#event_start_date').val(moment(start).format('YYYY-MM-DD hh:mm'));
//             //         $('#event_end_date').val(moment(end).format('YYYY-MM-DD hh:mm'));
//             //         modalDragShow('#event_entry_modal');
//             //         // $('#event_entry_modal').modal('show');
//             //     },
//             //     events: result,
//             //     eventRender: function(event, element, view) { 
//             //         element.bind('click', function() {
//             //             alert(event.id);
//             //         });
//             //     }
//             // }); //end fullCalendar block
//             new FullCalendar.Calendar('#calendar', {
//                 plugins: [ 'dayGrid', 'timeGrid', 'interaction' ],
//                 header: {
//                     left: 'prev,next today',
//                     center: 'title',
//                     right: 'dayGridMonth,timeGridWeek,timeGridDay'
//                 },
//                 defaultDate: '2014-11-12',
//                 defaultView: 'dayGridMonth',
//                 editable: true,
//                 businessHours: true,
//                 events: [
//                     {
//                         title: 'All Day Event',
//                         start: '2014-11-01'
//                     },
//                     {
//                         title: 'Long Event',
//                         start: '2014-11-07',
//                         end: '2014-11-10'
//                     },
//                     {
//                         id: 999,
//                         title: 'Repeating Event',
//                         start: '2014-11-09T16:00:00'
//                     },
//                     {
//                         id: 999,
//                         title: 'Repeating Event',
//                         start: '2014-11-16T16:00:00'
//                     },
//                     {
//                         title: 'Conference',
//                         start: '2014-11-11',
//                         end: '2014-11-13'
//                     },
//                     {
//                         title: 'Meeting',
//                         start: '2014-11-12T10:30:00',
//                         end: '2014-11-12T12:30:00'
//                     },
//                     {
//                         title: 'Lunch',
//                         start: '2014-11-12T12:00:00'
//                     },
//                     {
//                         title: 'Meeting',
//                         start: '2014-11-12T14:30:00'
//                     },
//                     {
//                         title: 'Happy Hour',
//                         start: '2014-11-12T17:30:00'
//                     },
//                     {
//                         title: 'Dinner',
//                         start: '2014-11-12T20:00:00'
//                     },
//                     {
//                         title: 'Birthday Party',
//                         start: '2014-11-13T07:00:00'
//                     },
//                     {
//                         title: 'Click for Google',
//                         url: 'http://google.com/',
//                         start: '2014-11-28'
//                     }
//                 ]
//             }).render();
//         },//end success block
//         error: function (xhr, status) {
//             alert('error get calendar.');
//         }
//     });//end ajax block	
// }

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
let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
var countHome, getDeptAll, getEduAll, getAttAll;
$(document).ready(function(){
    $('.bRider').click(function(){
        $('#xRider').modal('show');
        $('#xRider').find('.modal-title').text('Driver KPI');
    });
    if(dp.permW==1){
        $('.bPersonal').click(function(){
            location.replace(dp.base_url+'master/Client');
            // setTimeout(function () {  }, 2000);
        })
        $('.bCorporate').click(function(){
            location.replace(dp.base_url+'master/Client');
            // setTimeout(function () {  }, 2000);
        })
        $('.bOrder').click(function(){
            location.replace(dp.base_url+'Order');
            // setTimeout(function () {  }, 2000);
        })
    }
});
function strToInt(valx)
{
    for(var i = 0; i < valx.length; i++){
        var obj3 = valx;
        for(var prop in obj3){
            if(obj3.hasOwnProperty(prop) && obj3[prop] !== null && !isNaN(obj3[prop])){
                obj3[prop] = +obj3[prop];   
            }
        }
    }
    return obj3;
}
$('.content').addClass('bg-jte');
function on_loader(e) {
    var loaderOn = e.closest(".onLoader");
    $(loaderOn).block({
        message:
        '<i class="icon-spinner11 spinner"></i>',
        overlayCSS: {
            backgroundColor: "#1B2024",
            opacity: 0.85,
            cursor: "wait",
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: "none",
            color: "#fff",
        },
    });
}
function onx_loader(e) {
    $(e).block({
        message:
        '<i class="icon-spinner11 spinner"></i></br><small>Loading. . .</small>',
        overlayCSS: {
            backgroundColor: "#1B2024",
            opacity: 0.85,
            cursor: "wait",
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: "none",
            color: "#fff",
        },
    });
}
function stopon_loader(e) {
    var loaderOff = e.closest(".onLoader");
    $(loaderOff).unblock();
}
var getDept, getEdu, getAtt;
var obj                 = null;
var keys2               = null;
on_loader($('#department'));
on_loader($('#education'));
on_loader($('#attendance'));
on_loader($('.count0'));
on_loader($('.count1'));
// on_loader($('.count2'));
// on_loader($('.count3'));
getCount();
getDateNow();
Highcharts.setOptions({
    lang: {
        decimalPoint: '.',
        thousandsSep: ','
    }
});
// KPI Rider Pickup
getDept = Highcharts.chart('department', {
    colors: ['#4DD0E1','#F8BBD0','#CDDC39', '#B2EBF2', '#EF5350'],
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie',
        events: {
            load: dataDept
        }
    },
    title: {
        text: 'Jumlah Karyawan Perdepartemen'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
    series: [{
        name: 'Total',
        colorByPoint: true
    }]
});
// Delivery Riders
getEdu = Highcharts.chart('education', {
    colors: ['#4DD0E1','#F8BBD0','#CDDC39', '#B2EBF2', '#EF5350'],
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie',
        events: {
            load: dataEdu
        }
    },
    title: {
        text: 'Pendidikan'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
    series: [{
        name: 'Total',
        colorByPoint: true,
        // data: []
        // data: [
        //     {
        //         name: 'S1',
        //         y: 20,
        //         sliced: true,
        //         selected: true
        //     }, {
        //         name: 'D3',
        //         y: 30
        //     }, {
        //         name: 'S2',
        //         y: 10
        //     }, {
        //         name: 'SMA',
        //         y: 40
        //     }
        // ]
    }]
});
getAtt = Highcharts.chart('attendance', {
    chart: {
        type: 'column',
        events: {
            load: dataAtt
        }
    },
    title: {
        text: 'Grafik Kehadiran '+getDateNow(),
        style: {
            fontWeight: 'bold'
        }
    },
    xAxis: {
        type: 'category',
        labels: {
            autoRotation: [-45, -90],
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Value ( Rp )',
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        useHTML: true,
        formatter: function (){
            var nameEmployee = "<table>";
            var total = this.point.uniqueInfo.length;
            this.point.uniqueInfo.map(function(v,i){
                // console.log(i);
                if(i===0){
                    nameEmployee += '<tr>';
                    nameEmployee += '<td>- <b>'+v+'</b></td>';
                }else if((i+1)===total){
                    nameEmployee += '<td>- <b>'+v+'</b></td>';
                    nameEmployee += '</tr>';
                }else if((i + 1)% 4===0){
                    // console.log('oke');
                    nameEmployee += '<td>- <b>'+v+'</b></td>';
                    nameEmployee += '</tr>';
                    nameEmployee += '<tr>';
                }else{
                    nameEmployee += '<td>- <b>'+v+'</b></td>';
                }
            });
            nameEmployee += "</table>";
            // console.log(nameEmployee);
            return `${this.point.name}: <b>${this.y}</b><br>${nameEmployee}`;
        }
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            depth: 40,
            dataLabels: {
                enabled: true
            }
        }
    }
});
function dataDept(){
    getDeptAll = $.ajax({
        type: 'ajax',
        method: 'get',
        url: dp.link+'/getEmployeePerDept',
        dataType: 'json',
        success: function(response) {
            // console.log(response);
            response.map(function(v, i){
                if(i==0){
                    getDept.series[0].addPoint({
                        name: v.organization_name,
                        y: parseInt(v.total_karyawan),
                        sliced: true,
                        selected: true,
                        color: '#4dd0e1'
                    });
                }else{
                    getDept.series[0].addPoint({
                        name: v.organization_name,
                        y: parseInt(v.total_karyawan)
                    });
                }
            });
            stopon_loader($('#department'));
        },
        error: function(error) {
            console.log(error);
        }
    });
}
function dataEdu(){
    getEduAll = $.ajax({
        type: 'ajax',
        method: 'get',
        url: dp.link+'/getEduAll',
        dataType: 'json',
        success: function(response) {
            // console.log(response);
            response.map(function(v, i){
                if(i==0){
                    getEdu.series[0].addPoint({
                        name: v.status_name,
                        y: parseInt(v.total_edu),
                        sliced: true,
                        selected: true,
                        color: '#4dd0e1'
                    });
                }else{
                    getEdu.series[0].addPoint({
                        name: v.status_name,
                        y: parseInt(v.total_edu)
                    });
                }
            });
            stopon_loader($('#education'));
        },
        error: function(error) {
            console.log(error);
        }
    });
}
function dataAtt(){
    getAttAll = $.ajax({
        type: 'ajax',
        method: 'get',
        url: dp.link+'/getAtt',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            let mangkir = sakit = cuti = izin = telat1 = telat2 = 0;
            let mangkirName = [];
            let sakitName = [];
            let cutiName = [];
            let izinName = [];
            let telat1Name = [];
            let telat2Name = [];
            $.each(response.data, function(i, v){
                if(v[3]==0){
                    if(v[1]==1){
                        sakitName.push(i);
                        sakit++;
                    }else if(v[1]==2){
                        izinName.push(i);
                        izin++;
                    }else if(v[1]==3){
                        cutiName.push(i);
                        cuti++;
                    }else{
                        mangkirName.push(i);
                        mangkir++;
                    }
                }else{
                    if(v[4]==1){
                        telat1Name.push(i);
                        telat1++;
                    }else if(v[4]==2){
                        telat2Name.push(i);
                        telat2++;
                    }
                }
            });
            getAtt.addSeries({
                name: 'Kehadiran',
                colors: ['#ff4557', '#28a745', '#fbf49d', '#c7a644', '#007bff'],
                colorByPoint: true,
                groupPadding: 0,
                data: [
                    {name: 'Mangkir', y: mangkir, uniqueInfo: mangkirName},
                    {name: 'Sakit',y:  sakit, uniqueInfo: sakitName},
                    {name: 'Cuti', y: cuti, uniqueInfo: cutiName},
                    {name: 'Telat 1', y: telat1, uniqueInfo: telat1Name},
                    {name: 'Telat 2', y: telat2, uniqueInfo: telat2Name},
                ]
            });
            stopon_loader($('#attendance'));
        },
        error: function(error) {
            console.log(error);
        }
    });
}
function getCount(){
    countHome = $.ajax({
        type: 'ajax',
        method: 'get',
        url: dp.link+'/getCount',
        dataType: 'json',
        success: function(response) {
            // console.log(response);
            $('.count0').text(response[0].total);
            $('.count1').text(response[1].total);
            // $('.count2').text(response[2].total);
            // $('.count3').text(response[3].total);
            stopon_loader($('.count0'));
            stopon_loader($('.count1'));
            // stopon_loader($('.count2'));
            // stopon_loader($('.count3'));
        },
        error: function(error) {
            console.log(error);
        }
    });
}
function getDateNow()
{
    const date = new Date();
    const formatter = new Intl.DateTimeFormat("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
    return formatter.format(date); // e.g., December 31, 2024
}
window.addEventListener("beforeunload", function(event) {
    countHome.abort();
    getDeptAll.abort();
    getEduAll.abort();
    // event.returnValue = "Write something clever here..";
});
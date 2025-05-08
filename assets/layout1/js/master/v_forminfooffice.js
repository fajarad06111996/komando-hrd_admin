let dataPass = document.currentScript.getAttribute('params');
let password = '#*ettkHRD2024*#';
let decrypted = CryptoJSAesJson.decrypt(dataPass, password);
let dp = JSON.parse(decrypted);
var compPoint, companyLat, companyLng;
if(dp.center_point==''){
    companyLat = '-6.175557765244164';
    companyLng = '106.82715278255613';
}else{
    compPoint = dp.center_point.split(',');
    companyLat = compPoint[0];
    companyLng = compPoint[1];
}
document.body.classList.add("sidebar-collapse");
$(document).ready(function() {
    $('#myForm').submit(function(e) {
        e.preventDefault();
        var url = $('#myForm').attr('action');
        var data = $('#myForm').serialize();
        $.ajax({
            type: 'ajax',
            method: 'post',
            url: url,
            data: new FormData(this),
            contentType: false,
            processData: false,
            async: true,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.error) {
                    // notiferror_a('Error Data is not complete');
                    notiferror_a(response.message);
                }else if(response.status){
                    $('#mForm').modal('hide');
                    notiferror_a(response.message);
                    // notiferror_a('Error Update Customer To Database');
                }else{
                    $('#mForm').modal('hide');
                    if (response.type == 'add') {
                        var type = 'Added'
                    } else if (response.type == 'update') {
                        var type = "Updated"
                    }
                    notifsukses('Data DRIVER ' + type + ' Successfully');
                    // setTimeout(function () { window.location.replace(response.ledit); }, 2000);
                }
            },
            error: function(error) {
                console.log(error);
                $('#mForm').modal('hide');
                notiferror('Error Update To Database');
            }
        });
    });
});

jQuery(function($){
    $("#npwp").mask("99.999.999.9-999.999");
    $("#npwpedit").mask("99.999.999.9-999.999");
});

var map;
var marker;
var curlat;
var curlon;
function initMap() 
{
    var myLatlng = new google.maps.LatLng(eval(companyLat), eval(companyLng));
    var mapOptions = {
        zoom: 15,
        center: myLatlng,
        zoomControl: false,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        fullscreenControl: false
    }
    var map = new google.maps.Map(document.getElementById("map"), mapOptions);
    
    var icon = {
            url: dp.base_url+"assets/images/map-marker.png",
            scaledSize: new google.maps.Size(45, 45),
            origin: new google.maps.Point(0,0),
            anchor: new google.maps.Point(25,50) 
        };
        
    var marker = new google.maps.Marker({
        position: myLatlng,
        icon: icon,
        title: dp.office_name
    });


    // To add the marker to the map, call setMap();
    marker.setMap(map);
    
    // even listner ketika peta diklik *******************************************************
    google.maps.event.addListener(map, 'click', function(event) {
        //taruhMarker(this, event.latLng, geocoder);
        var posisiTitik = event.latLng;
        //var geocoder = geocoder;
        if( marker ){
            // pindahkan marker
            marker.setPosition(posisiTitik);
        } else {
            // buat marker baru
            marker = new google.maps.Marker({
            position: posisiTitik,
            icon: icon,
            map: map
            });
        }
    
        // isi nilai koordinat ke form
        //document.getElementById("txt_originlat").value = posisiTitik.lat();
        //document.getElementById("txt_originlng").value = posisiTitik.lng();
        var input = posisiTitik.lat() + ',' + posisiTitik.lng();
        document.getElementById("lat_point").value = posisiTitik.lat();
        document.getElementById("long_point").value = posisiTitik.lng();
        document.getElementById("center_point").value = input;
            
    });
}
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
var submit, notif;
$(document).ready(function() {
    $('.bSubmit').click(function(){
        spinnersdark(this);
    });
    $('#myForm').on('change', '#office_type', function(){
        var vBrand = $(this).val();
        if(vBrand){
            $('#office_type-error').css('display', 'none');
        }
    });
    $('#myForm').submit(async function(e) {
        e.preventDefault();
        var url     = $('#myForm').attr('action');
        var data    = $('#myForm').serialize();
        var valid   = $("#myForm").data('validator').form();
        if(valid==false){
            stopspinnersdark();
            return;
        }
        try {
            const myForm = await submitFormData('post', url, this);
            stopspinnersdark();
            if (myForm.error) {
                notiferror_a(myForm.message);
            }else if(myForm.status){
                $('#mForm').modal('hide');
                notiferror_a(myForm.text);
            }else{
                $('#mForm').modal('hide');
                notif = await notifsukses(myForm.text);
                if(notif.value==true){
                    window.location.replace(myForm.ledit);
                }
            }
        } catch (error) {
            stopspinnersdark();
            console.log(error);
            $('#mForm').modal('hide');
            notiferror('Error Update To Database');
        }
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
    geocoder = new google.maps.Geocoder();
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
            geocoder.geocode({'latLng': event.latLng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    var address = results[0].formatted_address.split(",");
                    var country = address[address.length -1];       
                    var cityname = address[address.length -3];                      
                    var state_zipcode = address[address.length -2].split(" ");
                    var state_zipcode2 = address[address.length -2].split(" ");
                    var leng_zip = state_zipcode2.length - 1;
                    state_zipcode.shift();
                    state_zipcode.pop();
                    var province = state_zipcode.join(" ");
                    var state   = state_zipcode[1];
                    var zipcode = state_zipcode2[leng_zip];
                    address = address.splice(0,(address.length -3));
                    document.getElementById("address").value = results[0].formatted_address;
                    document.getElementById("country").value = country;
                    document.getElementById("city").value = cityname;
                    document.getElementById("postal_code").value = zipcode;
                    document.getElementById("province").value = province;
                }
            }
        });
    });
    var addressDrv    = document.getElementById('address');
    var types         = document.getElementById('type-selector');
    // var strictBounds  = document.getElementById('strict-bounds-selector');
    // map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
    var autocomplete_drv = new google.maps.places.Autocomplete(addressDrv);
    // Bind the map's bounds (viewport) property to the autocomplete object,
    // so that the autocomplete requests use the current map bounds for the
    // bounds option in the request.
    // autocomplete_drv.bindTo('bounds', map);
    // autocomplete_dest.bindTo('bounds', map);
    // Set initial restrict to the greater list of countries.
    autocomplete_drv.setComponentRestrictions({'country': ['id']});
    // Set the data fields to return when the user selects a place.
    autocomplete_drv.setFields(['address_components', 'geometry', 'icon', 'name']);
    //var infowindow = new google.maps.InfoWindow();
    //var infowindowContent = document.getElementById('infowindow-content');
    //infowindow.setContent(infowindowContent);
    // even ketika ketik alamat Pengirim *******************************************************
    autocomplete_drv.addListener('place_changed', function() {
        //infowindow.close();
        var place = autocomplete_drv.getPlace();
        if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
        }
        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }
        /*infowindowContent.children['place-icon'].src = place.icon;
        infowindowContent.children['place-name'].textContent = place.name;
        infowindowContent.children['place-address'].textContent = address;*/
        // var or_lat = place.geometry.location.lat();
        // console.log(place.geometry.location.lng());
        document.getElementById("lat_point").value = place.geometry.location.lat();
        document.getElementById("long_point").value = place.geometry.location.lng();
        document.getElementById("center_point").value = place.geometry.location.lat()+","+place.geometry.location.lng();
        // var filtered_array1 = place.address_components.filter(function(address_component){
        // 	return address_component.types.includes("administrative_area_level_3");
        // });
        // document.getElementById("province").value = filtered_array1.length ? filtered_array1[0].long_name: ""; //place.address_components[6].long_name;
        var filtered_array2 = place.address_components.filter(function(address_component){
            return address_component.types.includes("administrative_area_level_2");
        });
        document.getElementById("city").value = filtered_array2.length ? filtered_array2[0].long_name: ""; //place.address_components[7].long_name;
        var filtered_array3 = place.address_components.filter(function(address_component){
            return address_component.types.includes("administrative_area_level_1");
        });
        document.getElementById("province").value = filtered_array3.length ? filtered_array3[0].short_name: ""; //place.address_components[7].long_name;
        var filtered_array4 = place.address_components.filter(function(address_component){
            return address_component.types.includes("postal_code");
        });
        document.getElementById("postal_code").value = filtered_array4.length ? filtered_array4[0].long_name: "";
        var filtered_array5 = place.address_components.filter(function(address_component){
            return address_component.types.includes("country");
        });
        document.getElementById("country").value = filtered_array5.length ? filtered_array5[0].long_name: "";
        //infowindow.open(map, marker);
    });
}
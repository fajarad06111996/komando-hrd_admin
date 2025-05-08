<style>
    /* Always set the map height explicitly to define the size of the div
    * element that contains the map. */
    #map {
        height: 300px;
        top:7px;
    }
    #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
    }
    #infowindow-content .title {
        font-weight: bold;
    }
    #infowindow-content {
        display: none;
    }
    #map #infowindow-content {
        display: inline;
    }
    .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
    }
    #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
    }
    .pac-controls {
        padding: 5px 11px;
    }
    .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
    }
    #txt_originsearchaddress:focus {
        border-color: #4d90fe;
    }
    #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
    }
    .form-control-placeholdericon {
            font-family: Verdana, FontAwesome, 'Material Icons';
        }
    .field-icon {
            float: right;
            margin-left: -25px;
            margin-top: -28px;
            margin-right: 0px;
            position: relative;
            z-index: 2;
        }
</style>
<div class="pac-card" id="pac-card"></div>
<div id="infowindow-content">
    <img src="" width="16" height="16" id="place-icon">
    <span id="place-name"  class="title"></span><br>
    <span id="place-address"></span>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card panel panel-blue">
      <div class="header-elements-inline panel-heading">
        <?= $title; ?>
        <div class="header-elements">
          <div class="list-icons">
            <a href="<?=$link;?>" class="list-icons-item" title="Back To List Data" data-placement="right" data-popup="tooltip">
              <i class="icon-undo2 pr-1" style="font-size:0.8em;padding-top: 4px;"></i>
            </a>
            <a class="list-icons-item" data-action="reload" title="Refresh" data-placement="right" data-popup="tooltip"></a>
            <a class="list-icons-item" data-action="fullscreen" title="Fullscreen" data-placement="right" data-popup="tooltip"></a>
          </div>
        </div>
      </div>
      <div class="alert alert-success" style="display: none;"></div>
      <div class="block-header text-center">
            <span class="block-title">CENTER POINT <?= !empty($off)?$off['office_name']:'';?></span>
        </div>
        <div class="block-content block-content-full text-center">
            <div id="map"></div>
        </div>
      <form action="<?= $formact; ?>" class="form-validasi form-horizontal" accept-charset="utf-8" enctype="multipart/form-data" method="post" id="myForm">
        <div class="panel_body pan">
          <div class="form-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">Office Code <b class="text-danger">*</b></label>
                        <div class="col-md-9">
                            <?= csrf_input(); ?>
                            <input name="idx" type="hidden" value="<?= !empty($off)?$off['idx']:'';?>"/>
                            <input type="hidden" id="center_point" name="center_point" value="<?= !empty($off)?$off['center_point']:'';?>">
                            <input type="hidden" id="lat_point" name="lat_point" value="<?= !empty($off)?$off['point_lat']:'';?>">
                            <input type="hidden" id="long_point" name="long_point" value="<?= !empty($off)?$off['point_long']:'';?>">
                            <input type="hidden" name="office_idx" value="<?= !empty($off)?$this->secure->enc($off['idx']):'';?>">
                            <input name="office_code" type="text" value="<?= !empty($off)?$off['office_code']:'';?>" placeholder="Office Code" class="form-control form-control-sm" <?= !empty($cust)?'readonly':'';?> required />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">Office Name <b class="text-danger">*</b></label>
                        <div class="col-md-9">
                            <input name="office_name" type="text" value="<?= !empty($off)?$off['office_name']:'';?>" placeholder="Office Name" class="form-control form-control-sm" required />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">Email <b class="text-danger">*</b></label>
                        <div class="col-md-9">
                            <input name="email" type="email" value="<?= !empty($off)?$off['email_id']:'';?>" placeholder="Email" class="form-control form-control-sm" required />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">Telephone <b class="text-danger">*</b></label>
                        <div class="col-md-9">
                            <input name="telephone" type="number" value="<?= !empty($off)?$off['telephone']:'';?>" placeholder="Telephone" class="form-control form-control-sm" required />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">Fax</label>
                        <div class="col-md-9">
                            <input name="fax" type="text" value="<?= !empty($off)?$off['fax']:'';?>" placeholder="Fax" class="form-control form-control-sm" />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">Tax Number</label>
                        <div class="col-md-9">
                            <input id="tNpwp" name="fax_id" type="text" value="<?= !empty($off)?$off['tax_id']:'';?>" placeholder="Fax" class="form-control form-control-sm" />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">Status Hub</label>
                        <div class="col-md-9">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" value="1" class="form-check-input-styled" <?= (!empty($off) && $off['status']=='1')?'checked':'';?> name="tBlock" id="tAktifb">Aktif
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" value="0" class="form-check-input-styled" <?= (!empty($off) && $off['status']=='0')?'checked':'';?> name="tBlock" id="tNaktifb">Non-Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">Address <b class="text-danger">*</b></label>
                        <div class="col-md-9">
                            <textarea rows="4" id="address" name="address" class="form-control form-control-sm elastic" placeholder="Address" required><?= !empty($off)?$off['address']:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">City <b class="text-danger">*</b></label>
                        <div class="col-md-9">
                            <input id="city" name="city" type="text" value="<?= !empty($off)?$off['city']:'';?>" placeholder="City" class="form-control form-control-sm" required />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">Province <b class="text-danger">*</b></label>
                        <div class="col-md-9">
                            <input id="province" name="province" type="text" value="<?= !empty($off)?$off['province']:'';?>" placeholder="Province" class="form-control form-control-sm" required />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">Postal Code <b class="text-danger">*</b></label>
                        <div class="col-md-9">
                            <input id="postal_code" name="postal_code" type="text" value="<?= !empty($off)?$off['postal_code']:'';?>" placeholder="Postal Code" class="form-control form-control-sm" required />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">State Code</label>
                        <div class="col-md-9">
                            <input id="state_code" name="state_code" type="text" value="<?= !empty($off)?$off['state_code']:'';?>" placeholder="State Code" class="form-control form-control-sm" />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">Country <b class="text-danger">*</b></label>
                        <div class="col-md-9">
                            <input id="country" name="country" type="text" value="<?= !empty($off)?$off['country']:'';?>" placeholder="Country" class="form-control form-control-sm" required />
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="d-flex justify-content-between align-items-center">
            <!-- <button type="submit" class="btn btn-light">Cancel</button> -->
            <a href="<?=$link;?>" class="btn btn-light" title="Back To List Data" data-placement="top" data-popup="tooltip">Cancel</a>
            <button type="submit" class="btn bg-blue ml-3 btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down"> <i class="icon-floppy-disk"></i> Save Data</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    var map;
    var marker;
    var curlat;
    var curlon;
	function initMap() 
	{
        <?php if(empty($off)){ ?>
		var myLatlng = new google.maps.LatLng(-6.175557765244164,106.82715278255613);
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
                url: "<?= base_url(); ?>assets/images/map-marker.png",
				scaledSize: new google.maps.Size(45, 45),
				origin: new google.maps.Point(0,0),
				anchor: new google.maps.Point(25,50)  
			};
        var marker = new google.maps.Marker({
            position: myLatlng,
            icon: icon,
            title:"Office Center Point"
        });
        <?php }else{ ?>
        var myLatlng = new google.maps.LatLng(<?= $off['center_point'] ?>);
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
                url: "<?= base_url(); ?>assets/images/map-marker.png",
				scaledSize: new google.maps.Size(45, 45),
				origin: new google.maps.Point(0,0),
				anchor: new google.maps.Point(25,50)  
			};
        var marker = new google.maps.Marker({
            position: myLatlng,
            icon: icon,
            title:"<?= $off['office_name'] ?>"
        });
        <?php } ?>
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
  $(document).ready(function() {
    document.body.classList.add("sidebar-collapse");
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
                    notifsukses('Data Office ' + type + ' Successfully');
                    setTimeout(function () { window.location.replace(response.ledit); }, 2000);
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
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= key_google(); ?>&libraries=places&callback=initMap" async defer></script>
@extends('voyager::master')

@section('page_header')
    <div class="container-fluid">
		<div class="card-header bg-card-header" style="margin-left:0px !important;">
			<h1 class="page-title">Track Trucks And Trailers</h1>
		</div>
    </div>
@stop

@section('content')
<style type="text/css">
#adminmenu{
width:100%;
}
.gm-style-iw.gm-style-iw-c {
    width: 345px !important;
}
#mapCanvas {
    width: 100%;
    height: 650px;
}
#map-layer {
    max-width: 900px;
    min-height: 550px;
}
.lbl-locations {
    font-weight: bold;
    margin-bottom: 15px;
}
.locations-option {
    display:inline-block;
    margin-right: 15px;
}
.btn-draw {
    background: green;
    color: #ffffff;
}
.scrollFix {
    line-height: 1.35;
    overflow: hidden;
    white-space: nowrap;
}
ul{
	margin:0 !important;
    padding: 0 !important;
}
html,
body,
#map,
#map_wrap {
  height: 100%;
  width: 100%;
}
.siderbarmap {
    background: #ffffff;
    padding: .90rem 1rem;
    border-radius: 5px;
    margin-top: 0.4rem;
}
.cont{
	position:relative;
}
.mapoptions {
    background: #00000012;
    overflow: hidden;
    clear: both;
    padding: 1rem;
    width: 22%;
    border-radius: 5px;
    position: absolute;
    z-index: 900;
    left: 20px;
    top: 145px;
    box-shadow: 0px 0px 10px -4px #3c49ea;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 12px !important;
}
.mapoptions > [class*=col-] {
    margin-bottom: 0px;
}
.mapoptions .row [class*=col-] {
    margin-bottom: 17px !important;
}
.select2-container .select2-selection--single {
    height: 42px !important;
}
.select2-container .select2-selection--single .select2-selection__rendered {
    padding-top: 10px !important;
    font-size: 15px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 37px !important;
}
.voyager .btn.btn-warning {
    padding: 8px;
    width: 100%;
    font-size: 16px;
    letter-spacing: 4px;
}
div#showdaterange {
    margin: 0px !important;
}
#loadinstatus{
    margin: 0px;
    text-align: center;
    color: #1f506f;
    font-weight: bold;
    opacity: 0.8;
    font-style: italic;
    font-size: 1.4rem;
}
</style>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<?php $publicpath = URL::to('/public/');?>
<div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">			
                <div class="panel panel-bordered">
                   <div class="cont">
                    	<div class="row mapoptions">
                    		<div class="col-md-12" styloe="margin:0 !important;">
                    		    <div class="row">
                    		        <div class="col-md-12">
                    		            <div class="d-grid gap-2 d-md-block"><a href="javascript:void(0);" id="resetmap" role="button" class="btn btn-warning">Set To All</a></div>
                    		        </div>
                    		        
                    		        <div class="col-md-12">
                            			<select class="form-control" name="trailers" id="trailers">
                            			<option></option>
                            			<?php
                            				foreach($locations as $location)
                            				{
                            					?>
                            					<option value="<?=$location->company;?>" data-lat="<?=$location->latitude?>" data-long="<?=$location->longitude?>"><?=$location->unit_number?></option>
                            					<?php 
                            				}
                            			?>
                            			</select>
                        			</div>
                        			<div class="col-md-12" id="showdaterange" style="display:none;margin:0 !important;">
                    		            <div class="form-group">
                    		                <input type="text" name="daterange" class="form-control" value="" />
                    		            </div>
                    		        </div>
                        			<div class="col-md-12">
                            			<div class="map_wrap">
                            				<div class="siderbarmap">
                            				    <div class="row">
                            				        <div class="col-md-12" style="margin:0 !important;">
                                				        <input id="wialonCheckbox" type="checkbox" onclick="toggleGroup('Wialon')" checked="checked" style="margin-right:.3rem;" />Wialon Trailors
                                				    </div>
                            				    </div>
                            				    
                            					<div class="row">
                            				        <div class="col-md-12" style="margin:0 !important;">
                            						    <input id="omnitracsCheckbox" type="checkbox" onclick="toggleGroup('Omnitracs')" checked="checked" style="margin-right:.3rem;" />Omnitracs Trucks
                            					    </div>
                            					</div>
                            					
                            					<div class="row">
                            				        <div class="col-md-12" style="margin:0 !important;">
                            						    <input id="orbcommCheckbox" type="checkbox" onclick="toggleGroup('orbcomm')" checked="checked" style="margin-right:.3rem;" />Orbcomm Trailors
                            					    </div>
                            					</div>
                            				</div>
                        			    </div>
                        			</div>
                        			<div class="col-md-12" id="loadinstatus" style="display:none;margin:0 !important;">
                        			    Data Loading...
                        			</div>
                    			</div>
                    		</div>
                    	</div>
                    	<p>&nbsp;</p>
                        
                        <div id="mapCanvas" style=""></div>
                    </div>
                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@stop

@section('javascript')

<script>
jQuery('#resetmap').on('click',function(ev)
{
    ev.preventDefault();
    $("#showdaterange").hide('slow');
    initMap();
});

$('#trailers').select2({placeholder: "Select Unit",dropdownAutoWidth : true,width: '100%'});

var markers = [
	<?php /* if($num_rows > 0)
	{ */
		foreach($locations as $location)
		{
			echo '["", '.$location->latitude.', '.$location->longitude.', "'.$location->company.'", "'.$location->unit_number.'"],';
		}
	//}
	?>
];
var xmlMarkers;
for( i = 0; i < markers.length; i++ ) {
	xmlMarkers += '<marker name="" lat="'+markers[i][1]+'" lng="'+markers[i][2]+'" type="'+markers[i][3]+'" unit="'+markers[i][4]+'" />';
}

var xmlData = '<markers> '+ xmlMarkers +'</markers>';

var markerGroups = {
		"Wialon": [],
		"Omnitracs": [],
		"orbcomm":[]
	};
var infoWindow = new google.maps.InfoWindow();

var publicpath = "<?php echo $publicpath;?>";

function initMap()
{
    var map;
	var bounds = new google.maps.LatLngBounds();
	var mapOptions = {
		mapTypeId: 'roadmap'
	};
					
	map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);
	map.setTilt(50);
		
	var markers = [
		<?php /* if($num_rows > 0)
		{ */
			foreach($locations as $location)
			{
				echo '["", '.$location->latitude.', '.$location->longitude.', "'.$location->company.'", "'.$location->unit_number.'"],';
			}
		//}
		?>
	];
	
	var infoWindow = new google.maps.InfoWindow(), marker, i;

	var xmlMarkers = '';
	for( i = 0; i < markers.length; i++ ) 
	{
		xmlMarkers += '<marker name="" lat="'+markers[i][1]+'" lng="'+markers[i][2]+'" type="'+markers[i][3]+'" unit="'+markers[i][4]+'" />';
		var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
		bounds.extend(position);
		
		marker = new google.maps.Marker({
			position: position,
			icon: publicpath +"/"+ markers[i][3] + ".png",
			map: map
		});

		map.fitBounds(bounds);
	}
    var xmlData = '<markers> '+ xmlMarkers +'</markers>';
	var xml = xmlParse(xmlData);
	var markers = xml.documentElement.getElementsByTagName("marker");
	for (var i = 0; i < markers.length; i++) 
	{ 
		var type = markers[i].getAttribute("type");
		var lati = markers[i].getAttribute("lat");
		var longi = markers[i].getAttribute("lng");
		var unitno = markers[i].getAttribute("unit");
		var point = new google.maps.LatLng(
			parseFloat(
				markers[i].getAttribute("lat")
			),
			parseFloat(
				markers[i].getAttribute("lng")
			)
		);

		var marker = createMarker(point, type, map, lati, longi,unitno);
		marker.setMap(map);
	}
	var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
		this.setZoom(4);
		google.maps.event.removeListener(boundsListener);
	});
	$('#trailers').on("change", function()
	{
	    $("#loadinstatus").show('slow');
	    $("#mapCanvas").css("opacity", "0.3");
		var unit_number = $(this).find("option:selected").text();
		var latitude = $(this).children('option:selected').data('lat');
		var longitude = $(this).children('option:selected').data('long');
		var ajaxurl = "<?php echo route('tracker.getlocation',['"+unit_number+"']);?>";

		$.ajax({
			url : ajaxurl,
			type : 'POST',
			data : {
				"_token": "{{ csrf_token() }}",
				unit_number : unit_number
			},
			dataType:'json',
			success : function(json) 
			{
			    $("#loadinstatus").hide('slow');
			    $("#mapCanvas").css("opacity", "1");
			    $("#showdaterange").show('slow');
				polyline(json);
			},
			error : function(request,error)
			{
				console.log("Request: "+JSON.stringify(request));
			}
		});
		var latlng = new google.maps.LatLng(latitude, longitude);

		map.setCenter(latlng);
		smoothZoom(map, 23, map.getZoom());
	});
	
	$('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) 
    {
        
        $("#loadinstatus").show('slow');
	    $("#mapCanvas").css("opacity", "0.3");
        var unit_number = $("#trailers").find("option:selected").text();
        var ajaxurl = "<?php echo route('tracker.getlocation',['"+unit_number+"']);?>";
        
        var begin = picker.startDate.format('YYYY-MM-DD hh:mm:ss');
        var stop = picker.endDate.format('YYYY-MM-DD hh:mm:ss');
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data : {
				"_token": "{{ csrf_token() }}",
				unit_number : unit_number,
				startdate : begin,
				enddate : stop
			},
			dataType:'json',
            success:function(json)
            {
                $("#loadinstatus").hide('slow');
			    $("#mapCanvas").css("opacity", "1");
			    $("#showdaterange").show('slow');
                polyline(json);
            },
            error: function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });
    });
} 

function createMarker(point, type, map, lat, longi, unitno)
{
	var contentString = 'Unit Number:' + unitno + '<br /> Latitude:' + lat + '<br /> Longitude:' + longi;
	var infowindow = new google.maps.InfoWindow({
		content: contentString,
	});
	var customIcons = {
		Wialon: { icon: '<?php echo $publicpath;?>/'+type+'.png' },
		Omnitracs: { icon: '<?php echo $publicpath;?>/'+type+'.png' },
		orbcomm: { icon: '<?php echo $publicpath;?>/'+type+'.png' }
	};

	var marker = new google.maps.Marker({
		map: map,
		position: point,
		icon: customIcons[type].icon,
		type: type
	});

	if (!markerGroups[type]) markerGroups[type] = [];
	markerGroups[type].push(marker);
	marker.addListener("click", () => {
		infowindow.open({
			anchor: marker,
			map,
			shouldFocus: false,
		});
	});
	
	return marker;
}

function toggleGroup(type) 
{
	for (var i = 0; i < markerGroups[type].length; i++) 
	{
		var marker = markerGroups[type][i];

		if (!marker.getVisible()) 
		{
			marker.setVisible(true);
		} else {
			marker.setVisible(false);
		}
	}
}

function bindInfoWindow(marker, map, infoWindow) 
{
	marker.addListener("click", () => {
		infowindow.open({
			anchor: marker,
			map,
			shouldFocus: false,
		});
	});
}


function doNothing() {}
google.maps.event.addDomListener(window, 'load', initMap);

function xmlParse(str) 
{
	if (typeof ActiveXObject != 'undefined' && typeof GetObject != 'undefined') 
	{
		var doc = new ActiveXObject('Microsoft.XMLDOM');
		doc.loadXML(str);
		return doc;
	}

	if (typeof DOMParser != 'undefined') 
	{
		return (new DOMParser()).parseFromString(str, 'text/xml');
	}

	return createElement('div', null);
}

function smoothZoom (map, max, cnt) {
	if (cnt >= max) {
		return;
	}
	else {
		z = google.maps.event.addListener(map, 'zoom_changed', function(event){
			google.maps.event.removeListener(z);
			smoothZoom(map, max, cnt + 1);
		});
		setTimeout(function(){map.setZoom(cnt)}, 80);
	}
} 
 
function polyline(json) 
{
	var pinColor = "FE7569";
	var pinImage = new google.maps.MarkerImage("http://labs.google.com/ridefinder/images/mm_20_red.png" + pinColor,
    new google.maps.Size(14, 21),
    new google.maps.Point(0,0),
    new google.maps.Point(10, 21));
    var mapOptions = {
        center: new google.maps.LatLng(json[0].latitude, json[0].longitude),
        zoom: 16,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById('mapCanvas'), mapOptions);

    var myTrip = new Array();
    const lineSymbol = {
        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
      };
    var val = Math.floor(1000 + Math.random() * 9000);
	for (var i = 0, length = json.length; i < length; i++)
	{
		var data = json[i];
		var t = data.latitude;
		var lng = data.longitude;
		var latLng = new google.maps.LatLng(t, lng);
		myTrip.push(latLng);
		var marker = new google.maps.Marker({
			position: latLng,
			map: map,
			icon: lineSymbol,
			title: data.latitude,
			offset:'100%'
		});
		infoBox(map, marker, data);
		var flightPath = new google.maps.Polyline({
            path: myTrip,
            strokeColor: "#0000FF",
            strokeOpacity: 1.0,
            strokeWeight: 4
        });
		infoPoly(map, flightPath, data);
	}
    
    flightPath.setMap(map);
}

function infoBox(map, marker, data) {
	var geocoder;
    var infoWindow = new google.maps.InfoWindow();
	var cityshortname;
	var citylongname;
	var latlng = new google.maps.LatLng(data.latitude, data.longitude);
	geocoder = new google.maps.Geocoder();
    geocoder.geocode({'latLng': latlng}, function(results, status) 
	{
		if (status == google.maps.GeocoderStatus.OK) 
		{
			if (results[1]) 
			{
				citylongname = results[0].formatted_address;
				console.log(results[0].formatted_address);
				for (var i=0; i<results[0].address_components.length; i++) 
				{
					for (var b=0;b<results[0].address_components[i].types.length;b++) 
					{
						if (results[0].address_components[i].types[b] == "administrative_area_level_1") 
						{
							city= results[0].address_components[i];
							break;
						}
					}
				}
			}else{
				citylongname = "No Address Found";
			}
		} else {
			setTimeout(3000);
		}
		var newtime = getMyFormatDate(data.time);
		var html = '<div class="infowindow scrollFix"> <div class="row"><div class="row col-md-12" style="margin-bottom:0px;"><div class="col-md-4"><strong>Unit Number:</strong> </div><div class="col-md-4">#'+data.unit_number+'</div></div><div class="row col-md-12" style="margin-bottom:0px;"><div class="col-md-4"><strong>Time:</strong> </div><div class="col-md-4">'+newtime+'</div></div><div class="row col-md-12" style="margin-bottom:0px;"><div class="col-md-4"><strong>Address:</strong> </div><div class="col-md-4">'+citylongname+'</div></div><div class="row col-md-12" style="margin-bottom:0px;"><div class="col-md-4"><strong>Latitude: </strong></div><div class="col-md-4">'+data.latitude+'</div></div><div class="row col-md-12" style="margin-bottom:0px;"><div class="col-md-4"><strong>Longitude: </strong></div><div class="col-md-4">'+data.longitude+'</div></div></div></div>';
		google.maps.event.addListener(marker, "click", function(e) 
		{
			infoWindow.setContent(html);
			infoWindow.open(map, marker);
		});

		(function(marker, data) 
		{
			google.maps.event.addListener(marker, "click", function(e) 
			{
				infoWindow.setContent(html);
			});
		})(marker, data);
	
	});
}

function infoPoly(map, flightPath, data) {
  google.maps.event.addListener(flightPath, 'click', function(event) {
    mk = new google.maps.Marker({
      map: map,
      position: event.latLng,

    });
    markers.push(mk);
    map.setZoom(20);
    map.setCenter(mk.getPosition());
    var betweenStr = "result no found";
    var betweenStr = "result no found";
    for (var i=0; i<flightPath.getPath().getLength()-1; i++) {
       var tempPoly = new google.maps.Polyline({
         path: [flightPath.getPath().getAt(i), flightPath.getPath().getAt(i+1)]
       })
       if (google.maps.geometry.poly.isLocationOnEdge(mk.getPosition(), tempPoly, 10e-6)) {
          betweenStr = "between "+i+ " and "+(i+1);
       }
    }

    (function(mk, betweenStr) {
      google.maps.event.addListener(mk, "click", function(e) {
        infowindow.setContent(betweenStr+"<br>loc:" + this.getPosition().toUrlValue(6));
        infowindow.open(map, mk);
      });
    })(mk, betweenStr);

    google.maps.event.trigger(mk,'click');
  });
}

function getMyFormatDate(date) {
	var date = new Date(date);
   var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
   var hours = date.getHours();
   var ampm = hours >= 12 ? 'PM' : 'AM';
   return months[date.getMonth()] + ' ' + date.getDate() + " " + date.getFullYear() + ' ' + hours + ':' + date.getMinutes() + ' ' + ampm;
}

function countDeciml(value)
{
    var countDecimals = function(value) {
      let text = value.toString()
      // verify if number 0.000005 is represented as "5e-6"
      if (text.indexOf('e-') > -1) {
        let [base, trail] = text.split('e-');
        let deg = parseInt(trail, 10);
        return deg;
      }
      // count decimals for number in representation like "0.123456"
      if (Math.floor(value) !== value) {
        return value.toString().split(".")[1].length || 0;
      }
      return 0;
    }
}

function countPlaces(num) {
    var sep = String(23.32).match(/\D/)[0];
    var b = String(num).split(sep);
  return b[1]? b[1].length : 0;
}
</script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script>
jQuery(document).ready(function($) 
{
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('input[name="daterange"]').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'YYYY-MM-DD hh:mm:ss'
        },
        todayHighlight: true,
        startDate: today,
        endDate: end,
        autoclose: true
    });
});
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzcJgxprqCjNZnhIs_bUpTNbpOZx_Kl6w&libraries=geometry&callback=initMap"></script>
@stop

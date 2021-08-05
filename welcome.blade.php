@extends('voyager::master')

@section('content')
@include('voyager::alerts')
        @include('voyager::dimmers')
<style type="text/css">
/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey; 
  border-radius: 8px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: gray; 
  border-radius: 8px;
}
.navbar{
    z-index:999999999999;
}
#adminmenu{
    width:100%;
}
button.gm-ui-hover-effect {
    background: #c9e2ad !important;
    border-top-right-radius: 5px !important;
    top: 3px !important;
    right: 3px !important;
    width: 28px !important;
    height: 29px !important;
}
.gm-style-iw.gm-style-iw-c {
    width: 277px !important;
    background: #000000bd !important;
    color: #f9f9f9 !important;
    text-align: center;
    padding: 15px !important;
}
#mapCanvas {
    width: 100%;
    height: 785px;
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
        background: #f2eaeac4;
    overflow: hidden;
    clear: both;
    padding: 1rem;
    width: 20%;
    border-radius: 5px;
    position: absolute;
    z-index: 900;
    left: 27px;
    top: 165px;
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
    font-size: 14px;
}
div#showdaterange > .form-group > input {
    border-radius: 0.35rem;
    height: 46px;
}
.historySection{
    position: absolute;
    right: 9px;
    top: 13em;
    z-index: 99999999999;
    background: #ffffffbf;
    width: 21%;
    padding: .70rem;
    color: #f9f9f9;
    border-radius: 5px;
    height: 399px;
    overflow-y: scroll;
    color: #404312;
    font-size: 14px;
    font-weight: 500;
}
.gm-style-iw-d{
    overflow:hidden !important;
}
.gm-style .gm-style-iw-c {
    position: absolute;
    box-sizing: border-box;
    overflow: hidden;
    top: 0;
    left: 0;
    transform: translate(-50%,-100%);
    border-radius: 8px;
    padding: 14px;
    background: #000000bd;
    color: #f9f9f9;
    box-shadow: 0 2px 7px 1px rgb(0 0 0 / 30%);
    text-align: left;
}
.infowindow{
    width: 100%;
    overflow-x: scroll;
}
.infowindowall{
    width: 100%;
    overflow-x: scroll;
}
.bgc{
    background: #f0eded9c;
    border-bottom: 1px solid #337ab7;
    padding: .65rem;
    text-align: left;
    font-size: 11px;
    color: #060101;
}
.bgc:hover{
    background: #d5dbea;
    color: #060505;
    transition: .6s;
    font-weight: 600;
}
span#unitselection {
    font-size: 1.6rem;
    color: #e64d4d;
    font-weight: bold;
    font-family: cursive;
}
.loadermap{
    position: absolute;
    z-index: 99999999999;
    background: #ffffffd4;
    height: 100%;
    width: 100%;
    display:none;
}
.loadermap img{
    position: absolute;
    top: 39%;
    left: 45%;
    width: 4%;
    background: #ffe6e6;
    border-radius: 50%;
    box-shadow: 0px 0px 22px 4px #947f7f;
}
.select2-dropdown--below{
    width:58% !important;
}
.select2-results__option {
    font-weight: 500;
    font-size: 13px;
}
.lastupdatedon{
    font-size: 8px;
    font-style: italic;
    font-weight: 600;
    color: #1b53bc;
    position: relative;
    top: 1.7rem;
    left: 0rem;
}
</style>

<link href="{{ asset('public/trackerscripts/css/select2.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('public/trackerscripts/css/drp.css') }}" />
<?php

$temp_uids=array();
$unique_results = array();
foreach($locations as $result)
{  if(!in_array($result->unit_number,$temp_uids))
   { $temp_uids[]=$result->unit_number;
      $unique_results[]=$result;
   }
}

$locations = $unique_results;
unset($temp_uids, $unique_results);

//echo count($locations);
?>
<?php $publicpath = URL::to('/public/');?>
    <div class="page-content" style="margin: auto;
    width: 99%;">
        <div class="row">
            <div class="col-md-12">			
                <div class="panel panel-bordered">
                   <div class="cont">
                        <div class="loadermap"><img src="{{ asset('public/map-loader.gif') }}"></div>
                        <div id="history_popup" class="historySection" style="display:none;">
                        </div>
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
                        					<option value="<?=$location->unit_number;?>" data-last-location-time="<?=$location->last_location_time?>" data-lat="<?=$location->latitude?>" data-long="<?=$location->longitude?>"><?=$location->unit_number?> ( <?=$location->company;?> )</option>
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
                    		
                    		<div id="divmarkers" style="display:none;"></div>
                    		<!--history view-->
                    		<!--<div class="col-md-12" styloe="margin:0 !important;">
                    		    <div class="row">
                        			<div class="col-md-12" id="showdaterange" style="display:none;margin:0 !important;">
                    		            <div class="form-group">
                    		                <input type="text" name="daterange" class="form-control" value="" />
                    		            </div>
                    		        </div>
                        			<div class="col-md-12">
                            			<div class="map_wrap">
                            				<div class="siderbarmap">
                            				    <div class="row">
                            				        <div class="col-md-12" style="margin:0 !important;" id="justtest">
                                				        
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
                    			</div>
                    		</div>-->
                    		
                    	</div>
                        <div id="mapCanvas" style=""></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script src="{{ asset('public/trackerscripts/js/jquery_three.js') }}"></script>
    <script src="{{ asset('public/trackerscripts/js/select2.js') }}"></script>
@stop

@section('javascript')
<script>
(function(){
    window.onload = function(){
        var script=document.createElement("script");
        script.type="text/javascript";
        script.async=true;
        script.src="https://maps.google.com/maps/api/js?key={{$google_api_key}}&sensor=false&callback=initMap";
        document.body.appendChild(script);
    };
})(jQuery);
jQuery(document).ready(function($)
{
    $( '.historydata' ).mouseenter(function() 
    {
        console.log($(this).attr("data-unit"));
    });
});
jQuery('#resetmap').on('click',function(ev)
{
    ev.preventDefault();
    $("#showdaterange").hide('slow');
    $("#history_popup").hide('slow');
    $("#history_popup").html("");
    initMap();
});

$('#trailers').select2({
        sorter: data => data.sort((a,b) => a.text.toUpperCase() > b.text.toUpperCase() ? 0 : -1),
        placeholder: "Select Unit",dropdownAutoWidth : true,width: '100%'
    });

var xmlMarkers;

var markerGroups = {
		"Wialon": [],
		"Omnitracs": [],
		"orbcomm":[]
	};
var infoWindow = new google.maps.InfoWindow();

var publicpath = "<?php echo $publicpath;?>/";

function initMap()
{
    var map;
	var bounds = new google.maps.LatLngBounds();
	var mapOptions = {
		mapTypeId: 'satellite'
	};
					
	map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);
	map.setTilt(50);
	
	var markers = [
    	<?php
    		foreach($locations as $location)
    		{
    		    echo '["", '.$location->latitude.', '.$location->longitude.', "'.$location->company.'", "'.$location->unit_number.'"],';
    		}
    	?>
    ];
	
	var infoWindow = new google.maps.InfoWindow(), marker, i;

	for( i = 0; i < markers.length; i++ ) 
	{
		xmlMarkers += '<marker name="" lat="'+markers[i][1]+'" lng="'+markers[i][2]+'" type="'+markers[i][3]+'" unit="'+markers[i][4]+'" />';
		var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
		bounds.extend(position);
		
		marker = new google.maps.Marker({
			position: position,
			icon: publicpath+markers[i][3]+".png",
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
	    $("#history-loader").show();
	    $(".loadermap").show();
	    $("#history_popup").hide('slow');
	    $("#loadinstatus").show('slow');
	    $("#mapCanvas").css("opacity", "0.3");
		var unit_number = $(this).find("option:selected").val();
		var latitude = $(this).children('option:selected').data('lat');
		var longitude = $(this).children('option:selected').data('long');
		var ajaxurl = "<?php echo route('voyager.getlocations', ['"+unit_number+"']);?>";

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
			    if ( json.length == 0 ) 
			    {
                    $("#loadinstatus").text('Sorry, no data found!');
    			    $("#mapCanvas").css("opacity", "1");
    			    $("#voyager-loader").hide();
    			    $(".loadermap").hide();
                    return false;
                }else{
                    $("#history-loader").hide();
    			    $("#loadinstatus").hide('slow');
    			    $(".loadermap").hide();
    			    $("#mapCanvas").css("opacity", "1");
    			    $("#showdaterange").show('slow');
    				polyline(json);
    				var listmenus = "<span id='unitselection'>#"+unit_number+"</span> <br />";
                    for (var t = 0; t < json.length; t++)
                    {
                        data = json[t];
                        listmenus += "<a href='javascript:void(0);' class='historydata' data-address='"+data.address+"' data-lattt='"+data.latitude+"' data-longgg='"+data.longitude+"' data-unit='"+data.unit_number+"' data-company='"+data.company+"'><div class='col-md-12 bgc'><div class='row'><div class='col-md-7' style='margin:0px !important;'>"+data.time+"</div><div class='col-md-5' style='margin:0px !important;'>"+data.latitude+", <br />"+data.longitude+"</div></div><div class='row'><div class='col-md-8 lastupdatedon'>Data Updated On: "+data.portaltime+"</div></div></div></a>";
                    }
                    $("#history_popup").show('slow');
                    $("#history_popup").html(listmenus);
                    
                    $('.historydata').on("click",function() 
                    { 
                        var history_lat = $(this).attr("data-lattt");
                        var history_lng = $(this).attr("data-longgg");
                        var history_unit = $(this).attr("data-unit");
                        var type = $(this).attr("data-company");
                        var address = $(this).attr("data-address");
                    	var latLng = new google.maps.LatLng(history_lat,history_lng);
                        addMarker(latLng,address);
                    });
                }
			},
			error : function(request,error)
			{
				console.log("Request: "+JSON.stringify(request));
			}
		});
		var latlng = new google.maps.LatLng(latitude, longitude);

		map.setCenter(latlng);
		smoothZoom(map, 7, map.getZoom());
	});
	
	$('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) 
    {
        $("#loadinstatus").show('slow');
        $(".loadermap").show();
	    $("#history_popup").hide('slow');
	    $("#mapCanvas").css("opacity", "0.3");
        var unit_number = $("#trailers").find("option:selected").val();
        if(unit_number == "")
        {
            unit_number = $(this).attr("data-selected-unit");
        }
        var ajaxurl = "<?php echo route('voyager.getlocations', ['"+unit_number+"']);?>";
        
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
                if ( json.length == 0 ) {
                    $("#loadinstatus").text('Sorry, no data found in this date range!');
                    $("#mapCanvas").css("opacity", "0.3");
			        $("#showdaterange").show('slow');
			        $(".loadermap").hide();
			        return false;
                }else{
                    $("#loadinstatus").hide('slow');
    			    $("#mapCanvas").css("opacity", "1");
    			    $("#showdaterange").show('slow');
    			    $(".loadermap").hide();
                    polyline(json);
                    var listmenus = "<span id='unitselection'>#"+unit_number+"</span> <br />";
                    for (var t = 0; t < json.length; t++){
                        data = json[t];
                        listmenus += "<a href='javascript:void(0);' class='historydata' data-address='"+data.address+"' data-lattt='"+data.latitude+"' data-longgg='"+data.longitude+"' data-unit='"+data.unit_number+"' data-company='"+data.company+"'><div class='col-md-12 bgc'><div class='row'><div class='col-md-7' style='margin:0px !important;'>"+data.time+"</div><div class='col-md-5' style='margin:0px !important;'>"+data.latitude+", <br />"+data.longitude+"</div></div><div class='row'><div class='col-md-8 lastupdatedon'>Data Updated On: "+data.portaltime+"</div></div></div></a>";
                    }
                    $("#history_popup").show('slow');
                    $("#history_popup").html(listmenus);
                    
                    $('.historydata').on("click",function() 
                    { 
                        var history_lat = $(this).attr("data-lattt");
                        var history_lng = $(this).attr("data-longgg");
                        var history_unit = $(this).attr("data-unit");
                        var type = $(this).attr("data-company");
                        var address = $(this).attr("data-address");
                    	var latLng = new google.maps.LatLng(history_lat,history_lng);
                        addMarker(latLng,address);
                    });
                }
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
    var contentString;
   
	contentString = ' <div class="infowindowall scrollFix"> <div class="row"> <div class="row col-md-12" style="margin-bottom: 0px;"> <div class="col-md-4"><strong>Unit Number:</strong></div><div class="col-md-4">#'+unitno+'</div></div><div class="row col-md-12" style="margin-bottom: 0px;"> <div class="col-md-4"><strong>Latitude: </strong></div><div class="col-md-4">'+lat+'</div></div><div class="row col-md-12" style="margin-bottom: 0px;"> <div class="col-md-4"><strong>Longitude: </strong></div><div class="col-md-4">'+longi+'</div></div></div></div>';
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
		type: type,
		title: capitalizeFirstLetter(type) + " ( "+unitno+" ) "
	});

	if (!markerGroups[type]) markerGroups[type] = [];
	markerGroups[type].push(marker);
	marker.addListener("click", () => {
		infowindow.open({
			anchor: marker,
			map,
			shouldFocus: false,
		});
		
		getUnitHistory(unitno, lat, longi,map);
	});

	return marker;
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
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
	var pinColor = "#000000";
	var pinImage = new google.maps.MarkerImage(
	    "http://labs.google.com/ridefinder/images/mm_20_red.png" + pinColor,
        new google.maps.Size(100, 100),
        new google.maps.Point(0,0),
        new google.maps.Point(10, 21)
    );
    var mapOptions = {
        center: new google.maps.LatLng(json[0].latitude, json[0].longitude),
        zoom: 7,
        mapTypeId: google.maps.MapTypeId.SATELLITE
    };
    map = new google.maps.Map(document.getElementById('mapCanvas'), mapOptions);

    var myTrip = new Array();
    const lineSymbol = {
        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
    }
    var val = Math.floor(1000 + Math.random() * 9000);
	for (var i = 0, length = json.length; i < length; i++)
	{
	    var data = json[i];
	    var dbicon = data.company;
		var t = data.latitude;
		var lng = data.longitude;
		var latLng = new google.maps.LatLng(t, lng);
		myTrip.push(latLng);
	    if (i == 0)
	    {
	        var marker = new google.maps.Marker({
    			position: latLng,
    			animation: google.maps.Animation.DROP,
    			strokeColor: "#000000",
                strokeOpacity: 1.0,
                strokeWeight: 2,
                geodesic: true,
                map: map,
    			icon: '<?php echo $publicpath;?>/'+data.company+'.png',
    			title: data.company + "( " +data.unit_number+ " )",
    			offset:'100%'
    		});
    		//google.maps.event.addListener(marker, 'click');
    		infoBox(map, marker, data);
    		var flightPath = new google.maps.Polyline({
                path: myTrip,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 3,
                animation: google.maps.Animation.DROP,
                icon: '<?php echo $publicpath;?>/'+data.company+'.png',
                offset: '100%'
            });
    		infoPoly(map, flightPath, data);
	       
	    }else{
    		var marker = new google.maps.Marker({
    			position: latLng,
    			strokeColor: "#000000",
                strokeOpacity: 1.0,
                strokeWeight: 2,
                geodesic: true,
                map: map,
    			icon: lineSymbol,
    			title: data.company + "( " +data.unit_number+ " )",
    			offset:'100%'
    		});
    		infoBox(map, marker, data);
    		var flightPath = new google.maps.Polyline({
                path: myTrip,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 3,
                icon: lineSymbol,
                offset: '100%'
            });
    		infoPoly(map, flightPath, data);
	    }
	}
    
    flightPath.setMap(map);
}

function closeLastOpenedInfoWindow(infoWindow) 
{
    return infoWindow.close();
}

var currWindow = false; 
function infoBox(map, marker, data) 
{
    var address;
    var infoWindow = new google.maps.InfoWindow();
	var newtime = getMyFormatDate(data.time);
	address = data.address.replace(/.{200}/g, "$0\n")
	var html = '<div class="infowindow scrollFix"> <div class="row"><div class="row col-md-12" style="margin-bottom:0px;"><div class="col-md-4"><strong>Unit Number:</strong> </div><div class="col-md-4">#'+data.unit_number+'</div></div><div class="row col-md-12" style="margin-bottom:0px;"><div class="col-md-4"><strong>Time:</strong> </div><div class="col-md-4">'+data.time+'</div></div><div class="row col-md-12" style="margin-bottom:0px;"><div class="col-md-4"><strong>Address:</strong> </div><div class="col-md-4">'+address+'</div></div><div class="row col-md-12" style="margin-bottom:0px;"><div class="col-md-4"><strong>Latitude: </strong></div><div class="col-md-4">'+data.latitude+'</div></div><div class="row col-md-12" style="margin-bottom:0px;"><div class="col-md-4"><strong>Longitude: </strong></div><div class="col-md-4">'+data.longitude+'</div></div></div></div>';
	google.maps.event.addListener(marker, "click", function(e) 
	{
	    if( currWindow ) 
	    {
           currWindow.close();
        }
        
        currWindow = infoWindow;
	    infoWindow.setContent(html);
		infoWindow.open(map, marker);
	});
}

function infoPoly(map, flightPath, data) { }

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

function getReverseGeocodingData(lat, lng) 
{
    var address;
    var ajaxurl = "<?php echo route('voyager.getgeolocation');?>";
    $.ajax({
        url : ajaxurl,
        type : 'POST',
        dataType:'json',
        data:{
            "_token": "{{ csrf_token() }}",
            lat:lat,
            lng:lng
        },
        success : function(data) {
            address = data;
        }
    });
    
    return address;
}

function getUnitHistory(unit_number,latitude,longitude,map)
{
    var bounds = new google.maps.LatLngBounds();
	var ajaxurl = "<?php echo route('voyager.getlocations', ['"+unit_number+"']); ?>";
	$("#loadinstatus").show('slow');
	$(".loadermap").show();
	$("#history_popup").hide('slow');
	$("#mapCanvas").css("opacity", "0.3");
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
		    if (json.length == 0 ) {
                console.log("NO DATA!");
                return false;
            }
            $('input[name="daterange"]').attr("data-selected-unit", unit_number);
		    $("#loadinstatus").hide('slow');
		    $(".loadermap").hide();
		    $("#mapCanvas").css("opacity", "1");
		    $("#showdaterange").show('slow');
			polyline(json);
			var listmenus = "<span id='unitselection'>#"+unit_number+"</span> <br />";
            for (var t = 0; t < json.length; t++)
            {
                data = json[t];
                listmenus += "<a href='javascript:void(0);' class='historydata' data-address='"+data.address+"' data-lattt='"+data.latitude+"' data-longgg='"+data.longitude+"' data-unit='"+data.unit_number+"' data-company='"+data.company+"'><div class='col-md-12 bgc'><div class='row'><div class='col-md-7' style='margin:0px !important;'>"+data.time+"</div><div class='col-md-5' style='margin:0px !important;'>"+data.latitude+", <br />"+data.longitude+"</div></div><div class='row'><div class='col-md-8 lastupdatedon'>Data Updated On: "+data.portaltime+"</div></div></div></a>";
            }
            $("#history_popup").show('slow');
            $("#history_popup").html(listmenus);
            
            $('.historydata').on("click",function() 
            {
                var history_lat = $(this).attr("data-lattt");
                var history_lng = $(this).attr("data-longgg");
                var history_unit = $(this).attr("data-unit");
                var type = $(this).attr("data-company");
                var address = $(this).attr("data-address");
            	var latLng = new google.maps.LatLng(history_lat,history_lng);
                addMarker(latLng,address);
            });
		},
		error : function(request,error)
		{
			console.log("Request: "+JSON.stringify(request));
		}
	});
	
	latitude = parseFloat(latitude);
	longitude = parseFloat(longitude);
	var position = new google.maps.LatLng(latitude, longitude);
	map.setCenter(position);
	smoothZoom(map, 2, map.getZoom());
}

function convertTZ(date, tzString) {
    return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: tzString}));   
}

function addmarkeronmouseover(address,lat, long, map)
{
    var latLng = new google.maps.LatLng(lat,long);
    addMarker(latLng,address);
}

var marker;
function addMarker(location,address) 
{
    var infowindow = new google.maps.InfoWindow({
      size: new google.maps.Size(150, 150)
    });

     //Remove previous Marker.
    if (marker != null) {
        marker.setMap(null);
    }
    
    marker = new google.maps.Marker({
        position: location,
        map: map
    });
    
    
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent("Location: " + address);
        infowindow.open(map, marker);
    });
    map.setCenter(marker.getPosition());
    smoothZoom(map, 18, map.getZoom());
}
</script>
<script type="text/javascript" src="{{ asset('public/trackerscripts/js/moment.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/trackerscripts/js/drp.js') }}"></script>
<script>
jQuery(document).ready(function($) 
{
    var date = new Date();
    var today = moment().startOf('hour');
    var end = moment().startOf('hour').add(32, 'hour');
    $('input[name="daterange"]').daterangepicker({
        timePicker: true,
        timePickerIncrement: 5,
        timePicker24Hour: true,
        locale: {
            format: 'YYYY-MM-DD HH:mm:ss'
        },
        todayHighlight: true,
        startDate: today,
        endDate: end,
        autoclose: true,
    });
    
    $(".infowindowall").parents("div.gm-style-iw").addClass("customwidth");
});
</script>
@stop

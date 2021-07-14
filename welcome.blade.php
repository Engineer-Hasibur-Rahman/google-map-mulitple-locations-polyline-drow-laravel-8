<?php
define("API_KEY", "your-api-key");
?>
<html>
<head>
<title>How to draw route Path on Map using Google Maps Direction API in PHP | Tutorialswebsite</title>
<style>
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
    padding: .12rem 0.38rem;
    border-radius: 5px;
    margin-top: 0.4rem;
}
.cont{
	position:relative;
}
.mapoptions {
    background: #00000096;
    overflow: hidden;
    clear: both;
    padding: 1rem;
    width: 25%;
    border-radius: 5px;
    position: absolute;
    z-index: 900;
    right: 80px;
    top: 90px;
}
</style>
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<div class="container cont">
	<p>&nbsp;</p>
	<div class="row mapoptions">
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
			<div class="map_wrap">
				<div class="siderbarmap">
					<ul>
						<input id="wialonCheckbox" type="checkbox" onclick="toggleGroup('Wialon')" checked="checked" style="margin-right:.3rem;" />Wialon
						<input id="omnitracsCheckbox" type="checkbox" onclick="toggleGroup('Omnitracs')" checked="checked" style="margin-right:.3rem;" />Omnitracs
					</ul>
				</div>
			</div>
		</div>
	</div>
	<p>&nbsp;</p>
    
    <div id="mapCanvas"></div>
</div>

<?php $publicpath = URL::to('/');?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$('#trailers').select2({placeholder: "Select Unit",dropdownAutoWidth : true,width: '100%'});
var markers = [
	<?php if($num_rows > 0)
	{
		foreach($locations as $location)
		{
			echo '["", '.$location->latitude.', '.$location->longitude.', "'.$location->company.'", "'.$location->unit_number.'"],';
		}
	}
	?>
];
var xmlMarkers;
for( i = 0; i < markers.length; i++ ) {
	xmlMarkers += '<marker name="" lat="'+markers[i][1]+'" lng="'+markers[i][2]+'" type="'+markers[i][3]+'" unit="'+markers[i][4]+'" />';
}

//var xmlData = '<markers> '+ xmlMarkers +'</markers>';

var markerGroups = {
		"Wialon": [],
		"Omnitracs": []
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
		<?php if($num_rows > 0)
		{
			foreach($locations as $location)
			{
				echo '["", '.$location->latitude.', '.$location->longitude.', "'.$location->company.'"],';
			}
		}
		?>
	];

	var infoWindow = new google.maps.InfoWindow(), marker, i;

	var xmlMarkers = '';
	for( i = 0; i < markers.length; i++ ) 
	{
		xmlMarkers += '<marker name="" lat="'+markers[i][1]+'" lng="'+markers[i][2]+'" type="'+markers[i][3]+'" />';
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
	}
	var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
		this.setZoom(4);
		google.maps.event.removeListener(boundsListener);
	});
	$('#trailers').on("change", function()
	{
		var unit_number = $(this).find("option:selected").text();
		var latitude = $(this).children('option:selected').data('lat');
		var longitude = $(this).children('option:selected').data('long');
		var ajaxurl = "<?php echo route('map.getlocation',['"+unit_number+"']);?>";

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
				polyline(json);
			},
			error : function(request,error)
			{
				console.log("Request: "+JSON.stringify(request));
			}
		});
		var latlng = new google.maps.LatLng(latitude, longitude);

		var marker = new google.maps.Marker({position: latlng,map:map,title:"found location"});
		
		var html = '<div class="infowindow scrollFix"> <div class="row"><div class="row col-md-12"><div class="col-md-4"><strong>Unit Number:</strong> </div><div class="col-md-4">'+unit_number+'</div></div><div class="row col-md-12"><div class="col-md-4"><strong>Latitude: </strong></div><div class="col-md-4">'+latitude+'</div></div><div class="row col-md-12"><div class="col-md-4"><strong>Longitude: </strong></div><div class="col-md-4">'+longitude+'</div></div></div></div>';

		google.maps.event.addListener(marker, 'click', (function(marker, i) 
		{
			return function() {
				infoWindow.setContent(html);
				infoWindow.open(map, marker);
			}
		})(marker, i));

		map.setCenter(latlng);
		smoothZoom(map, 20, map.getZoom());
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
		Omnitracs: { icon: '<?php echo $publicpath;?>/'+type+'.png' }
	};

	var marker = new google.maps.Marker({
		map: map,
		position: point,
		icon: customIcons[type].icon,
		type: type
	});

	if (!markerGroups[type]) markerGroups[type] = [];
	markerGroups[type].push(marker);
	bindInfoWindow(marker, map, infoWindow);
	
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
	for (var i = 0, length = json.length; i < length; i++)
	{
		var data = json[i];
		var latLng = new google.maps.LatLng(data.latitude, data.longitude);
		myTrip.push(latLng);
		var marker = new google.maps.Marker({
			position: latLng,
			map: map,
			icon: pinImage,
			title: data.latitude
		});
		infoBox(map, marker, data);
		var flightPath = new google.maps.Polyline({
			path: json,
			geodesic: true,
			strokeColor: '#FF0000',
			strokeOpacity: 1.0,
			strokeWeight: 2,
			map:map
		});
		infoPoly(map, flightPath, data);
	}
    var flightPath = new google.maps.Polyline({
        path: myTrip,
        strokeColor: "#0000FF",
        strokeOpacity: 0.8,
        strokeWeight: 2
    });
    flightPath.setMap(map);
}

function infoBox(map, marker, data) {
    var infoWindow = new google.maps.InfoWindow();
	var html = '<div class="infowindow scrollFix"> <div class="row"><div class="row col-md-12"><div class="col-md-4"><strong>Unit Number:</strong> </div><div class="col-md-4">'+data.unit_number+'</div></div><div class="row col-md-12"><div class="col-md-4"><strong>Latitude: </strong></div><div class="col-md-4">'+data.latitude+'</div></div><div class="row col-md-12"><div class="col-md-4"><strong>Longitude: </strong></div><div class="col-md-4">'+data.longitude+'</div></div></div></div>';
    google.maps.event.addListener(marker, "click", function(e) {
        infoWindow.setContent(html);
		infoWindow.open(map, marker);
    });

    (function(marker, data) {
      google.maps.event.addListener(marker, "click", function(e) {
        salta(data.tm);
      });
    })(marker, data);
}

function infoPoly(map, flightPath, data) {
  google.maps.event.addListener(flightPath, 'click', function(event) {
    mk = new google.maps.Marker({
      map: map,
      position: event.latLng,

    });
    markers.push(mk);
    map.setZoom(17);
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
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo API_KEY; ?>&libraries=geometry&callback=initMap"></script>
<div id="map"></div>
</body>
</html>
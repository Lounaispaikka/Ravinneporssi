
Array.min = function(array){
    return Math.min.apply(Math, array);
};

Array.max = function(array){
    return Math.max.apply(Math, array);
};

String.prototype.between = function(prefix, suffix) {
	s = this;
	var i = s.indexOf(prefix);
	if (i >= 0) {
		s = s.substring(i + prefix.length);
	}
	else {
		return '';
	}
	
	if (suffix) {
		i = s.indexOf(suffix);
		if (i >= 0) {
			s = s.substring(0, i);
		}
		else {
			return '';
		}
	}
		return s;
}

function decodePolyLine(instring) {
	
	var outstring;
	var points;
	
	instring = instring.replace(/\\\\/g, "\\");
	points = decodeLine(instring);
	
	return points;
	
}

function sanitizeZoom(zoom) {
	
	var sanitized;
	
	for (var i=minimumZoom;i<=maximumZoom;i*=zoomStepping) {
		
		if (i>zoom) {sanitized = i; break;}
		
	}
	
	if (sanitized<minimumZoom || sanitized>maximumZoom) {sanitized=currentZoom;}
	
	return sanitized;
	
}

function convertPoints(points) {

	var newpoints = new Array();
	
	for (var i=0;i<points.length;i++) {
	
		var coordinates = transformCoordinates(points[i][1], points[i][0]);
		newpoints.push(new Array(coordinates[0], coordinates[1]));
	
	}
	
	return newpoints;
	
}

function decodeLine(encoded) {
	
	var len = encoded.length;
	var index = 0;
	var array = [];
	var lat = 0;
	var lng = 0;
	
	while (index < len) {
		
		var b;
		var shift = 0;
		var result = 0;
		
		do {
			b = encoded.charCodeAt(index++) - 63;
			result |= (b & 0x1f) << shift;
			shift += 5;
		} while (b >= 0x20);
		
		var dlat = ((result & 1) ? ~(result >> 1) : (result >> 1));
		lat += dlat;
		
		shift = 0;
		result = 0;
		
		do {
			b = encoded.charCodeAt(index++) - 63;
			result |= (b & 0x1f) << shift;
			shift += 5;
		} while (b >= 0x20);
		
		var dlng = ((result & 1) ? ~(result >> 1) : (result >> 1));
		lng += dlng;
		
		array.push([lat * 1e-5, lng * 1e-5]);
	}
	
	return array;
}

function deg2rad(deg) {
	return deg * (Math.PI/180);
}

function sanitizeDistance(distance) {
	
	var km = Math.floor(distance);
	var meters = Math.round((distance-km)*1000);
	
	if (km>0) {
		return km+" km "+meters+" m";
	} else {
		return meters+" m";
	}
	
}

function sanitizeTime(seconds) {
	
	var minutes = Math.round(seconds/60);
	var hours = Math.floor(minutes/60)
	
	minutes = minutes-(hours*60);
	
	if (hours>0) {
		return hours+" h "+minutes+" min";		
	} else {
		return minutes+" min";		
	}
	
}

function sanitizeMinimalDistance(distance) {
	
	var km = Math.round(distance);
	
	return km+" km";
	
}

function sanitizeMinimalTime(seconds) {
	
	return Math.round(seconds/60)+" min";
	
}

function getPolygonCenter(polygon) {
	
	var latitude = 0;
	var longitude = 0;
	
	for (var i=0;i<polygon.length;i++) {
		
		latitude += polygon[i][0]; latitude += polygon[i][2];
		longitude += polygon[i][1];	longitude += polygon[i][3];	
		
	}	
		
	return Array((latitude/(polygon.length*2)),(longitude/(polygon.length*2)));
	
}

function getPolygonMaxSize(polygon) {
	
	var latitude = 0;
	var longitude = 0;
	
	var latitudeMin = 0;
	var latitudeMax = 0;
	var longitudeMin = 0;
	var longitudeMax = 0;
	
	for (var i=0;i<polygon.length;i++) {
		
		if (latitudeMin==0 || polygon[i][0]<latitudeMin) {latitudeMin = polygon[i][0];}
		if (latitudeMax==0 || polygon[i][0]>latitudeMax) {latitudeMax = polygon[i][0];}
		
		if (latitudeMin==0 || polygon[i][2]<latitudeMin) {latitudeMin = polygon[i][2];}
		if (latitudeMax==0 || polygon[i][2]>latitudeMax) {latitudeMax = polygon[i][2];}
		
		if (longitudeMin==0 || polygon[i][1]<longitudeMin) {longitudeMin = polygon[i][1];}
		if (longitudeMax==0 || polygon[i][1]>longitudeMax) {longitudeMax = polygon[i][1];}
		
		if (longitudeMin==0 || polygon[i][3]<longitudeMin) {longitudeMin = polygon[i][3];}
		if (longitudeMax==0 || polygon[i][3]>longitudeMax) {longitudeMax = polygon[i][3];}
		
	}	
		
	if ((latitudeMax-latitudeMin) > (longitudeMax-longitudeMin)) {return (latitudeMax-latitudeMin);} else {return (longitudeMax-longitudeMin);}
		
}

function sanitizeAreaHectare(area) {
	
	return (area/10000).toFixed(2).replace(".",",");	
	
}

function sanitizeArea(area) {
	
	return Math.round(area);	
	
}

function getPointDistance(x,y,x2,y2) {
	
	return Math.sqrt(((x2-x)*(x2-x))+((y2-y)*(y2-y)));
	
}

function getDistance(lat1, lon1, lat2, lon2) {
	var R = 6371;
	var dLat = deg2rad(lat2-lat1);
	var dLon = deg2rad(lon2-lon1); 
	var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon/2) * Math.sin(dLon/2); 
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
	var d = R * c;
	return d;
}  

function escapeLB (key, val) {
	if (typeof(val)!="string") return val;
	return val
	.replace(/[\"]/g, '\\"')
	.replace(/[\\]/g, '\\\\')
	.replace(/[\/]/g, '\\/')
	.replace(/[\b]/g, '\\b')
	.replace(/[\f]/g, '\\f')
	.replace(/[\n]/g, '\\n')
	.replace(/[\r]/g, '\\r')
	.replace(/[\t]/g, '\\t')
	; 
}

function transformCoordinates(lon, lat) {
	Proj4js.defs["EPSG:3067"] = "+proj=utm +zone=35 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs";
	var source = new Proj4js.Proj("EPSG:4326");
	var destination = new Proj4js.Proj("EPSG:3067");
	var point = new Proj4js.Point(lon, lat);
	Proj4js.transform(source, destination, point);
	return Array(point.x, point.y);
}

function transformCoordinatesReverse(x, y) {
	Proj4js.defs["EPSG:3067"] = "+proj=utm +zone=35 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs";
	var destination = new Proj4js.Proj("EPSG:4326");
	var source = new Proj4js.Proj("EPSG:3067");
	var point = new Proj4js.Point(x, y);
	Proj4js.transform(source, destination, point);
	return Array(point.x, point.y);
}

function transformVisibleCoordinates(x, y) {
		
	var coordinates = transformCoordinates(viewLongitude, viewLatitude);

	var currentX = coordinates[0]+(x-($(window).width()/2))*(currentZoom/tileSize);
	var currentY = coordinates[1]-(y-($(window).height()/2))*(currentZoom/tileSize);
	
	var coordinates2 = transformCoordinatesReverse(currentX, currentY);

	return Array(coordinates2[0], coordinates2[1]);
		
}

function transformVisibleCoordinatesRev(x, y) {
	
	var coordinates = transformCoordinates(viewLongitude, viewLatitude);
	
	var currentVisiblePosition = $("#mapView").position();
	
	return Array((($(window).width()/2)-currentVisiblePosition.left)+(x-coordinates[0])*(tileSize/currentZoom), (($(window).height()/2)-currentVisiblePosition.top)+(coordinates[1]-y)*(tileSize/currentZoom));
	
}

function notify(msg) {
	
	$("#notifierMsg").html(msg);
		
	$("#notifier").fadeIn(300);	
	
}

function closeNotifier() {
	
	$("#notifier").fadeOut(300);	
	
}


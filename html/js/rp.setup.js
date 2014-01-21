var mapTiles = new Array();

var fallBackLatitude = 63.253412;
var fallBackLongitude = 26.894531;
var fallBackZoom = 400000;

var physicalLatitude = 0;
var physicalLongitude = 0;
var currentLatitude = physicalLatitude;
var currentLongitude = physicalLongitude;
var viewLatitude = physicalLatitude;
var viewLongitude = physicalLongitude;
var fixedPhysicalLatitude = 0;
var fixedPhysicalLongitude = 0;
var baseLatitude = 0;
var baseLongitude = 0;
var tileSize = 500;
var currentZoom = 100000;
var iterationSpace = 100;
var zoomStepping = 2;
var minimumZoom = 500;
var maximumZoom = 1000000;
var compensateX = -105;
var compensateY = 177;
var multiplyCompensateX = 0.999998;
var multiplyCompensateY = 0.99999;
var breakDistance = 1000;

var notifierEnabled = false;
var notifierInterval = 30;
var notifierDelay = 10;

var drawingRoute = false;
var drawingField = false;
var routeLineWidth = 5;
var routeLineColor = "#ff0000";
var routeArray = new Array();
var fieldIndex = 1;
var fieldLineWidth = 3;
var fieldLineColor = "#00ff00";
var fieldArray = new Array();

var defineHomePositionWithAddress = false;

var routeIndex = 1;
var fieldIndex = 1;

var isMobile = false; 

var currentRouteX = 0;
var currentRouteY = 0;
var doubleClickX = 0;
var doubleClickY = 0;

var currentFieldX = 0;
var currentFieldY = 0;

var annotationMaxWidth = 57;
var annotationMaxHeight = 90;

var mapLayers = new Array();
var mapMaxZoom = new Array();

var currentRouteStart;
var currentRouteEnd;
var currentRouteWaypointsURL;
var currentRouteDistance;
var currentRouteTime;

mapLayers["1_1"] = "peltolohkorekisteri:Peltolohkorekisteri";

mapLayers["2_1"] = "peruskartta";
mapLayers["2_2"] = "ortokuva";
mapLayers["2_3"] = "taustakartta";

mapMaxZoom[mapLayers["2_1"]] = 90000;
mapMaxZoom[mapLayers["2_2"]] = 10000;
mapMaxZoom[mapLayers["2_3"]] = 2000000;

var currentForeGroundLayer = "";
var currentBackGroundLayer = mapLayers["2_3"];

var forceOpenField = 0;
var forceOpenNotice = 0;

var forceAddNotice = false;
var forceAddField = false;
var noticePreDefinedAddress = "";

var rePositionNotice = 0;

var rePositionField = 0;
var reDrawField = 0;

var clientLoggedIn = false;
var routeStepper = 2;

var targetLocationTitle = "";
var targetLocationLatitude = 0;
var targetLocationLongitude = 0;

var overRideDoubleClickLatitude = 0;
var overRideDoubleClickLongitude = 0;
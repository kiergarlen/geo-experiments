	var map, baseMaps;
	window.onresize = function(event) {
		resizeDiv();
	};

	function resizeDiv() {
		//vpw = $(window).width();
		vph = $(window).height() - $('#appbar').height();
		//$('#map').css({'height': vph + 'px'});
		$('#map').css({'height': vph + 'px'});
	}

	function clearMap() {
		for(var i in map._layers) {
			if(map._layers[i]._path !== undefined) {
				try {
					map.removeLayer(map._layers[i]);
				}
				catch(e) {
					//console.log("problem with " + e + map._layers[i]);
				}
			}
		}
	}

	function initmap() {
		// set up the map
		var ceaAttrib = "Sistemas de Información del Agua, CEA Jalisco ";
		//ceajalisco.map-6yctxbd3
		//ceajalisco.map-q1ze88ua
		var mapBoxBase = new L.TileLayer('http://{s}.tiles.mapbox.com/v3/ceajalisco.map-stx9qnd2/{z}/{x}/{y}.png', {
			detectRetina: true,
			retinaVersion: 'ceajalisco.map-stx9qnd2',
			subdomains: ['a', 'b', 'c', 'd'],
			attribution: ceaAttrib + 'Tiles Courtesy of <a href="http://www.mapbox.com/" target="_blank">MapBox</a>.'
		});
		//ceajalisco.map-x3sxiv5i
		var mapBoxTerrain = new L.TileLayer('http://{s}.tiles.mapbox.com/v3/ceajalisco.map-2drpjqxi/{z}/{x}/{y}.png', {
			detectRetina: true,
			retinaVersion: 'ceajalisco.map-2drpjqxi',
			subdomains: ['a', 'b', 'c', 'd'],
			attribution: ceaAttrib + 'Tiles Courtesy of <a href="http://www.mapbox.com/" target="_blank">MapBox</a>.'
		});
		var mapBoxSatellite = new L.TileLayer('http://{s}.tiles.mapbox.com/v3/jcsanford.map-c487ey3y/{z}/{x}/{y}.png', {
			maxZoom: 18,
			subdomains: ['a', 'b', 'c', 'd'],
			attribution: ceaAttrib + 'Map data (c) <a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, CC-BY-SA.'
		});

		var miniMapBase = new L.TileLayer('http://{s}.tiles.mapbox.com/v3/ceajalisco.map-stx9qnd2/{z}/{x}/{y}.png', {
			detectRetina: true,
			retinaVersion: 'ceajalisco.map-stx9qnd2',
			subdomains: ['a', 'b', 'c', 'd']
		});
		var miniMapTerrain = new L.TileLayer('http://{s}.tiles.mapbox.com/v3/ceajalisco.map-2drpjqxi/{z}/{x}/{y}.png', {
			detectRetina: true,
			retinaVersion: 'ceajalisco.map-2drpjqxi',
			subdomains: ['a', 'b', 'c', 'd']
		});
		var cloudmadeUrl = "http://{s}.tile.cloudmade.com/8ee2a50541944fb9bcedded5165f09d9/1714@2x/256/{z}/{x}/{y}.png";
		cloudmadeUrl = "http://{s}.tile.cloudmade.com/8ee2a50541944fb9bcedded5165f09d9/1714/256/{z}/{x}/{y}.png";
		cloudmadeUrl = "http://{s}.tile.cloudmade.com/8ee2a50541944fb9bcedded5165f09d9/997/256/{z}/{x}/{y}.png";
		//gray
		//cloudmadeUrl = "http://{s}.tile.cloudmade.com/8ee2a50541944fb9bcedded5165f09d9/22677/256/{z}/{x}/{y}.png";
		var cloudmadeAttrib = "Imagery © CloudMade";
		var cloudmade = new L.TileLayer(cloudmadeUrl, {attribution: ceaAttrib + '-' + cloudmadeAttrib});
		//OSM tiles
		var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
		var osmAttrib = 'Map data © OpenStreetMap contributors';
		var osm = new L.TileLayer(osmUrl, {attribution: osmAttrib});
		//MapQuest Street tiles
		var mapquestAttrib = 'Tiles Courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png">';
		var mapquestStreetUrl = 'http://otile1.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png';
		var mapquestStreet = new L.TileLayer(mapquestStreetUrl, {attribution: ceaAttrib + '-' + osmAttrib + '-' + mapquestAttrib});
		//MapQuest Sattelite tiles
		var mapquestSatteliteAttrib = "Portions Courtesy NASA/JPL-Caltech and U.S. Depart. of Agriculture, Farm Service Agency";
		var mapquestSatelliteUrl = 'http://otile1.mqcdn.com/tiles/1.0.0/sat/{z}/{x}/{y}.png';
		var mapquestSatellite = new L.TileLayer(mapquestSatelliteUrl, {attribution: ceaAttrib + '-' + osmAttrib + '-' + mapquestSatteliteAttrib + '-' + mapquestAttrib});
		//baseMaps = {
		//	"Base": mapBoxBase,
		//	"Terreno": mapBoxTerrain,
		//	"Satélite": mapBoxSatellite
		//};

		var googleRoad = new L.Google('ROADMAP');
		var googleTerrain = new L.Google('TERRAIN');
		var googleSatellite = new L.Google('SATELLITE');

		baseMaps = {
			"Mapa": googleRoad,
			"Terreno": googleTerrain,
			"Satélite": googleSatellite
		};
		map = new L.Map('map', {
			center: new L.LatLng(20.9528,-102.746),
			zoom: 9,
			trackResize: true
		});
		//map.addLayer(mapBoxTerrain);
		map.addLayer(googleRoad);
		//L.control.zoom().addTo(map);
		L.control.scale({imperial:false, updateWhenIdle:true}).addTo(map);
		//var minimap = new L.Control.MiniMap(mapquestStreet, { toggleDisplay: true, zoomLevelOffset: -5, autoToggleDisplay: true }).addTo(map);
	}
	initmap();
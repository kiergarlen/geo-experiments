$(document).ready(function () {
	'use strict';
	resizeDiv();

	//L.graticule({
	//	interval: 1,
	//	style: {
	//		color: '#ccc',
	//		weight: 1
	//	}
	//}).addTo(map);
	//L.control.zoom().addTo(map);
	//L.control.scale({imperial:false, updateWhenIdle:true}).addTo(map);
	//L.control.attribution({prefix:false}).addAttribution('CEA Jalisco-UEAS').addTo(map);
	var overlayMaps = [];
	var layersControl = {};
	var ptarStatus = [
		{
			"o":0,
			"s":"Fuera De Operación"
		},
		{
			"o":1,
			"s":"En Operación Dentro De Norma"
		},
		{
			"o":3,
			"s":"En Construcción"
		},
		{
			"o":4,
			"s":"En Rehabilitación"
		},
		{
			"o":9,
			"s":"En Proceso De Estabilización"
		},
		{
			"o":14,
			"s":"En Operación Fuera De Norma"
		}
	];
	var locColors = [
		'#67000d',
		'#a50f15',
		'#cb181d',
		'#ef3b2c',
		'#fb6a4a',
		'#fc9272',
		'#fcbba1',
		'#fee0d2',
		'#fff5f0'
	];
	var riverStyle = {
		color: '#0078ff',
		weight: 2,
		opacity: 0.75
	};
	var districtStyle = {
		color: '#fafafa',
		weight: 2,
		fillColor: '#666',
		opacity: 1,
		fillOpacity: 0.25
	};
	var districtVerdeStyle = {
		color: '#fafafa',
		weight: 2,
		fillColor: '#222',
		opacity: 1,
		fillOpacity: 0.25
	};
	var urbanPolyStyle = {
		color: '#efa533',
		fillColor: '#ffb543',
		weight: 1,
		opacity: 1,
		fillOpacity: 0.125
	};
	var damMarkerStyle = {
		radius: 3,
		fillColor: '#ec8040',
		color: '#804000',
		weight: 1,
		opacity: 1,
		fillOpacity: 1
	};

	var processFeatures = function(feature, layer) {
		var popupContent = '';
		if (feature.properties && feature.properties.name) {
			popupContent += feature.properties.name;
		}
		layer.bindPopup(popupContent);
	};

	var processDistrictsFeatures = function(feature, layer) {
		var popupContent = '';
		if (feature.properties) {
			if (feature.properties.id) {
				popupContent += '(' + feature.properties.id + ')<br>';
			}
			if (feature.properties.name) {
				popupContent += feature.properties.name;
			}
		}
		layer.bindPopup(popupContent);
	};

	var damIcon = L.icon({
		iconUrl: '../../images/map/iconPresa@2x.png',
		iconSize: [27, 18],
		iconAnchor: [13, 18],
		popupAnchor: [0, -14]
	});

	var ptarIcon = L.icon({
		iconUrl: '../../images/map/iconPtar@2x.png',
		iconSize: [27, 17],
		iconAnchor: [13, 17],
		popupAnchor: [0, -14]
	});

	var processDamMarkers = function(feature, latlng) {
		return L.marker(latlng, {icon: damIcon});
	};

	var processLocMarkers = function(feature, latlng) {
		return L.circleMarker(latlng, {
			//radius: 2 + (feature.properties.lv / 2),
			radius: 3 + feature.properties.lv,
			fillColor: locColors[feature.properties.lv - 1],
			color: locColors[0],
			weight: 1,
			opacity: 1,
			fillOpacity: 0.75,
			stroke: true
		});
	};

	var processPtarMarkers = function(feature, latlng) {
		return L.marker(latlng, {icon: ptarIcon});
	};

	var processLocsFeatures = function(feature, layer) {
		var popupContent = '';
		if (feature.properties) {
			if (feature.properties.id) {
				popupContent += '(' + feature.properties.id + ')<br>';
			}
			if (feature.properties.name) {
				popupContent += feature.properties.name + '<br>';
			}
			if (feature.properties.p) {
				popupContent += '<h6>Población:' + feature.properties.p + '</h6>';
			}
		}
		layer.bindPopup(popupContent);
	};

	var processPtarsFeatures = function(feature, layer) {
		var popupContent = '';
		if (feature.properties) {
			if (feature.properties.cve) {
				popupContent += '(' + feature.properties.cve + ')<br>';
			}
			if (feature.properties.name) {
				popupContent += feature.properties.name + '<br>';
			}
			if (feature.properties.g) {
				popupContent += 'Capacidad:' + feature.properties.g + ' l/s<br>';
			}
			if (feature.properties.h) {
				popupContent += 'Habs. bene.:' + feature.properties.h + '<br>';
			}
		}
		layer.bindPopup(popupContent);
	};

	var processWaterBodiesFeatures = function(feature, layer) {
		var popupContent = '';
		var wbHierarchies = [
			'Cuerpos de agua principales',
			'Presas principales',
			'Presas',
			'Otros cuerpos de agua'
		];
		if (feature.properties) {
			if (feature.properties.id) {
				popupContent += '(' + feature.properties.id + ')<br>';
			}
			if (feature.properties.name) {
				popupContent += feature.properties.name + '<br>';
			}
			if (feature.properties.hierarchy) {
				popupContent += 'Grupo:' + wbHierarchies[feature.properties.hierarchy - 1] + '<br>';
			}
		}
		layer.bindPopup(popupContent);
	};

	var processBasinsFeatures = function(feature, layer) {
		var popupContent = '';
		var wbHierarchies = [
			'Cuerpos de agua principales',
			'Presas principales',
			'Presas',
			'Otros cuerpos de agua'
		];
		if (feature.properties) {
			if (feature.properties.id) {
				popupContent += '(' + feature.properties.id + ')<br>';
			}
			if (feature.properties.name) {
				popupContent += feature.properties.name + '<br>';
			}
		}
		layer.bindPopup(popupContent);
	};

	var districtsSantiagoGeoLayer = L.geoJson(riversDistrictsSantiagoGeo, {
		style: districtStyle,
		onEachFeature: processDistrictsFeatures
	});

	var districtsVerdeGeoLayer = L.geoJson(riversDistrictsVerdeGeo, {
		style: districtVerdeStyle,
		onEachFeature: processDistrictsFeatures
	});

	var urbanPolysGeoLayer = L.geoJson(riversUrbanPolysGeo, {
		style: urbanPolyStyle,
		onEachFeature: processFeatures
	});

	////DELETE ME LATER
	//var urbanPolysGeoPrimeLayer = L.geoJson(riversUrbanPolysGeoPrime, {
	//	style: urbanPolyStyle,
	//	onEachFeature: processFeatures
	//});

	var riversGeoLayer = L.geoJson(riversGeo, {
		style: riverStyle,
		onEachFeature: processFeatures
	});

	var damsGeoLayer = L.geoJson(riversDamsGeo, {
		pointToLayer: processDamMarkers,
		onEachFeature: processDistrictsFeatures
	});

	var locs1GeoLayer = L.geoJson(riversLocs1Geo, {
		pointToLayer: processLocMarkers,
		onEachFeature: processLocsFeatures
	});

	var locs2GeoLayer = L.geoJson(riversLocs2Geo, {
		pointToLayer: processLocMarkers,
		onEachFeature: processLocsFeatures
	});

	var locs3GeoLayer = L.geoJson(riversLocs3Geo, {
		pointToLayer: processLocMarkers,
		onEachFeature: processLocsFeatures
	});

	var locs4GeoLayer = L.geoJson(riversLocs4Geo, {
		pointToLayer: processLocMarkers,
		onEachFeature: processLocsFeatures
	});

	var locs5GeoLayer = L.geoJson(riversLocs5Geo, {
		pointToLayer: processLocMarkers,
		onEachFeature: processLocsFeatures
	});

	var locs6GeoLayer = L.geoJson(riversLocs6Geo, {
		pointToLayer: processLocMarkers,
		onEachFeature: processLocsFeatures
	});

	var locs7GeoLayer = L.geoJson(riversLocs7Geo, {
		pointToLayer: processLocMarkers,
		onEachFeature: processLocsFeatures
	});

	var locs8GeoLayer = L.geoJson(riversLocs8Geo, {
		pointToLayer: processLocMarkers,
		onEachFeature: processLocsFeatures
	});

	var ptarsGeoLayer = L.geoJson(riversPtarsGeo, {
		pointToLayer: processPtarMarkers,
		onEachFeature: processPtarsFeatures
	});

	var ptarsNoOpGeoLayer = L.geoJson(riversPtarsNoOpGeo, {
		pointToLayer: processPtarMarkers,
		onEachFeature: processPtarsFeatures
	});

	var waterBodiesGeoLayer = L.geoJson(riversWaterBodiesGeo, {
		style: {
			color: '#00f',
			weight: 1,
			fillColor: '#00f',
			opacity: 1,
			fillOpacity: 0.5
		},
		onEachFeature: processWaterBodiesFeatures
	});

	//var waterBodiesGeoLayerComplete = L.geoJson(riversWaterBodiesGeoComplete, {
	//	style: {
	//		color: '#f00',
	//		weight: 1,
	//		fillColor: '#f00',
	//		opacity: 1,
	//		fillOpacity: 1
	//	},
	//	onEachFeature: processWaterBodiesFeatures
	//});

	var metroAreasGeo = L.geoJson(riversMetroAreasGeo, {
		style: {
			color: '#913383',
			weight: 1,
			fillColor: '#b163a3',
			opacity: 1,
			fillOpacity: 0.5,
			stroke: false
		},
		onEachFeature: processFeatures
	});

	var getBasinColor = function(idx) {
		var colors = {
			'16': '#88a8af',
			'19': '#28484f'
		};
		return colors[idx];
	};
	var basinsGeo = L.geoJson(riversBasinsGeo, {
		style: function style(feature) {
			return {
				fillColor: getBasinColor(feature.properties.id),
				weight: 2,
				opacity: 0.5,
				color: getBasinColor(feature.properties.id),
				dashArray: '5',
				fillOpacity: 0.5
			};
		},
		onEachFeature: processBasinsFeatures
	});

	var layers = [
		{
			name: 'Cuencas',
			layer: basinsGeo
		},
		{
			name: 'Municipios, Cuenca del Río Santiago',
			layer: districtsSantiagoGeoLayer
		},
		{
			name: 'Municipios, Cuenca del Río Verde',
			layer: districtsVerdeGeoLayer
		},
		{
			name: 'Zonas Metropolitanas',
			layer: metroAreasGeo
		},
		{
			name: 'Polígonos urbanos',
			layer: urbanPolysGeoLayer
		},
		{
			name: 'Cuerpos de agua',
			layer: waterBodiesGeoLayer
		},
		//{
		//	name: 'Cuerpos de agua (Otros)',
		//	layer: waterBodiesGeoLayerComplete
		//},
		{
			name: 'Ríos',
			layer: riversGeoLayer
		},
		{
			name: 'Presas',
			layer: damsGeoLayer
		},
		{
			name: 'PTAR\'s (fuera de operación)',
			layer: ptarsNoOpGeoLayer
		},
		{
			name: 'PTAR\'s (operando)',
			layer: ptarsGeoLayer
		},
		{
			name: 'Localidades >100,000 habs.',
			layer: locs8GeoLayer
		},
		{
			name: 'Localidades 50,000-99,999 habs.',
			layer: locs7GeoLayer
		},
		{
			name: 'Localidades 20,000-49,999 habs.',
			layer: locs6GeoLayer
		},
		{
			name: 'Localidades 2,500-19,999 habs',
			layer: locs5GeoLayer
		},
		{
			name: 'Localidades 1,000-2,499 habs.',
			layer: locs4GeoLayer
		},
		{
			name: 'Localidades 500-999 habs.',
			layer: locs3GeoLayer
		},
		{
			name: 'Localidades 250-499 habs.',
			layer: locs2GeoLayer
		},
		{
			name: 'Localidades 1-249 habs.',
			layer: locs1GeoLayer
		}
	];

	function addMapLayerGroup(layers) {
		var i,l;
		l = layers.length;
		for (i = 0; i < l; i++) {
			overlayMaps[layers[i].name] = layers[i].layer;
		}
	}
	addMapLayerGroup(layers);
	layersControl = L.control.layers(baseMaps, overlayMaps).addTo(map);
});
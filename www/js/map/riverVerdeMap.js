/*jslint browser: true, white: true */
/*global $, L, map, baseMaps, resizeDiv, riversDistrictsSantiagoGeo, riversDistrictsVerdeGeo, riversUrbanPolysGeo, riversGeo, riversBasinsGeo, damsGeo, riversLocs1Geo, riversLocs2Geo, riversLocs3Geo, riversLocs4Geo, riversLocs5Geo, riversLocs6Geo, riversLocs7Geo, riversLocs8Geo, riversPtarsGeo, riversPtarsNoOpGeo, riversWaterBodiesGeo, riversMetroAreasGeo */
$(document).ready(function () {
  'use strict';
  //ptarStatus = [
  //  {"o":0,"s":"Fuera De Operación"},
  //  {"o":1,"s":"En Operación Dentro De Norma"},
  //  {"o":3,"s":"En Construcción"},
  //  {"o":4,"s":"En Rehabilitación"},
  //  {"o":9,"s":"En Proceso De Estabilización"},
  //  {"o":14,"s":"En Operación Fuera De Norma"}
  //],
  //damMarkerStyle = {
  //  radius: 3,
  //  fillColor: '#ec8040',
  //  color: '#804000',
  //  weight: 1,
  //  opacity: 1,
  //  fillOpacity: 1
  //},
  var overlayMaps = [],
  layersControl = {},
  locColors = [
    '#67000d',
    '#a50f15',
    '#cb181d',
    '#ef3b2c',
    '#fb6a4a',
    '#fc9272',
    '#fcbba1',
    '#fee0d2',
    '#fff5f0'
  ],
  waterBodyStyle = {
    color: '#0000ff',
    weight: 1,
    fillColor: '#0000ff',
    opacity: 1,
    fillOpacity: 0.5
  },
  wbHierarchies = [
    'Cuerpos de agua principales',
    'Presas principales',
    'Presas',
    'Otros cuerpos de agua'
  ],
  riverStyle = {
    color: '#0078ff',
    weight: 2,
    opacity: 0.75
  },
  districtStyle = {
    color: '#fafafa',
    weight: 2,
    fillColor: '#666',
    opacity: 1,
    fillOpacity: 0.25
  },
  districtVerdeStyle = {
    color: '#fafafa',
    weight: 2,
    fillColor: '#222',
    opacity: 1,
    fillOpacity: 0.25
  },
  urbanPolyStyle = {
    color: '#efa533',
    fillColor: '#ffb543',
    weight: 1,
    opacity: 1,
    fillOpacity: 0.125
  },
  metroAreaStyle = {
    color: '#913383',
    weight: 1,
    fillColor: '#b163a3',
    opacity: 1,
    fillOpacity: 0.5,
    stroke: false
  },
  regionNames = [
    'Norte',
    'Altos - Norte',
    'Altos - Sur',
    'Ciénega',
    'Sureste',
    'Sur',
    'Sierra de Amula',
    'Costa - Sur',
    'Costa - Norte',
    'Sierra Occidental',
    'Valles',
    'Centro'
  ],
  regionColors = [
    '#8a743a',
    '#688a3e',
    '#6b25a8',
    '#2685ad',
    '#27b037',
    '#a84a84',
    '#42ad81',
    '#a2852a',
    '#a66646',
    '#76ad28',
    '#90963c',
    '#99202a'
  ],
  basinColors = {
    '1':'#33b04a',
    '2':'#4863b0',
    '3':'#2499ad',
    '4':'#278273',
    '5':'#934426',
    '6':'#438521',
    '7':'#87651d',
    '8':'#3e8a5c',
    '9':'#748a1e',
    '10':'#4778a1',
    '11':'#a89b28',
    '12':'#9728a8',
    '13':'#3146b1',
    '14':'#b13035',
    '15':'#feff73',
    '16':'#b09d58',
    '17':'#ffaa01',
    '18':'#552998',
    '19':'#aa3f83',
    '20':'#5b3c82'
  },
  processFeatures = function(feature, layer) {
    var popupContent = '';
    if (feature.properties && feature.properties.name) {
      popupContent += feature.properties.name;
    }
    layer.bindPopup(popupContent);
  },
  processDistrictsToRegions = function(feature, layer) {
    var popupContent = '';
    if (feature.properties) {
      if (feature.properties.id) {
        popupContent += '(' + feature.properties.ra + ')<br>';
      }
      if (feature.properties.name) {
        popupContent += regionNames[feature.properties.ra - 1];
      }
    }
    layer.bindPopup(popupContent);
  },
  processDistrictsFeatures = function(feature, layer) {
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
  },
  damIcon = L.icon({
    iconUrl: '../../images/map/iconPresa@2x.png',
    iconSize: [27, 18],
    iconAnchor: [13, 18],
    popupAnchor: [0, -14]
  }),
  ptarIcon = L.icon({
    iconUrl: '../../images/map/iconPtar@2x.png',
    iconSize: [27, 17],
    iconAnchor: [13, 17],
    popupAnchor: [0, -14]
  }),
  processDamMarkers = function(feature, latlng) {
    return L.marker(latlng, {icon: damIcon});
  },
  processLocMarkers = function(feature, latlng) {
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
  },
  processPtarMarkers = function(feature, latlng) {
    return L.marker(latlng, {icon: ptarIcon});
  },
  processLocsFeatures = function(feature, layer) {
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
  },
  processPtarsFeatures = function(feature, layer) {
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
  },
  processWaterBodiesFeatures = function(feature, layer) {
    var popupContent = '';
    if (feature.properties) {
      if (feature.properties.id) {
        popupContent += '(' + feature.properties.id + ')<br>';
      }
      if (feature.properties.name) {
        popupContent += feature.properties.name + '<br>';
      }
      if (feature.properties.hierarchy) {
        popupContent += 'Grupo:';
        popupContent += wbHierarchies[feature.properties.hierarchy - 1];
        popupContent += ' [' + feature.properties.hierarchy + ']<br>';
      }
    }
    layer.bindPopup(popupContent);
  },
  processBasinsFeatures = function(feature, layer) {
    var popupContent = '';
    if (feature.properties) {
      if (feature.properties.id) {
        popupContent += '(' + feature.properties.id + ')<br>';
      }
      if (feature.properties.name) {
        popupContent += feature.properties.name + '<br>';
      }
    }
    layer.bindPopup(popupContent);
  },
  processUrbanPolysFeatures = function(feature, layer) {
    var popupContent = '';
    if (feature.properties) {
      if (feature.properties.id) {
        popupContent += '(' + feature.properties.id + ')<br>';
      }
      if (feature.properties.id) {
        popupContent += '[' + feature.properties.c + ']<br>';
      }
      if (feature.properties.name) {
        popupContent += feature.properties.name + '<br>';
      }
    }
    layer.bindPopup(popupContent);
  },
  districtsGeoLayer = L.geoJson(districtsGeo, {
    style: districtStyle,
    onEachFeature: processDistrictsFeatures
  }),
  regionsGeoLayer = L.geoJson(districtsGeo, {
    style: function style(feature) {
    return {
      fillColor: regionColors[feature.properties.ra - 1],
      weight:2,
      opacity:0.1,
      color: regionColors[feature.properties.ra - 1],
      dashArray:'5',
      fillOpacity:0.5,
      stroke:true
    };
  },
    onEachFeature: processDistrictsToRegions
  }),
  urbanPolysGeoLayer = L.geoJson(riversUrbanPolysFullGeo, {
    style: urbanPolyStyle,
    onEachFeature: processUrbanPolysFeatures
  }),
  riversGeoLayer = L.geoJson(riversGeo, {
    style: riverStyle,
    onEachFeature: processFeatures
  }),
  damsGeoLayer = L.geoJson(damsGeo, {
    pointToLayer: processDamMarkers,
    onEachFeature: processDistrictsFeatures
  }),
  locs1GeoLayer = L.geoJson(riversLocs1Geo, {
    pointToLayer: processLocMarkers,
    onEachFeature: processLocsFeatures
  }),
  locs2GeoLayer = L.geoJson(riversLocs2Geo, {
    pointToLayer: processLocMarkers,
    onEachFeature: processLocsFeatures
  }),
  locs3GeoLayer = L.geoJson(riversLocs3Geo, {
    pointToLayer: processLocMarkers,
    onEachFeature: processLocsFeatures
  }),
  locs4GeoLayer = L.geoJson(riversLocs4Geo, {
    pointToLayer: processLocMarkers,
    onEachFeature: processLocsFeatures
  }),
  locs5GeoLayer = L.geoJson(riversLocs5Geo, {
    pointToLayer: processLocMarkers,
    onEachFeature: processLocsFeatures
  }),
  locs6GeoLayer = L.geoJson(riversLocs6Geo, {
    pointToLayer: processLocMarkers,
    onEachFeature: processLocsFeatures
  }),
  locs7GeoLayer = L.geoJson(riversLocs7Geo, {
    pointToLayer: processLocMarkers,
    onEachFeature: processLocsFeatures
  }),
  locs8GeoLayer = L.geoJson(riversLocs8Geo, {
    pointToLayer: processLocMarkers,
    onEachFeature: processLocsFeatures
  }),
  ptarsGeoLayer = L.geoJson(riversPtarsGeo, {
    pointToLayer: processPtarMarkers,
    onEachFeature: processPtarsFeatures
  }),
  ptarsNoOpGeoLayer = L.geoJson(riversPtarsNoOpGeo, {
    pointToLayer: processPtarMarkers,
    onEachFeature: processPtarsFeatures
  }),
  waterBodiesGeoLayer = L.geoJson(waterBodiesGeo, {
    style: function style(feature) {
      return {
        fillColor:'#0000ff',
        weight:1,
        opacity:0.5 - (feature.properties.hierarchy / 10),
        color:'#0000ff',
        fillOpacity:0.5 - (feature.properties.hierarchy / 10)
      };
    },
    onEachFeature: processWaterBodiesFeatures
  }),
  metroAreasGeo = L.geoJson(riversMetroAreasGeo, {
    style: metroAreaStyle,
    onEachFeature: processFeatures
  }),
  getBasinColor = function(idx) {
    //var colors = {
    //  '16': '#88a8af',
    //  '19': '#28484f'
    //};
    return basinColors[idx];
  },
  basinsGeo = L.geoJson(riversBasinsGeo, {
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
  }),
  subBasinsGeo = L.geoJson(riversSubBasinsGeo, {
    style: function style(feature) {
      return {
        fillColor: getBasinColor(feature.properties.c),
        weight: 2,
        opacity: 0.5,
        color: '#ffffff',
        dashArray: '2',
        fillOpacity: 0.5
      };
    },
    onEachFeature: processBasinsFeatures
  }),
  layers = [
    {name: 'Cuencas', layer: basinsGeo},
    {name: 'Subcuencas', layer: subBasinsGeo},
    {name: 'Regiones Administrativas', layer: regionsGeoLayer},
    {name: 'Municipios', layer: districtsGeoLayer},
    {name: 'Zonas Metropolitanas', layer: metroAreasGeo},
    {name: 'Polígonos urbanos', layer: urbanPolysGeoLayer},
    {name: 'Cuerpos de agua', layer: waterBodiesGeoLayer},
    {name: 'Ríos', layer: riversGeoLayer},
    {name: 'Principales Presas', layer: damsGeoLayer},
    {name: 'PTAR\'s (fuera de operación)', layer: ptarsNoOpGeoLayer},
    {name: 'PTAR\'s (operando)', layer: ptarsGeoLayer},
    {name: 'Localidades >100,000 habs.', layer: locs8GeoLayer},
    {name: 'Localidades 50,000-99,999 habs.', layer: locs7GeoLayer},
    {name: 'Localidades 20,000-49,999 habs.', layer: locs6GeoLayer},
    {name: 'Localidades 2,500-19,999 habs', layer: locs5GeoLayer},
    {name: 'Localidades 1,000-2,499 habs.', layer: locs4GeoLayer},
    {name: 'Localidades 500-999 habs.', layer: locs3GeoLayer},
    {name: 'Localidades 250-499 habs.', layer: locs2GeoLayer},
    {name: 'Localidades 1-249 habs.', layer: locs1GeoLayer}
  ],
  addMapLayerGroup = function(layers) {
    var i,l;
    l = layers.length;
    for (i = 0; i < l; i += 1) {
      overlayMaps[layers[i].name] = layers[i].layer;
    }
  };
  addMapLayerGroup(layers);
  resizeDiv();
  layersControl = L.control.layers(baseMaps, overlayMaps).addTo(map);
});
var map,
baseMaps,
resizeDiv = function() {
  //vpw = $(window).width();
  vph = $(window).height() - $('#appbar').height();
  //$('#map').css({'height': vph + 'px'});
  $('#map').css({'height': vph + 'px'});
},
clearMap = function () {
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
},
initmap = function() {
  var ceaAttrib = "Sistemas de Información del Agua, CEA Jalisco ",
    googleRoad = new L.Google('ROADMAP'),
    googleTerrain = new L.Google('TERRAIN'),
    googleSatellite = new L.Google('SATELLITE'),
    mapBoxBase = new L.TileLayer('', {attribution: ceaAttrib});
/*
new L.TileLayer('http://{s}.tiles.mapbox.com/v3/ceajalisco.map-stx9qnd2/{z}/{x}/{y}.png', {
      subdomains: ['a', 'b', 'c', 'd']
    });
*/
  baseMaps = {
    "Mapa": googleRoad,
    "Terreno": googleTerrain,
    "Satélite": googleSatellite,
    "Base": mapBoxBase
  };
  map = new L.Map('map', {
    center: new L.LatLng(20.617201,-103.516438),
    zoom: 8
    //,
    //maxBounds: [[18.346137,-107.035391],[23.474739,-98.846883]]
  });
  map.addLayer(mapBoxBase);
  L.control.scale({imperial:false, updateWhenIdle:true}).addTo(map);
};
window.onresize = function(event) {
  resizeDiv();
};
initmap();
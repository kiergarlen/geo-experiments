/*jslint browser: true, white: true */
/*global $, L, map, baseMaps, resizeDiv, omnivore */
$(document).ready(function () {
  'use strict';
  var overlayMaps = [],
  layersControl = {},
  customPopupOptions = {
    'minWidth': '200',
    'maxWidth': '610'
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
  ppotIcon = L.icon({
    iconUrl: '../../images/map/iconPpot@2x.png',
    iconSize: [26, 23],
    iconAnchor: [13, 12],
    popupAnchor: [0, -12]
  }),
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
  basinColors = [
    '#33b04a',
    '#4863b0',
    '#2499ad',
    '#278273',
    '#934426',
    '#438521',
    '#87651d',
    '#3e8a5c',
    '#748a1e',
    '#4778a1',
    '#a89b28',
    '#9728a8',
    '#3146b1',
    '#b13035',
    '#feff73',
    '#b09d58',
    '#ffaa01',
    '#552998',
    '#aa3f83',
    '#5b3c82'
  ],
  admBasinsColors = [
    '#402f5f',//dark purple
    '#759e40',//light, desaturated green
    '#e78f19',//Koi
    '#cf5b36',//Vivacious
    '#0b3e7b'//Mykonos blue
  ],
  processDamMarkers = function(feature, latlng) {
    return L.marker(latlng, {icon: damIcon});
  },
  processLocMarkers = function(feature, latlng) {
    var lvl = 1;
    if (feature.properties.lv !== undefined) {
      lvl = feature.properties.lv;
    }
    return L.circleMarker(latlng, {
      //radius: 2 + (lvl / 2),
      radius: 3 + lvl,
      fillColor: locColors[lvl - 1],
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
  processPpotMarkers = function(feature, latlng) {
    return L.marker(latlng, {icon: ppotIcon});
  },
  processFeatures = function(feature, layer) {
    layer.bindPopup(L.Util.template(
      '<section><small class="text-muted">Id</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header></section>',
      feature.properties
      )
    );
  },
  processIrrigationFeatures = function(feature, layer) {
    layer.bindPopup(L.Util.template(
      '<section><small class="text-muted">Distrito de riego</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header><br>Área: <strong>{a}</strong> ha</section>',
      feature.properties)
    );
  },
  processDamsFeatures = function(feature, layer) {
    layer.bindPopup(L.Util.template(
      '<section class="hidden-xs hidden-sm" style="display:block; width:770px; height:400px;"><iframe src="ficha/presa.php?id={id}" style="width:770px;height:400px;border:0;"></iframe></section><section class="hidden-md hidden-lg"><small class="text-muted">Presa</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header>Volumen máximo: <strong>{vmax}</strong> Mm3<br>Municipio: <strong>{mun}</strong><br><a class="btn btn-default btn-md btn-block"href="ficha/presa.php?id={id}"target="_blank"><span class="text-muted">Más información</span></a></section>',
      feature.properties
      ),
      {
        'minWidth': '200',
        'maxWidth': '790'
      }
    );
  },
  processAcuifersFeatures = function(feature, layer) {
    layer.bindPopup(L.Util.template(
      '<section><small class="text-muted">Acuífero</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header><br>Disponibilidad: <strong>{v}</strong> Mm3/año</section>',
      feature.properties)
    );
  },
  processRiverFeatures = function(feature, layer) {
    var riverLevels = [
      'Río principal',
      'Río',
      'Río afluente',
      'Corriente',
      'Otros'
    ];
    layer.bindPopup(L.Util.template(
      '<section><small class="text-muted">Río</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header><br>Categoría: <strong>{c}</strong></section>',
      {
        name: feature.properties.name,
        id: feature.properties.id,
        l: feature.properties.l,
        c: riverLevels[feature.properties.l - 1]
      }
      )
    );
  },
  processAdmBasins = function(feature, layer) {
    layer.bindPopup(L.Util.template(
      '<section class="hidden-xs hidden-sm" style="display:block; width:570px; height:320px;"><iframe src="ficha/adm_cue.php?id={id}" style="width:560px;height:300px;border:0;"></iframe></section><section class="hidden-md hidden-lg"><small class="text-muted">Región Hidrológico-Adminiva.</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header><a class="btn btn-default btn-md btn-block"href="ficha/adm_cue.php?id={id}"target="_blank"><span class="text-muted">Más información</span></a></section>',
      feature.properties
      ),
      customPopupOptions
    );
  },
  processRegions = function(feature, layer) {
    layer.bindPopup(L.Util.template(
      '<section class="hidden-xs hidden-sm" style="display:block; width:570px; height:320px;"><iframe src="ficha/adm_reg.php?id={id}" style="width:560px;height:300px;border:0;"></iframe></section><section class="hidden-md hidden-lg"><small class="text-muted">Región adminiva.</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header><a class="btn btn-default btn-md btn-block"href="ficha/adm_reg.php?id={id}"target="_blank"><span class="text-muted">Más información</span></a></section>',
      feature.properties
      ),
      customPopupOptions
    );
  },
  processDistrictsFeatures = function(feature, layer) {
    layer.bindPopup(L.Util.template(
      '<section class="hidden-xs hidden-sm" style="display:block; width:570px; height:320px;"><iframe src="ficha/mun.php?id={id}" style="width:560px;height:300px;border:0;"></iframe></section><section class="hidden-md hidden-lg"><small class="text-muted">Municipio</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header><a class="btn btn-default btn-md btn-block"href="ficha/mun.php?id={id}"target="_blank"><span class="text-muted">Más información</span></a></section>',
      feature.properties
      ),
      customPopupOptions
    );
  },
  processLocsFeatures = function(feature, layer) {
    layer.bindPopup(L.Util.template(
      '<section class="hidden-xs hidden-sm" style="display:block; width:570px; height:320px;"><iframe src="ficha/loc.php?id={id}" style="width:560px;height:300px;border:0;"></iframe></section><section class="hidden-md hidden-lg"><small class="text-muted">Localidad</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header><p>{p} habs. <br><small class="text-muted">(INEGI 2010)</small></p><a class="btn btn-default btn-md btn-block"href="ficha/loc.php?id={id}"target="_blank"><span class="text-muted">Más información</span></a></section>',
      feature.properties
      ),
      customPopupOptions
    );
  },
  processPtarsFeatures = function(feature, layer) {
    layer.bindPopup(L.Util.template(
      '<section><small class="text-muted">Planta de tratamiento</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header><br>Capacidad: <strong>{g}</strong> l/s<br>Habs. beneficiados: <strong>{h}</strong></section>',
      feature.properties
      )
    );
  },
  processPpotsFeatures = function(feature, layer) {
    layer.bindPopup(L.Util.template(
      '<section><small class="text-muted">Planta potabilizadora</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header>Municipio: <strong>{mun}</strong><br>Localidad: <strong>{loc}</strong><br>Capacidad: <strong>{g}</strong> l/s<br>Fuente: <strong>{f}</strong><br>Proceso: <strong>{p}</strong><br>Condición: <strong>{s}</strong></section>',
      feature.properties
      )
    );
  },
  processBasinsFeatures = function(feature, layer) {
    layer.bindPopup(L.Util.template(
      '<section class="hidden-xs hidden-sm" style="display:block; width:570px; height:320px;"><iframe src="ficha/cue.php?id={id}" style="width:560px;height:300px;border:0;"></iframe></section><section class="hidden-md hidden-lg"><small class="text-muted">Cuenca</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header><a class="btn btn-default btn-md btn-block"href="ficha/cue.php?id={id}"target="_blank"><span class="text-muted">Más información</span></a></section>',
      feature.properties
      ),
      customPopupOptions
    );
  },
  processSubBasinsFeatures = function(feature, layer) {
    layer.bindPopup(L.Util.template(
      '<section><small class="text-muted">Subcuenca</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header></section>',
      feature.properties
      )
    );
    layer.bindPopup(L.Util.template(
      '<section class="hidden-xs hidden-sm" style="display:block; width:570px; height:320px;"><iframe src="ficha/sub_cue.php?id={id}" style="width:560px;height:300px;border:0;"></iframe></section><section class="hidden-md hidden-lg"><small class="text-muted">Subcuenca</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header><a class="btn btn-default btn-md btn-block"href="ficha/sub_cue.php?id={id}"target="_blank"><span class="text-muted">Más información</span></a></section>',
      feature.properties
      ),
      customPopupOptions
    );
  },
  processUrbanPolysFeatures = function(feature, layer) {
    layer.bindPopup(L.Util.template(
      '<section class="hidden-xs hidden-sm" style="display:block; width:570px; height:320px;"><iframe src="ficha/loc.php?id={id}" style="width:560px;height:310px;border:0;"></iframe></section><section class="hidden-md hidden-lg"><small class="text-muted">Localidad</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header><a class="btn btn-default btn-md btn-block"href="ficha/loc.php?id={id}"target="_blank"><span class="text-muted">Más información</span></a></section>',
      feature.properties
      ),
      {
        'minWidth': '200',
        'maxWidth': '770'
      }
    );
  },
  layers = [
    {
      name: 'Cuencas',
      layer: omnivore.topojson('../json/geo/basinsTopo30.json',
        null,
        L.geoJson(null,
          {
              style: function style(feature) {
                return {
                  fillColor: basinColors[feature.properties.id - 1],
                  weight: 2,
                  opacity: 0.5,
                  color: '#ffffff',
                  dashArray: '5',
                  fillOpacity: 0.5
              };
            },
            onEachFeature: processBasinsFeatures
          }
        )
      )
    },
    {
      name: 'Subcuencas',
      layer: omnivore.topojson('../json/geo/subBasinsTopo30.json',
        null,
        L.geoJson(null,
          {
            style: function style(feature) {
              return {
                fillColor: basinColors[feature.properties.c - 1],
                weight: 1,
                opacity: 0.5,
                color: '#ffffff',
                dashArray: '5',
                fillOpacity: 0.5
              };
            },
            onEachFeature: processSubBasinsFeatures
          }
        )
      )
    },
    {
      name: 'Límite estatal',
      layer: omnivore.topojson('../json/geo/jalStateTopo30.json',
        null,
        L.geoJson(null,
          {
            style: {
              fillColor: "#cccccc",
              weight: 4.0,
              opacity: 1,
              color: "#ffffff",
              fillOpacity: 0,
              fill: false,
              stroke: true
            },
            onEachFeature: processFeatures
          }
        )
      )
    },
    {
      name: 'Regiones Hidro. Adminivas.',
      layer: omnivore.topojson('../json/geo/admBasinsTopo30.json',
        null,
        L.geoJson(null,
          {
            style: function style(feature) {
              return {
                fillColor: admBasinsColors[feature.properties.id - 1],
                weight: 2,
                opacity: 1.0,
                color: admBasinsColors[feature.properties.id - 1],
                fillOpacity: 0.3,
                stroke: true
              };
            },
            onEachFeature: processAdmBasins
          }
        )
      )
    },
    {
      name: 'Regiones Administrativas',
      layer: omnivore.topojson('../json/geo/admRegionsTopo30.json',
        null,
        L.geoJson(null,
          {
            style: function style(feature) {
              return {
                fillColor: regionColors[feature.properties.id - 1],
                weight: 2,
                opacity: 1.0,
                color: regionColors[feature.properties.id - 1],
                fillOpacity: 0.3,
                stroke: true
              };
            },
            onEachFeature: processRegions
          }
        )
      )
    },
    {
      name: 'Municipios',
      layer: omnivore.topojson('../json/geo/districtsItjTopo30.json',
        null,
        L.geoJson(null,
          {
            style: {
              color: '#fafafa',
              weight: 2,
              fillColor: '#666',
              opacity: 1,
              fillOpacity: 0.25
            },
            onEachFeature: processDistrictsFeatures
          }
        )
      )
    },
    {
      name: 'Zonas Metropolitanas',
      layer: omnivore.topojson('../json/geo/metroAreasTopo30.json',
        null,
        L.geoJson(null,
          {
            style: {
              color: '#913383',
              weight: 1,
              fillColor: '#b163a3',
              opacity: 1,
              fillOpacity: 0.5,
              stroke: false
            },
            onEachFeature: processFeatures
          }
        )
      )
    },
    {
      name: 'Polígonos urbanos',
      layer: omnivore.topojson('../json/geo/urbanPolysTopo30.json',
        null,
        L.geoJson(null,
          {
            style: {
              color: '#efa533',
              fillColor: '#ffb543',
              weight: 1,
              opacity: 1,
              fillOpacity: 0.125
            },
            onEachFeature: processUrbanPolysFeatures
          }
        )
      )
    },
    {
      name: 'Cuerpos de agua',
      layer: omnivore.topojson('../json/geo/waterBodiesTopo30.json',
        null,
        L.geoJson(null,
          {
            style: {
              color: '#0078ff',
              weight: 1,
              fillColor: '#0078ff',
              opacity: 1,
              fillOpacity: 0.75,
              stroke: false
            },
            onEachFeature: function(feature, layer) {
              var waterBodyLevels = [
                'Cuerpos de agua principales',
                'Presas principales',
                'Presas',
                'Otros cuerpos de agua'
              ];
              layer.bindPopup(L.Util.template(
                '<section><small class="text-muted">Cuerpo de agua</small> <span class="label label-default">{id}</span><header><h4>{name}</h4></header><br>Grupo: <strong>{g}</strong></section>',
                {
                  id: feature.properties.id,
                  name: feature.properties.name,
                  g: waterBodyLevels[feature.properties.hierarchy - 1]
                }
                )
              );
            },
            filter: function(feature, layer) {
              return feature.properties.hierarchy > 0;
            }
          }
        )
      )
    },
    {
      name: 'Ríos',
      layer: omnivore.topojson('../json/geo/riversTopo30.json',
        null,
        L.geoJson(null,
          {
            style: function style(feature) {
              //var riverColors = [
              //  '#2a5afb',
              //  '#5282fc',
              //  '#81abfc',
              //  '#b2d0fe',
              //  '#d0e5ff'
              //];
              return {
                //color: riverColors[feature.properties.l - 1],
                color: '#0078ff',
                weight: 5 - Math.round(feature.properties.l / 2),
                opacity: 1.1 - ((feature.properties.l / 10) * 1.5)
              };
            },
            onEachFeature: processRiverFeatures
          }
        )
      )
    },
    {
      name: 'Distritos de Riego',
      layer: omnivore.topojson('../json/geo/irrigationDistrictsTopo30.json',
        null,
        L.geoJson(null,
          {
            style: {
              color: '#48986f',
              weight: 1,
              fillColor: '#38c87f',
              opacity: 1,
              fillOpacity: 0.75
            },
            onEachFeature: processIrrigationFeatures
          }
        )
      )
    },
    {
      name: 'Acuíferos (disponibilidad)',
      layer: omnivore.topojson('../json/geo/acuifersTopo30.json',
        null,
        L.geoJson(null,
          {
            style: function style(feature) {
              return {
                fillColor: (feature.properties.v > 0) ? '#48c86f' : '#cb1830',
                weight: 2,
                opacity: 1,
                color:  (feature.properties.v > 0) ? '#48c86f' : '#cb1830',
                fillOpacity: 0.5
              };
            },
            onEachFeature: processAcuifersFeatures
          }
        )
      )
    },
    {
      name: 'Principales Presas',
      layer: omnivore.geojson('../json/geo/damsGeo.json',
        null,
        L.geoJson(null,
          {
            pointToLayer: processDamMarkers,
            onEachFeature: processDamsFeatures
          }
        )
      )
    },
    {
      name: 'PTAR\'s (fuera de operación)',
      layer: omnivore.geojson('../json/geo/ptarsNoOpGeo.json',
        null,
        L.geoJson(null,
          {
            pointToLayer: processPtarMarkers,
            onEachFeature: processPtarsFeatures
          }
        )
      )
    },
    {
      name: 'PTAR\'s (en operación)',
      layer: omnivore.geojson('../json/geo/ptarsGeo.json',
        null,
        L.geoJson(null,
          {
            pointToLayer: processPtarMarkers,
            onEachFeature: processPtarsFeatures
          }
        )
      )
    },
    {
      name: 'Plantas potabilizadoras',
      layer: omnivore.geojson('../json/geo/ppotsGeo.json',
        null,
        L.geoJson(null,
          {
            pointToLayer: processPpotMarkers,
            onEachFeature: processPpotsFeatures
          }
        )
      )
    },
    {
      name: 'Localidades >100,000 habs.',
      layer: omnivore.geojson('../json/geo/locs8Geo.json',
        null,
        L.geoJson(null,
          {
            pointToLayer: processLocMarkers,
            onEachFeature: processLocsFeatures
          }
        )
      )
    },
    {
      name: 'Localidades 50,000-99,999 habs.',
      layer: omnivore.geojson('../json/geo/locs7Geo.json',
        null,
        L.geoJson(null,
          {
            pointToLayer: processLocMarkers,
            onEachFeature: processLocsFeatures
          }
        )
      )
    },
    {
      name: 'Localidades 20,000-49,999 habs.',
      layer: omnivore.geojson('../json/geo/locs6Geo.json',
        null,
        L.geoJson(null,
          {
            pointToLayer: processLocMarkers,
            onEachFeature: processLocsFeatures
          }
        )
      )
    },
    {
      name: 'Localidades 2,500-19,999 habs',
      layer: omnivore.geojson('../json/geo/locs5Geo.json',
        null,
        L.geoJson(null,
          {
            pointToLayer: processLocMarkers,
            onEachFeature: processLocsFeatures
          }
        )
      )
    },
    {
      name: 'Localidades 1,000-2,499 habs.',
      layer: omnivore.geojson('../json/geo/locs4Geo.json',
        null,
        L.geoJson(null,
          {
            pointToLayer: processLocMarkers,
            onEachFeature: processLocsFeatures
          }
        )
      )
    },
    {
      name: 'Localidades 500-999 habs.',
      layer: omnivore.geojson('../json/geo/locs3Geo.json',
        null,
        L.geoJson(null,
          {
            pointToLayer: processLocMarkers,
            onEachFeature: processLocsFeatures
          }
        )
      )
    },
    {
      name: 'Localidades 250-499 habs.',
      layer: omnivore.geojson('../json/geo/locs2Geo.json',
        null,
        L.geoJson(null,
          {
            pointToLayer: processLocMarkers,
            onEachFeature: processLocsFeatures
          }
        )
      )
    },
    {
      name: 'Localidades 1-249 habs.',
      layer: omnivore.geojson('../json/geo/locs1Geo.json',
        null,
        L.geoJson(null,
          {
            pointToLayer: processLocMarkers,
            onEachFeature: processLocsFeatures
          }
        )
      )
    }
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
$(document).ready(function () {
  var chartLv, chartHist,
  VOL_MAX = levelsData[1].vMax,
  damId = levelsData[1].id,
  y = String(levelsData[1].d).split('-')[0],
  levelChart = {
    chart: {
      renderTo: 'levelChart',
      type: 'column',
      height: 280
    },
    credits: {enabled:false},
    title: {text: 'Volumen actual y año anterior'},
    colors: [
      {
        linearGradient: { x1: 0, y1: 0, x2: 1, y2: 0 },
        stops: [
          [0, 'rgba(250,250,250,0.5)'],
          [1, 'rgba(255,255,255,0.0)'],
          [2, 'rgba(250,250,250,0.5)']
        ]
      },
      {
        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
        stops: [
          [0, 'rgba(100,160,200,1.0)'],
          [1, 'rgba(160,215,255,0.75)'],
          [2, 'rgba(140,200,240,0.0)']
        ]
      }
    ],
    xAxis: {
      categories: [
        '' + levelsData[0].label,
        '' + levelsData[1].label
      ]
    },
    yAxis: {
      title: {
        margin: 1,
        text: 'Volumen (%)'
      },
      max: 130
    },
    legend: {
      enabled: false
    },
    tooltip: {
      valueSuffix: ' %',
      crosshairs: false,
      shared: false,
      valueDecimals: 2
    },
    plotOptions: {
      column: {
        stacking: 'normal',
        borderWidth: 0,
        shadow: {
          color: '#000000',
          offsetX: 0,
          offsetY: 1,
          opacity: 0.25,
          width: 2
        }
      }
    }
  },
  historyChart = {
    chart: {
      renderTo: "historyChart",
      type: 'line',
      zoomType: 'x'
    },
    credits: {
      href: "http://www.ceajalisco.gob.mx",
      text: null
    },
    title: {
      text: 'Comportamiento en los últimos años'
    },
    dateTimeLabelFormats: {
      millisecond: '%H:%M:%S.%L',
      second: '%H:%M:%S',
      minute: '%H:%M',
      hour: '%H:%M',
      day: '%e %b',
      week: '%e %b',
      month: '%b %Y',
      year: '%Y'
    },
    colors: [
      'rgba(90, 100, 110, 0.3)',
      'rgba(120, 130, 140, 0.75)',
      'rgba(130, 180, 150, 1.0)'
    ],
    xAxis: {
      type: 'datetime',
      dateTimeLabelFormats: {
        millisecond: '%H:%M:%S.%L',
        second: '%H:%M:%S',
        minute: '%H:%M',
        hour: '%H:%M',
        day: '%e %b',
        week: '%e %b',
        month: '%b',
        year: '%Y'
      }
    },
    yAxis: {
      title: {
        margin: 1,
        text: "Volumen (Mm3)"
      },
      min: 0
    },
    legend: {enabled: true},
    tooltip: {
      xDateFormat: '%d %b',
      valueSuffix: ' Mm3',
      crosshairs: true,
      shared: true,
      useHTML: true,
      valueDecimals: 2,
    }
  },
  createBehaviorChartVO = function(d, dArray) {
    return {
      x: Date.UTC(2011,(dArray[1] - 1), dArray[2]) + (3600 * 1000 * 24),
      y: parseFloat(d.v)
    }
  },
  processLevels = function(data) {
    var i, j, l = data.length;
    levelChart.series = [
      {
        marker: {enabled: false},
        name: 'Dif.',
        data: [
          [
            'Diferencia ' + levelsData[0].label,
            levelsData[0].dif
          ],
          [
            'Diferencia ' + levelsData[1].label,
            levelsData[1].dif
          ]
        ],
        states: {hover: {lineWidth: 0}},
        enableMouseTracking: false
      },
      {
        marker: {enabled: true},
        name: 'Vol.',
        data: [
          [
            'Volumen ' + levelsData[0].label,
            levelsData[0].v
          ],
          [
            'Volumen ' + levelsData[1].label,
            levelsData[1].v
          ]
        ],
      }
    ];
    chartLv = new Highcharts.Chart(levelChart);
  },
  processHistory = function(data) {
    var i,j,l, seriesArray, seriesLabel;
    l = data.length;
    dArray = String(data[0].d).split('-');
    seriesLabel = dArray[0];
    seriesArray = [
      {
        marker: {
          enabled: false
        },
        name: seriesLabel,
        data: []
      }
    ];
    j = 0;
    for (i = 0; i < l; i++) {
      dArray = String(data[i].d).split('-');
      if (dArray[0] == seriesLabel) {
        seriesArray[j].data.push(createBehaviorChartVO(data[i], dArray));
      }
      else {
        seriesLabel = dArray[0];
        j++;
        seriesArray.push({
          marker: {enabled: false},
          name: seriesLabel,
          data: []
        });
        seriesArray[j].data.push(createBehaviorChartVO(data[i], dArray));
      }
    }
    historyChart.tooltip.formatter = function() {
      var s = "",
        d = new Date(),
        porc = 0,
        dString = "",
        shortMonths = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'],
        i = 0,
        l = 0;
      d.setTime(this.x);
      s += "<small>" + d.getDate() + " " + shortMonths[d.getMonth()] + "</small><br>";
      l = this.points.length;
      for (i = 0; i < l; i++) {
        if (this.points[i] !== undefined) {
          s += (function (data) {
            var label = "<b><span style='color: " + data.series.color + "'>";
            label += data.series.name + "</span></b> ";
            label += "<small>Vol. </small><b>" + data.y + "</b><small>Mm3 </small>";
            label += "<small>(" + (Math.ceil((data.y / VOL_MAX) * 10000) / 100) + "%)</small><br>";
            return label;
          })(this.points[i]);
        }
      }
      return s;
    };
    historyChart.series = seriesArray;
    chartHist = new Highcharts.Chart(historyChart);
  };

  $.getJSON("../../amfphp/services/getDamHistory.php?id=" + damId + "&y=" + y, function(data) {
    processHistory(data);
  });

  processLevels(levelsData);
});
/**
 * Gray theme for Highcharts JS
 * @author Torstein Hønsi
 */

Highcharts.theme = {
	lang: {
		loading: 'Cargando...',
		shortMonths: ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'],
		months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		contextButtonTitle: 'Menú contextual',
		downloadJPEG: 'Descargar como JPEG',
		downloadPDF: 'Descargar como PDF',
		downloadPNG: 'Descargar como PNG',
		downloadSVG: 'Descargar como SVG',
		printChart: 'Imprimir',
		resetZoom: 'Ver todo',
		resetZoomTitle: 'Ver todo'
	},
	colors: [ "#7395d6", "#736557", "#df5353", "#aaeeee", "#ff0066", "#eeaaee",
		"#55bf3b", "#df5353", "#7798bf", "#aaeeee", "#dddf0d", "#7798bf", "#55bf3b"],
	chart: {
		backgroundColor: {
			linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
			stops: [
				[0, 'rgb(252, 252, 252)'],
				[1, 'rgb(255, 255, 255)']
			]
		},
		borderWidth: 0,
		borderRadius: 0,
		plotBackgroundColor: null,
		plotShadow: false,
		plotBackgroundImage: 'http://www.ceajalisco.gob.mx/images/bgEscudoJal.png',
		plotBorderWidth: 0
	},
	title: {
		style: {
			color: '#404040',
			font: '16px Helvetica, Arial, sans-serif'
		}
	},
	subtitle: {
		style: {
			color: '#606060',
			font: '12px Helvetica, Arial, sans-serif'
		}
	},
	xAxis: {
		gridLineWidth: 0,
		tickColor: '#404040',
		lineColor: '#96172e',
		lineWidth: 1,
		tickWidth: 1,
		tickLength: 5,
		labels: {
			style: {
				color: '#606060',
				fontWeight: 'bold'
			}
		},
		title: {
			style: {
				color: '#808080',
				font: 'bold 12px Helvetica, Arial, sans-serif'
			}
		}
	},
	yAxis: {
		alternateGridColor: null,
		minorTickInterval: null,
		gridLineColor: 'rgba(40, 40, 40, .5)',
		minorGridLineColor: 'rgba(40, 40, 40, 0.25)',
		tickColor: '#404040',
		lineColor: '#96172e',
		lineWidth: 1,
		tickWidth: 1,
		tickLength: 5,
		labels: {
			style: {
				color: '#404040',
				fontWeight: 'bold'
			}
		},
		title: {
			style: {
				color: '#808080',
				font: 'bold 12px Helvetica, Arial, sans-serif'
			}
		}
	},
	legend: {
		itemStyle: {
			color: '#505050'
		},
		itemHoverStyle: {
			color: '#404040'
		},
		itemHiddenStyle: {
			color: '#c0c0c0'
		}
	},
	labels: {
		style: {
			color: '#505050'
		}
	},
	tooltip: {
		backgroundColor: {
			linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
			stops: [
				[0, 'rgba(240, 240, 240, 0.9)'],
				[1, 'rgba(255, 255, 255, 1.0)']
			]
		},
		borderWidth: 1,
		borderRadius: 0,
		style: {
			color: '#404040'
		}
	},

	plotOptions: {
		series: {
			shadow: true
		},
		line: {
			dataLabels: {
				color: '#505050'
			},
			marker: {
				lineColor: '#c0c0c0'
			}
		},
		spline: {
			marker: {
				lineColor: '#c0c0c0'
			}
		},
		scatter: {
			marker: {
				lineColor: '#c0c0c0'
			}
		},
		candlestick: {
			lineColor: '#303030'
		}
	},

	toolbar: {
		itemStyle: {
			color: '#505050'
		}
	},

	navigation: {
		buttonOptions: {
			symbolStroke: '#303030',
			hoverSymbolStroke: '#808080',
			theme: {
				fill: {
					linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
					stops: [
						[0.4, '#606060'],
						[0.6, '#c0c0c0']
					]
				},
				stroke: '#000000'
			}
		}
	},

	// scroll charts
	rangeSelector: {
		buttonTheme: {
			fill: {
				linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
				stops: [
					[0.4, '#c0c0c0'],
					[0.6, '#808080']
				]
			},
			stroke: '#000000',
			style: {
				color: '#505050',
				fontWeight: 'bold'
			},
			states: {
				hover: {
					fill: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						stops: [
							[0.4, '#808080'],
							[0.6, '#c0c0c0']
						]
					},
					stroke: '#000000',
					style: {
						color: '#303030'
					}
				},
				select: {
					fill: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						stops: [
							[0.1, '#f0f0f0'],
							[0.3, '#c0c0c0']
						]
					},
					stroke: '#000000',
					style: {
						color: '#a0a0a0'
					}
				}
			}
		},
		inputStyle: {
			backgroundColor: '#c0c0c0',
			color: 'silver'
		},
		labelStyle: {
			color: 'silver'
		}
	},

	navigator: {
		handles: {
			backgroundColor: '#606060',
			borderColor: '#404040'
		},
		outlineColor: '#505050',
		maskFill: 'rgba(255, 255, 255, 0.5)',
		series: {
			color: '#a0a0a0',
			lineColor: '#a6c7ed'
		}
	},

	scrollbar: {
		barBackgroundColor: {
				linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
				stops: [
					[0.4, '#c0c0c0'],
					[0.6, '#808080']
				]
			},
		barBorderColor: '#505050',
		buttonArrowColor: '#505050',
		buttonBackgroundColor: {
				linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
				stops: [
					[0.4, '#c0c0c0'],
					[0.6, '#808080']
				]
			},
		buttonBorderColor: '#505050',
		rifleColor: '#404040',
		trackBackgroundColor: {
			linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
			stops: [
				[0, '#f0f0f0'],
				[1, '#c0c0c0']
			]
		},
		trackBorderColor: '#606060'
	},

	// special colors for some of the demo examples
	legendBackgroundColor: 'rgba(220, 220, 220, 0.8)',
	legendBackgroundColorSolid: 'rgb(220, 220, 220)',
	dataLabelsColor: '#f0f0f0',
	textColor: '#303030',
	maskColor: 'rgba(220, 220, 220, 0.3)'
};

// Apply the theme
var highchartsOptions = Highcharts.setOptions(Highcharts.theme);

function addCommas(number, decimals, dec_point, thousands_sep) {
	var s = '', n, prec, toFixedFix;
	decimals = setDefault(decimals, 2);
	dec_point = setDefault(dec_point, '.');
	thousands_sep = setDefault(thousands_sep, ',');
	decimals = setDefault(decimals, 2);
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	n = !isFinite(+number) ? 0 : +number;
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
	toFixedFix = function (n, prec) {
		return String(Math.round(n * Math.pow(10, prec)) / Math.pow(10, prec));
	};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, thousands_sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec_point);
}

function sortAlpha(a, b) {
	var labelA = a.label.toLowerCase(), labelB = b.label.toLowerCase();
	if (labelA < labelB) {
		return -1;
	}
	if (labelA > labelB) {
		return 1;
	}
	return 0;
}

function windowSize() {
	'use strict';
	var w = 0, h = 0;
	if (!isNaN(window.innerWidth)) {
		//non-IE
		w = window.innerWidth;
		h = window.innerHeight;
	} else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
		//IE 6 'standards mode'
		w = document.documentElement.clientWidth;
		h = document.documentElement.clientHeight;
	} else if(document.body && (document.body.clientWidth || document.body.clientHeight)) {
		//IE 4
		w = document.body.clientWidth;
		h = document.body.clientHeight;
	}
	return {height: h, width: w};
}

function setDefault(argument, defaultValue) {
	return (argument === undefined) ? defaultValue : argument;
}

function resizeElement(elementId, doResizeWidth, doResizeHeight, divider, margin) {
	if (elementId !== undefined && elementId.toString().length > 0) {
		doResizeWidth = setDefault(doResizeWidth, true);
		doResizeHeight = setDefault(doResizeHeight, true);
		divider = setDefault(divider, 1);
		margin = setDefault(margin, 0);
		if (!isNaN(divider) && divider > 0 && divider < 100) {
			if (!isNaN(margin) && margin > 0 && margin < windowSize().height && margin < windowSize().width) {
				if (doResizeWidth) {
					$('#'+ elementId).css({'width': ((windowSize().width / divider) - margin) + 'px'});
				}
				if (doResizeHeight) {
					$('#'+ elementId).css({'height': ((windowSize().height / divider) - margin) + 'px'});
				}
			}
		}
	}
}

function resizeDivHalfHeight(elementId) {
	resizeElement(elementId, false, true, 2, 30);
}

function resizeDivHalfWidth(elementId) {
	resizeElement(elementId, true, false, 2, 30);
}

function resizeDivFullHeight(elementId) {
	resizeElement(elementId, false, true, 1, 30);
}

function resizeDivFullWidth(elementId) {
	resizeElement(elementId, true, false, 1, 30);
}
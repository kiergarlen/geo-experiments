function addCommas(number, decimals, dec_point, thousands_sep) {
	decimals = (typeof decimals === "undefined") ? 2 : decimals;
	dec_point = (typeof dec_point === "undefined") ? "." : dec_point;
	thousands_sep = (typeof thousands_sep === "undefined") ? "," : thousands_sep;

	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',
	toFixedFix = function (n, prec) {
		var k = Math.pow(10, prec);
		return '' + Math.round(n * k) / k;
	};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
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
	var myWidth = 0, myHeight = 0;
	if( typeof(window.innerWidth) == 'number') {
		//Non-IE
		myWidth = window.innerWidth;
		myHeight = window.innerHeight;
	} else if(document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
		//IE 6+ in 'standards compliant mode'
		myWidth = document.documentElement.clientWidth;
		myHeight = document.documentElement.clientHeight;
	} else if(document.body && (document.body.clientWidth || document.body.clientHeight) ) {
		//IE 4 compatible
		myWidth = document.body.clientWidth;
		myHeight = document.body.clientHeight;
	}
	return {height: myHeight, width: myWidth};
}

function resizeDivHalfHeight(divId) {
	/*
	var vph = (windowSize().height / 2) - 30, itemId = "#" + divId;
	document.getElementById(itemId).style.height = vph + 'px';
	*/
	var vph = (windowSize().height / 2) - 30;
	$('#'+divId).css({'height': vph + 'px'});
}

function resizeDivHalfWidth(divId) {
	var vpw = (windowSize().width / 2) - 30;
	$('#'+divId).css({'width': vpw + 'px'});
}

function resizeDivFullHeight(divId) {
	var vph = windowSize().height - 30;
	$('#'+divId).css({'height': vph + 'px'});
}

function resizeDivFullWidth(divId) {
	var vpw = windowSize().width - 30;
	$('#'+divId).css({'width': vpw + 'px'});
}
//*************** Despliegue de lista  color de las graficas
Highcharts.setOptions({
	colors: ['#d5001e', '#337ab7'],
	chart: {
		style: {
			fontFamily: 'Roboto'
		}
	},
	lang: {
        numericSymbols: ['.000', '.000.000', '.000.000.000', '.000.000.000.000', '.000.000.000.000.000', '.000.000.000.000.000.000'] //otherwise by default ['k', 'M', 'G', 'T', 'P', 'E']
    }
});

function getUrlShare(redSocial, smt, link) {
    var redSocialUrl = 'http://www.facebook.com/sharer.php?';

    function serialize(obj) {
        return Object.keys(obj).map(function(p) {
            return encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]);
        }).join("&");
    }

    function postToRedSocial(url) {
        var url = smt.options.exporting.url + url,
            title = smt.options.title.text;
        
        link.target = '_blank';
        link.href = redSocialUrl + 'u=' + encodeURIComponent(url) + '&t=' + encodeURIComponent(title);
    }

    $.ajax({
        type: 'POST',
        data: serialize({
            svg: smt.getSVGForExport(),
            type: 'image/jpeg',
            async: true
        }),
        url: smt.options.exporting.url,
        success: postToRedSocial,
        error: function(e) {
            throw e;
        }
    });
}




//*************** Despliegue de lista  color de las graficas

Highcharts.setOptions({
	colors: ['#cc0000', '#337ab7'],
	chart: {
		style: {
			fontFamily: 'Arial'
		}
	},
	lang: {
        numericSymbols: ['.000', '.000.000', '.000.000.000', '.000.000.000.000', '.000.000.000.000.000', '.000.000.000.000.000.000'] //otherwise by default ['k', 'M', 'G', 'T', 'P', 'E']
    }
});





/*var optionsProfileVisit = {
	annotations: {
		position: 'back'
	},
	dataLabels: {
		enabled:false
	},
	chart: {
		type: 'bar',
		height: 300
	},
	fill: {
		opacity:1
	},
	plotOptions: {
	},
	series: [{
		name: 'sales',
		data: [9,20,30,20,10,20,30,20,10,20,30,20]
	}],
	colors: '#435ebe',
	xaxis: {
		categories: ["Jan","Feb","Mar","Apr","May","Jun","Jul", "Aug","Sep","Oct","Nov","Dec"],
	},
}*/

var optionsProfileVisit = {
	series: [{
			name: 'sales',
          	data: [44, 55, 41, 64, 22, 43, 21, 52, 13, 44, 32, 41]
        }, {
			name: 'expenses',
          	data: [53, 32, 33, 52, 13, 44, 32, 41, 64, 22, 43, 21]
    	}, {
			name: 'results',
          	data: [53, 32, 33, 52, 13, 44, 32, 41, 64, 22, 43, 21]
    	}
	],
	colors : [
		'#26d4a8', '#ff5959', '#435ebe' 
	],
	chart: {
	type: 'bar',
	height: 430
	},
	plotOptions: {
		bar: {
		horizontal: false,
		dataLabels: {
			position: 'back',
		},
		}
	},
	dataLabels: {
		enabled: true,
		offsetX: -6,
		style: {
		fontSize: '12px',
		colors: ['#fff']
		}
	},
	stroke: {
		show: true,
		width: 1,
		colors: ['#fff']
	},
	tooltip: {
		shared: true,
		intersect: false
	},
	xaxis: {
		categories: ["Jan","Feb","Mar","Apr","May","Jun","Jul", "Aug","Sep","Oct","Nov","Dec"],
	},
}



var chartProfileVisit = new ApexCharts(document.querySelector("#chart-evolution-income"), optionsProfileVisit);
chartProfileVisit.render();
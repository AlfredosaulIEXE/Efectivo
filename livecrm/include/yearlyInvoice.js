$(document).ready(function(e) {
    var data2 = [];
for(var i=0; i<allYearInvoice.length;i++){
	data2.push([year(allYearInvoice[i]),parseInt(closedYearlyInvoice[i])]);
}
var data3 = [];
for(var i=0; i<allYearInvoice.length;i++){
	data3.push([year(allYearInvoice[i]),parseInt(openedYearlyInvoice[i])]);
	
}
//alert(JSON.stringify(data2));
///alert(JSON.stringify(data3));
var dataset = [
	{
		label: first_label_invoice,
		data: data3,
		color: "#1ab394",
		bars: {
			show: true,
			align: "center",
			barWidth: 24 * 60 * 60 * 600,
			lineWidth:5
		}

	}, {
		label: second_label_invoice,
		data: data2,
		yaxis: 2,
		color: "#464f88",
		lines: {
			lineWidth:1,
				show: true,
				fill: true,
			fillColor: {
				colors: [{
					opacity: 0.2
				}, {
					opacity: 0.2
				}]
			}
		},
		splines: {
			show: false,
			tension: 0.6,
			lineWidth: 1,
			fill: 0.1
		},
	}
];


var options = {
	xaxis: {
		mode: "time",
		
		tickSize: [2, "year"],
		tickLength: 0,
		axisLabel: "Date",
		axisLabelUseCanvas: true,
		axisLabelFontSizePixels: 12,
		axisLabelFontFamily: 'Arial',
		axisLabelPadding: 10,
		color: "#838383"
	},
	yaxes: [{
		position: "left",
		color: "#838383",
		axisLabelUseCanvas: true,
		axisLabelFontSizePixels: 12,
		axisLabelFontFamily: 'Arial',
		axisLabelPadding: 3
	}, {
		position: "right",
		clolor: "#838383",
		axisLabelUseCanvas: true,
		axisLabelFontSizePixels: 12,
		axisLabelFontFamily: ' Arial',
		axisLabelPadding: 67
	}
	],
	legend: {
		noColumns: 3,
		labelBoxBorderColor: "#000000",
		position: "nw"
	},
	grid: {
		hoverable: true,
		borderWidth: 2,
	   // borderWidth: 0,
		color: '#838383'
	}
};
function year(year) {   
    return new Date(year, 1, 1).getTime();
}
/*function gd(year, month, day) {
	return new Date(year, month - 1, day).getTime();
}*/

var previousPoint = null, previousLabel = null;

$.plot($("#flot-dashboard-chart-invoice"), dataset, options);
});

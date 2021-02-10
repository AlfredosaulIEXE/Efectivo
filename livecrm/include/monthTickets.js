$(document).ready(function(e) {
    var data2 = [];
for(var i=1; i<=12;i++){
	data2.push([gd(d.getFullYear(),i,i),closedMonthlyTicket[i]]);
}
var data3 = [];
for(var i=1; i<=12;i++){
	data3.push([gd(d.getFullYear(),i,i),openedMonthlyTicket[i]]);
	
}
var dataset = [
	{
		label: first_label_ticket,
		data: data3,
		color: "#1ab394",
		bars: {
			show: true,
			align: "center",
			barWidth: 24 * 60 * 60 * 600,
			lineWidth:0
		}

	}, {
		label: second_label_ticket,
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
		
		tickSize: [1, "month"],
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
		///max: <?=$max?>,
		
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
		noColumns: 1,
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
function gd(year, month, day) {
	return new Date(year, month - 1, 1).getTime();
}
/*function gd(year, month, day) {
	return new Date(year, month - 1, day).getTime();
}*/

var previousPoint = null, previousLabel = null;

$.plot($("#flot-dashboard-chart-ticket"), dataset, options);
});

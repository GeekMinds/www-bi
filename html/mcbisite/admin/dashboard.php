<?php
require_once("service/models/config.php");
$logued = true;
if(!isUserLoggedIn()) { 
	$logued = false;
	echo '<script type="text/javascript">top.location.href="'.$websiteUrl.'login.php";</script>';
	die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>Web Site Traffic Analysis (Jan 2014)</title>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="scripts/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.js"></script>
    
    <style>
		body{
			overflow:hidden;
		}
    	content{
			margin: auto;
			max-width: 920px;
			width: 100%;
			height: auto;
			display: block;
		}
    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            // prepare the data
            var source =
            {
                datatype: "tab",
                datafields: [
                    { name: 'Date' },
                    { name: 'Referral' },
                    { name: 'SearchPaid' },
                    { name: 'SearchNonPaid' }
                ],
                url: 'website_analytics.txt'
            };
            var dataAdapter = new $.jqx.dataAdapter(source, { async: false, autoBind: true, loadError: function (xhr, status, error) { alert('Error loading "' + source.url + '" : ' + error); } });

            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            // prepare jqxChart settings
            var settings = {
                title: "Web Site Traffic Analysis (Jan 2014)",
                description: "",
                enableAnimations: true,
                showLegend: true,
                padding: { left: 5, top: 5, right: 11, bottom: 5 },
                titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
                source: dataAdapter,
                categoryAxis:
                    {
                        text: 'Category Axis',
                        textRotationAngle: 0,
                        dataField: 'Date',
                        formatFunction: function (value) {
                            return  value.getDate();
                        },
                        toolTipFormatFunction: function (value) {
                            return value.getDate() + '-' + months[value.getMonth()] + '-' + value.getFullYear();
                        },
                        showTickMarks: true,
                        type: 'date',
                        baseUnit: 'day',
                        tickMarksInterval: 1,
                        tickMarksColor: '#888888',
                        unitInterval: 1,
                        showGridLines: true,
                        gridLinesInterval: 31,
                        gridLinesColor: '#888888',
                        minValue: '01/01/2012',
                        maxValue: '01/31/2012',
                        valuesOnTicks: false
                    },
                colorScheme: 'scheme01',
                seriesGroups:
                    [
                        {
                            type: 'stackedline',
                            valueAxis:
                            {
                                unitInterval: 500,
                                minValue: 0,
                                maxValue: 3000,
                                displayValueAxis: true,
                                description: 'Daily Visits',
                                //descriptionClass: 'css-class-name',
                                axisSize: 'auto',
                                tickMarksColor: '#888888'
                            },
                            series: [
                                    { dataField: 'Referral', displayText: 'Referral Traffic' },
                                    { dataField: 'SearchPaid', displayText: 'Paid Search Traffic' },
                                    { dataField: 'SearchNonPaid', displayText: 'Non-Paid Search Traffic' }
                                ]
                        }
                    ]
            };

            // setup the chart
            $('#jqxChart').jqxChart(settings);
			otherChart();
        });
		
		
		function otherChart(){
			var source =
            {
                datatype: "csv",
                datafields: [
                    { name: 'Browser' },
                    { name: 'Share' }
                ],
                url: 'desktop_browsers_share_dec2011.txt'
            };
            var dataAdapter = new $.jqx.dataAdapter(source, { async: false, autoBind: true, loadError: function (xhr, status, error) { alert('Error loading "' + source.url + '" : ' + error); } });
            // prepare jqxChart settings
            var settings = {
                title: "Desktop browsers share in Dec 2013",
                description: "(source: wikipedia.org)",
                enableAnimations: true,
                showLegend: false,
                legendPosition: { left: 520, top: 140, width: 100, height: 100 },
                padding: { left: 5, top: 5, right: 5, bottom: 5 },
                titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
                source: dataAdapter,
                colorScheme: 'scheme02',
                seriesGroups:
                    [
                        {
                            type: 'pie',
                            showLabels: true,
                            series:
                                [
                                    { 
                                        dataField: 'Share',
                                        displayText: 'Browser',
                                        labelRadius: 100,
                                        initialAngle: 15,
                                        radius: 130,
                                        centerOffset: 0,
                                        formatSettings: { sufix: '%', decimalPlaces: 1 }
                                    }
                                ]
                        }
                    ]
            };
            // setup the chart
            $('#jqxChart2').jqxChart(settings);	
		}

    </script>

</head>

<body class='default'>
	<content>
        <div id='jqxChart' style="width:680px; height:400px">    
        </div>
        
        <div id='jqxChart2' style="width:680px; height:400px">
        </div>
	</content>
</body>

</html>


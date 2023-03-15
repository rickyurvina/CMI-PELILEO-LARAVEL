<div id="project-progress-all" style="min-height: 500px"></div>

@push('page_script')
    <script>
        am4core.ready(function () {
            // create chart
            let chart_5 = am4core.create("project-progress-all", am4charts.XYChart);

            chart_5.data = @json($projectProgress);

            // Create axes
            let categoryAxis = chart_5.yAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "name";
            categoryAxis.renderer.grid.template.strokeDasharray = "3,3"
            categoryAxis.autoGridCount = false;
            // categoryAxis.gridCount = 50;
            categoryAxis.renderer.minGridDistance = 10;
            categoryAxis.renderer.cellStartLocation = 0.3
            categoryAxis.renderer.cellEndLocation = 0.7
            categoryAxis.renderer.grid.template.location = 0;

            let valueAxis = chart_5.xAxes.push(new am4charts.ValueAxis());
            valueAxis.min = 0;
            valueAxis.renderer.baseGrid.disabled = true;
            valueAxis.renderer.opposite = true;
            valueAxis.renderer.grid.template.strokeDasharray = "3,3"

            // Create series
            function createSeries(field, name) {

                // Set up series
                var series = chart_5.series.push(new am4charts.ColumnSeries());
                series.dataFields.valueX = field;
                series.dataFields.categoryY = "name";
                series.name = name;

                // Configure columns
                series.columns.template.width = am4core.percent(100);
                series.columns.template.tooltipText = "[font-size:14px]{categoryY}: [bold]{valueX}[/bold]";

                return series;
            }

            chart_5.colors.list = [
                am4core.color("#03a9f4"),
                am4core.color("#FFC75F"),
                am4core.color("#7dc855"),
                am4core.color("#D65DB1"),
                am4core.color("#FF6F91")
            ];

            createSeries("physical", "Avance Físico");
            createSeries("budget", "Avance Presupuestario");

            // Legend
            chart_5.legend = new am4charts.Legend();


            // Set cell size in pixels
            var cellSize = 30;
            chart_5.events.on("datavalidated", function(ev) {

                // Get objects of interest
                var chart = ev.target;
                var categoryAxis = chart.yAxes.getIndex(0);

                // Calculate how we need to adjust chart height
                var adjustHeight = chart.data.length * cellSize - categoryAxis.pixelHeight;

                // get current chart height
                var targetHeight = chart.pixelHeight + adjustHeight;

                // Set it on chart's container
                chart.svgContainer.htmlElement.style.height = targetHeight + "px";
            });

        });
    </script>
@endpush
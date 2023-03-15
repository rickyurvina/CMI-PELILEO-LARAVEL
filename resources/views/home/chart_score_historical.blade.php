<div id="score-historical" style="height: 300px;"></div>
<div id="score-data" style="display: none;"></div>

@push('page_script')
    <script>
        am4core.ready(function () {
            let chart_score_historical = am4core.create("score-historical", am4charts.XYChart);

            chart_score_historical.data = @json($historicalScore);

            // Create axes
            let categoryAxis = chart_score_historical.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "frequency";
            categoryAxis.title.text = "{{ \App\Models\Indicator::PERIODS[1] }}";
            categoryAxis.renderer.minGridDistance = 50;
            categoryAxis.renderer.grid.template.location = 0.5;
            categoryAxis.renderer.labels.template.adapter.add("html", function(html, target) {
                if(target.dataItem.dataContext) {
                    return `<span style="font-weight: bold;color:` + target.dataItem.dataContext.color + `">` + target.dataItem.dataContext.frequency + `</span>`;
                }
            });

            let valueAxis = chart_score_historical.yAxes.push(new am4charts.ValueAxis());
            valueAxis.title.text = "Score";
            valueAxis.min = 0;
            valueAxis.max = 10;
            valueAxis.renderer.grid.template.disabled = true;
            valueAxis.renderer.labels.template.disabled = true;

            function createGrid(value) {
                let range = valueAxis.axisRanges.create();
                range.value = value;
                range.label.text = "{value}";
            }

            createGrid(0);
            createGrid(2);
            createGrid(4);
            createGrid(6);
            createGrid(8);
            createGrid(10);

            // Create series
            let series = chart_score_historical.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = "value";
            series.dataFields.categoryX = "frequency";
            series.name = "Score";
            series.tooltipText = "{name}: [bold]{valueY}[/]";
            series.strokeWidth = 3;
            series.strokeDasharray = "5,4";
            series.stroke = am4core.color("#567aca");
            let circleBullet = series.bullets.push(new am4charts.CircleBullet());
            circleBullet.circle.fill = am4core.color("#fff");
            circleBullet.propertyFields.stroke = "color";
            circleBullet.circle.strokeWidth = 3;
            series.tooltip.pointerOrientation = "vertical";


            chart_score_historical.legend = new am4charts.Legend();

            // Add cursor
            chart_score_historical.cursor = new am4charts.XYCursor();

            chart_score_historical.events.on("datavalidated", function(ev) {
                chart_score_historical.exporting.dataFields = {
                    "frequency": "",
                    "value": "Score"
                }
                chart_score_historical.exporting.getHTML("html", {
                    addColumnNames: true,
                    pivot: true,
                    emptyAs: "",
                    tableClass: "table table-sm m-0"
                }, false).then(function(html) {
                    var div = document.getElementById("score-data");
                    div.innerHTML = html;
                });
            });

            // A button to toggle the data table
            let button = chart_score_historical.createChild(am4core.SwitchButton);
            button.align = "right";
            button.leftLabel.text = "Ver Datos";
            button.isActive = false;

            // Set toggling of data table
            button.events.on("toggled", function(ev) {
                var div = document.getElementById("score-data");
                if (button.isActive) {
                    div.style.display = "block";
                }
                else {
                    div.style.display = "none";
                }
            });
        });
    </script>
@endpush
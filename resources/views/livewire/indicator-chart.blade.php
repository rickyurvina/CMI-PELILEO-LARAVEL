<div>
    <div x-data x-init='() => {
        var chart_{{ $indicator->id }} = am4core.create("indicator-chart-{{ $indicator->id }}", am4charts.XYChart);

        chart_{{ $indicator->id }}.data = @json($data);

        // Create axes
        var categoryAxis = chart_{{ $indicator->id }}.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "frequency";
        categoryAxis.title.text = "{{ \App\Models\Indicator::PERIODS[$indicator->frequency] }}";
        categoryAxis.renderer.minGridDistance = 50;
        categoryAxis.renderer.grid.template.location = 0.5;
        categoryAxis.renderer.labels.template.adapter.add("html", function(html, target) {
            if(target.dataItem.dataContext) {
                return `<span style="font-weight: bold;color:` + target.dataItem.dataContext.color + `">` + target.dataItem.dataContext.frequency.replace(/ \(.*/, "") + `<br>
                <span style="opacity: 0.4;">` + target.dataItem.dataContext.year + `</span></span>`;
            }
        });

        var valueAxis = chart_{{ $indicator->id }}.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "{{ $indicator->measureUnit->value }}";

        // Create series
        var series = chart_{{ $indicator->id }}.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "value";
        series.dataFields.categoryX = "frequency";
        series.name = "Meta";
        series.tooltipText = "{name}: [bold]{valueY}[/]";
        series.strokeWidth = 3;
        series.strokeDasharray = "5,4";
        series.stroke = am4core.color("#9d00ff");
        let circleBullet = series.bullets.push(new am4charts.CircleBullet());
        circleBullet.circle.fill = am4core.color("#fff");
        circleBullet.circle.stroke = am4core.color("#9d00ff");
        circleBullet.circle.strokeWidth = 3;
        series.tooltip.pointerOrientation = "vertical";

        var series1 = chart_{{ $indicator->id }}.series.push(new am4charts.LineSeries());
        series1.dataFields.valueY = "actual";
        series1.dataFields.categoryX = "frequency";
        series1.name = "Actual";
        series1.tooltipText = "{name}: [bold]{valueY}[/]";
        series1.strokeWidth = 3;
        series1.bullets.push(new am4charts.CircleBullet());
        let circleBullet1 = series1.bullets.push(new am4charts.CircleBullet());
        circleBullet1.circle.fill = am4core.color("#fff");
        circleBullet1.propertyFields.stroke = "color";
        circleBullet1.circle.strokeWidth = 3;

        chart_{{ $indicator->id }}.legend = new am4charts.Legend();

        // Add cursor
        chart_{{ $indicator->id }}.cursor = new am4charts.XYCursor();

        chart_{{ $indicator->id }}.events.on("datavalidated", function(ev) {
          chart_{{ $indicator->id }}.exporting.dataFields = {
              "frequency": "",
              "value": "Meta",
              "actual": "Actual",
              "progress": "%",
          }
          chart_{{ $indicator->id }}.exporting.adapter.add("data", function(data) {
              for (var i = 0; i < data.data.length; i++) {
                data.data[i].progress += "%";
              }
              return data;
            });

          chart_{{ $indicator->id }}.exporting.getHTML("html", {
            addColumnNames: true,
            pivot: true,
            emptyAs: "",
            tableClass: "table table-sm m-0"
          }, false).then(function(html) {
            var div = document.getElementById("chartdata-{{ $indicator->id }}");
            div.innerHTML = html;
          });
        });

        // A button to toggle the data table
        var button = chart_{{ $indicator->id }}.createChild(am4core.SwitchButton);
        button.align = "right";
        button.leftLabel.text = "Ver Datos";
        button.isActive = false;

        // Set toggling of data table
        button.events.on("toggled", function(ev) {
              var div = document.getElementById("chartdata-{{ $indicator->id }}");
              if (button.isActive) {
                div.style.display = "block";
              }
              else {
                div.style.display = "none";
              }
        });
    }'
    >
        <div id="indicator-chart-{{ $indicator->id }}" class="w-100" style="height: 300px">

        </div>
        <div id="chartdata-{{ $indicator->id }}" style="display: none;"></div>
    </div>

    @push('page_script')
        <script>
            window.addEventListener('updateIndicatorChart-{{ $indicator->id }}', event => {

                let chart_{{ $indicator->id }} = am4core.create("indicator-chart-{{ $indicator->id }}", am4charts.XYChart);
                chart_{{ $indicator->id }}.data = event.detail.data;

                // Create axes
                var categoryAxis = chart_{{ $indicator->id }}.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "frequency";
                categoryAxis.title.text = "{{ \App\Models\Indicator::PERIODS[$indicator->frequency] }}";
                categoryAxis.renderer.minGridDistance = 50;
                categoryAxis.renderer.grid.template.location = 0.5;
                categoryAxis.renderer.labels.template.adapter.add("html", function(html, target) {
                    if(target.dataItem.dataContext) {
                        return `<span style="font-weight: bold;color:` + target.dataItem.dataContext.color + `">` + target.dataItem.dataContext.frequency.replace(/ \(.*/, "") + `<br>
                                <span style="opacity: 0.4;">` + target.dataItem.dataContext.year + `</span></span>`;
                    }
                });

                let valueAxis = chart_{{ $indicator->id }}.yAxes.push(new am4charts.ValueAxis());
                valueAxis.title.text = "{{ $indicator->measureUnit->value }}";

                // Create series
                var series = chart_{{ $indicator->id }}.series.push(new am4charts.LineSeries());
                series.dataFields.valueY = "value";
                series.dataFields.categoryX = "frequency";
                series.name = "Meta";
                series.tooltipText = "{name}: [bold]{valueY}[/]";
                series.strokeWidth = 3;
                series.strokeDasharray = "5,4";
                series.stroke = am4core.color("#9d00ff");
                let circleBullet = series.bullets.push(new am4charts.CircleBullet());
                circleBullet.circle.fill = am4core.color("#fff");
                circleBullet.circle.stroke = am4core.color("#9d00ff");
                circleBullet.circle.strokeWidth = 3;
                series.tooltip.pointerOrientation = "vertical";

                var series1 = chart_{{ $indicator->id }}.series.push(new am4charts.LineSeries());
                series1.dataFields.valueY = "actual";
                series1.dataFields.categoryX = "frequency";
                series1.name = "Actual";
                series1.tooltipText = "{name}: [bold]{valueY}[/]";
                series1.strokeWidth = 3;
                series1.bullets.push(new am4charts.CircleBullet());
                let circleBullet1 = series1.bullets.push(new am4charts.CircleBullet());
                circleBullet1.circle.fill = am4core.color("#fff");
                circleBullet1.propertyFields.stroke = "color";
                circleBullet1.circle.strokeWidth = 3;

                chart_{{ $indicator->id }}.legend = new am4charts.Legend();

                // Add cursor
                chart_{{ $indicator->id }}.cursor = new am4charts.XYCursor();

                chart_{{ $indicator->id }}.events.on("datavalidated", function(ev) {
                    chart_{{ $indicator->id }}.exporting.dataFields = {
                        "frequency": "",
                        "value": "Meta",
                        "actual": "Actual",
                        "progress": "%",
                    }
                    chart_{{ $indicator->id }}.exporting.adapter.add("data", function(data) {
                        for (var i = 0; i < data.data.length; i++) {
                            data.data[i].progress += "%";
                        }
                        return data;
                    });

                    chart_{{ $indicator->id }}.exporting.getHTML("html", {
                        addColumnNames: true,
                        pivot: true,
                        emptyAs: "",
                        tableClass: "table table-sm m-0"
                    }, false).then(function(html) {
                        var div = document.getElementById("chartdata-{{ $indicator->id }}");
                        div.innerHTML = html;
                    });
                });

                // A button to toggle the data table
                var button = chart_{{ $indicator->id }}.createChild(am4core.SwitchButton);
                button.align = "right";
                button.leftLabel.text = "Ver Datos";
                button.isActive = false;

                // Set toggling of data table
                button.events.on("toggled", function(ev) {
                    var div = document.getElementById("chartdata-{{ $indicator->id }}");
                    if (button.isActive) {
                        div.style.display = "block";
                    }
                    else {
                        div.style.display = "none";
                    }
                });

            })

        </script>
    @endpush

</div>

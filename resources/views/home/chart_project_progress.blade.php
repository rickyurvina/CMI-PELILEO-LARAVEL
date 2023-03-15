<div id="project-progress" style="height: 300px;"></div>

@push('page_script')
    <script>
        am4core.ready(function () {
            let chart = am4core.create("project-progress", am4charts.GaugeChart);
            chart.hiddenState.properties.opacity = 0;

            let axis = chart.xAxes.push(new am4charts.ValueAxis());
            axis.min = 0;
            axis.max = 100;
            axis.strictMinMax = true;
            axis.renderer.inside = true;
            axis.renderer.radius = am4core.percent(97);
            axis.renderer.line.strokeOpacity = 1;
            axis.renderer.line.strokeWidth = 5;
            axis.renderer.line.stroke = chart.colors.getIndex(0);
            axis.renderer.ticks.template.disabled = false
            axis.renderer.ticks.template.stroke = chart.colors.getIndex(0);
            axis.renderer.labels.template.radius = 35;
            axis.renderer.ticks.template.strokeOpacity = 1;
            axis.renderer.grid.template.disabled = true;
            axis.renderer.ticks.template.length = 10;
            axis.hiddenState.properties.opacity = 1;
            axis.hiddenState.properties.visible = true;
            axis.setStateOnChildren = true;
            axis.renderer.hiddenState.properties.endAngle = 180;

            let axis2 = chart.xAxes.push(new am4charts.ValueAxis());
            axis2.min = 0;
            axis2.max = 100;
            axis2.strictMinMax = true;

            axis2.renderer.line.strokeOpacity = 1;
            axis2.renderer.line.strokeWidth = 5;
            axis2.renderer.line.stroke = chart.colors.getIndex(3);
            axis2.renderer.ticks.template.stroke = chart.colors.getIndex(3);

            axis2.renderer.ticks.template.disabled = false
            axis2.renderer.ticks.template.strokeOpacity = 1;
            axis2.renderer.grid.template.disabled = true;
            axis2.renderer.ticks.template.length = 10;
            axis2.hiddenState.properties.opacity = 1;
            axis2.hiddenState.properties.visible = true;
            axis2.setStateOnChildren = true;
            axis2.renderer.hiddenState.properties.endAngle = 180;

            let hand = chart.hands.push(new am4charts.ClockHand());
            hand.fill = axis.renderer.line.stroke;
            hand.stroke = axis.renderer.line.stroke;
            hand.axis = axis;
            hand.pin.radius = 14;
            hand.startWidth = 10;
            hand.showValue({{ $physical }});


            let hand2 = chart.hands.push(new am4charts.ClockHand());
            hand2.fill = axis2.renderer.line.stroke;
            hand2.stroke = axis2.renderer.line.stroke;
            hand2.axis = axis2;
            hand2.pin.radius = 10;
            hand2.startWidth = 10;
            hand2.showValue({{ $budget }});

            let legend = new am4charts.Legend();
            legend.isMeasured = false;
            legend.y = am4core.percent(108);
            legend.verticalCenter = "bottom";
            legend.parent = chart.chartContainer;
            legend.data = [{
                "name": "Av. Físico",
                "fill": chart.colors.getIndex(0)
            }, {
                "name": "Av. Presupuestario",
                "fill": chart.colors.getIndex(3)
            }];

            legend.itemContainers.template.events.on("hit", function(ev) {
                let index = ev.target.dataItem.index;

                if (!ev.target.isActive) {
                    chart.hands.getIndex(index).hide();
                    chart.xAxes.getIndex(index).hide();
                    labelList.getIndex(index).hide();
                }
                else {
                    chart.hands.getIndex(index).show();
                    chart.xAxes.getIndex(index).show();
                    labelList.getIndex(index).show();
                }
            });

            let labelList = new am4core.ListTemplate(new am4core.Label());
            labelList.template.isMeasured = false;
            labelList.template.background.strokeWidth = 2;
            labelList.template.fontSize = 25;
            labelList.template.padding(10, 20, 10, 20);
            labelList.template.y = am4core.percent(50);
            labelList.template.horizontalCenter = "middle";

            let label = labelList.create();
            label.parent = chart.chartContainer;
            label.x = am4core.percent(40);
            label.background.stroke = chart.colors.getIndex(0);
            label.fill = chart.colors.getIndex(0);
            label.text = hand.value + '%';

            let label2 = labelList.create();
            label2.parent = chart.chartContainer;
            label2.x = am4core.percent(60);
            label2.background.stroke = chart.colors.getIndex(3);
            label2.fill = chart.colors.getIndex(3);
            label2.text = hand2.value + '%';
        });
    </script>
@endpush
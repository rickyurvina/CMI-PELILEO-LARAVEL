@props(['id', 'score' => 0, 'title' => ''])

<div id="score-{{ $id }}" {{ $attributes }}></div>

@push('page_script')
    <script>
        am4core.ready(function () {

            // create chart
            let chart = am4core.create("score-{{ $id }}", am4charts.GaugeChart);
            chart.hiddenState.properties.opacity = 0;
            chart.fontSize = 11;
            chart.innerRadius = am4core.percent(80);
            chart.resizable = true;

            /**
             * Normal axis
             */

            let axis = chart.xAxes.push(new am4charts.ValueAxis());
            axis.min = 0;
            axis.max = 10;
            axis.strictMinMax = true;
            axis.renderer.radius = am4core.percent(80);
            axis.renderer.inside = true;
            axis.renderer.line.strokeOpacity = 0.1;
            axis.renderer.ticks.template.disabled = false;
            axis.renderer.ticks.template.strokeOpacity = 1;
            axis.renderer.ticks.template.strokeWidth = 0.5;
            axis.renderer.ticks.template.length = 5;
            axis.renderer.grid.template.disabled = true;
            axis.renderer.labels.template.radius = am4core.percent(20);
            axis.renderer.labels.template.fontSize = "1.0em";

            /**
             * Axis for ranges
             */
            let axis2 = chart.xAxes.push(new am4charts.ValueAxis());
            axis2.min = 0;
            axis2.max = 10;
            axis2.strictMinMax = true;
            axis2.renderer.labels.template.disabled = true;
            axis2.renderer.ticks.template.disabled = true;
            axis2.renderer.grid.template.disabled = false;
            axis2.renderer.grid.template.opacity = 0.5;
            axis2.renderer.labels.template.bent = true;
            axis2.renderer.labels.template.fill = am4core.color("#000");
            axis2.renderer.labels.template.fontWeight = "bold";
            axis2.renderer.labels.template.fillOpacity = 0.3;

            /**
             Ranges
             */
            let range = axis2.axisRanges.create();
            range.axisFill.fill = am4core.color('#ee1f25');
            range.axisFill.fillOpacity = 0.8;
            range.axisFill.zIndex = -1;
            range.value = 0;
            range.endValue = {{ \App\Models\IndicatorGoal::RED_FLAG }};
            range.grid.strokeOpacity = 0;
            range.stroke = am4core.color('#ee1f25').lighten(-0.1);
            range.label.inside = true;
            range.label.inside = true;
            range.label.location = 0.5;
            range.label.inside = true;
            range.label.radius = am4core.percent(10);
            range.label.paddingBottom = -8; // ~half font size
            range.label.fontSize = "0.9em";

            let range1 = axis2.axisRanges.create();
            range1.axisFill.fill = am4core.color('#fdae19');
            range1.axisFill.fillOpacity = 0.8;
            range1.axisFill.zIndex = -1;
            range1.value = {{ \App\Models\IndicatorGoal::RED_FLAG }};
            range1.endValue = {{ \App\Models\IndicatorGoal::GOAL_FLAG }};
            range1.grid.strokeOpacity = 0;
            range1.stroke = am4core.color('#fdae19').lighten(-0.1);
            range1.label.inside = true;
            range1.label.inside = true;
            range1.label.location = 0.5;
            range1.label.inside = true;
            range1.label.radius = am4core.percent(10);
            range1.label.paddingBottom = -5; // ~half font size
            range1.label.fontSize = "0.9em";

            let range2 = axis2.axisRanges.create();
            range2.axisFill.fill = am4core.color('#0f9747');
            range2.axisFill.fillOpacity = 0.8;
            range2.axisFill.zIndex = -1;
            range2.value = {{ \App\Models\IndicatorGoal::GOAL_FLAG }};
            range2.endValue = {{ \App\Models\IndicatorGoal::BEST_FLAG }};
            range2.grid.strokeOpacity = 0;
            range2.stroke = am4core.color('#0f9747').lighten(-0.1);
            range2.label.inside = true;
            range2.label.inside = true;
            range2.label.location = 0.5;
            range2.label.inside = true;
            range2.label.radius = am4core.percent(10);
            range2.label.paddingBottom = -5; // ~half font size
            range2.label.fontSize = "0.9em";

            /**
             * Label 1
             */
            let label = chart.radarContainer.createChild(am4core.Label);
            label.isMeasured = false;
            label.fontSize = "2.0em";
            label.x = am4core.percent(50);
            label.horizontalCenter = "middle";
            label.verticalCenter = "bottom";
            label.text = {{ $score }};

            /**
             * Hand
             */
            let hand = chart.hands.push(new am4charts.ClockHand());
            hand.axis = axis2;
            hand.innerRadius = am4core.percent(55);
            hand.startWidth = 8;
            hand.pin.disabled = true;
            hand.value = {{ $score }};
            hand.fill = am4core.color("#444");
            hand.stroke = am4core.color("#000");

            // Add chart title
            var title = chart.titles.create();
            title.text = "{{ $title }}";
            title.fontSize = 14;

            // Add bottom label
            let labelButton = chart.chartContainer.createChild(am4core.Label);
            labelButton.text = "Score";
            labelButton.align = "center";
            labelButton.fontSize = "2.0em";

        });
    </script>
@endpush
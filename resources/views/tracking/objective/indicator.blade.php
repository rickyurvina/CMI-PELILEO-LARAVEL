<div class="row">
    <div class="col-12">
        <div class="card border shadow-0 shadow-sm-hover mb-g">
            <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                <div class="row">
                    <div class="col-md-6 col-lg-4 col-sm-12">
                        <div class="info-card-text fs-lg text-info">{{ $indicator->name }}</div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <b>Responsable:</b> {{ $indicator->responsible }}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <b>Tipo: </b> {{ trans('general.indicator.' . $indicator->type) }}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <b>Score: </b> {{ $indicator->score() }}
                            </div>
                        </div>
                        <div class="row">
                            @if($indicator->hasProgress())
                                <div class="col-12" style="height: 250px" id="status-{{ $indicator->id }}">

                                </div>
                            @else
                                <div class="alert alert-info fade show ml-5 mt-5" role="alert">
                                    <div class="d-flex align-items-center">
                                         <span class="h4">
                                            No existe información reportada
                                         </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-8 col-sm-12">
                        <livewire:indicator-chart :id="$indicator->id" key="'indicatorchart'.$loop->index"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page_script')
    <script>
        am4core.ready(function () {
            let chartMin = 0;
            let chartMax = 100;

            function lookUpGrade(lookupScore, grades) {
                // Only change code below this line
                for (var i = 0; i < grades.length; i++) {
                    if (
                        grades[i].lowScore <= lookupScore &&
                        grades[i].highScore >= lookupScore
                    ) {
                        return grades[i];
                    }
                }
                return null;
            }

            let data = {
                score: {{ $indicator->progress() }},
                gradingData: @json($indicator->thresholds())
            };

            // create chart
            let chart = am4core.create("status-{{ $indicator->id }}", am4charts.GaugeChart);
            chart.hiddenState.properties.opacity = 0;
            chart.fontSize = 11;
            chart.innerRadius = am4core.percent(80);
            chart.resizable = true;

            /**
             * Normal axis
             */

            let axis = chart.xAxes.push(new am4charts.ValueAxis());
            axis.min = chartMin;
            axis.max = chartMax;
            axis.strictMinMax = true;
            axis.renderer.radius = am4core.percent(80);
            axis.renderer.inside = true;
            axis.renderer.line.strokeOpacity = 0.1;
            axis.renderer.ticks.template.disabled = false;
            axis.renderer.ticks.template.strokeOpacity = 1;
            axis.renderer.ticks.template.strokeWidth = 0.5;
            axis.renderer.ticks.template.length = 5;
            axis.renderer.grid.template.disabled = true;
            axis.renderer.labels.template.radius = am4core.percent(15);
            axis.renderer.labels.template.fontSize = "0.9em";

            /**
             * Axis for ranges
             */
            let axis2 = chart.xAxes.push(new am4charts.ValueAxis());
            axis2.min = chartMin;
            axis2.max = chartMax;
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
            for (let grading of data.gradingData) {
                let range = axis2.axisRanges.create();
                range.axisFill.fill = am4core.color(grading.color);
                range.axisFill.fillOpacity = 0.8;
                range.axisFill.zIndex = -1;
                range.value = grading.lowScore > chartMin ? grading.lowScore : chartMin;
                range.endValue = grading.highScore < chartMax ? grading.highScore : chartMax;
                range.grid.strokeOpacity = 0;
                range.stroke = am4core.color(grading.color).lighten(-0.1);
                range.label.inside = true;
                range.label.inside = true;
                range.label.location = 0.5;
                range.label.inside = true;
                range.label.radius = am4core.percent(10);
                range.label.paddingBottom = -5; // ~half font size
                range.label.fontSize = "0.9em";
            }

            let matchingGrade = lookUpGrade(data.score, data.gradingData);

            /**
             * Label 1
             */

            let label = chart.radarContainer.createChild(am4core.Label);
            label.isMeasured = false;
            label.fontSize = "4em";
            label.x = am4core.percent(50);
            label.paddingBottom = 15;
            label.horizontalCenter = "middle";
            label.verticalCenter = "bottom";
            label.text = data.score;
            label.fill = am4core.color(matchingGrade.color);

            /**
             * Label 2
             */

            let label2 = chart.radarContainer.createChild(am4core.Label);
            label2.isMeasured = false;
            label2.fontSize = "1em";
            label2.horizontalCenter = "middle";
            label2.verticalCenter = "bottom";
            label2.text = matchingGrade.title.toUpperCase();
            label2.fill = am4core.color(matchingGrade.color);


            /**
             * Hand
             */
            let hand = chart.hands.push(new am4charts.ClockHand());
            hand.axis = axis2;
            hand.innerRadius = am4core.percent(55);
            hand.startWidth = 8;
            hand.pin.disabled = true;
            hand.value = data.score;
            hand.fill = am4core.color("#444");
            hand.stroke = am4core.color("#000");

            // Add bottom label
            let labelButton = chart.chartContainer.createChild(am4core.Label);
            labelButton.text = '{{ $indicator->type == \App\Models\Indicator::TYPE_ASCENDING ? '% Av Acumulado': 'Último Período Reportado' }}';
            labelButton.align = "center";
        });
    </script>
@endpush

<div id="indicator-status-chart" style="height: 250px;"></div>

@push('page_script')
    <script>
        am4core.ready(function () {
            let chart = am4core.create("indicator-status-chart", am4charts.PieChart3D);
            chart.hiddenState.properties.opacity = 0;

            chart.legend = new am4charts.Legend();

            chart.data = @json($indicatorsByStatus);

            let series = chart.series.push(new am4charts.PieSeries3D());
            series.dataFields.value = "count";
            series.dataFields.category = "status";
            series.labels.template.disabled = true;
            series.ticks.template.disabled = true;
            series.slices.template.adapter.add("fill", (fill, target) => {
                if (target.dataItem) {
                    switch (target.dataItem.dataContext.status) {
                        case 'Inaceptable':
                            return am4core.color('#ff2f44');
                        case 'Alerta':
                            return am4core.color('#fdae19');
                        case 'Aceptable':
                            return am4core.color('#47b73a');
                    }
                }
            });
        });
    </script>
@endpush
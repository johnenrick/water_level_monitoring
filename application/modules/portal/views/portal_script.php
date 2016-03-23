<script>
    $.material.init();
    $(document).ready(function () {

            $('.fms-header .badge').text($('.fms-content .panel').size() + " sensors");

            var dps = []; // dataPoints

            var chart_settings = function (dpoints, color) {
                    return {
                        data: [{
                            type: "splineArea",
                            color: color,
                            dataPoints: dpoints
                    }]
                    };
                }
                // themes
            var fms_primary = function (dpoints) {
                return chart_settings(dpoints, "#009688");
            }
            var fms_success = function (dpoints) {
                return chart_settings(dpoints, "#4caf50");
            }
            var fms_info = function (dpoints) {
                return chart_settings(dpoints, "#03a9f4");
            }
            var fms_warning = function (dpoints) {
                return chart_settings(dpoints, "#ff5722");
            }
            var fms_danger = function (dpoints) {
                return chart_settings(dpoints, "#f44336");
            }

            var sensor1 = new CanvasJS.Chart("sensor1", fms_primary(dps));
            var sensor2 = new CanvasJS.Chart("sensor2", fms_success(dps));
            var sensor3 = new CanvasJS.Chart("sensor3", fms_info(dps));
            var sensor4 = new CanvasJS.Chart("sensor4", fms_warning(dps));
            var sensor5 = new CanvasJS.Chart("sensor5", fms_danger(dps));


            var xVal = 0;
            var yVal = 100;
            var prev_yVal = 0;
            var updateInterval = 1000;
            var dataLength = 500; // number of dataPoints visible at any point

            var displayValue = function (e, val, prev_val) {
                var container = $(e).closest('ul');
                container.find('.fms-value').text(val);
                if (val > prev_val)
                    container.find('.fms-arrow').removeClass('fms-safe').addClass('fms-danger').text('arrow_upward');
                else
                    container.find('.fms-arrow').removeClass('fms-danger').addClass('fms-safe').text('arrow_downward');
            }

            var updateChart = function (count) {
                count = count || 1;
                // count is number of times loop runs to generate random dataPoints.

                for (var j = 0; j < count; j++) {
                    yVal = Math.abs(yVal + Math.round(5 + Math.random() * (-5 - 5)));
                    dps.push({
                        x: xVal,
                        y: yVal
                    });
                    xVal++;
                };
                if (dps.length > dataLength) {
                    dps.shift();
                }


                sensor1.render();
                displayValue('#sensor1', yVal, prev_yVal);

                sensor2.render();
                displayValue('#sensor2', yVal, prev_yVal);

                sensor3.render();
                displayValue('#sensor3', yVal, prev_yVal);

                sensor4.render();
                displayValue('#sensor4', yVal, prev_yVal);

                sensor5.render();
                displayValue('#sensor5', yVal, prev_yVal);

                prev_yVal = yVal;
            };

            // generates first set of dataPoints
            updateChart(dataLength);

            // update chart after specified time.
            setInterval(function () {
                updateChart()
            }, updateInterval);



            // settings
            $('#fms-add-sensor').click(function () {

            });


            $(".panel-heading .btn-fab.btn-fab-mini").click(function(e){
                e.preventDefault();
                return false;
            });

            // display chart
            $(".panel").click(function () {
                $(this).find('.fms-graph-container').slideToggle();
            });

        });
</script>
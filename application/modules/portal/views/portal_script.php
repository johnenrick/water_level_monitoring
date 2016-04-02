<script>
    /*global CanvasJS*/
    $.material.init();
    $('.fms-header .badge').text($('.fms-content .panel').size() + " sensors");

    var dps = []; // dataPoints
    var colors = ["#009688", "#4caf50", "#03a9f4", "#ff5722", "#f44336"];
    var sensorList = [];

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
    };
    var fms_success = function (dpoints) {
        return chart_settings(dpoints, "#4caf50");
    };
    var fms_info = function (dpoints) {
        return chart_settings(dpoints, "#03a9f4");
    };
    var fms_warning = function (dpoints) {
        return chart_settings(dpoints, "#ff5722");
    };
    var fms_danger = function (dpoints) {
        return chart_settings(dpoints, "#f44336");
    };
    $(document).ready(function () {

            /*Create Sensor List*/
            $.post(api_url("C_device/retrieveDevice"), {}, function(data){
                var response = JSON.parse(data);
                sensorList = [];
                if(!response["error"].length){
                    sensorList = response["data"];
                    for(var ctr = 0; ctr < response["data"].length; ctr++){
                        var sensorPanel = $("#prototype").find(".sensorPanel").clone();
                        sensorPanel.find(".fms-location").text(response["data"][ctr]["description"]);
                        sensorPanel.find(".fms-graph").attr("id", "sensor"+ctr);
                        sensorList[ctr]["sensor_graph"] = new CanvasJS.Chart("sensor"+ctr, fms_primary(dps));
                        $("#fms-sensor-container").append(sensorPanel);
                    }
                }else{
                    console.log(response["error"][0]["message"]);
                }
            })

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
            $("#fms-sensor-container").on("click", ".panel",function () {
                $(this).find('.fms-graph-container').slideToggle();
            });

        });
</script>
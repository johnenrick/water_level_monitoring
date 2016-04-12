<script>
    /*global CanvasJS, webMap */
    $.material.init();

    var dps = []; // dataPoints
    var colors = ["#009688", "#4caf50", "#03a9f4", "#ff5722", "#f44336"];
    var panelHeading = ["panel-primary", "panel-success", "panel-warning"];
    var sensorList = [];
    var xVal = 0;
    var yVal = 100;
    var prev_yVal = 0;
    var updateInterval = 1000;
    var dataLength = 20; // number of dataPoints visible at any point
       
    var updateChart = function (count) {
        count = count || 1;
        
        for(var ctr = 0; ctr < sensorList.length; ctr++){
            
            updateWaterLevel(ctr);
        }

        prev_yVal = yVal;
    };
    var updateWaterLevel = function(deviceIndex){
        var filter = {
            condition : {
                device_ID: sensorList[deviceIndex]["ID"]
            },
            limit : dataLength,
            sort : {
                water_level__datetime : "desc"
            }
        };
        $.post(api_url("C_water_level/retrieveWaterLevel"), filter, function(data){ 
            var response = JSON.parse(data);
            if(!response["error"].length){
                for(var ctr = response["data"].length - 1; ctr >= 0 ; ctr--){
                    if((sensorList[deviceIndex]["data_point"].length === 0) || (sensorList[deviceIndex]["data_point"][sensorList[deviceIndex]["data_point"].length -1]["datetime"]*1 < response["data"][ctr]["datetime"]*1)){
                        displayValue(
                            '#sensor'+deviceIndex, 
                            response["data"][ctr]["measurement"]/100, 
                            sensorList[deviceIndex]["data_point"].length ? sensorList[deviceIndex]["data_point"][sensorList[deviceIndex]["data_point"].length-1]["measurement"]/100 : 0);
                        response["data"][ctr]["x"] = new Date(response["data"][ctr]["datetime"]*1000);
                        response["data"][ctr]["y"] = response["data"][ctr]["measurement"]/100;
                        sensorList[deviceIndex]["data_point"].push(response["data"][ctr]);
                        if(sensorList[deviceIndex]["data_point"].length === dataLength){
                            sensorList[deviceIndex]["data_point"].shift();
                        }
                    }
                }
            }
            sensorList[deviceIndex]["sensor_graph"].render();
        })
    };
    var displayValue = function (e, val, prev_val) {    
        var container = $(e).closest('ul');
        container.find('.fms-value').text(val);
        if (val > prev_val)
            container.find('.fms-arrow').removeClass('fms-safe').addClass('fms-danger').text('arrow_upward');
        else
            container.find('.fms-arrow').removeClass('fms-danger').addClass('fms-safe').text('arrow_downward');
    }
    $(document).ready(function () {
        
        /*Create Sensor List*/
        $.post(api_url("C_device/retrieveDevice"), {}, function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                sensorList = response["data"];
                $("#sensorQuantity").text(response["data"].length +" sensor(s)");
                for(var ctr = 0; ctr < response["data"].length; ctr++){
                    var sensorPanel = $("#prototype").find(".sensorPanel").clone();
                    $("#fms-sensor-container").append(sensorPanel);
                    sensorPanel.addClass(panelHeading[ctr]);
                    sensorPanel.attr("device_id", response["data"][ctr]["ID"]);
                    sensorPanel.find(".fms-location").text(response["data"][ctr]["description"]);
                    sensorPanel.find(".fms-sensor-name").text("Sensor "+response["data"][ctr]["ID"]);
                    sensorPanel.find(".fms-graph").attr("id", "sensor"+ctr);
                    sensorList[ctr]["data_point"] = new Array();
                    sensorList[ctr]["sensor_graph"] = new CanvasJS.Chart("sensor"+ctr, {
                        axisX: {
                            valueFormatString: "mm",
                            interval:1,
                            tickLength : 1,
                            intervalType: "minute"
                        },
                        axisY:{
                            maximum: 5,
                        },
                        data: [{
                            type: "splineArea",
                            color : colors[ctr],
                            dataPoints: sensorList[ctr]["data_point"] 
                        }]
                    });
                }
                // generates first set of dataPoints
                updateChart(dataLength);
                // update chart after specified time.
                setInterval(function () {
                    updateChart();
                }, updateInterval);
            }else{
                console.log(response["error"][0]["message"]);
            }

        })

        








        
        // settings
        $("#fms-sensor-container").on("click", ".editSensor", function(){
            $.post(api_url("C_device/retrieveDevice"), {ID : $(this).parent().parent().attr("device_id")}, function(data){
                var response = JSON.parse(data);
                if(!response["error"].length){
                    $("#fms-modal").modal("show");
                    $("#fms-modal").find("input[name=ID]").val(response["data"]["ID"]);
                    $("#fms-modal").find("input[name='updated_data[description]']").val(response["data"]["description"]);
                    $("#fms-modal").find("input[name='updated_data[longitude]']").val(response["data"]["longitude"]);
                    $("#fms-modal").find("input[name='updated_data[latitude]']").val(response["data"]["latitude"]);
                    $("#sensorSettingLatitude").text(response["data"]["latitude"]);
                    $("#sensorSettingLongitude").text(response["data"]["longitude"]);
                }
            });
        });
        $("#editSensorSettingSubmit").click(function(){
            $("#editSensorSettingForm").trigger("submit");
        });
        $("#editSensorSettingForm").attr("action", api_url("C_device/updateDevice"));
        $("#editSensorSettingForm").ajaxForm({
            success: function (data) {
                var response = JSON.parse(data);
                if(!response["error"].length){
                    webMap.showDevice();
                    $(".sensorPanel[device_id='"+$("#fms-modal").find("input[name=ID]").val()+"']").find(".fms-location").text($("#fms-modal").find("input[name='updated_data[description]']").val());
                }else{

                }
            }
        });
        $("#editSensorSettingChangeLocation").click(function(){
            $("#fms-modal").modal("hide");
            webMap.webMap.selectLocation(function(latlng){
                webMap.webMap.selectLocation(function(latlng){console.log(latlng)});
                $("#sensorSettingLatitude").text(latlng.lat);
                $("#sensorSettingLongitude").text(latlng.lng);
                $("#fms-modal").find("input[name='updated_data[longitude]']").val(latlng.lng);
                $("#fms-modal").find("input[name='updated_data[latitude]']").val(latlng.lat);
                $("#fms-modal").modal("show");
            });
        })
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
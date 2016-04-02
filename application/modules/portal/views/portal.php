<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>F-M-S</title>

    <!-- Material Design fonts -->
    <!--<link rel="stylesheet" href="<?=asset_url("font/font_style.css")?>" type="text/css">-->
    <link rel="stylesheet" href="<?=asset_url("css/font_style.css")?>" type="text/css">
    <link href="<?=asset_url("css/icon.css")?>" rel="stylesheet" type="text/css">

    <!-- Bootstrap -->
    <link href="<?=asset_url("css/bootstrap.min.css")?>" rel="stylesheet">

    <!-- Bootstrap Material Design -->
    <link href="<?=asset_url("css/bootstrap-material-design.css")?>" rel="stylesheet">
    <link href="<?=asset_url("css/ripples.min.css")?>" rel="stylesheet">

    <style>
        body,
        html {
            height: 100%;
        }

        body {
            overflow: hidden;
        }

        .full-height {
            height: 100%;
            min-height: 100%;
        }

        .fms-left-container {
            background-color: rgba(0, 0, 0, 0.08);
            /*#eeeff4;*/
            padding-left: 0;
            padding-right: 0;
            position: absolute;
            z-index: 2;
        }

        .fms-left-container .fms-header {
            height: 60px;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 4;
        }

        .fms-left-container .fms-header h3 {
            float: left;
            color: white;
        }

        .fms-left-container .fms-header a {
            float: right;
            margin-top: 10px;
        }

        .fms-left-container .fms-content {
            padding-top: 20px;
            min-height: calc(100% - 60px);
            max-height: calc(100% - 60px);
            overflow-y: auto;
        }

        .fms-left-container .fms-content .fms-location {
            margin-top: 20px;
        }

        .fms-left-container .fms-content h2 {
            margin-top: 10px;
        }

        .fms-value,
        .fms-arrow {
            transition: all 0.5s ease-out;
        }

        .fms-arrow.fms-danger {
            color: red;
        }

        .fms-arrow.fms-safe {
            color: limegreen;
        }

        .fms-graph-container {
            /*            transition: all 1s ease-out;*/
            display: none;
        }

        .fms-graph-container hr {
            margin-top: -10px;
        }

        .fms-graph {
            height: 250px;
            width: 100%;
        }

        .panel {
            cursor: pointer;
        }

        #fms-sensor-container .panel .panel-heading .btn-fab.btn-fab-mini {
            height: 25px;
            min-width: 25px;
            width: 25px;
            font-size: 18px;
            float: right;
            margin-left: 5px;
            margin-top: -3px;
        }

        #fms-sensor-container .panel .panel-heading .btn-fab.btn-fab-mini i {
            -webkit-transform: translate(-12px, -12px);
            -ms-transform: translate(-12px, -12px);
            -o-transform: translate(-12px, -12px);
            transform: translate(-12px, -12px);
            line-height: 24px;
            width: 24px;
            font-size: 18px;
        }

        .fms-left-container #fms-add-sensor-container {
            position: absolute;
            z-index: 3;
            background-color: white;
            margin-top: 60px;
            min-height: calc(100% - 60px);
            max-height: calc(100% - 60px);
            overflow-y: auto;
        }

        .fms-right-container {
            padding: 0;
        }

        .fms-right-container #fms-map-container {
            background: url('img/bg.PNG');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: bottom;
        }

        .canvasjs-chart-credit {
            display: none !important;
        }

        .canvasjs-chart-tooltip {
            z-index: 2 !important;
        }

        .canvasjs-chart-canvas {
            height: 250px !important;
            width: 100% !important;
        }
        /*
        .scroll-shadow {
            background: linear-gradient(white 30%, hsla(0, 0%, 100%, 0)), linear-gradient(hsla(0, 0%, 100%, 0) 10px, white 70%) bottom, radial-gradient(at top, rgba(0, 0, 0, 0.3), transparent 70%), radial-gradient(at bottom, rgba(0, 0, 0, 0.3), transparent 70%) bottom;
            background-repeat: no-repeat;
            background-size: 100% 20px, 100% 20px, 100% 10px, 100% 10px;
            background-attachment: local, local, scroll, scroll;
        }
*/

        .scroll-shadow::-webkit-scrollbar-track {
            background-color: rgba(0, 0, 0, 0.05);
            display: none;
        }

        .scroll-shadow::-webkit-scrollbar {
            width: 6px;
            background-color: none;
            display: none;
        }

        .scroll-shadow::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            display: none;
        }

        .unselectable {
            -webkit-user-select: none;
            /* Chrome/Safari */
            -moz-user-select: none;
            /* Firefox */
            -ms-user-select: none;
            /* IE10+ */
        }

        .capitalize {
            text-transform: capitalize;
        }

        .no-padding {
            padding: 0;
        }
    </style>

</head>

<body>

    <div class="container-fluid full-height">
        <div class="row full-height">
            <div class="col-md-4 col-sm-5 col-xs-12 full-height fms-left-container">
                <div class="col-md-12 fms-header">
                    <div class="col-md-10 no-padding">
                        <h3>Flood Monitoring System <span class="badge"></span></h3>
                    </div>
                    <div class="col-md-2 no-padding">
                        <a id="fms-add-sensor" class="btn btn-default btn-fab btn-fab-mini" data-toggle="modal" data-target="#fms-modal"><i class="material-icons">add</i></a>
                    </div>
                </div>
                <div id="fms-sensor-container" class="col-md-12 fms-content scroll-shadow">
                    
                </div>
            </div>
            <div class="col-md-12 full-height fms-right-container">
                <div id="fms-map-container" class="col-md-12 full-height no-padding"></div>
            </div>

            <div id="fms-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Modal title</h4>
                        </div>
                        <div class="modal-body">
                            <p>One fine body…</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="prototype" style="display:none">
        <div class="panel panel-primary sensorPanel">
            <div class="panel-heading">
                <span class="fms-sensor-name">Sensor 1</span>
                <a class="btn btn-default btn-fab btn-fab-mini"><i class="material-icons">delete_forever</i></a>
                <a class="btn btn-default btn-fab btn-fab-mini"><i class="material-icons">edit</i></a>
            </div>
            <!-- List group -->
            <ul class="list-group unselectable">
                <li class="list-group-item">
                    <div class="col-md-5 col-sm-12 col-xs-5">
                        <h2><span class="fms-value">20</span> <span class="fms-unit">m</span> <i class="material-icons fms-arrow fms-danger">arrow_upward</i></h2>
                    </div>
                    <div class=" col-md-7 col-sm-12 col-xs-7">
                        <h4 class="fms-location capitalize">Bacayan, Cebu City, Cebu</h4>
                    </div>
                </li>
                <li class="list-group-item fms-graph-container">
                    <hr>
                    <div  class="fms-graph">
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <!-- jQuery -->
    <script type="text/javascript" src="<?=asset_url("js/jquery-2.1.4.min.js")?>"></script>
    <script type="text/javascript" src="<?=asset_url("js/jquery-ui.min.js")?>"></script>
    <script type="text/javascript" src="<?=asset_url("js/canvasjs.min.js")?>"></script>
    <!-- Twitter Bootstrap -->
    <script type="text/javascript" src="<?=asset_url("js/bootstrap.min.js")?>"></script>

    <!-- Material Design for Bootstrap -->
    <script type="text/javascript" src="<?=asset_url("js/material.js")?>"></script>
    <script type="text/javascript" src="<?=asset_url("js/ripples.min.js")?>"></script>
    <script>
        $.material.init();
    </script>



    <!--  FMS Script  -->

    <!--  source http://canvasjs.com/html5-javascript-dynamic-chart/ -->
    <script type="text/javascript">

    </script>


</body>

</html>

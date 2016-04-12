<script>
    var webMap = {};
    webMap.initializeWastemapManagement = function(){
        webMap.webMap = new WebMapComponent("#fms-map-container ",{});
        webMap.webMap.selectLocation(function(latlng){
            console.log(latlng);
        });
        webMap.showDevice();
    };
    webMap.showDevice = function(){
        $.post(api_url("C_device/retrieveDevice"), {}, function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                
                for(var x = 0; x < response["data"].length; x++){
                    webMap.webMap.removeMarkerList(response["data"][x]["ID"]);
                    webMap.webMap.addMarker(response["data"][x]["ID"], 2, response["data"][x]["ID"], response["data"][x]["description"], response["data"][x]["longitude"], response["data"][x]["latitude"], false, false);
                }
            }
        });
    };
    
    $(document).ready(function(){
        load_page_component("web_map_component", webMap.initializeWastemapManagement);
        
    });
</script>
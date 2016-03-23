<script>
    var system_data = {
        url : {
            base_url : "<?=base_url()?>",
            api_url : "<?=api_url()?>",
            asset_url : "<?=asset_url()?>"
        }
    };
    function base_url(link){
       return system_data.url.base_url+((typeof link === "undefined") ? "" : link);
    }
    function api_url(link){
       return system_data.url.api_url+link;
    }
    function asset_url(link){
       return system_data.url.asset_url+link;
    }
</script>
<!--Modularization-->
<script>
    /*Loading Modules*/
    function load_module(moduleLink, moduleName){
        if($("#moduleContainer").find(".moduleHolder[module_link='"+moduleLink+"']").length === 0){
            $.post(base_url(moduleLink), {}, function(data){
                /*CHECK IF JSON OR HTML FOR AUTHORIZATION*/

                var moduleHolder = $("#systemComponent").find(".moduleHolder").clone();

                moduleHolder.attr("module_link", moduleLink);
                moduleHolder.attr("id",moduleName.replace(/_([a-z])/g, function (g) { return g[1].toUpperCase(); }));
                moduleHolder.append(data);
                $("#moduleContainer").append(moduleHolder);
                /*show page*/
                $('.wl-page-content:not(.moduleHolder[module_link="'+moduleLink+'"])').hide();
                if($('.moduleHolder[module_link="'+moduleLink+'"]').is(":visible") === false){
                    $('.moduleHolder[module_link="'+moduleLink+'"]').fadeIn(500);
                    refresh_call(moduleName);
                }

            });
        }else{
            /*show page*/
            $('.wl-page-content:not(.moduleHolder[module_link="'+moduleLink+'"])').hide();
            if($('.moduleHolder[module_link="'+moduleLink+'"]').is(":visible") === false){
                $('.moduleHolder[module_link="'+moduleLink+'"]').fadeIn(500);
                refresh_call(moduleName);
            }

        }
    }
</script>
<!--Standard Form validation-->
<script>
    /**
     * Shows error in the form submitted
     * @param {DOM} elementSelected the form that has been submitted
     * @param {type} errorList list of error from the api
     * @returns {undefined}
     */
    function show_form_error(elementSelected, errorList){
        elementSelected.find(".formMessage").empty();
        elementSelected.find(".has-error").removeClass(".has-error");
        errorList.forEach(function(errorValue){
            if(errorValue["status"] > 100 && errorValue["status"] < 1000){/*Form Validation Error*/
                for(var index in errorValue["message"]){
                    elementSelected.find(".formMessage").append("* "+errorValue["message"][index]+"<br>");
                    elementSelected.find("input[name='"+index+"']").parent().addClass("has-error");
                }
            }else if(errorValue["status"] > 1000 && errorValue["status"] < 10000){/*System Error*/

            }else{
                elementSelected.find(".formMessage").append("* "+errorValue["message"]+"<br>");
            }
        });
    }
    function clear_form_error(elementSelected){
        elementSelected.find(".formMessage").empty();
        elementSelected.find(".has-error").removeClass(".has-error");
    }
    /**
     * Show a system message at the bottom of the interface
     *
     * @param {int} status status of the message to avoid conflict
     * @param {int} messageType warning|danger|success|info
     * @param {object} messageDetail the message to be displayed
     * @param {object} link object containing the text and href of the link
     * @returns {undefined}
     */
    function show_system_message(status, messageType, messageDetail, link){
        var messagePrototype = $("#systemComponent").find(".systemMessage").clone();
        messagePrototype.find(".alert-message").text(messageDetail);
        messagePrototype.attr("message_status", status);
        if(typeof link !== "undefined"){
            messagePrototype.find(".alert-link").text(link["text"]);
            if(typeof link["href"] !== "undefined"){
                messagePrototype.find(".alert-link").attr("href", link["href"]);
            }else if(typeof link["callback"] !== "undefined"){
                messagePrototype.find(".alert-link").click(link["callback"]);
            }

        }
        switch(messageType){
            case 1: /*warning*/
                messagePrototype.addClass("alert-warning");
                messagePrototype.find(".alert-title").text("Warning!");
                break;
            case 2: /*danger*/
                messagePrototype.addClass("alert-danger");
                messagePrototype.find(".alert-title").text("Alert!");
                break;
            case 3: /*success*/
                messagePrototype.addClass("alert-success");
                messagePrototype.find(".alert-title").text("Success!");
                break;
            case 4: /*info*/
                messagePrototype.addClass("alert-info");
                messagePrototype.find(".alert-title").text("Information!");
                break;
        }

        $("#systemMessageContainer").prepend(messagePrototype);
        messagePrototype.fadeIn();
    }
</script>
<!--Component-->
<script>
    /**
     * Load a Page Component to the Document.
     * @param {string} component The name of the component to be loaded
     * @param {function} callBack The function called after the component is loaded. This is where the purpose of loading a component being place
     * @returns {none}
     */
    function load_page_component(component, callBack){
        if($("."+component).length === 0 ){
            $.post("<?=base_url()?>system_application/loadPageComponent", {component : component}, function(data){
                $("#pageComponentContainer").append(data);
                callBack();
            });
        }else{
            callBack();
        }

    }
</script>
<!--Document Ready-->
<script>
    $(document).ready(function(){

    });
</script>

<script>
$(document).ready(function(){
    //$("#primary, .hero").addClass('hide');
    //$('body').css('background','url("'+$('.hero img').attr('src')+'") no-repeat');
    //$('body').css('background-size','100%');

        var img_url = "<?=  base_url()?>assets/img/PHOTOS/";
        var img_def = "<?=  base_url()?>assets/img/default.JPG";
    //var element = $('<object id="webcard" type="application/x-webcard" width="0" height="0"><param name="onload" value="pluginLoaded" /></object>');
    //$('body').append(element);
    $('#page-nav, .hero, .footer').addClass('hide');
    $('#primary').css('padding-top','40px');
    $(".img").error(function(){
        $(".img").attr('src',img_def);
    });

/////////////////////////////////////
//         RFID - code start       //
/////////////////////////////////////

    pluginLoaded();
     function addEvent(obj, name, func)
    {
        if (obj.attachEvent) {
            obj.attachEvent("on" + name, func);
        } else {
            obj.addEventListener(name, func, false);
        }
    }

    function pluginLoaded() {
        window.webcard = document.getElementById("webcard");
        addEvent(webcard, "cardpresent", returnUIDtCard);
    }
    var protalImageCounter = false;
    function returnUIDtCard(reader) {
        clearTimeout(protalImageCounter);
        reader.connect(2); // 1-Exclusive, 2-Shared
        var apdu = "FFCA000000";
        var resp = reader.transcieve(apdu);
        var uid;
        var action;
        if (resp.substr(-4) == "9000") {
            uid = resp.substr(0, resp.length - 4);

            if(uid){
                action = (reader.name.replace(/\s/g, "").toLowerCase()=="acsacr1220")?"out":"in";
                $.post("<?=  api_url()?>c_circulation/retrieveDisplayStudent",{uid:uid,location:"gate-portal",action:action},function(data){
                    
                    var response = JSON.parse(data);
                    console.log(response)
                    if(!response["error"].length){
                        $("#"+action+" .name").css("color","");
                        $("#"+action+" .img").attr('src',img_url+response['data']['identification_number']+".jpg");
                        $("#"+action+" .id").text(response['data']['identification_number']);
                        $("#"+action+" .name").text((response['data']['last_name']+', '+response['data']['first_name']+' '+response['data']['middle_name']).toUpperCase());

                        protalImageCounter = setTimeout(function() {
                            if(protalImageCounter !== true){
                                $("#"+action+" .img").attr('src',img_def);
                                $("#"+action+" .id").text($("#"+action+" .id").attr('data-default'));
                                $("#"+action+" .name").text($("#"+action+" .name").attr('data-default'));
                            }
                        }, 5000);
                    }else{
                        $("#"+action+" .name").text(("Not Found!").toUpperCase()).css("color", "red");
                        $("#"+action+" .img").attr('src',img_def);
                        $("#"+action+" .id").text($("#"+action+" .id").attr('data-default'));
                        protalImageCounter = setTimeout(function() {
                            if(protalImageCounter !== true){
                                $("#"+action+" .img").attr('src',img_def);
                                $("#"+action+" .id").text($("#"+action+" .id").attr('data-default'));
                                $("#"+action+" .name").text($("#"+action+" .name").attr('data-default')).css("color", "");
                            }
                        }, 5000);
                    }
                });
            }
        }
        reader.disconnect();
    }
/////////////////////////////////////
//          RFID - code end        //
/////////////////////////////////////





});

</script>

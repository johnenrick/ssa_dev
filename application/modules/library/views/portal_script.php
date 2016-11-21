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
	$("#img").error(function(){
		$("#img").attr('src',img_def);
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

    function returnUIDtCard(reader) {
        reader.connect(2); // 1-Exclusive, 2-Shared
        var apdu = "FFCA000000";
        var resp = reader.transcieve(apdu);
        var uid;
        var action;
        if (resp.substr(-4) == "9000") {
            uid = resp.substr(0, resp.length - 4);

            if(uid){
                $.post("<?=  api_url()?>c_circulation/retrieveDisplayStudent",{uid:uid,location:"library-portal",action:""},function(data){
                    var response = JSON.parse(data);
                        if(!response["error"].length){
                            console.log(response['data']);
                            $("#img").attr('src',img_url+response['data']['identification_number']+".jpg");
                            $("#id").text(response['data']['identification_number']);
                            $("#name").text(response['data']['last_name']+', '+response['data']['first_name']+' '+response['data']['middle_name']);
                            setTimeout(function() {
                                $("#img").attr('src',img_def);
                                $("#id").text($("#id").attr('data-default'));
                                $("#name").text($("#name").attr('data-default'));
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

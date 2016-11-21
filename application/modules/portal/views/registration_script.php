<script>
$(document).ready(function(){
    //$("#primary, .hero").addClass('hide');
    //$('body').css('background','url("'+$('.hero img').attr('src')+'") no-repeat');
    //$('body').css('background-size','100%');

        var img_url = "<?=  base_url()?>assets/img/PHOTOS/";
        var img_def = "<?=  base_url()?>assets/img/default.JPG";
        var newRow = $(".sample").clone();
    //var element = $('<object id="webcard" type="application/x-webcard" width="0" height="0"><param name="onload" value="pluginLoaded" /></object>');
    //$('body').append(element);
    //$('#page-nav, .hero, .footer').addClass('hide');
    //$('#primary').css('padding-top','40px');

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
    var response;

    function returnUIDtCard(reader) {
        reader.connect(2); // 1-Exclusive, 2-Shared
        var apdu = "FFCA000000";
        var resp = reader.transcieve(apdu);
        var uid;
        var action;
        var clone;
        if (resp.substr(-4) == "9000") {
            uid = resp.substr(0, resp.length - 4);

            if(uid && !$("#listBody").find(".listUID input[value='"+uid+"']").length){
                $.post("<?=  api_url()?>c_circulation/retrieveDisplayID",{uid:uid},function(data){
                    clone = newRow.clone();
                    clone.find(".listUID").append(uid.toUpperCase());
                    clone.find(".listUID input").val(uid);
                    clone.removeClass('hide').fadeOut();
                    response = JSON.parse(data);
                    if(!response["error"].length){
                        console.log(response['data']);
                        clone.addClass("info");
                        clone.find(".listID input").val(response['data']['identification_number']);
                        clone.find(".listName").text((response['data']['first_name']+' '+response['data']['middle_name']+' '+response['data']['last_name']).toUpperCase());
                    }
                    $("#listBody tbody").append(clone.fadeIn('500'));
                    clone.find(".listID input").focus();

                });
            }
        }
        reader.disconnect();
    }
/////////////////////////////////////
//          RFID - code end        //
/////////////////////////////////////

    $("#listBody").on("change","input[name='listID']", function(e){
        var ths = $(this);
        $.post("<?=  api_url()?>c_circulation/retrieveDisplayID",{id:ths.val()},function(data){
            response = JSON.parse(data);
            if(!response["error"].length){
                $(ths).closest('tr').removeClass('danger');
                $(ths).closest('tr').find('.listName').text((response['data']['first_name']+' '+response['data']['middle_name']+' '+response['data']['last_name']).toUpperCase());
            }
            else
            {
                $(ths).closest('tr').addClass('danger');
            }

        });
    });

    $("#listBody").on("keydown","input[name='listID']", function(e){
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.keyCode == 65 && e.ctrlKey === true) ||
            (e.keyCode == 67 && e.ctrlKey === true) ||
            (e.keyCode == 86 && e.ctrlKey === true) ||
            (e.keyCode == 88 && e.ctrlKey === true) ||
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $("#listBody").on("click", ".listRemoveButton", function(){
       $(this).parent().find(".confirmButton").show();
       $(this).parent().find(".actionButton").hide();
       $(this).closest('tr').addClass('warning');
    });
    $("#listBody").on("click", ".listNoRemoveButton", function(){
       $(this).parent().find(".confirmButton").hide();
       $(this).parent().find(".actionButton").show();
       $(this).closest('tr').removeClass('warning');
    });
    $("#listBody").on("click", ".listYesRemoveButton", function(){
       var ths = $(this);
        ths.closest('tr').remove();
    });

    $("#register").click(function(){
        var data = $('form').serializeArray();
        $.post("<?=  api_url()?>c_circulation/registerRFID",{'list':data},function(data){
            $('.alert-success').show().delay(5000).fadeOut();
            $('#listBody tbody').empty();
        });
    });

    $( "form" ).submit(function( event ) {
      event.preventDefault();
    });

});

</script>

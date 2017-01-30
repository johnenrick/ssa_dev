
<script>
    /* global systemApplication */
    var systemApplication = {
        userInformation : {
            userID : "<?=user_id()?>"*1,
            userType : "<?= user_type()?>"*1,
            firstName : "<?= user_first_name()?>",
            lastName : "<?= user_last_name()?>",
            middleName : "<?=user_middle_name()?>"
        },
        url : {
            apiUrl : "<?=api_url()?>",
            baseUrl : "<?=base_url()?>"
        },
        privilige : {}
    };
    
    /*Load Module Header*/
    $.post(systemApplication.url.apiUrl+"c_access_control_list/retrieveAccessControlList", {account_ID : "<?=user_id()?>"*1, retrieve_all : 1}, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            for(var x = 0; x < response["data"].length; x++){
                var parent = false;
                //systemApplication.privilige[response["data"][x]["module_ID"]] = (response["data"][x]).slice();
                if(response["data"][x]["parent_module_ID"]*1){
                    if($("#moduleHead"+response["data"][x]["parent_module_ID"]).length){
                        
                    }else{
                        var newModule = $(".prototype").find(".moduleHead").clone();
                        newModule.attr("id", "moduleHead"+response["data"][x]["parent_module_ID"]);
                        newModule.find(".moduleHeadName").text(response["data"][x]["parent_module_description"]);
                        (response["data"][x]["module_link"] !== "") ?  $("#headerModule").append(newModule) : "";
                    }
                    var subModule = $(".prototype").find(".subModule").clone();
                    subModule.find(".moduleHeadName").text(response["data"][x]["description"]);
                    subModule.find(".moduleHeadName").attr("href",systemApplication.url.baseUrl+response["data"][x]["module_link"]);
                    newModule.find("ul").append(subModule);
                    
                }else{
                    var newModule = $(".prototype").find(".moduleHead").clone();
                    newModule.attr("id", "moduleHead"+response["data"][x]["module_ID"]);
                    newModule.find(".moduleHeadName").text(response["data"][x]["description"]);
                    //$("#headerModule").append(newModule);
                    (response["data"][x]["module_link"] !== "") ?  $("#headerModule").append(newModule) : "";
                    //sub modules
                    //systemApplication.privilige[response["data"][x]["module_ID"]]["sub_module"] = false;
                    for(var y = 0; y < response["data"][x]["sub_module"].length; y++){
                        //systemApplication.privilige[response["data"][x]["module_ID"]]["sub_module"][response["data"][x]["sub_module"][y]["ID"]] = response["data"][x]["sub_module"][y];
                        var subModule = $(".prototype").find(".subModule").clone();
                        subModule.find(".moduleHeadName").text(response["data"][x]["sub_module"][y]["description"]);
                        subModule.find(".moduleHeadName").attr("href",systemApplication.url.baseUrl+response["data"][x]["sub_module"][y]["link"]);
                        (response["data"][x]["sub_module"][y]["link"] !== "") ? newModule.find("ul").append(subModule) : "";
                       
                    }
                }
            }
        }
    });
    
   
    
    /* Global systemUtility */
    var systemUtility = {};
    /*
     * @param {string} messageSelector Element selector where message will be shown
     * @param {string} message Message to be shown
     * @param {string} messageType Type of messaage : success, info, warning, danger
     * @param {string} duration Duration the message will be shown in millisecons. 0 is infinite 
     * @returns (int)   
     * */
    systemUtility.showMessage = function(messageSelector ,message, messageType, duration){
        var messagePrototype = $(".prototype").find(".alertMessagePrototype").clone();
        messagePrototype.removeClass("alert-warning");
        messagePrototype.removeClass("alert-danger");
        messagePrototype.removeClass("alert-info");
        messagePrototype.removeClass("alert-success");
        messagePrototype.addClass("alert-"+messageType);
        messagePrototype.append(message);
        messageSelector = (typeof messageSelector === "string") ? $(messageSelector) : messageSelector;
        messageSelector.append(messagePrototype);
        messageSelector.focus();
        $('html, body').animate({
            scrollTop: $(messageSelector).offset().top - 100
        }, 1000);
        if(duration > 0){
            setTimeout(function(){
                messagePrototype.remove();
            },duration);
        }
        return messagePrototype;
    };
    /***
     * @param {String} messageSelector Element selector where message will be shown 
     * @param {Object} errorList Object contaning the list of errors returned from the API
     * @returns {undefined}     */
    systemUtility.showErrorMessage = function(messageSelector, errorList){
        for(var x = 0; x < errorList.length; x++){
            switch(errorList[x]["status"]){
                case 200 : alert("Hello World");
                    break;
                default : 
                    systemUtility.showMessage(
                            messageSelector,
                            errorList[x]["message"],
                            "warning",
                            2000
                            );
                    break;
            }
        }
    };
    systemApplication.current_academic_year_pointer = 2;
    systemApplication.academic_year = [
        {
            academic_year : 1396828800,
            label : "2014-15"
        },
        {
            academic_year : 1428336000,
            label : "2015-16"
        },
        {
            academic_year : 1459958400,
            label : "2016-17"
        },
        {
            academic_year : 1491523200,
            label : "2017-18"
        }
    ];
    systemApplication.academic_year_label = {
        1396828800 : "2014-15",
        1428336000 : "2015-16",
        1459958400 : "2016-17",
        1491523200 : "2017-18"
    };
    /***
    * Append list of academic years

     * @param {type} elementSelector
     * @returns {undefined}     */
    systemUtility.addAcademicYearSelectOption = function(elementSelector){
        for(var x = 0 ; x < systemApplication.academic_year.length; x++){
            $(elementSelector).append("<option value='"+systemApplication.academic_year[x]["academic_year"]+"' >"+systemApplication.academic_year[x]["label"]+"</option>");
        }
        $(elementSelector).val(systemUtility.getCurrentAcademicYear());
        /*var currentYear = new Date();
        for(var x=1990; x <= currentYear.getFullYear(); x++){
            var date = new Date("4,7,"+x);
            $(elementSelector).append("<option value='"+(date.getTime()/1000)+"' >"+x+"</option>");
        }*/
    };
    systemUtility.getCurrentAcademicYear = function(){
        /*var currentDateYear = new Date();
        var currentYear = (currentDateYear.getMonth() >= 5 && currentDateYear.getDate() >= 24) ? currentDateYear.getFullYear()  :currentDateYear.getFullYear()-1;
        return (new Date("4,7,"+currentYear)).getTime()/1000;*/
        return systemApplication.academic_year[systemApplication.current_academic_year_pointer]["academic_year"];
    };
    systemUtility.getNextAcademicYear = function(currentYearTimestamp){
        return systemApplication.academic_year[systemApplication.current_academic_year_pointer+1]["academic_year"];
    };
    systemUtility.formatAMPM = function(date){
        var hours = date.getHours();
         var minutes = date.getMinutes();
         var ampm = hours >= 12 ? 'PM' : 'AM';
         hours = hours % 12;
         hours = hours ? hours : 12; // the hour '0' should be '12'
         minutes = minutes < 10 ? '0' + minutes : minutes;
         var strTime = hours + ':' + minutes + ' ' + ampm;
         return strTime;
    };
    systemUtility.formatTime = function(date){
        var hours = date.getHours();
     var minutes = date.getMinutes();
     hours = hours < 10 ? '0' + hours : hours;
     minutes = minutes < 10 ? '0' + minutes : minutes;
     var strTime = hours + ':' + minutes;
     return strTime;
    };
    systemUtility.insertDecimalPoints = function(s){
        var l = s.length;
        var res = ""+s[0];
        for (var i=1;i<l-1;i++)
        {
            if ((l-i)%3==0)
                res+= ",";
            res+=s[i];
        }
        res+=s[l-1];

        res = res.replace(',.','.');

        return res;
    };
    systemUtility.pad = function(num, size) {
        var s = num+"";
        while (s.length < size) s = "0" + s;
        return s;
    };
    systemUtility.exportTable = function(elementID, filename){
        var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
        var textRange; var j=0;
        tab = document.getElementById(elementID); // id of table

        for(j = 0 ; j < tab.rows.length ; j++) 
        {     
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
        }

        tab_text=tab_text+"</table>";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE "); 

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open("txt/html","replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus(); 
            sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
        }  
        else{                 //other browser not tested on IE 11
//            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
            var a = document.createElement('a');
            a.href  = 'data:application/vnd.ms-excel,' + encodeURIComponent(tab_text);
            a.download = filename + '.xls';
            a.click();
        }
    }
    /*############HEADER SCRIPTS################*/
    $(document).ready(function(){
        $("#headerAccountChangePasswordSubmitButton").click(function(){
            
            if($("#headerAccountChangePasswordOldPassword").val() === ""){
                systemUtility.showMessage("#headerAccountChangePasswordMessage" ,"Please type your old password", "warning" , 2000);
                return false;
            }
            if($("#headerAccountChangePasswordNewPassword").val() === ""){
                systemUtility.showMessage("#headerAccountChangePasswordMessage" ,"Please type your new password", "warning" , 2000);
                return false;
            }
            if($("#headerAccountChangePasswordNewPassword").val() !== $("#headerAccountChangePasswordVerifyPassword").val()){
                systemUtility.showMessage("#headerAccountChangePasswordMessage" ,"New Password and Old Password didn't matched.", "warning" , 2000);
                return false;
            }
            var newData = {
                old_password : $("#headerAccountChangePasswordOldPassword").val(),
                new_password : $("#headerAccountChangePasswordNewPassword").val()
            };
            $("#headerAccountChangePasswordSubmitButton").button("loading");
            $.post(systemApplication.url.apiUrl+"c_account/changePassword", newData, function(data){
                var response = JSON.parse(data);
                if(!response["error"].length){
                    $("#headerAccountChangePasswordOldPassword").val("");
                    $("#headerAccountChangePasswordNewPassword").val("");
                    $("#headerAccountChangePasswordVerifyPassword").val("");
                    systemUtility.showMessage("#headerAccountChangePasswordMessage" ,"Password Changed", "success" , 2000);
                }else{
                    systemUtility.showErrorMessage("#headerAccountChangePasswordMessage", response["error"]);
                }
                $("#headerAccountChangePasswordSubmitButton").button("reset");
            });

        });
    });
    systemUtility.romanize = function(num) {
        if (!+num)
            return false;
        var digits = String(+num).split(""),
            key = ["","C","CC","CCC","CD","D","DC","DCC","DCCC","CM",
                   "","X","XX","XXX","XL","L","LX","LXX","LXXX","XC",
                   "","I","II","III","IV","V","VI","VII","VIII","IX"],
            roman = "",
            i = 3;
        while (i--)
            roman = (key[+digits.pop() + (i * 10)] || "") + roman;
        return Array(+digits.join("") + 1).join("M") + roman;
    };
</script>

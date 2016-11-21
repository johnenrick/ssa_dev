<script>
    var studentAccountManagement = {};
    $(document).ready(function(){
        
        $("#previousStudentListPage").click(function(){
           if($("#currentStudenListPage").text()*1 -1){
               $("#currentStudenListPage").text($("#currentStudenListPage").text()*1 -1);
               studentAccountManagement.retrieveStudentList();
           }
        });
        $("#nextStudentListPage").click(function(){
            $("#currentStudenListPage").text($("#currentStudenListPage").text()*1 + 1);
            studentAccountManagement.retrieveStudentList();
        });
        studentAccountManagement.retrieveStudentList();
        $("#studentListBody").on("click", ".removeStudentListEntry", function(){
           $(this).parent().find(".confirmButton").show();
           $(this).parent().find(".actionButton").hide();
        });
        $("#studentListBody").on("click", ".noRemoveStudentListEntry", function(){
           $(this).parent().find(".confirmButton").hide();
           $(this).parent().find(".actionButton").show();
        });
        $("#studentListBody").on("click", ".yesRemoveStudentListEntry", function(){
           studentAccountManagement.removeStudentList($(this).parent().parent());
        });
        $("#previousSchoolHistorySchoolYear").change(function(){
             $("#previousSchoolHistoryDatetime").val((new Date("1,1,"+$("#previousSchoolHistorySchoolYear").val()).getTime())/1000);
        });
        $("#previousSchoolHistoryForm").ajaxForm({
            beforeSubmit : function(){

            },
            success : function(data){
                var response = JSON.parse(data);

                if(!response["error"].length){
                    $("#previousSchoolHistoryForm").trigger("reset");

                    $("#studentInfo").trigger("reset");
                    $("#studentInformationDiv").slideUp();
                    studentAccountManagement.retrieveStudentList();
                }
            }
        });
        $("#studentGuardian1Form").ajaxForm({
            beforeSubmit : function(){

            },
            success : function(data){
                var response = JSON.parse(data);
                if(!response["error"].length){

                    $("#studentGuardian1Form").trigger("reset");
                }
            }
        });
        $("#studentGuardian2Form").ajaxForm({
            beforeSubmit : function(){

            },
            success : function(data){
                var response = JSON.parse(data);
                if(!response["error"].length){
                    $("#studentGuardian12Form").trigger("reset");
                }
            }
        });
        $("#createStudent").click(function(){
            $("#studentInfo").attr("action", systemApplication.url.apiUrl+"c_account/createAccount");
            $("#previousSchoolHistoryForm").attr("action", systemApplication.url.apiUrl+"c_account_school_history/createAccountSchoolHistory");
            $("#studentGuardian1Form").attr("action", systemApplication.url.apiUrl+"c_account/createAccountGuardian");
            $("#studentGuardian2Form").attr("action", systemApplication.url.apiUrl+"c_account/createAccountGuardian");

            $("#createStudent").hide();
            $("#submitInfo").show();
            $("#closeInfo").show();

            $("#previousSchoolHistoryForm").trigger("reset");
            $("#studentInfo").trigger("reset");
            $("#studentGuardian1Form").trigger("reset");
            $("#studentGuardian2Form").trigger("reset");

            $("#studentInformationDiv").slideDown();
            $('select, input').attr('disabled',false);
        });
        $("#closeInfo").click(function(){
            $("#closeInfo").hide();
            $("#submitInfo").hide();
            $("#createStudent").show();

            $("#edit").hide();
            $("#studentInformationDiv").slideUp();
        });
        $("#edit").click(function(){
            $("#closeInfo").show();
            $("#submitInfo").show();
            $("#edit").hide();
            $("#submitInfo").html("Save");
        });
        studentAccountManagement.refreshPreviousSchoolHistoryMaintainables();
        $("#studentListFilterSearch").click(function(){
            studentAccountManagement.retrieveStudentList();
        });
        $(".studentListSorter").click(function(){ 
            var filter = {
                sort : {}
            };
            $(".studentListSorter").attr("sorted",0);
            $(this).attr("sorted",1);
            // 1 - asc, 0 - desc
            if($(this).attr("sort")*1){
                $(this).attr("sort", 0 );
                $(this).find(".sortDown").show();
                $(this).find(".sortUp").hide();
            }else{
                $(this).attr("sort", 1);
                $(this).find(".sortDown").hide();
                $(this).find(".sortUp").show();
            }
            studentAccountManagement.retrieveStudentList(filter);
            
        });
    });
    $(document).ready(function () {
        $('input[name="birth_date"]').on('change',function(){
            var d = $(this).val();
            d = new Date(d);
            $('input[name="birth_datetime"]').val(Date.parse(d) / 1000);
        });

        $("#submitInfo").click(function(e){
            $.post($('form#studentInfo').attr('action'), $('form#studentInfo').serialize(), function (data) {

                var response = JSON.parse(data);
                $('.help-inline').removeClass('hide');
                if(!response.data){
                    systemUtility.showErrorMessage("#studentAccountManagementPeronalInformationMessage", response["error"]);
                    $('.help-inline').html(response.error[0].message);
                }else{
                    $(".previousSchoolHistoryAccountID").val(response.data);
                    $("#previousSchoolHistoryForm").submit();
                    $("#studentGuardian1Form").submit();
                    $("#studentGuardian2Form").submit();
                    $('.help-inline').html("Succesfully save.");
                    $("#closeInfo").trigger("click");
                    studentAccountManagement.retrieveStudentList();
                }
            });
            return false;
        });

        $("#studentListBody").on('click',".viewStudentListEntry",function(){
            var acc_id = $(this).closest('tr').attr('account_id');
            var url = ($("form#studentInfo").attr('action')).replace('createAccount','retrieveAccountBasicInformation').replace('updateAccount','retrieveAccountBasicInformation');
            $('button[type="submit"]').addClass('hide');
            $('button#edit').removeClass('hide');
            $("#studentInformationDiv").find('select, input').attr('disabled',true);
            $("#studentInfo").attr("action", systemApplication.url.apiUrl+"c_account/updateAccount");
            $.post(url,{account_ID:acc_id},function(data){
                var response = JSON.parse(data);
                if(response.data)
                {
                    for( info in response.data){
                        if(info == 'gender' || info == 'religion_maintainable_ID' || info == 'nationality_maintainable_ID'){
                            $('select[name="'+info+'"]').val(response.data[info]);
                        }else if(info == 'birth_datetime')
                        {
                            var d = new Date(response.data[info]*1000);
                            $('#bdatetime').val( d.getFullYear() +'-'+ ("0"+(d.getMonth()+1)).slice(-2) +'-'+ ("0"+(d.getDate())).slice(-2));
                        }
                        else
                            $('input[name="'+info+'"]').val(response.data[info]);
                    }
                    studentAccountManagement.viewPreviousSchoolHistory(acc_id);
                    studentAccountManagement.viewAccountGuardian(acc_id);
                    $("#studentInformationDiv").slideDown();
                    $("#createStudent").slideUp();
                    $("#closeInfo").show();
                    $("#edit").show();
                    $("#submitInfo").hide();
                }
            });
        });
        $('button#edit').click(function(){
            var url = ($("form#studentInfo").attr('action')).replace('createAccount','updateAccount');
            $("form#studentInfo").attr('action',url);
            $('button[type="submit"]').removeClass('hide').html('Save');
            $('button#edit').addClass('hide');
            $('select, input').attr('disabled',false);
        });

    });
    studentAccountManagement.addStudentYearLevel = function(ID, course_ID, yearLevel, academicYear, grade){
        
    };
    studentAccountManagement.removeStudentList = function(row){
        $.post("<?=  api_url()?>c_account/deleteAccount",{ID : row.attr("account_id")},function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                row.remove();
                studentAccountManagement.retrieveStudentList();
            }
        });
    };
    studentAccountManagement.retrieveStudentList = function(filter){
        $("#studentListFilterSearch").button("loading");
        filter = (typeof filter === "undefined") ? {} : filter;
        filter.retrieve_type = 1;
        ($("#studentListFilterIdentificationNumber").val() !== "") ? filter.identification_number = $("#studentListFilterIdentificationNumber").val() : null;
        ($("#studentListFilterLastName").val() !== "") ? filter.last_name = $("#studentListFilterLastName").val() : null;
        ($("#studentListFilterFirstName").val() !== "") ? filter.first_name = $("#studentListFilterFirstName").val() : null;
        ($("#studentListFilterMiddleName").val() !== "") ? filter.middle_name = $("#studentListFilterMiddleName").val() : null;
        filter.offset = ((($("#currentStudenListPage").text()*1 - 1) > 0) ? ($("#currentStudenListPage").text()*1 - 1) : 0) * 20;
        filter.limit = 20;
        filter.account_type_ID = 4;
        
        //sorting
        if($(".studentListSorter[sorted=1]").length){
            filter.sort = {};
            if($(".studentListSorter[sorted=1]").attr("sorter_name") === "name"){
                filter.sort.last_name = $(".studentListSorter[sorted=1]").attr("sort")*1;
                filter.sort.first_name = $(".studentListSorter[sorted=1]").attr("sort")*1;
                filter.sort.middle_name = $(".studentListSorter[sorted=1]").attr("sort")*1;
            }else{
                filter.sort.identification_number = $(".studentListSorter[sorted=1]").attr("sort")*1;
            }
        }
        $.post("<?=  api_url()?>c_account/retrieveAccountBasicInformation",filter,function(data){
        
          var response = JSON.parse(data);
          $("#studentListBody").empty();
          var totalPages = Math.ceil(response["result_count"]/20);
          $("#totalStudentListPage").text(totalPages);
          if(!response["error"].length){
              
              if($("#currentStudenListPage").text()*1 <= totalPages){
                  
                  for(var x = 0; x < response["data"].length; x++){
                      var newRow = $(".prototype").find(".studentListRow").clone();
                      newRow.attr("account_id", response["data"][x]["account_ID"]);
                      newRow.find(".identification_number").text(response["data"][x]["identification_number"]);
                      newRow.find(".account_full_name").text((
                              response["data"][x]["last_name"]
                              + ", " +
                              response["data"][x]["first_name"]
                              +" "+
                              response["data"][x]["middle_name"]
                              ).toUpperCase());
                      newRow.find(".confirmButton").hide();
                      $("#studentListBody").append(newRow);
                  }
              }else{
                  $("#totalStudentListPage").text(0);
                  $("#currentStudenListPage").text(0);
              }
          }
          $("#studentListFilterSearch").button("reset");
          $("#currentStudenListPage").text( ($("#currentStudenListPage").text()*1 > $("#totalStudentListPage").text()*1) ?  $("#totalStudentListPage").text()*1 : $("#currentStudenListPage").text()*1);
       });
    };
    studentAccountManagement.refreshPreviousSchoolHistoryMaintainables = function(){
        //previous school
        $.post("<?=  api_url()?>c_maintainable/retrieveMaintainable",{maintainable_type_ID : 3},function(data){
            var response = JSON.parse(data);
            $("#previousSchoolCampusHistory").empty();
            $("#previousSchoolCampusHistory").append("<option value='0' >N/A</option>")
            if(!response["error"].length){
                
                for(var x = 0; x < response["data"].length; x++){
                    $("#previousSchoolCampusHistory").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>")
                }
            }
        });
        $.post("<?=  api_url()?>c_course/retrieveCourse",{},function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                $("#previousSchoolHistoryCourse").empty();
                $("#previousSchoolHistoryCourse").append("<option>N/A</option>");
                for(var x = 0; x < response["data"].length; x++){
                    $("#previousSchoolHistoryCourse").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>")
                }
            }
        });
        //Nationality
        $.post("<?=  api_url()?>c_maintainable/retrieveMaintainable",{maintainable_type_ID : 2},function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                $("select[name='nationality_maintainable_ID']").empty();
                $("select[name='nationality_maintainable_ID']").append("<option>N/A</option>");

                for(var x = 0; x < response["data"].length; x++){
                    $("select[name='nationality_maintainable_ID']").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>")
                }
            }
        });
        //religion
        $.post("<?=  api_url()?>c_maintainable/retrieveMaintainable",{maintainable_type_ID : 1},function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                $("select[name='religion_maintainable_ID']").empty();
                $("select[name='religion_maintainable_ID']").append("<option>N/A</option>");
                for(var x = 0; x < response["data"].length; x++){
                    $("select[name='religion_maintainable_ID']").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
                }
            }
        });
        //relationship
        $.post("<?=  api_url()?>c_maintainable/retrieveMaintainable",{maintainable_type_ID : 5},function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                $(".relationshipMaintainable").empty();
                $(".relationshipMaintainable").append("<option>N/A</option>");
                for(var x = 0; x < response["data"].length; x++){
                    $(".relationshipMaintainable").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
                }
            }
        });
    };
    studentAccountManagement.newPreviousSchoolHistory = function(){
        $("#previousSchoolHistoryForm").trigger("reset");
        $("#previousSchoolHistoryForm").attr("action", systemApplication.url.apiUrl+"c_account_school_history/createAccountSchoolHistory");
    };
    studentAccountManagement.viewPreviousSchoolHistory = function(acountID){
        $.post("<?=  api_url()?>c_account_school_history/retrieveAccountSchoolHistory",{account_ID : acountID},function(data){
            var response = JSON.parse(data);
            $("#previousSchoolHistoryForm").trigger("reset");
            $("#previousSchoolHistoryAccountID").val(acountID);
            $("#previousSchoolHistoryID").val(0);
            if(!response["error"].length){
                $("#previousSchoolHistoryForm").attr("action", systemApplication.url.apiUrl+"c_account_school_history/updateAccountSchoolHistory");
                $("#previousSchoolHistoryID").val(response["data"][0]["ID"]);
                $("#previousSchoolHistoryCourse").val(response["data"][0]["course_ID"]);
                $("#previousSchoolHistoryYearLevel").val(response["data"][0]["year_level"]);
                $("#previousSchoolCampusHistory").val(response["data"][0]["school_campus_maintainable_ID"]);
                $("#previousSchoolHistoryCourse").val(response["data"][0]["course_ID"]);
                $("#previousSchoolHistoryDatetime").val(response["data"][0]["datetime"]);
                var date = new Date(response["data"][0]["datetime"]*1000);
                $("#previousSchoolHistorySchoolYear").val(1900+date.getYear());
                $("#previousSchoolHistorySection").val(response["data"][0]["section"]);
            }else{
                $("#previousSchoolHistoryForm").attr("action", systemApplication.url.apiUrl+"c_account_school_history/createAccountSchoolHistory");
            }
        });
    };
    studentAccountManagement.viewAccountGuardian = function(acountID){
        $.post(systemApplication.url.apiUrl+"c_account/retrieveAccountGuardian",{account_ID : acountID},function(data){
            var response = JSON.parse(data);
            $("#studentGuardian1Form").trigger("reset");
            $("#studentGuardian2Form").trigger("reset");
            $("#studentGuardian1Form").attr("action", systemApplication.url.apiUrl+"c_account/createAccountGuardian");
            $("#studentGuardian2Form").attr("action", systemApplication.url.apiUrl+"c_account/createAccountGuardian");
            if(!response["error"].length){
                console.log(response["data"]);
                for(var x = 0; x < response["data"].length; x++){

                    $("#studentGuardian"+(x+1)).val(acountID);
                    $("#studentGuardian"+(x+1)+"Form").attr("action", systemApplication.url.apiUrl+"c_account/updateAccountGuardian");
                    $("#studentGuardianID"+(x+1)).val(response["data"][x]["ID"]);
                    $("#studentGuardianAccountID"+(x+1)).val(response["data"][x]["account_ID"]);
                    $("#studentGuardianFamilyName"+(x+1)).val(response["data"][x]["last_name"]);
                    $("#studentGuardianGivenName"+(x+1)).val(response["data"][x]["first_name"]);
                    $("#studentGuardianMiddleName"+(x+1)).val(response["data"][x]["middle_name"]);
                    $("#studentGuardianRelationship"+(x+1)).val(response["data"][x]["relationship"]);
					$("#studentGuardianAddress"+(x+1)).val(response["data"][x]["address"]);
                    $("#studentGuardianContactNumber"+(x+1)).val(response["data"][x]["contact_number"]);
                }
            }else{
            }
        });
    }
</script>

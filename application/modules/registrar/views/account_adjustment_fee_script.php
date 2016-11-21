<script>
    /*global systemApplication, systemUtility*/
var accountAdjustmentFee = {};
accountAdjustmentFee.sectionList = [];
accountAdjustmentFee.accountAdjustmentFeeListFilter = {};
$(document).ready(function(){
    accountAdjustmentFee.retrieveList();
    accountAdjustmentFee.refreshOptions();
    //form action buttons
    $("#accountAdjustmentFeeType").change(function(){
        $.post(systemApplication.url.apiUrl+"c_assessment_item/retrieveAssessmentItem", {assessment_type_ID : $(this).val(), academic_year : systemUtility.getCurrentAcademicYear()}, function(data){
         
            var response = JSON.parse(data);
            $("#accountAdjustmentFeeItem").empty();
            $("#accountAdjustmentFeeAmount").text("0.00");
            if(!response["error"].length){
                $("#accountAdjustmentFeeItem").append("<option value='0' >None</option>");
                for(var x = 0; x < response["data"].length; x++){
                    $("#accountAdjustmentFeeItem").append("<option  value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
                }
            }
        });
    });
    $("#accountAdjustmentFeeItem").change(function(){
        $.post(systemApplication.url.apiUrl+"c_course_annual_fee/retrieveCourseAnnualFee", {assessment_item_ID : $(this).val(), type:2, academic_year : systemUtility.getCurrentAcademicYear()}, function(data){
            console.log(data);
            var response = JSON.parse(data);
           $("#accountAdjustmentFeeAdjustment").empty();
          //  $("#accountAdjustmentFeeAdjustment").append("<option value='0' >None</option>");
            $("#accountAdjustmentFeeAmount").text("0.00");
           if(!response["error"].length){
               
               for(var x = 0; x < response["data"].length; x++){
                   $("#accountAdjustmentFeeAdjustment").append("<option amount='"+response["data"][x]["amount"]+"' value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
               }
               $("#accountAdjustmentFeeAdjustment").trigger("change");
           }
        });
    });
    $("#accountAdjustmentFeeAdjustment").change(function(){
        $("#accountAdjustmentFeeAmount").text(($(this).find("option[value="+$("#accountAdjustmentFeeAdjustment").val()+"]").attr("amount")*1).toFixed(2));
        accountAdjustmentFee.retrieveList();
    });
    $("#accountAdjustmentFeeCreateFormButton").click(function(){
        accountAdjustmentFee.changeFormAction(1);
        $("#accountAdjustmentFeeForm").trigger("reset");
        $("#accountAdjustmentFeeForm").attr("action", "<?=api_url()?>c_assessment_item/createAssessmentItem");
        $("#accountAdjustmentFeeDiv").slideDown();
    });
    $("#accountAdjustmentFeeSubmitFormButton").click(function(){
        $("#accountAdjustmentFeeForm").submit();
    });
    $("#accountAdjustmentFeeCloseFormButton").click(function(){
        accountAdjustmentFee.changeFormAction(2);
    });
    $("#accountAdjustmentFeeForm").ajaxForm({
        success : function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                accountAdjustmentFee.retrieveList();
                $("#accountAdjustmentFeeForm").trigger("reset");
                $("#accountAdjustmentFeeDiv").slideUp();
                accountAdjustmentFee.changeFormAction(2);
            }else{
                $("#accountAdjustmentFeeMessage").html(response["error"][0]["message"]);
                $("#accountAdjustmentFeeMessage").show();
                setTimeout(function(){
                    $("#accountAdjustmentFeeMessage").html("");
                    $("#accountAdjustmentFeeMessage").hide();
                },3000 * response["error"].length);
            }
        }
    });
    //section list page nav
    $("#accountAdjustmentFeeListPreviousPage").click(function(){
        if($("#accountAdjustmentFeeListCurrentPage").text()*1 -1){
            $("#accountAdjustmentFeeListCurrentPage").text($("#accountAdjustmentFeeListCurrentPage").text()*1 -1);
            accountAdjustmentFee.retrieveList();
        } 
     });
    $("#accountAdjustmentFeeListNextPage").click(function(){
        $("#accountAdjustmentFeeListCurrentPage").text($("#accountAdjustmentFeeListCurrentPage").text()*1 + 1);
        accountAdjustmentFee.retrieveList();
    });
     //class section list action
    $("#accountAdjustmentFeeListBody").on("click", ".accountAdjustmentFeeListSelectButton", function(){
       $(this).parent().find(".confirmButton").show();
       $(this).parent().find(".actionButton").hide();
       $(this).parent().find(".accountAdjustmentFeeListMessageButton").text("Select ? ");
       $(this).parent().find(".accountAdjustmentFeeListMessageButton").attr("action", 1);
    });
    $("#accountAdjustmentFeeListBody").on("click", ".accountAdjustmentFeeListRemoveButton", function(){
       $(this).parent().find(".confirmButton").show();
       $(this).parent().find(".actionButton").hide();
       $(this).parent().find(".accountAdjustmentFeeListMessageButton").text("Delete ? ");
       $(this).parent().find(".accountAdjustmentFeeListMessageButton").attr("action", 2);
    });
    $("#accountAdjustmentFeeListBody").on("click", ".accountAdjustmentFeeListYesButton", function(){
        var data = {
             course_annual_fee_ID : $(this).parent().parent().attr("course_annual_fee_ID"),
             account_ID : $(this).parent().parent().attr("account_ID"),
             academic_year : systemUtility.getCurrentAcademicYear()
        };
        if($(this).parent().find(".accountAdjustmentFeeListMessageButton").attr("action")*1 === 1 ){
            $.post(systemApplication.url.apiUrl+"c_course_annual_fee/createCourseAnnualFeeSelectedAccount", data, function(data){
               var response = JSON.parse(data);
               if(!response["error"].length){

               }else{
                   alert(response["error"][0]["message"]);
               }
               accountAdjustmentFee.retrieveList();
            });
        }else{
            $.post(systemApplication.url.apiUrl+"c_course_annual_fee/deleteCourseAnnualFeeSelectedAccount", data, function(data){
               var response = JSON.parse(data);
               if(!response["error"].length){

               }else{
                   alert(response["error"][0]["message"]);
               }
               accountAdjustmentFee.retrieveList();
            });
        }
    });

    $("#accountAdjustmentFeeListBody").on("click", ".accountAdjustmentFeeListNoButton", function(){
       $(this).parent().find(".confirmButton").hide();
       if($(this).parent().find(".accountAdjustmentFeeListMessageButton").attr("action")*1 === 1 ){
           $(this).parent().find(".accountAdjustmentFeeListSelectButton").show();
       }else{
           $(this).parent().find(".accountAdjustmentFeeListRemoveButton").show();
       }
    });
    //list filter and sorting
    $(".accountAdjustmentFeeListSorter").click(function(){ 
        
        $(".accountAdjustmentFeeListSorter").attr("sorted",0);
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
        accountAdjustmentFee.retrieveList();
    });
    $("#accountAdjustmentFeeListFilterSearch").click(function(){
      
        accountAdjustmentFee.accountAdjustmentFeeListFilter = {};
        ($("#accountAdjustmentFeeIdentificationNumber").val() !== "") ? accountAdjustmentFee.accountAdjustmentFeeListFilter.identification_number = $("#accountAdjustmentFeeIdentificationNumber").val() : null;
        ($("#accountAdjustmentFeeLastName").val() !== "") ? accountAdjustmentFee.accountAdjustmentFeeListFilter.last_name = $("#accountAdjustmentFeeLastName").val() : null;
        ($("#accountAdjustmentFeeFirstName").val() !== "") ? accountAdjustmentFee.accountAdjustmentFeeListFilter.first_name = $("#accountAdjustmentFeeFirstName").val() : null;
        ($("#accountAdjustmentFeeMiddleName").val() !== "") ? accountAdjustmentFee.accountAdjustmentFeeListFilter.middle_name = $("#accountAdjustmentFeeMiddleName").val() : null;
        accountAdjustmentFee.retrieveList({retrieve_type : 1});
    });
});
accountAdjustmentFee.removeList = function(row){
    $.post("<?=  api_url()?>c_assessment_item/deleteAssessmentItem",{ID : row.attr("assment_type_id")},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            row.remove();
            accountAdjustmentFee.retrieveList();
        }
    });
}
accountAdjustmentFee.refreshOptions = function(filter){
  $.post("<?=api_url()?>c_assessment_type/retrieveAssessmentType", filter, function(data){
      
      var response = JSON.parse(data);
      if(!response["error"].length){
          var currentValue = $("#accountAdjustmentFeeType").val();
          $("#accountAdjustmentFeeType").empty();
          $("#accountAdjustmentFeeType").append("<option value='' >None</option>");
          for(var x = 0; x <response["data"].length; x++){
              $("#accountAdjustmentFeeType").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
          }
          (currentValue*1) ? $("#accountAdjustmentFeeType").val(currentValue) : null;
      }
  }); 
};
accountAdjustmentFee.retrieveList = function(filter){
    $("#accountAdjustmentFeeListFilterSearch").button("loading");
    filter = (typeof filter === "undefined") ? {} : filter;
    for(var x in accountAdjustmentFee.accountAdjustmentFeeListFilter){
        filter[x] = accountAdjustmentFee.accountAdjustmentFeeListFilter[x];
    }
    filter.limit = 20;
    filter.retrieve_type= 1;
    filter.offset = ((($("#accountAdjustmentFeeListCurrentPage").text()*1 - 1) > 0) ? ($("#accountAdjustmentFeeListCurrentPage").text()*1 - 1) : 0) * 20;
    if($(".accountAdjustmentFeeListSorter[sorted=1]").length){
        filter.sort = {};
        if($(".accountAdjustmentFeeListSorter[sorted=1]").attr("sorter_name") === "student_name"){
            filter.sort.account_basic_information__last_name = $(".accountAdjustmentFeeListSorter[sorted=1]").attr("sort")*1;
            filter.sort.account_basic_information__first_name = $(".accountAdjustmentFeeListSorter[sorted=1]").attr("sort")*1;
            filter.sort.account_basic_information__middle_name = $(".accountAdjustmentFeeListSorter[sorted=1]").attr("sort")*1;
        }else{
            filter.sort[$(".accountAdjustmentFeeListSorter[sorted=1]").attr("sorter_name")] = $(".accountAdjustmentFeeListSorter[sorted=1]").attr("sort")*1;
        }
    }
    filter.course_annual_fee_ID = $("#accountAdjustmentFeeAdjustment").val();
    
    $.post("<?=api_url()?>c_course_annual_fee/retrieveAccountAdjusment", filter, function(data){
        var response = JSON.parse(data);
        $("#accountAdjustmentFeeListTotalResult").text("0");
        $("#accountAdjustmentFeeListTotalPage").text(1);
        var totalPages = Math.ceil(response["result_count"]/20);
        $("#accountAdjustmentFeeListBody").empty();
        $("#accountAdjustmentFeeListTotalResult").text(response["result_count"]);
        $("#accountAdjustmentFeeListTotalPage").text(totalPages);
        if(!response["error"].length){
            if($("#accountAdjustmentFeeListCurrentPage").text()*1 <= totalPages){
                for(var x = 0; x < response["data"].length; x++){
                    var newRow = $(".prototype").find(".accountAdjustmentFeeListRow").clone();
                    newRow.attr("account_ID", response["data"][x]["account_ID"]);
                    newRow.attr("course_annual_fee_id", $("#accountAdjustmentFeeAdjustment").val()*1);
                    newRow.find(".accountAdjustmentFeeListIdentificationNumber").text(response["data"][x]["identification_number"]);
                    newRow.find(".accountAdjustmentFeeListAccountName").text((
                            response["data"][x]["last_name"] +", "+
                            response["data"][x]["first_name"] +" "+
                            response["data"][x]["middle_name"]
                            ).toUpperCase());
                    if(response["data"][x]["course_annual_fee_ID"]*1){
                        newRow.find(".accountAdjustmentFeeListRemoveButton").show();
                    }else{
                        newRow.find(".accountAdjustmentFeeListSelectButton").show();
                    }
                    newRow.find(".confirmButton").hide();
                    
                    
                    $("#accountAdjustmentFeeListBody").append(newRow);
                }
            }
        }
        $("#accountAdjustmentFeeListFilterSearch").button("reset");
        $("#accountAdjustmentFeeListCurrentPage").text( ($("#accountAdjustmentFeeListCurrentPage").text()*1 > $("#accountAdjustmentFeeListTotalPage").text()*1) ?  $("#accountAdjustmentFeeListTotalPage").text()*1 : $("#accountAdjustmentFeeListCurrentPage").text()*1);
    });
};
</script>
<script>
var generalLedger = {};
generalLedger.sectionList = [];
generalLedger.assessentTypeListFilter = {};
$(document).ready(function(){
    generalLedger.retrieveList();
    //form action buttons
    $("#generalLedgerCreateFormButton").click(function(){
        generalLedger.changeFormAction(1);
        $("#generalLedgerForm").trigger("reset");
        $("#generalLedgerForm").attr("action", "<?=api_url()?>c_general_ledger/createGeneralLedger");
        $("#generalLedgerDiv").slideDown();
    });
    $("#generalLedgerSubmitFormButton").click(function(){ 
        $("#generalLedgerForm").submit();
    });
    $("#generalLedgerCloseFormButton").click(function(){
        generalLedger.changeFormAction(2);
    });
    $("#generalLedgerForm").ajaxForm({
        success : function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                generalLedger.retrieveList();
                $("#generalLedgerForm").trigger("reset");
                $("#generalLedgerDiv").slideUp();
                generalLedger.changeFormAction(2);
            }else{
                $("#generalLedgerMessage").html(response["error"][0]["message"]);
                $("#generalLedgerMessage").show();
                setTimeout(function(){
                    $("#generalLedgerMessage").html("");
                    $("#generalLedgerMessage").hide();
                },3000 * response["error"].length);
            }
        }
    });
    //section list page nav
    $("#generalLedgerListPreviousPage").click(function(){
        if($("#generalLedgerListCurrentPage").text()*1 -1){
            $("#generalLedgerListCurrentPage").text($("#generalLedgerListCurrentPage").text()*1 -1);
            generalLedger.retrieveList();
        } 
     });
    $("#generalLedgerListNextPage").click(function(){
        $("#generalLedgerListCurrentPage").text($("#generalLedgerListCurrentPage").text()*1 + 1);
        generalLedger.retrieveList();
    });
     //class section list action
    $("#generalLedgerListBody").on("click", ".generalLedgerListRemoveButton", function(){
       $(this).parent().find(".confirmButton").show();
       $(this).parent().find(".actionButton").hide();
    });
    $("#generalLedgerListBody").on("click", ".generalLedgerListNoRemoveButton", function(){
       $(this).parent().find(".confirmButton").hide();
       $(this).parent().find(".actionButton").show();
       $(this).parent().find(".confirmChangeButton").hide();
    });
    $("#generalLedgerListBody").on("click", ".generalLedgerListYesRemoveButton", function(){
       generalLedger.removeList($(this).parent().parent());
    });

    $("#generalLedgerListBody").on("click", ".generalLedgerListYesChangeButton", function(){
       generalLedger.saveList($(this).parent().parent());
    });
    //list filter and sorting
    $(".generalLedgerListSorter").click(function(){ 
        $(".generalLedgerListSorter").attr("sorted",0);
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
        
        generalLedger.retrieveList();
    });
    $("#generalLedgerListFilterSearch").click(function(){
        $("#generalLedgerListBody").empty();
        generalLedger.assessentTypeListFilter = {};
        ($("#generalLedgerListFilterDescription").val() !== "") ? generalLedger.assessentTypeListFilter.description = $("#generalLedgerListFilterDescription").val() : null;
        ($("#generalLedgerListFilterCode").val() !== "") ? generalLedger.assessentTypeListFilter.code = $("#generalLedgerListFilterCode").val() : null;
        generalLedger.retrieveList({retrieve_type : 1});
    });
    //entry
    $("#generalLedgerListBody").on("click", ".generalLedgerListViewButton", function(){
        generalLedger.viewTuitionFee($(this).parent().parent().attr("assment_type_id"));
    });
});
generalLedger.viewTuitionFee = function(courseAnnualFeeID){
    $("#generalLedgerForm").trigger("reset");
    $.post("<?=  api_url()?>c_general_ledger/retrieveGeneralLedger",{ID : courseAnnualFeeID},function(data){
        var response = JSON.parse(data);
        console.log(response);
        if(!response["error"].length){
            $("#generalLedgerForm").trigger("reset");
            $("#generalLedgerID").val(response["data"]["ID"]*1);
            $("#generalLedgerDescription").val(response["data"]["description"]);
            $("#generalLedgerCode").val(response["data"]["code"]);
            generalLedger.changeFormAction(1);
            $("#generalLedgerForm").attr("action", "<?=api_url()?>c_general_ledger/updateGeneralLedger");
            $("#generalLedgerDiv").slideDown();
        }
    });
}
generalLedger.removeList = function(row){
    $.post("<?=  api_url()?>c_general_ledger/deleteGeneralLedger",{ID : row.attr("assment_type_id")},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            row.remove();
            generalLedger.retrieveList();
        }
    });
}
generalLedger.changeFormAction = function(status){
    switch(status*1){
        case 1://Create Button
            $("#generalLedgerCloseFormButton").show();
            $("#generalLedgerSubmitFormButton").show();
            $("#generalLedgerCreateFormButton").hide();
            break;
        case 2://Close Button
            $("#generalLedgerDiv").slideUp();
            $("#generalLedgerCloseFormButton").hide();
            $("#generalLedgerSubmitFormButton").hide();
            $("#generalLedgerCreateFormButton").show();
            break;
    }
};
generalLedger.retrieveList = function(filter){
    $("#generalLedgerListFilterSearch").button("loading");
    filter = (typeof filter === "undefined") ? {} : filter;
    for(x in generalLedger.assessentTypeListFilter){
        filter[x] = generalLedger.assessentTypeListFilter[x];
    }
    filter.limit = 20;
    filter.retrieve_type = 1;
    filter.offset = ((($("#generalLedgerListCurrentPage").text()*1 - 1) > 0) ? ($("#generalLedgerListCurrentPage").text()*1 - 1) : 0) * 20;
    if($(".generalLedgerListSorter[sorted=1]").length){
        filter.sort= {};
        if($(this).attr("sorter_name") === "course_year_level"){
            filter.sort.year_level = $(".generalLedgerListSorter[sorted=1]").attr("sort")*1;
            filter.sort.course_ID = $(".generalLedgerListSorter[sorted=1]").attr("sort")*1;
        }else if($(".generalLedgerListSorter[sorted=1]").attr("sorter_name") === "description"){
            filter.sort["description"] = $(".generalLedgerListSorter[sorted=1]").attr("sort")*1;
        }else{
            filter.sort[$(".generalLedgerListSorter[sorted=1]").attr("sorter_name")] = $(".generalLedgerListSorter[sorted=1]").attr("sort")*1;
        }
    }
    $.post("<?=api_url()?>c_general_ledger/retrieveGeneralLedger", filter, function(data){
        var response = JSON.parse(data);
        console.log(response);
        
        $("#generalLedgerListTotalResult").text("0");
        if(!response["error"].length){
            var totalPages = Math.ceil(response["result_count"]/20);
            $("#generalLedgerListBody").empty();
            $("#generalLedgerListTotalResult").text(response["result_count"]);
            $("#generalLedgerListTotalPage").text(totalPages);
            
            if($("#generalLedgerListCurrentPage").text()*1 <= totalPages){
                for(var x = 0; x < response["data"].length; x++){
                    var newRow = $(".prototype").find(".generalLedgerListRow").clone();
                    newRow.attr("assment_type_id", response["data"][x]["ID"]);
                    newRow.find(".generalLedgerListDescription").text(response["data"][x]["description"]);
                    newRow.find(".generalLedgerListCode").text(response["data"][x]["code"]);
                    newRow.find(".confirmButton").hide();
                    
                    
                    $("#generalLedgerListBody").append(newRow);
                }
            }
        }
        $("#generalLedgerListFilterSearch").button("reset");
        $("#generalLedgerListCurrentPage").text( ($("#generalLedgerListCurrentPage").text()*1 > $("#generalLedgerListTotalPage").text()*1) ?  $("#generalLedgerListTotalPage").text()*1 : $("#generalLedgerListCurrentPage").text()*1);
    });
};
</script>
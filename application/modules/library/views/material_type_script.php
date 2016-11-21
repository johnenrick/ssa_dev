<script>
var materialAccessControl = {};
materialAccessControl.sectionList = [];
materialAccessControl.assessentTypeListFilter = {};
$(document).ready(function(){
    materialAccessControl.retrieveList();
    //form action buttons
    $("#materialAccessControlCreateFormButton").click(function(){
        materialAccessControl.changeFormAction(1);
        $("#materialAccessControlForm").trigger("reset");
        $("#materialAccessControlForm").attr("action", "<?=api_url()?>c_material_type/createMaterialAccessControl");
        $("#materialAccessControlDiv").slideDown();
    });
    $("#materialAccessControlSubmitFormButton").click(function(){
        $("#materialAccessControlForm").submit();
    });
    $("#materialAccessControlCloseFormButton").click(function(){
        materialAccessControl.changeFormAction(2);
    });
    $("#materialAccessControlForm").ajaxForm({
        success : function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                materialAccessControl.retrieveList();
                $("#materialAccessControlForm").trigger("reset");
                $("#materialAccessControlDiv").slideUp();
                materialAccessControl.changeFormAction(2);
            }else{
                $("#materialAccessControlMessage").html(response["error"][0]["message"]);
                $("#materialAccessControlMessage").show();
                setTimeout(function(){
                    $("#materialAccessControlMessage").html("");
                    $("#materialAccessControlMessage").hide();
                },3000 * response["error"].length);
            }
        }
    });
    //section list page nav
    $("#materialAccessControlListPreviousPage").click(function(){
        if($("#materialAccessControlListCurrentPage").text()*1 -1){
            $("#materialAccessControlListCurrentPage").text($("#materialAccessControlListCurrentPage").text()*1 -1);
            materialAccessControl.retrieveList();
        }
     });
    $("#materialAccessControlListNextPage").click(function(){
        $("#materialAccessControlListCurrentPage").text($("#materialAccessControlListCurrentPage").text()*1 + 1);
        materialAccessControl.retrieveList();
    });
     //class section list action
    $("#materialAccessControlListBody").on("click", ".materialAccessControlListRemoveButton", function(){
       $(this).parent().find(".confirmButton").show();
       $(this).parent().find(".actionButton").hide();
    });
    $("#materialAccessControlListBody").on("click", ".materialAccessControlListNoRemoveButton", function(){
       $(this).parent().find(".confirmButton").hide();
       $(this).parent().find(".actionButton").show();
       $(this).parent().find(".confirmChangeButton").hide();
    });
    $("#materialAccessControlListBody").on("click", ".materialAccessControlListYesRemoveButton", function(){
       materialAccessControl.removeList($(this).parent().parent());
    });

    $("#materialAccessControlListBody").on("click", ".materialAccessControlListYesChangeButton", function(){
       materialAccessControl.saveList($(this).parent().parent());
    });
    //list filter and sorting
    $(".materialAccessControlListSorter").click(function(){
        $(".materialAccessControlListSorter").attr("sorted",0);
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

        materialAccessControl.retrieveList();
    });
    $("#materialAccessControlListFilterSearch").click(function(){
        $("#materialAccessControlListBody").empty();
        materialAccessControl.assessentTypeListFilter = {};
        ($("#materialAccessControlListFilterAccessionNumber").val() !== "") ? materialAccessControl.assessentTypeListFilter.accessionNumber = $("#materialAccessControlListFilterAccessionNumber").val() : null;
        ($("#materialAccessControlListFilterCallNumber").val() !== "") ? materialAccessControl.assessentTypeListFilter.callNumber = $("#materialAccessControlListFilterCallNumber").val() : null;
        ($("#materialAccessControlListFilterTitle").val() !== "") ? materialAccessControl.assessentTypeListFilter.title = $("#materialAccessControlListFilterTitle").val() : null;
        ($("#materialAccessControlListFilterAuthor").val() !== "") ? materialAccessControl.assessentTypeListFilter.author = $("#materialAccessControlListFilterAuthor").val() : null;
        materialAccessControl.retrieveList({retrieve_type : 1});
    });
    //entry
    $("#materialAccessControlListBody").on("click", ".materialAccessControlListViewButton", function(){
        materialAccessControl.viewTuitionFee($(this).parent().parent().attr("assment_type_id"));
    });
});
materialAccessControl.viewTuitionFee = function(courseAnnualFeeID){
    $("#materialAccessControlForm").trigger("reset");
    $.post("<?=  api_url()?>c_material_type/retrieveMaterialAccessControl",{ID : courseAnnualFeeID, byusers : $(".users").length},function(data){
        var response = JSON.parse(data);
        var dt;
        console.log(response);
        if(!response["error"].length){
            $("#materialAccessControlForm").trigger("reset");
            $("#materialAccessControlID").val(response["data"][0]["ID"]*1);
            $("#materialAccessControlDescription").val(response["data"][0]["description"]);

            for(var x = 0; x < response["data"].length; x++){
                $(".materialAccessControlPeriodNumber").eq(x).val(response["data"][x]["period"]);
                $('.materialAccessControlPeriodUnit option[value="'+response["data"][x]["unit"]+'"]').eq(x).prop('selected', true);
                $(".materialAccessControlFineRate").eq(x).val(response["data"][x]["fine_rate"]);
            }
            materialAccessControl.changeFormAction(1);
            $("#materialAccessControlForm").attr("action", "<?=api_url()?>c_material_type/updateMaterialAccessControl");
            $("#materialAccessControlDiv").slideDown();
        }
    });
}
materialAccessControl.removeList = function(row){
    $.post("<?=  api_url()?>c_material_type/deleteMaterialAccessControl",{ID : row.attr("assment_type_id"), libraryusers : $(".users").length},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            row.remove();
            materialAccessControl.retrieveList();
        }
    });
}
materialAccessControl.changeFormAction = function(status){
    switch(status*1){
        case 1://Create Button
            $("#materialAccessControlCloseFormButton").show();
            $("#materialAccessControlSubmitFormButton").show();
            $("#materialAccessControlCreateFormButton").hide();
            break;
        case 2://Close Button
            $("#materialAccessControlDiv").slideUp();
            $("#materialAccessControlCloseFormButton").hide();
            $("#materialAccessControlSubmitFormButton").hide();
            $("#materialAccessControlCreateFormButton").show();
            break;
    }
};
materialAccessControl.retrieveList = function(filter){
    $("#materialAccessControlListFilterSearch").button("loading");
    filter = (typeof filter === "undefined") ? {} : filter;
    for(x in materialAccessControl.assessentTypeListFilter){
        filter[x] = materialAccessControl.assessentTypeListFilter[x];
    }
    console.log(filter);
    filter.limit = 20;
    filter.retrieve_type = 1;
    filter.offset = ((($("#materialAccessControlListCurrentPage").text()*1 - 1) > 0) ? ($("#materialAccessControlListCurrentPage").text()*1 - 1) : 0) * 20;
    if($(".materialAccessControlListSorter[sorted=1]").length){
        filter.sort= {};
        if($(this).attr("sorter_name") === "course_year_level"){
            filter.sort.year_level = $(".materialAccessControlListSorter[sorted=1]").attr("sort")*1;
            filter.sort.course_ID = $(".materialAccessControlListSorter[sorted=1]").attr("sort")*1;
        }else if($(".materialAccessControlListSorter[sorted=1]").attr("sorter_name") === "description"){
            filter.sort["description"] = $(".materialAccessControlListSorter[sorted=1]").attr("sort")*1;
        }else{
            filter.sort[$(".materialAccessControlListSorter[sorted=1]").attr("sorter_name")] = $(".materialAccessControlListSorter[sorted=1]").attr("sort")*1;
        }
    }
    $.post("<?=api_url()?>c_material_type/retrieveMaterialAccessControl", filter, function(data){
        var response = JSON.parse(data);
        console.log(response);

        $("#materialAccessControlListTotalResult").text("0");
        if(!response["error"].length){
            var totalPages = Math.ceil(response["result_count"]/20);
            $("#materialAccessControlListBody").empty();
            $("#materialAccessControlListTotalResult").text(response["result_count"]);
            $("#materialAccessControlListTotalPage").text(totalPages);
            if($("#materialAccessControlListCurrentPage").text()*1 <= totalPages){
                for(var x = 0; x < response["data"].length; x++){
                    var newRow = $(".prototype").find(".materialAccessControlListRow").clone();
                    newRow.attr("assment_type_id", response["data"][x]["ID"]);
                    newRow.find(".materialAccessControlListNumber").text(x+1);
                    newRow.find(".materialAccessControlListDescription").text(response["data"][x]["description"]);
                    //newRow.find(".materialAccessControlListPeriod").text(response["data"][x]["period"]+" "+((response["data"][x]["unit"]==0)?"Hour/s":"Day/s"));
                    newRow.find(".confirmButton").hide();


                    $("#materialAccessControlListBody").append(newRow);
                }
            }
        }
        $("#materialAccessControlListFilterSearch").button("reset");
        $("#materialAccessControlListCurrentPage").text( ($("#materialAccessControlListCurrentPage").text()*1 > $("#materialAccessControlListTotalPage").text()*1) ?  $("#materialAccessControlListTotalPage").text()*1 : $("#materialAccessControlListCurrentPage").text()*1);
    });


};


//Library users
$.post("<?=  api_url()?>c_material_type/retrieveLibraryUser",{},function(data){
    var response = JSON.parse(data);
    var newclone;
    if(!response["error"].length){
        var clone = $(".users").clone();
        $(".users").remove();

        for(var x = 0; x < response["data"].length; x++){
            newclone = clone.clone();
            newclone.find("legend").html(response["data"][x]["description"]);
            newclone.find("input.libraryuserID").val(response["data"][x]["ID"]);
            $(".users-container").append(newclone);
        }
    }
});
</script>

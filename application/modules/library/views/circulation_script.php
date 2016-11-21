<script>
var material = {};
var newBook;
material.sectionList = [];
material.assessentTypeListFilter = {};
var img_url = "<?=  base_url()?>assets/img/PHOTOS/";
var img_def = "<?=  base_url()?>assets/img/default.JPG";
$(document).ready(function(){
    $("#img").error(function(){
        $("#img").attr('src',img_def);
    });
    material.retrieveList();
        newBook = $("#loanBookList").find(".loanedBook").clone().eq(0);
    //form action buttons
    $("#materialCreateFormButton").click(function(){
        material.changeFormAction(1);
        var rate = 0;
        $("#materialBorrowerID").removeAttr('disabled');
        $("#materialListLibraryUsers").removeAttr('disabled');
        $("thead tr").eq(0).show();
        $('#materialListLoanDateTimeBorrowed').removeAttr('disabled');
        $("#materialForm").trigger("reset");
        $("#materialLoanBody *").remove();
        $(".materialLoanFine span").text(rate.toFixed(2));
        $("#materialLoanTotalFine span").text(rate.toFixed(2));
        var d = new Date();
        var format = d.getFullYear() +'-'+ ("0"+(d.getMonth()+1)).slice(-2) +'-'+ ("0"+(d.getDate())).slice(-2)
                    +'T'+ (d.toTimeString().substr(0,5)) + ":00.0";
        $("#materialListLoanDateTimeBorrowed").val(format);
        $("#materialForm").attr("action", "<?=api_url()?>c_circulation/createCirculation");
        $("#materialDiv").slideDown();
    });
    $("#materialSubmitFormButton").click(function(){
        $("#materialForm").submit();
    });
    $("#materialCloseFormButton").click(function(){
        material.changeFormAction(2);
    });
    $("#materialForm").ajaxForm({
        success : function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                material.retrieveList();
                $("#materialForm").trigger("reset");
                $("#materialLoanBody *").remove();
                $("#materialListTotalLoan").text("0");
                $("#materialDiv").slideUp();
                material.changeFormAction(2);
            }else{
                $("#materialMessage").html(response["error"][0]["message"]);
                $("#materialMessage").show();
                setTimeout(function(){
                    $("#materialMessage").html("");
                    $("#materialMessage").hide();
                },3000 * response["error"].length);
            }
        }
    });
    $("#materialBorrowerID").change(function(){
        viewImage();
    });
    //section list page nav
    $("#materialListPreviousPage").click(function(){
        if($("#materialListCurrentPage").text()*1 -1){
            $("#materialListCurrentPage").text($("#materialListCurrentPage").text()*1 -1);
            material.retrieveList();
        }
     });
    $("#materialListNextPage").click(function(){
        $("#materialListCurrentPage").text($("#materialListCurrentPage").text()*1 + 1);
        material.retrieveList();
    });
     //class section list action
    $("#materialListBody").on("click", ".materialListRemoveButton", function(){
       $(this).parent().find(".confirmButton").show();
       $(this).parent().find(".actionButton").hide();
    });
    $("#materialListBody").on("click", ".materialListNoRemoveButton", function(){
       $(this).parent().find(".confirmButton").hide();
       $(this).parent().find(".actionButton").show();
       $(this).parent().find(".confirmChangeButton").hide();
    });
    $("#materialListBody").on("click", ".materialListYesRemoveButton", function(){
       material.removeList($(this).parent().parent());
    });

    $("#materialListBody").on("click", ".materialListYesChangeButton", function(){
       material.saveList($(this).parent().parent());
    });

    $("#materialDiv").on("click", ".materialLoanReturnButton", function(){
        var rate = 0;
        var total = $("#materialLoanTotalFine span").text();
        var ths = this;

        $.post("<?=  api_url()?>c_circulation/updateCirculation",
               {
                returndatetime : Math.floor(Date.now()/1000 + 8*3600),
                loanBookID :$(this).parent().children("input.loanBookID").val(),
                bookID :$(this).parent().children("input.bookID").val(),
               },
        function(data){
            var response = JSON.parse(data);
            if(response['data']) {
                $(ths).hide();
                //$(ths).closest('tr').children('td.materialLoanFine').children('span').text(response['data']);
                //rate = response['data']['fine'];
                d1 = new Date( Math.floor((response['data']['data']['loan_datetime'] - 8*3600) *1000));
                d1.setDate(d1.getDate() + parseInt(response['data']['data']['period']) - 1);
                if(calcBusinessDays(new Date( Math.floor((response['data']['data']['loan_datetime'] - 8*3600) *1000)),d1)>=0)
                {
                    do {
                        calc = parseInt(response['data']['data']['period']) - calcBusinessDays(new Date( Math.floor((response['data']['data']['loan_datetime'] - 8*3600) *1000)),d1);
                        d1.setDate(d1.getDate() + calc);
                    }
                    while(calc);
                    d1.setDate(d1.getDate() + 1);
                    calc = calcBusinessDays(d1,new Date( Math.floor((response['data']['return_datetime'] - 8*3600) *1000)));
                }
                else calc = 0;
                rate = parseFloat( ((calc>0)?calc:0) * response['data']['data']['fine_rate']);
                console.log(calc);
                $(ths).closest('tr').children("td.materialLoanFine").children('span').text(rate.toFixed(2));
                total = parseFloat(total);
                total +=parseFloat(rate);
                //alert(total);
                var d = new Date( Math.floor((response['data']['return_datetime'] - 8*3600) *1000));
                $("#materialLoanTotalFine span").text(total.toFixed(2));
                $(ths).closest("td").children(".loanDateReturn").val(response['data']['return_datetime']);
                var format = ("0"+(d.getMonth()+1)).slice(-2) +'/'+ ("0"+(d.getDate())).slice(-2)  +'/'+ d.getFullYear()
                            +' '+ (d.toLocaleTimeString(navigator.language, {hour: '2-digit', minute:'2-digit'}));
                $(ths).parent().append("<span style='font-size:14px'>"+format+"</span>");
            }
        });
    });

    //list filter and sorting
    $(".materialListSorter").click(function(){
        $(".materialListSorter").attr("sorted",0);
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

        material.retrieveList();
    });
    $("#materialListLoanAccessionNumber").keypress(function(e){
        if(e.which == 13){
            $("#materialListLoanBookAdd").click();
            return false;
        }
    });

    $("#materialListFilterBorrowerID").keypress(function(e){
        if(e.which == 13){
            $("#materialListFilterSearch").click();
            return false;
        }
    });
    $("#materialListFilterSearch").click(function(){
        $("#materialListBody").empty();
        material.assessentTypeListFilter = {};
        if($("#materialListFilterBorrowerID").val() !== "")
        {
            switch($("#circulationSearchOptionSelect").val())
            {
                case "Name":
                    material.assessentTypeListFilter.name = $("#materialListFilterBorrowerID").val();
                    break
                case "ID Number":
                    material.assessentTypeListFilter.borrowerID = $("#materialListFilterBorrowerID").val();
            }
        }
        material.retrieveList({retrieve_type : 1});
    });
    //entry
    $("#materialListBody").on("click", ".materialListViewButton", function(){
        material.viewTuitionFee($(this).parent().parent().attr("assment_type_id"),$(this).parent().parent().attr("assment_datetime"));
    });

    $("#circulationSearchOptionSelect").change(function(){
        $("#materialListFilterBorrowerID").attr("placeholder",$(this).val());
    });

    function viewImage(){
        $.post("<?=  api_url()?>c_circulation/retrieveDisplayID",{id:$("#materialBorrowerID").val()},function(data){
                var response = JSON.parse(data);
                console.log("response");
                console.log(response);
                if(!response["error"].length){console.log(img_url + response['data']['identification_number'] + '.JPG');
                    $("#img").attr('src',img_url + response['data']['identification_number'] + '.JPG');
                    $("#name").text(response['data']['first_name']+" "+response['data']['last_name']);
                }
                else{
                    $("#img").attr('src',img_def);
                    $("#name").text("Full Name");
                }
            });
    }



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
        if (resp.substr(-4) == "9000") {
            uid = resp.substr(0, resp.length - 4);
        }
        reader.disconnect();
        $.post("<?=  api_url()?>c_circulation/retrieveDisplayID",{uid:uid},function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                if($("#materialBorrowerID").is(":visible") && !$('#materialBorrowerID').prop('disabled')){
                    $("#materialBorrowerID").val(response['data']['identification_number']);
                    viewImage();
                }
                else{
                    $("#materialListFilterBorrowerID").val(response['data']['identification_number']);
                    $("#materialListFilterSearch").trigger('click');
                }
            }
        });
    }
/////////////////////////////////////
//          RFID - code end        //
/////////////////////////////////////



});
material.viewTuitionFee = function(courseAnnualFeeID,datetime){
    $("#materialForm").trigger("reset");
    $("#materialLoanBody *").remove();

    $.post("<?=  api_url()?>c_circulation/retrieveCirculation",{borrowerID : courseAnnualFeeID, datetime : false, group : false},function(data){
        var response = JSON.parse(data);
            var dif = 0;
            var rate = 0;
            var total = 0;
            var d, d1, d2, format, format1, format2, calc;
        console.log(response);

        if(!response["error"].length){
            $("#materialForm").trigger("reset");
            $("#materialLoanBody *").remove();
            $("#materialListTotalLoan").text("0");
            $("#materialBorrowerID").val(response["data"][0]["identification_number"]).attr('disabled','');
            $('#materialListLibraryUsers option[value="'+response["data"][0]["library_user_ID"]+'"]').prop('selected', true);
            $("#materialListLibraryUsers").attr('disabled','');
            $("thead tr").eq(0).hide();
            $("#img").attr('src',img_url + response['data'][0]['identification_number'] + '.JPG');
            $("#name").text(response['data'][0]['last_name']+", "+response['data'][0]['first_name']+" "+response['data'][0]['middle_name']);


            d = new Date((response["data"][0]["loan_datetime"]-8*3600)*1000);
            format = d.getFullYear() +'-'+ ("0"+(d.getMonth()+1)).slice(-2) +'-'+ ("0"+(d.getDate())).slice(-2)
                        +'T'+ (d.toTimeString().substr(0,5)) + ":00.0";
            console.log(format);
            $('#materialListLoanDateTimeBorrowed').val(format).attr('disabled','').closest('div').css("display","none");
            var newRow, lastEntry;
            for(var x = 0; x < response["data"].length; x++){
                newRow = $(".prototype2").find(".materialListRow").clone();
                lastEntry = $("#materialLoanBody tr").last().children().first().text();
                newRow.find(".materialLoanNumber").text((lastEntry.length)?parseInt(lastEntry)+1:1);
                newRow.find("input.loanBookID").val(response["data"][x]["ID"]);
                newRow.find("input.bookID").val(response["data"][x]["book_ID"]);
                newRow.find(".materialLoanAccessionNumber").text(response["data"][x]["accession_number"]);
                newRow.find(".materialLoanTitle").text(response["data"][x]["title"]);
                newRow.find(".confirmButton").hide();


                d1 = new Date( Math.floor((response['data'][x]['loan_datetime'] - 8*3600) *1000));
                format1 = ("0"+(d1.getMonth()+1)).slice(-2) +'/'+ ("0"+(d1.getDate())).slice(-2)  +'/'+ d1.getFullYear();
                
                d1.setDate(d1.getDate() + parseInt(response['data'][x]['period']) - 1);
                if(calcBusinessDays(new Date( Math.floor((response['data'][x]['loan_datetime'] - 8*3600) *1000)),d1)>=0)
                {
                    do {
                        calc = parseInt(response['data'][x]['period']) - calcBusinessDays(new Date( Math.floor((response['data'][x]['loan_datetime'] - 8*3600) *1000)),d1);
                        d1.setDate(d1.getDate() + calc);
                    }
                    while(calc);
                    format2 = ("0"+(d1.getMonth()+1)).slice(-2) +'/'+ ("0"+(d1.getDate())).slice(-2)  +'/'+ d1.getFullYear();
                }
                else format2 = "-";
                newRow.find(".materialLoanBorrowed").text(format1);
                newRow.find(".materialLoanDue").text(format2);
                d1.setDate(d1.getDate() + 1);
                calc = calcBusinessDays(d1,new Date( Math.floor((response['data'][x]['return_datetime'] - 8*3600) *1000)));
                rate = parseFloat( ((calc>0)?calc:0) * response['data'][x]['fine_rate']);


                if(response['data'][x]['return_datetime']>0) {
                    newRow.find(".materialLoanReturnButton").hide();
                    //$(ths).closest('tr').children('td.materialLoanFine').children('span').text(response['data']);
                    //console.log(response['data'][x]['fine']);
                    //alert(total);
                    d = new Date( Math.floor((response['data'][x]['return_datetime'] - 8*3600) *1000));
                    newRow.find(".materialLoanReturnButton").closest("td").children(".loanDateReturn").val(response['data'][x]['return_datetime']);
                    format = ("0"+(d.getMonth()+1)).slice(-2) +'/'+ ("0"+(d.getDate())).slice(-2)  +'/'+ d.getFullYear()
                                +' '+ (d.toLocaleTimeString(navigator.language, {hour: '2-digit', minute:'2-digit'}));
                    newRow.find(".materialLoanReturnButton").parent().append("<span style='font-size:14px'>"+format+"</span>");
                }
                else{
                    newRow.find(".materialLoanReturnButton").show();
                    rate = 0;
                }

                newRow.find(".materialLoanFine span").text(rate.toFixed(2));
                newRow.find(".materialLoanRemoveButton").hide();
                newRow.find("input[name='loanAccession']").val(response["data"][x]["accession_number"]);

                $("#materialLoanBody").append(newRow);
                total +=rate;
                $("#materialLoanTotalFine span").text(total.toFixed(2));
                $("#materialListTotalLoan span").text(($("#materialListTotalLoan").text()*1) +1);
            }


            material.changeFormAction(3);
            $("#materialForm").attr("action", "<?=api_url()?>c_circulation/updateCirculation");
            $("#materialDiv").slideDown();
            $("#materialListTotalLoan").text($("#materialLoanBody .materialListRow").length);
        }
    });
}
material.removeList = function(row){
    $.post("<?=  api_url()?>c_circulation/deleteCirculation",{ID : row.attr("assment_type_id")},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            row.remove();
            material.retrieveList();
        }
    });
}
material.changeFormAction = function(status){
    switch(status*1){
        case 1://Create Button
            $("#materialCloseFormButton").show();
            $("#materialSubmitFormButton").show();
            $("#materialCreateFormButton").hide();
            break;
        case 2://Close Button
            $("#img").attr('src',img_def);
            $("#name").text("Full Name");
            $("#materialDiv").slideUp();
            $("#materialCloseFormButton").hide();
            $("#materialSubmitFormButton").hide();
            $("#materialCreateFormButton").show();
            break;

        case 3://Create Button
            $("#materialCloseFormButton").show();
            $("#materialSubmitFormButton").hide();
            $("#materialCreateFormButton").hide();
            break;
    }
};
material.retrieveList = function(filter,group){
    $("#materialListFilterSearch").button("loading");
    filter = (typeof filter === "undefined") ? {} : filter;
    for(x in material.assessentTypeListFilter){
        filter[x] = material.assessentTypeListFilter[x];
    }
    console.log(filter);
    filter.limit = 20;
    filter.retrieve_type = 1;
    filter.offset = ((($("#materialListCurrentPage").text()*1 - 1) > 0) ? ($("#materialListCurrentPage").text()*1 - 1) : 0) * 20;
    if($(".materialListSorter[sorted=1]").length){
        filter.sort= {};
        if($(this).attr("sorter_name") === "course_year_level"){
            filter.sort.year_level = $(".materialListSorter[sorted=1]").attr("sort")*1;
            filter.sort.course_ID = $(".materialListSorter[sorted=1]").attr("sort")*1;
        }else if($(".materialListSorter[sorted=1]").attr("sorter_name") === "description"){
            filter.sort["description"] = $(".materialListSorter[sorted=1]").attr("sort")*1;
        }else{
            filter.sort[$(".materialListSorter[sorted=1]").attr("sorter_name")] = $(".materialListSorter[sorted=1]").attr("sort")*1;
        }
    }
    $.post("<?=api_url()?>c_circulation/retrieveCirculation", filter, function(data){
        var response = JSON.parse(data);
        console.log(response);

        $("#materialListTotalResult").text("0");
        if(!response["error"].length){
            var totalPages = Math.ceil(response["result_count"]/20);
            $("#materialListBody").empty();
            $("#materialListTotalResult").text(response["result_count"]);
            $("#materialListTotalPage").text(totalPages);

            if($("#materialListCurrentPage").text()*1 <= totalPages){
                for(var x = 0; x < response["data"].length; x++){
                    var newRow = $(".prototype").find(".materialListRow").clone();
                    newRow.addClass((response["data"][x]["returned"]!=response["data"][x]["totalItem"])?"success":"");
                    newRow.attr("assment_type_id", response["data"][x]["identification_number"]);
                    newRow.attr("assment_datetime", response["data"][x]["loan_datetime"]);
                    newRow.find(".materialListBorrowerID").text(response["data"][x]["identification_number"]);
                    newRow.find(".materialListFullname").text(response['data'][x]['last_name']+", "+response["data"][x]["first_name"]+" "+response["data"][x]["middle_name"]);
                    newRow.find(".materialListAuthor").text(response["data"][x]["author"]);

//
//                    var d = new Date( Math.floor((response["data"][x]["loan_datetime"] - 8*3600) *1000));
//                    var format = ("0"+(d.getMonth()+1)).slice(-2) +'/'+ ("0"+(d.getDate())).slice(-2)  +'/'+ d.getFullYear()
//                                +' '+ (d.toLocaleTimeString(navigator.language, {hour: '2-digit', minute:'2-digit'}));
//                    newRow.find(".materialListDate").text(format);
                    newRow.find(".confirmButton").hide();


                    $("#materialListBody").append(newRow);
                }
            }
        }
        $("#materialListFilterSearch").button("reset");
        $("#materialLoanBody *").remove();
        $("#materialListTotalLoan").text("0");
        $("#materialListCurrentPage").text( ($("#materialListCurrentPage").text()*1 > $("#materialListTotalPage").text()*1) ?  $("#materialListTotalPage").text()*1 : $("#materialListCurrentPage").text()*1);
    });

     //class section loan action
    $("#materialLoanBody").on("click", ".materialLoanRemoveButton", function(){
       $(this).parent().find(".confirmButton").show();
       $(this).parent().find(".actionButton").hide();
    });
    $("#materialLoanBody").on("click", ".materialLoanNoRemoveButton", function(){
       $(this).parent().find(".confirmButton").hide();
       $(this).parent().find(".actionButton").show();
       $(this).parent().find(".confirmChangeButton").hide();
    });
    $("#materialLoanBody").on("click", ".materialLoanYesRemoveButton", function(){
       $(this).closest("tr").remove();
        $("#materialListTotalLoan").text(($("#materialListTotalLoan").text()*1) -1);
        var listctr = 1;
        $("#materialLoanBody td.materialLoanNumber").each(function(){
            $(this).text(listctr++);
        });
    });
};

$("#materialListLoanBookAdd").click(function(){
    if(!$("#materialListLoanAccessionNumber").val()
       || $("#materialListLibraryUsers option:selected").val()=="none"
       || (parseInt($("#materialListLibraryUsers option:selected").attr("data-limit")) && parseInt($("#materialListLibraryUsers option:selected").attr("data-limit"))<=$("#materialLoanBody tr").length)){
        if($("#materialListLoanAccessionNumber").val()) $("#materialListLibraryUsers").parent().addClass("has-error");
        return false;
    }
    $("#materialListLibraryUsers").parent().removeClass("has-error");
    $.post("<?=  api_url()?>c_cataloging/retrieveMaterial",{retrive_type:0,limit:1,accessionNumber:$("#materialListLoanAccessionNumber").val()},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            if(response["data"][0]["loaned"]<response["data"][0]["quantity"] && $('#materialLoanBody .materialLoanAccessionNumber:contains("'+response["data"][0]["accession_number"]+'")').length<response["data"][0]["quantity"]){
                var newRow = $(".prototype2").find(".materialListRow").clone();
                var lastEntry = $("#materialLoanBody tr").last().children().first().text();
                newRow.find(".materialLoanNumber").text((lastEntry.length)?parseInt(lastEntry)+1:1);
                newRow.find("input.loanBookID").val(response["data"][0]["ID"]);
                newRow.find(".materialLoanAccessionNumber").text(response["data"][0]["accession_number"]);
                newRow.find(".materialLoanTitle").text(response["data"][0]["title"]);
                newRow.find(".confirmButton").hide();
                newRow.find(".materialLoanReturnButton").hide();
                newRow.find(".materialLoanRemoveButton").show();
                newRow.find("input[name='loanAccession']").val(response["data"][0]["accession_number"]);

                $("#materialListLoanAccessionNumber").closest('th').removeClass('has-error');
                $("#materialLoanBody").append(newRow);
                $("#materialListTotalLoan").text(($("#materialListTotalLoan").text()*1) +1);
                $("#addItem-msg").text("");
            }
            else {
                $("#materialListLoanAccessionNumber").closest('th').addClass('has-error');
                $("#addItem-msg").text("No book available to be loan.");
            }
        }
        else{
            $("#materialListLoanAccessionNumber").closest('th').addClass('has-error');
            $("#addItem-msg").text("Can't find given book.");
        }
    });
});
    //Library users
$.post("<?=  api_url()?>c_material_access_control/retrieveLibraryUser",{},function(data){
    var response = JSON.parse(data);
    var newclone;
    if(!response["error"].length){
        $("select[name='libraryUsers']").empty();
        for(var x = response["data"].length-1; x >= 0 ; x--){
            $("select[name='libraryUsers']").append("<option value='"+response["data"][x]["ID"]+"' data-limit='"+response["data"][x]["max_material"]+"'>"+response["data"][x]["description"]+"</option>")
        }
    }
});

function calcBusinessDays(dDate1, dDate2) {         // input given as Date objects

  var iWeeks, iDateDiff, iAdjust = 0;

  if (dDate2 < dDate1) return -1;                 // error code if dates transposed

  var iWeekday1 = dDate1.getDay();                // day of week
  var iWeekday2 = dDate2.getDay();

  iWeekday1 = (iWeekday1 == 0) ? 7 : iWeekday1;   // change Sunday from 0 to 7
  iWeekday2 = (iWeekday2 == 0) ? 7 : iWeekday2;

  if ((iWeekday1 > 5) && (iWeekday2 > 5)) iAdjust = 1;  // adjustment if both days on weekend

  iWeekday1 = (iWeekday1 > 5) ? 5 : iWeekday1;    // only count weekdays
  iWeekday2 = (iWeekday2 > 5) ? 5 : iWeekday2;

  // calculate differnece in weeks (1000mS * 60sec * 60min * 24hrs * 7 days = 604800000)
  iWeeks = Math.floor((dDate2.getTime() - dDate1.getTime()) / 604800000)

  if (iWeekday1 <= iWeekday2) {
    iDateDiff = (iWeeks * 5) + (iWeekday2 - iWeekday1)
  } else {
    iDateDiff = ((iWeeks + 1) * 5) - (iWeekday1 - iWeekday2)
  }

  iDateDiff -= iAdjust                            // take into account both days on weekend

  return (iDateDiff + 1);                         // add 1 because dates are inclusive

}
</script>

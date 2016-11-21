<script>
    /*global systemApplication*/
    var borrowedMaterial = {};
    $(document).ready(function(){
        borrowedMaterial.borrowedMaterialList = $("#borrowedMaterialTable").apipagination({
            apiUrl : systemApplication.url.apiUrl+"c_circulation/retrieveCirculation",
            customFilterGenerator : function(){
                return {
                    retrieve_type : 1,
                    return_datetime : 0
                };
            },
            tableSorter : {
                1 : "title",
                2 : "borrower_full_name",
                3 : "load_due_datetime"
            },
            tableFilter : {
                title : "Title",
                borrower_full_name : "Borrower's Name"
            },
            pageLimit: 0,
            responseCallback : borrowedMaterial.showBorrowedItem
        });
        borrowedMaterial.borrowedMaterialList.showPage();
        $("#exportBorrowedMaterial").click(function(){
            fnExcelReport();
        });
    });
    borrowedMaterial.showBorrowedItem = function(response){
        console.log(response);
        if(!response["error"].length){
            for(var x = 0; x < response["data"].length; x++){
                var row = $(".prototype .borrowedMaterialRow").clone();
                row.attr("library_circulation_id", response["data"][x]["ID"]);
                row.find(".bookTitle").text(response["data"][x]["title"]);
                row.find(".borrowerFullName").text(response["data"][x]["first_name"]+" "+response["data"][x]["middle_name"]+" "+response["data"][x]["last_name"]);
                var calc = 0;
                var format2 = "-";
                var loanDate = new Date( Math.floor((response['data'][x]['loan_datetime'] - 8*3600) *1000));
                loanDate.setDate(loanDate.getDate() + parseInt(response['data'][x]['period']) - 1);
                if(calcBusinessDays(new Date( Math.floor((response['data'][x]['loan_datetime'] - 8*3600) *1000)),loanDate)>=0)
                {
                    do {
                        calc = parseInt(response['data'][x]['period']) - calcBusinessDays(new Date( Math.floor((response['data'][x]['loan_datetime'] - 8*3600) *1000)),loanDate);
                        loanDate.setDate(loanDate.getDate() + calc);
                    }
                    while(calc);
                    format2 = ("0"+(loanDate.getMonth()+1)).slice(-2) +'/'+ ("0"+(loanDate.getDate())).slice(-2)  +'/'+ loanDate.getFullYear();
                }
                
                row.find(".dueDate").text(format2);
                var currentDate = new Date();
                currentDate.setHours(1);
                if(currentDate.getTime() >   loanDate.getTime()){
                    row.addClass("danger");
                }
                $("#borrowedMaterialTable").append(row);
            }
        }
    };
    function calcBusinessDays(dDate1, dDate2) {         // input given as Date objects
        var iWeeks, iDateDiff, iAdjust = 0;
        if (dDate2 < dDate1)
            return -1;                 // error code if dates transposed
        var iWeekday1 = dDate1.getDay();                // day of week
        var iWeekday2 = dDate2.getDay();
        iWeekday1 = (iWeekday1 == 0) ? 7 : iWeekday1;   // change Sunday from 0 to 7
        iWeekday2 = (iWeekday2 == 0) ? 7 : iWeekday2;
        if ((iWeekday1 > 5) && (iWeekday2 > 5))
            iAdjust = 1;  // adjustment if both days on weekend
        iWeekday1 = (iWeekday1 > 5) ? 5 : iWeekday1;    // only count weekdays
        iWeekday2 = (iWeekday2 > 5) ? 5 : iWeekday2;
        // calculate differnece in weeks (1000mS * 60sec * 60min * 24hrs * 7 days = 604800000)
        iWeeks = Math.floor((dDate2.getTime() - dDate1.getTime()) / 604800000)
        if (iWeekday1 <= iWeekday2) {
            iDateDiff = (iWeeks * 5) + (iWeekday2 - iWeekday1)
        } else {
            iDateDiff = ((iWeeks + 1) * 5) - (iWeekday1 - iWeekday2)
        }
        iDateDiff -= iAdjust;                            // take into account both days on weekend
        return (iDateDiff + 1);                         // add 1 because dates are inclusive
    }
    function fnExcelReport()
    {
        var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
        var textRange; var j=0;
        var tab = ($('#borrowedMaterialTable')).clone(); // id of table
        var rows = tab.find("tbody tr");
        tab_text += "<tr><td>Title</td><td>Borrower</td><td>Due Date</td></tr>"
        for(j = 0 ; j < rows.length ; j++) 
        {    
            tab_text=tab_text+rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
        }

        tab_text=tab_text+"</table>";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE "); 
       var sa;
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open("txt/html","replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus(); 
            sa=txtArea1.document.execCommand("SaveAs",true,"sumit.xls");
        }  
        else                 //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

        return (sa);
    }
</script>
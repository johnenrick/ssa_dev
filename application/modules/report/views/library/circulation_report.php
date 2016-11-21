<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<script src="<?php echo load_asset("js/datepicker.js");?>"></script>
<script src="<?php echo load_asset("js/print.js");?>"></script>
<script src="<?php echo load_asset("js/tableAPIPagination.js");?>"></script>
<h1>Circulation Report</h1>

<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend></legend>
    </div>
    <div id="assessmentItemReportListMessage" class="col-md-11 col-md-offset-1">

    </div>
</div>
<div class="row" >
    <div class="panel panel-default" >

        <div class="panel-body">
            <div class="row">
                <div class="col-md-3 col-xs-4 ">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input id="assessmentItemReportListFilterStartDatetime" class="form-control" type="date" placeholder="mm/dd/yy">
                    </div>
                    <div class="form-group" style="display:none">
                        <label>Assessment Type</label>
                        <select id="assessmentItemReportListFilterAssessmentType" class="form-control" >

                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-xs-4">
                    <div class="form-group">
                        <label>End Date</label>
                        <input id="assessmentItemReportListFilterEndDatetime" class="form-control" type="date" placeholder="mm/dd/yy">
                    </div>
                    <div class="form-group" style="display:none">
                        <label>Assessment Item</label>
                        <input id="assessmentItemReportListFilterAssessmentItem" class="form-control" type="text" placeholder="Assessment Descriptions">
                    </div>
                </div>
                <div class="col-md-2 col-xs-4">
                    <label>&nbsp</label><br>
                    <button id="assessmentItemReportListFilterSearch" class="btn btn-primary" >
                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                    </button>

                </div>
                <div class="col-md-2 col-xs-4" style="text-align:right">
                    <label>&nbsp</label><br>
                    <button id="assessmentItemReportListPrintReport" class="btn btn-primary" >
                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
                    </button>
                </div>
            </div>
        </div>
        </div>
</div>
<div class="row" >
    <div class="col-md-8 col-md-offset-2">
        <div id="assessmentItemReportListTablePrint">
        <table class="table table-hover" id="assessmentItemReportListTable">
            <thead>
                <tr>
                    <th style="width:60%">Description
                    </th>
                    <th style="width:40%;text-align:center">No. of Student/Employee
                    </th>
                </tr>
            </thead>
            <tbody id="assessmentItemReportListBody">
            </tbody>
            <tfoot >
                <tr>
                    <td style="text-align:right;font-weight:bold">Total : </td>
                    <td id="assessmentItemReportListTotalAmount"  style="text-align:center">0</td>
                </tr>
            </tfoot>
        </table>
        </div>

        <div class="prototype" style="display:none;">
            <table>
                <tr class="assessmentItemReportListRow" payment_assessment_item_ID="0" >
                    <td class="assessmentItemReportListAssessmentTypeCode"></td>
                    <td class="assessmentItemReportListAssessmentItem" style="text-align:center"></td>
                </tr>
            </table>
        </div>
    </div>
</div>

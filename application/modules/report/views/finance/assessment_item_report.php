<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<script src="<?php echo load_asset("js/datepicker.js");?>"></script>
<script src="<?php echo load_asset("js/print.js");?>"></script>
<script src="<?php echo load_asset("js/tableAPIPagination.js");?>"></script>
<h1>Payment Report</h1>

<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend>Assessment Item Report</legend>
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
                        <input id="assessmentItemReportListFilterStartDatetime" class="form-control" type="date" placeholder="mm/dd/yyyy">
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
                        <input id="assessmentItemReportListFilterEndDatetime" class="form-control" type="date" placeholder="mm/dd/yyyy">
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
    <div class="col-md-12">
        <div id="assessmentItemReportListTablePrint">
        <table class="table table-hover" id="assessmentItemReportListTable">
            <thead>
                <tr>
                    <th class="assessmentItemReportListSorter" sorter_name="assessment_type__code" sort="0" style="width:10%">Type
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="assessmentItemReportListSorter" sorter_name="assessment_item__description" sort="0" style="width:30%">Assessment Item
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="assessmentItemReportListSorter" sorter_name="payment_assessment_item__total_amount" sort="0" style="width:15%">Amount
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                </tr>
            </thead>
            <tbody id="assessmentItemReportListBody">
            </tbody>
            <tfoot >
                <tr>
                    <td></td>
                    <td style="text-align:right;font-weight:bold">Total : </td>
                    <td id="assessmentItemReportListTotalAmount"  style="text-align:right">0.00</td>
                </tr>
                <tr class="tablePagination">
                    <td style="font-size:13px;font-weight:bold">
                        <span id="assessmentItemReportListTotalResult">0</span> Result(s)
                    </td>
                    <td style="text-align:center;" >
                        <button class="btn" id="assessmentItemReportListPreviousPage">Previous</button>
                        <button id="assessmentItemReportListNextPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right" colspan="2">
                        Page <span id="assessmentItemReportListCurrentPage">1</span>
                        of <span id="assessmentItemReportListTotalPage">1</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
        <div class="modal fade" id="assessmentItemReportAssessmentItemDetail" selection_mode="1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="font-size:14px">
                <div class="modal-content "  >
                    <div class="modal-body" >
                        
                        <div class="form-group">
                            <div id="assessmentItemReportAssessmentItemDetailMessage">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success pull-right" id="btnExport" onclick="fnExcelReport();"><span class="glyphicon glyphicon-export" aria-hidden="true"></span> EXPORT to Excel</button>
                                <table class="table" id="assessmentItemReportAssessmentItemDetailTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>OR#</th>
                                            <th>Payee</th>
                                            <th>Detail</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><strong>Total: </strong></td>
                                            <td id="assessmentItemReportAssessmentItemDetailTotalAmount" style="text-align:right"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="prototype" style="display:none;">
            <table>
                <tr class="assessmentItemReportListRow" payment_assessment_item_ID="0" >
                    <td class="assessmentItemReportListAssessmentTypeCode"></td>
                    <td class="assessmentItemReportListAssessmentItem"></td>
                    <td class="assessmentItemReportListAmount" style="text-align:right"></td>
                </tr>
                <tr class="assessmentItemReportAssessmentItemDetailRow" >
                    <td class="assessmentItemReportAssessmentItemDetailDatetime"></td>
                    <td class="assessmentItemReportAssessmentItemDetailOrderReceiptNumber" ></td>
                    <td class="assessmentItemReportAssessmentItemDetailPayeeFullName"></td>
                    <td class="assessmentItemReportAssessmentItemDetailDetail"></td>
                    <td class="assessmentItemReportAssessmentItemDetailAmount" style="text-align:right"></td>
                </tr>
            </table>
        </div>
    </div>
    <iframe id="txtArea1" style="display:none"></iframe>
</div>

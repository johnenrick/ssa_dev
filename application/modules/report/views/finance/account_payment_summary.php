<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<script src="<?=load_asset()?>js/print.js"></script>
<h1>Class Section Account Payment Summary</h1>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" >
            <div class="panel-heading">
                <h3 class="panel-title">
                    Class Section
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div id="accountStatementMessage" class="col-md-12"></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="school_year">School Year</label>
                            <select class="form-control accountStatementFilter" id="accountStatementSchoolYearFilter" type="text" placeholder="School Year" name="school_year"></select>
                        </div>
                        <div class="form-group">
                            <label for="course_ID">Course & Year</label>
                            <div class="form-inline">
                                <select id="accountStatementCourseFilter" class="form-control accountStatementFilter " type="text" placeholder="Course" name="course_ID">

                                </select>
                                <select id="accountStatementYearLevelFilter" class="form-control accountStatementFilter" type="text" placeholder="Year Level" name="year_level">
                                    <option value="0">None</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="section_ID">Section</label>
                            <select class="form-control accountStatementFilter" id="accountStatementSectionFilter" type="text" placeholder="section" name="section_ID"></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" >
    <div class="col-md-12">
        <button class="btn btn-primary pull-right" id="accountStatementAccountStatementBatchPrintButton" data-loading-text="Please wat...">
            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Batch Print
        </button>
    </div>
</div>
<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <style>
                /*table,
                table tr td {
                        page-break-inside: avoid;
                }
                @page {
                  margin: 30;
                }*/
                #summaryTable{
                    text-transform: uppercase;
                }
                @media print
                {
                  table { page-break-after:auto }
                  tr    { page-break-inside:avoid; page-break-after:auto }
                  td    { page-break-inside:avoid; page-break-after:auto }
                  thead { display:table-header-group }
                  tfoot { display:table-footer-group }
                }
        </style>
        <table id="summaryTable" class=" table table-hover">
            <thead>
                <tr>
                    <th>ID #</th>
                    <th>Name</th>
                    <th>Tuition</th>
                    <th>Special Fee</th>
                    <th>Other Fee</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th>Tuition Balance</th>
                </tr>
            </thead>
            <tbody id="accountStatementStudentListBody">
            </tbody>
            <tfoot >
                <tr>
                    <td colspan="2" style="text-align: right;font-weight: bold">TOTAL</td>
                    <td class="totalTuition" style="text-align: right;font-weight: bold"></td>
                    <td class="totalSpecialFee" style="text-align: right;font-weight: bold"></td>
                    <td class="totalOtherFee" style="text-align: right;font-weight: bold"></td>
                    <td class="totalDiscount" style="text-align: right;font-weight: bold"></td>
                    <td class="totalTotal" style="text-align: right;font-weight: bold"></td>
                    <td class="totalTuitionBalance" style="text-align: right;font-weight: bold"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="modal fade" id="accountStatementAccountStatement"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content "  >
            <div class="modal-body" >
                <div class="row">
                    <div class="col-md-12" id="accountStatementAccountStatementListMessage">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-right" id="accountStatementAccountStatementPrintButton" data-loading-text="Please wat...">
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="prototype" style="display:none;">
    <table>
        <tr class="accountStatementStudentListRow" class_section_id="0">

            <td class="accountStatementStudentListIDNumber" ></td>
            <td class="accountStatementStudentListName"></td>
            <td class="accountStatementStudentListTuition" style="text-align: right">0.00</td>
            <td class="accountStatementStudentListSpecialFee" style="text-align: right">0.00</td>
            <td class="accountStatementStudentListOtherFee" style="text-align: right">0.00</td>
            <td class="accountStatementStudentListDiscount" style="text-align: right">0.00</td>
            <td class="accountStatementStudentListTotal" style="text-align: right">0.00</td>
            <td class="accountStatementStudentListTuitionBalance" style="text-align: right">0.00</td>
        </tr>
        <tr class="accountStatementAccountStatementListRow">
<!--            <td class="accountStatementAccountStatementListDescription"></td>
            <td class="accountStatementAccountStatementListAmount" style="text-align:right"></td>-->
            <td class="accountStatementAccountStatementListDatetime"></td>
            <td class="accountStatementAccountStatementListOR"></td>
            <td class="accountStatementAccountStatementListRemarks"></td>
            <td class="accountStatementAccountStatementListAmount" style="text-align:right"></td>
            
            
        </tr>
    </table>
    <div class="accountStatementAccountStatementRow" style="height:25.6cm">
        <div style="font-family: Verdana, Arial, San-Serif;padding:10px;font-size:12px">
            Account Statement of <span class="accountStatementAccountStatementFullName" style="font-weight:bold"></span> in academic year of <span style="font-weight:bold" class="accountStatementAccountStatementAcademicYear"></span>
        </div>
        <table class="table table-hover" style="font-size:13px;width:100%;border-radius: 5px;border-style: solid;padding: 10px;font-family: Verdana, Arial, San-Serif;margin-bottom:10px">
            <thead style="font-weight:bold;text-align:center">
                <tr>
                    <td colspan="4">STATEMENT OF ACCOUNT</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>OR</td>
                    <td>Description</td>
                    <td>Amount</td>
                </tr>
            </thead>
            <tbody class="accountStatementAccountStatementList">
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align:right;font-weight:bold">Total Amount Paid : </td>
                    <td class="accountStatementAccountStatementTotalAmountPaid" style="text-align:right;font-weight:bold">0.00</td>

                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align:right;font-weight:bold">Total Tuition Fee : </td>
                    <td class="accountStatementAccountStatementTotalAnnualFee" style="text-align:right;font-weight:bold">0.00</td>

                </tr>
                <tr >
                    <td></td>
                    <td></td>
                    <td style="text-align:right;font-weight:bold">Remaining Balance : </td>
                    <td class="accountStatementAccountStatementTotalRemainingBalance" style="text-align:right;font-weight:bold">0.00</td>

                </tr>
            </tfoot>
        </table>
    </div>
</div>

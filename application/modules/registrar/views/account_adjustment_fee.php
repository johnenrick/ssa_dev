<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Account Adjustment Fee</h1>

<div id="accountAdjustmentFeeDiv" >
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary" >
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Adjustment Fee
                        </h3>
                    </div>
                    <div class="panel-body">
                        <form id="accountAdjustmentFeeForm" method="POST">
                            <input type="hidden" name="type" value="1">
                            <input id="accountAdjustmentFeeID" type="hidden" name="ID" value="0">
                            <div class="row">
                                <div class="col-md-12" >
                                    <div class='alert alert-warning' id='accountAdjustmentFeeMessage' style='text-align:center;display:none'></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group ">
                                        <label for="school_year">Assessment Type</label>
                                        <div class="">
                                            <select class="form-control"  id="accountAdjustmentFeeType" type="text" placeholder="Assessment Type" name="assessment_type_ID"></select>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group ">
                                        <label for="school_year">Assessment Item</label>
                                        <div class="">
                                            <select class="form-control"  id="accountAdjustmentFeeItem" type="text" placeholder="Assessment Type" name="assessment_item_ID"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group ">
                                        <label for="school_year">Adjustment</label>
                                        <div class="">
                                            <select class="form-control"  id="accountAdjustmentFeeAdjustment" type="text" placeholder="Assessment Type" name="assessment_item_ID"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group ">
                                        <label for="school_year">Amount</label>
                                        <div class="" style="text-align:right">
                                            <span class="form-control"  id="accountAdjustmentFeeAmount" type="text" placeholder="Amount" name="amount">0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-primary" >
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Search Student
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group ">
                                    <label for="school_year">ID Number</label>
                                    <input class="form-control"  id="accountAdjustmentFeeIdentificationNumber" type="text" placeholder="ID Number" name="identification_number">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group ">
                                    <label for="school_year">Last Name</label>
                                    <input class="form-control"  id="accountAdjustmentFeeLastName" type="text" placeholder="Last Name" name="last_name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group ">
                                    <label for="school_year">First Name</label>
                                    <input class="form-control"  id="accountAdjustmentFeeFirstName" type="text" placeholder="First Name" name="first_name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group ">
                                    <label for="school_year">Middle Name</label>
                                    <input class="form-control"  id="accountAdjustmentFeeMiddleName" type="text" placeholder="Middle Name" name="middle_name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="text-align:center">
                                <button id="accountAdjustmentFeeListFilterSearch" data-loading-text="Searching..." class="btn btn-primary">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                                </button>
                                <button id="accountAdjustmentFeeListFilterClear" data-loading-text="Searching..." class="btn btn-warning">
                                    <span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend>Student List</legend>
    </div>
    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="accountAdjustmentFeeListSorter" sorter_name="account_basic_information__identification_number" sort="0" style="width:22%">ID Number
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="accountAdjustmentFeeListSorter" sorter_name="student_name" sort="0" >Student Name
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th style="width:20%">Action</th>
                </tr>
            </thead>
            <tbody id="accountAdjustmentFeeListBody">
            </tbody>
            <tfoot>
                <tr>
                    <td style="font-size:13px;font-weight:bold">
                        <span id="accountAdjustmentFeeListTotalResult">0</span> Result(s)
                    </td>

                    <td style="text-align:center;">
                        <button class="btn" id="accountAdjustmentFeeListPreviousPage">Previous</button>
                        <button id="accountAdjustmentFeeListNextPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right">
                        Page <span id="accountAdjustmentFeeListCurrentPage">1</span>
                        of <span id="accountAdjustmentFeeListTotalPage">1</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none;">
            <table>
                <tr class="accountAdjustmentFeeListRow" course_annual_fee_ID="0">
                    <td class="accountAdjustmentFeeListIdentificationNumber"></td>
                    <td class="accountAdjustmentFeeListAccountName"></td>
                    <td>
                        <!--<button class="btn-primary  actionButton accountAdjustmentFeeListViewButton" type="button">View</button>-->
                        <button class="btn btn-xs btn-success  actionButton accountAdjustmentFeeListSelectButton" type="button" style="display:none">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Select
                        </button>
                        <button class="btn btn-xs btn-danger  actionButton accountAdjustmentFeeListRemoveButton" type="button" style="display:none">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Remove
                        </button>
                        <span class="confirmButton accountAdjustmentFeeListMessageButton" style="font-weight:bold" type="button"></span>
                        <button class="btn btn-xs btn-info x confirmButton accountAdjustmentFeeListYesButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton accountAdjustmentFeeListNoButton"  type="button">No</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

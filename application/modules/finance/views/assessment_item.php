<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Assessment Item</h1>

<div id="assessmentItemDiv" style="display:none">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary" >
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Assessment Item
                        </h3>
                    </div>
                    <div class="panel-body">
                        <form id="assessmentItemForm" method="POST">
                            <input type="hidden" name="type" value="1">
                            <input id="assessmentItemID" type="hidden" name="ID" value="0">
                            <div class="row">
                                <div class="col-md-12" >
                                    <div class='alert alert-warning' id='assessmentItemMessage' style='text-align:center;display:none'></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group ">
                                        <label for="school_year">Assessment Type</label>
                                        <div class="">
                                            <select class="form-control"  id="assessmentItemType" type="text" placeholder="Assessment Type" name="assessment_type_ID"></select>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="school_year">General Ledger</label>
                                        <div class="">
                                            <select class="form-control"  id="assessmentItemGeneralLedger" type="text" placeholder="General Ledger" name="general_ledger_ID"></select>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="school_year">Description</label>
                                        <div class="">
                                            <input class="form-control"  id="assessmentItemDescription" type="text" placeholder="Description" name="description">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="school_year">For Cashiering</label>
                                        <div class="">
                                            <select class="form-control"  id="assessmentItemTellering" type="text" name="tellering">
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
</div>
<div class="col-md-10 col-md-offset-1">
    <br>
    <button class="btn btn-warning pull-right" id="assessmentItemCloseFormButton" type="button" style="margin-right:10px;display:none">Close</button>&nbsp;
    <button class="btn btn-success pull-right" id="assessmentItemSubmitFormButton" type="submit" style="margin-right:10px;display:none">Submit</button>&nbsp;
    <button class="btn btn-success pull-right" id="assessmentItemCreateFormButton" type="button" style="margin-right:10px">Create</button>
</div>
<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend>Assessment Item List</legend>
    </div>
    <!--##### New Student ####--->

    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="assessmentItemListSorter" sorter_name="assessment_type_description" sort="0" style="width:22%">Assessment Type
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="assessmentItemListSorter" sorter_name="general_ledger_description" sort="0" >General Ledger
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="assessmentItemListSorter" sorter_name="description" sort="0" >Description
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th style="width:20%">Action</th>
                </tr>
                <tr>
                    <th>
                        <select id="assessmentItemListFilterAssessmentType" class="form-control" type="text" placeholder="ASsessment Type">
                            
                        </select>
                    </th>
                    <th>
                        <select id="assessmentItemListFilterGeneralLedger" class="form-control" type="text" placeholder="General Ledger">
                            
                        </select>
                    </th>
                    <th>
                        <input id="assessmentItemListFilterDescription" class="form-control" type="text" placeholder="Description">
                    </th>
                    <th>
                        <button id="assessmentItemListFilterSearch" data-loading-text="Searching..." class="btn btn-primary">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody id="assessmentItemListBody">
            </tbody>
            <tfoot>
                <tr>
                    <td style="font-size:13px;font-weight:bold">
                        <span id="assessmentItemListTotalResult">0</span> Result(s)
                    </td>

                    <td style="text-align:center;" colspan="2" >
                        <button class="btn" id="assessmentItemListPreviousPage">Previous</button>
                        <button id="assessmentItemListNextPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right">
                        Page <span id="assessmentItemListCurrentPage">1</span>
                        of <span id="assessmentItemListTotalPage">1</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none;">
            <table>
                <tr class="assessmentItemListRow" assessment_type_id="0">
                    <td class="assessmentItemListAssessmentType"></td>
                    <td class="assessmentItemListGeneralLedger"></td>
                    <td class="assessmentItemListDescription"></td>
                    <td>
                        <!--<button class="btn-primary  actionButton assessmentItemListViewButton" type="button">View</button>-->
                        <button class="btn btn-xs btn-info  actionButton assessmentItemListViewButton" type="button">View</button>
                        <button class="btn btn-xs btn-danger  actionButton assessmentItemListRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton assessmentItemListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton assessmentItemListNoRemoveButton"  type="button">No</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>General Ledger</h1>

<div id="generalLedgerDiv" class="row" style="display:none">
    <div class="col-md-12">
        <div class="panel panel-primary" >
            <div class="panel-heading" >
                <h3 class="panel-title">
                    General Ledger Form
                </h3>
            </div>
            <div class="panel-body">
                <form id="generalLedgerForm" method="POST">
                    <input type="hidden" name="type" value="1">
                    <input id="generalLedgerID" type="hidden" name="ID" value="0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12" >
                                    <div class='alert alert-warning' id='generalLedgerMessage' style='text-align:center;display:none'></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group ">
                                        <label for="school_year">Description</label>
                                        <div class="">
                                            <input class="form-control"  id="generalLedgerDescription" type="text" placeholder="Description" name="description">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="school_year">Code</label>
                                        <div class="">
                                            <input class="form-control" id="generalLedgerCode" type="text" placeholder="Code" name="code">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-md-10 col-md-offset-1">
    <br>
    <button class="btn btn-warning pull-right" id="generalLedgerCloseFormButton" type="button" style="margin-right:10px;display:none">Close</button>&nbsp;
    <button class="btn btn-success pull-right" id="generalLedgerSubmitFormButton" type="submit" style="margin-right:10px;display:none">Submit</button>&nbsp;
    <button class="btn btn-success pull-right" id="generalLedgerCreateFormButton" type="button" style="margin-right:10px">Create</button>
</div>
<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend>General Ledger List</legend>
    </div>
    <!--##### New Student ####--->

    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="generalLedgerListSorter" sorter_name="description" sort="0" >Description
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="generalLedgerListSorter" sorter_name="code" sort="0" style="width:30%">Code
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th style="width:20%">Action</th>
                </tr>
                <tr>
                    <th>
                        <input id="generalLedgerListFilterDescription" class="form-control" type="text" placeholder="Description">
                    </th>
                    <th>
                        <input id="generalLedgerListFilterCode" class="form-control inline" type="text" placeholder="Code" >
                    </th>
                    <th>
                        <button id="generalLedgerListFilterSearch" data-loading-text="Searching..." class="btn btn-primary">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody id="generalLedgerListBody">
            </tbody>
            <tfoot>
                <tr>
                    <td style="font-size:13px;font-weight:bold">
                        <span id="generalLedgerListTotalResult">0</span> Result(s)
                    </td>

                    <td style="text-align:center;" >
                        <button class="btn" id="generalLedgerListPreviousPage">Previous</button>
                        <button id="generalLedgerListNextPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right">
                        Page <span id="generalLedgerListCurrentPage">1</span>
                        of <span id="generalLedgerListTotalPage">1</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none;">
            <table>
                <tr class="generalLedgerListRow" assessment_type_id="0">
                    <td class="generalLedgerListDescription"></td>
                    <td class="generalLedgerListCode"></td>
                    <td>
                        <!--<button class="btn-primary  actionButton generalLedgerListViewButton" type="button">View</button>-->
                        <button class="btn btn-xs btn-info  actionButton generalLedgerListViewButton" type="button">View</button>
                        <button class="btn btn-xs btn-danger  actionButton generalLedgerListRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton generalLedgerListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton generalLedgerListNoRemoveButton"  type="button">No</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

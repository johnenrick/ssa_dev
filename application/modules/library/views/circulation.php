<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Circulation</h1>

<div id="materialDiv" class="row" style="display:none">
    <div class="col-md-12">
       <div class="panel-body">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

            <form id="materialForm" method="POST">
                <div class="panel panel-primary" >
                    <div class="panel-heading"  data-toggle="collapse"  href="#librarymaterialBasicInformationTab">
                        <h3 class="panel-title">
                            Loan Information
                        </h3>
                    </div>
                    <div id="librarymaterialBasicInformationTab" class="panel-collapse collapse in" >
                        <div class="panel-body">
                                <input type="hidden" name="type" value="1">
                                <div class="row">
                                    <div class="col-md-12" id="loanBookList">
                                        <div class="row">
                                            <div class="col-md-12" >
                                                <div class='alert alert-warning' id='materialMessage' style='text-align:center;display:none'></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <img src="<?=  base_url()?>assets/img/default.JPG" id="img" class="img-thumbnail" width="200" height="200">
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                      <h2 id="name" data-default="Full Name">Full Name</h2>
                                                    </div>
                                                </div>
                                               <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group ">
                                                            <label for="school_year">Borrower ID</label>
                                                            <div class="">
                                                                <input class="form-control"  id="materialBorrowerID" type="text" placeholder="Borrower ID" name="borrowerID" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group ">
                                                            <label for="school_year">Borrower Type</label>
                                                            <div class="">
                                                                <select class="form-control"  id="materialListLibraryUsers" name="libraryUsers" required></select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                            <div class="form-group">
                                                               <label>&nbsp;</label>
                                                                <div class="input-group form-inline">
                                                                    <div class="input-group-addon">Date &amp; Time </div>
                                                                    <input type="datetime-local" class="form-control" id="materialListLoanDateTimeBorrowed" name="datetime">
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!--<legend style="font-size:16px"></legend>-->
                                        <br/>
                                        <div class="row">
                                           <div class="col-md-12">
                                            <table class="table table-condensed table-hover" >
                                                <thead>
                                                    <tr>
                                                        <th colspan="3" class="form-inline"  style="text-align:right;color:red">
                                                            <span id="addItem-msg"></span>
                                                       </th>
                                                        <th class="col-md-2" colspan="4">
                                                            <input id="materialListLoanAccessionNumber" class="form-control" type="text" placeholder="Accession Number">
                                                        </th>
                                                        <th class="col-md-1">
                                                            <button type="button" id="materialListLoanBookAdd" data-loading-text="Adding..." class="btn btn-primary">
                                                                <span class="glyphicon" aria-hidden="true"></span> Add Item
                                                            </button>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-md-1">&nbsp;#&nbsp;</th>
                                                        <th class="col-md-1">ACNO</th>
                                                        <th class="col-md-6" colspan="2">Title</th>
                                                        <th class="col-md-1">Date Borrowed</th>
                                                        <th class="col-md-1">Due Date</th>
                                                        <th class="col-md-1">Fine</th>
                                                        <th class="col-md-1" colspan="2">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="materialLoanBody">
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td style="font-size:13px;font-weight:bold" colspan="3">
                                                            <span id="materialListTotalLoan">0</span> Item(s)
                                                        </td>
                                                        <td colspan="2"></td>
                                                        <td style="text-align:right"> Total: </td>
                                                        <td>
                                                           <span id="materialLoanTotalFine">₱<span>0.00</span></span>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            </div>
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
    <button class="btn btn-warning pull-right" id="materialCloseFormButton" type="button" style="margin-right:10px;display:none">Close</button>&nbsp;
    <button class="btn btn-success pull-right" id="materialSubmitFormButton" type="submit" style="margin-right:10px;display:none">Submit</button>&nbsp;
    <button class="btn btn-success pull-right" id="materialCreateFormButton" type="button" style="margin-right:10px">Create</button>
</div>
<!--###### Student Listing--->
<div class="row" >
    <!--##### New Student ####--->

    <div class="col-md-10 col-md-offset-1">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>
<!--
                        <div class="col-md-6">
                            <input id="materialListFilterDateStart" class="form-control" type="date">
                        </div>
                        <div class="col-md-6">
                            <input id="materialListFilterDateEnd" class="form-control" type="date">
                        </div>
-->
                    </th>
                    <th>
                        <input id="materialListFilterBorrowerID" class="form-control" type="text" placeholder="Name">
                    </th>
                    <th>
                        <select class="form-control"  id="circulationSearchOptionSelect" name="circulationSearchOption">
                            <option>Name</option>
                            <option>ID Number</option>
                        </select>
                    </th>
                    <th>
                        <button id="materialListFilterSearch" data-loading-text="Searching..." class="btn btn-primary">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                        </button>
                    </th>
                </tr>
                <tr>
                    <th class="materialListSorter" sorter_name="borrower_ID" sort="0" style="width:18%">Borrower ID
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th colspan="2" class="materialListSorter" sorter_name="last_name" sort="0">Name
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
<!--
                    <th class="materialListSorter" sorter_name="loan_datetime" sort="0" style="width:18%">Date Borrowed
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
-->
                    <th style="width:15%">Action</th>
                </tr>
            </thead>
            <tbody id="materialListBody">
            </tbody>
            <tfoot>
                <tr>
                    <td style="font-size:13px;font-weight:bold">
                        <span id="materialListTotalResult">0</span> Result(s)
                    </td>

                    <td style="text-align:center;" >
                        <button class="btn" id="materialListPreviousPage">Previous</button>
                        <button id="materialListNextPage" class="btn">Next</button>
                    </td>
                    <td colspan="2" style="text-align:right">
                        Page <span id="materialListCurrentPage">1</span>
                        of <span id="materialListTotalPage">1</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none;">
            <table>
                <tr class="materialListRow" assessment_type_id="0">
                    <td class="materialListBorrowerID"></td>
                    <td colspan="2" class="materialListFullname"></td>
<!--                    <td class="materialListDate"></td>-->
                    <td>
                        <!--<button class="btn-primary  actionButton materialListViewButton" type="button">View</button>-->
                        <button class="btn btn-xs btn-info  actionButton materialListViewButton" type="button">View</button>
                        <!--
                        <button class="btn btn-xs btn-danger  actionButton materialListRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton materialListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton materialListNoRemoveButton"  type="button">No</button>
                        -->
                    </td>
                </tr>
            </table>
        </div>
        <div class="prototype2" style="display:none;">
            <table>
                <tr class="materialListRow">
                    <td class="materialLoanNumber">0</td>
                    <td class="materialLoanAccessionNumber"></td>
                    <td class="materialLoanTitle" colspan="2"></td>
                    <td class="materialLoanBorrowed" style="text-align:center"></td>
                    <td class="materialLoanDue" style="text-align:center"></td>
                    <td class="materialLoanFine" style="background-color:#E9F0F7">₱<span style="color:green">0.00</span></td>
                    <td style="color:#337ab7">
                       <input type="hidden" name="loanBookID[]" class="loanBookID" value="0">
                       <input type="hidden" name="loanDateReturn[]" class="loanDateReturn" value="0">
                       <input type="hidden" name="bookID[]" class="bookID" value="0">
                        <button class="btn btn-xs btn-success  actionButton materialLoanReturnButton" type="button">Return</button>
                        <button class="btn btn-xs btn-danger  actionButton materialLoanRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton materialLoanYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton materialLoanNoRemoveButton"  type="button">No</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<object id="webcard" type="application/x-webcard" width="0" height="0">
<!--  <param name="onload" value="pluginLoaded" />-->
</object>

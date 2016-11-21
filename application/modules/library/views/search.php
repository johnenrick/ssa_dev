<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Search</h1>

<div id="catalogingDiv" class="row" style="display:none">
    <div class="col-md-12">
        <form id="catalogingForm" method="POST">
            <div class="panel panel-primary" >
                <div class="panel-heading" data-toggle="collapse"  href="#materialMaterialTab">
                    <h3 class="panel-title">
                        Material Information
                    </h3>
                </div>
                <div id="materialMaterialTab" class="panel-collapse collapse in" >
                    <div class="panel-body">
                        <input type="hidden" name="type" value="1">
                        <input id="catalogingID" type="hidden" name="ID" value="0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class='alert alert-warning' id='catalogingMessage' style='text-align:center;display:none'></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                       <div class="form-group ">
                                            <label for="school_year">Control Number</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingControlNumber" type="text" placeholder="Control Number" name="controlNumber" required>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Material</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingTypeOfMaterial" type="text" placeholder="Material" name="typeOfMaterial" required>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Title</label>
                                            <textarea class="form-control" id="catalogingTitle" name="title" rows="2" placeholder="Title" style="resize:vertical"></textarea>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Author</label>
                                            <div class="">
                                                <input class="form-control" id="catalogingAuthor" type="text" placeholder="Author" name="author" required>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Publisher</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingPublisher" type="text" placeholder="Publisher" name="publisher">
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Publisher's Address</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingPublisherAddress" type="text" placeholder="Publisher's Address" name="publisherAddress">
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Year</label>
                                            <div class="">
                                                <input class="form-control" id="catalogingCopyright" type="text" placeholder="Year" name="copyright">
                                            </div>
                                        </div>
                                       <div class="form-group ">
                                            <label for="school_year">Series Title</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingSeriesTitle" type="text" placeholder="Series Title" name="seriesTitle" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                       <div class="form-group ">
                                            <label for="school_year">Physical Description</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingPhysicalDescription" type="text" placeholder="Physical Description" name="physicalDescription" required>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Call Number</label>
                                            <div class="">
                                                <input class="form-control" id="catalogingCallNumber" type="text" placeholder="Call Number" name="callNumber" required>
                                            </div>
                                        </div>
                                       <div class="form-group ">
                                            <label for="school_year">ISBN</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingISBN" type="text" placeholder="ISBN" name="isbn" required>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Edition</label>
                                            <div class="">
                                                <input class="form-control" id="catalogingEdition" type="number" placeholder="Edition" name="edition">
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Accession Number</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingAccessionNumber" type="text" placeholder="Accession Number" name="accessionNumber" required>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Notes</label>
                                            <textarea class="form-control" id="catalogingNotes" name="notes" rows="3" placeholder="Notes" style="resize:vertical"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           <div class="panel panel-primary" >
                <div class="panel-heading" data-toggle="collapse"  href="#materialSubjectTab">
                    <h3 class="panel-title">
                        Subject
                    </h3>
                </div>
                <div id="materialSubjectTab" class="panel-collapse collapse out" >
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class='alert alert-warning' id='materialMessage' style='text-align:center;display:none'></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group ">
                                            <label for="school_year">Subject 1</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingSubject1" type="text" placeholder="Subject 1" name="subject1" required>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Subject 2</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingSubject2" type="text" placeholder="Subject 2" name="subject2" required>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Subject 3</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingSubject3" type="text" placeholder="Subject 3" name="subject3" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group ">
                                            <label for="school_year">Subject 4</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingSubject4" type="text" placeholder="Subject 4" name="subject4" required>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Subject 5</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingSubject5" type="text" placeholder="Subject 5" name="subject5" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary" >
                <div class="panel-heading" data-toggle="collapse"  href="#materialAETab">
                    <h3 class="panel-title">
                        Additional Entry
                    </h3>
                </div>
                <div id="materialAETab" class="panel-collapse collapse out" >
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class='alert alert-warning' id='materialMessage' style='text-align:center;display:none'></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                       <div class="form-group ">
                                            <label for="school_year">Title</label>
                                            <textarea class="form-control" id="catalogingAddedEntryTitle" name="addedEntryTitle" rows="2" placeholder="Title" style="resize:vertical"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group ">
                                            <label for="school_year">Author 1</label>
                                            <div class="">
                                                <input class="form-control" id="catalogingAEAuthor1" type="text" placeholder="Author 1" name="aeauthor1" required>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Author 2</label>
                                            <div class="">
                                                <input class="form-control" id="catalogingAEAuthor2" type="text" placeholder="Author 2" name="aeauthor2" required>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Author 3</label>
                                            <div class="">
                                                <input class="form-control" id="catalogingAEAuthor3" type="text" placeholder="Author 3" name="aeauthor3" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary" >
                <div class="panel-heading" data-toggle="collapse"  href="#materialRecordTab">
                    <h3 class="panel-title">
                        Record Information
                    </h3>
                </div>
                <div id="materialRecordTab" class="panel-collapse collapse out" >
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class='alert alert-warning' id='materialMessage' style='text-align:center;display:none'></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group ">
                                            <label for="school_year">Location</label>
                                            <div class="">
                                                <input class="form-control"  id="catalogingLocation" type="text" placeholder="Location" name="location" required>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Quantity</label>
                                            <div class="">
                                                <input class="form-control" id="catalogingQuantity" type="number" placeholder="Quantity" name="quantity">
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Library Use</label>
                                            <div class="">
                                                <select class="form-control"  id="catalogingLibraryMaterialAccessControl" name="materialAccessControl" placeholder="Library Use" required><option>None</option></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group ">
                                            <label for="school_year">Date Acquired</label>
                                            <div class="">
                                                <input class="form-control" id="catalogingDateAcquired" type="date" placeholder="Date Acquired" name="dateAcquired">
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="school_year">Supplier</label>
                                            <div class="">
                                                <input class="form-control" id="catalogingSupplier" type="text" placeholder="Supplier" name="supplier">
                                            </div>
                                        </div>
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
<div class="col-md-10 col-md-offset-1">
    <br>
    <button class="btn btn-warning pull-right" id="catalogingCloseFormButton" type="button" style="margin-right:10px;display:none">Close</button>&nbsp;
    <button class="btn btn-success pull-right" id="catalogingSubmitFormButton" type="submit" style="margin-right:10px;display:none">Submit</button>&nbsp;
    <button class="btn btn-success pull-right" id="catalogingCreateFormButton" type="button" style="margin-right:10px;display:none">Create</button>
</div>
<!--###### Student Listing--->
<div class="row" >
    <!--##### New Student ####--->

    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                   <!--
                    <th>
                        <input id="catalogingListFilterAccessionNumber" class="form-control" type="text" placeholder="Accession No.">
                    </th>
                    <th>
                        <input id="catalogingListFilterCallNumber" class="form-control inline" type="text" placeholder="Call No." >
                    </th>
                    <th>
                        <input id="catalogingListFilterTitle" class="form-control" type="text" placeholder="Title">
                    </th>
                    <th>
                        <input id="catalogingListFilterAuthor" class="form-control" type="text" placeholder="Author">
                    </th>
                    -->
                    <th colspan="2"></th>
                    <th>
                        <input id="catalogingListFilterInput" class="form-control" type="text" placeholder="Keyword">
                    </th>
                    <th colspan="2">
                        <select class="form-control"  id="catalogingListFilterSelect" name="catalogingListFilterSelect">
                            <option>Keyword</option>
                            <option>Title</option>
                            <option>Author</option>
                            <option>Subject</option>
                            <option>Call No.</option>
                            <option>ISBN</option>
                            <option>Notes</option>
                        </select>
                    </th>
                    <th>
                        <button id="catalogingListFilterSearch" data-loading-text="Searching..." class="btn btn-primary">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                        </button>
                    </th>
                </tr>
                <tr>
<!--
                    <th class="catalogingListSorter" sorter_name="accession_number" sort="0" style="width:15%">Acc. No.
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
-->
                    <th class="catalogingListSorter" sorter_name="call_number" sort="0" style="width:15%">Call No.
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th colspan="2" class="catalogingListSorter" sorter_name="title" sort="0" width="54%">Title
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="catalogingListSorter" sorter_name="author" sort="0">Author
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="catalogingListSorter" sorter_name="copyright" sort="0">Year
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="catalogingListSorter" sorter_name="loaned" sort="0">Status
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <!--<th style="width:15%">Action</th>-->
                </tr>
            </thead>
            <tbody id="catalogingListBody">
            </tbody>
            <tfoot>
                <tr>
                    <td style="font-size:13px;font-weight:bold">
                        <span id="catalogingListTotalResult">0</span> Result(s)
                    </td>

                    <td colspan="4" style="text-align:center;" >
                        <button class="btn" id="catalogingListPreviousPage">Previous</button>
                        <button id="catalogingListNextPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right">
                        Page <span id="catalogingListCurrentPage">1</span>
                        of <span id="catalogingListTotalPage">1</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none;">
            <table>
                <tr class="catalogingListRow" assessment_type_id="0">
<!--                    <td class="catalogingListAccessionNumber"></td>-->
                    <td class="catalogingListCallNumber"></td>
                    <td colspan="2" class="catalogingListTitle"></td>
                    <td class="catalogingListAuthor"></td>
                    <td class="catalogingListYear"></td>
                    <td class="catalogingListStatus"></td>
                    <!--
                    <td>
                        <button class="btn-primary  actionButton catalogingListViewButton" type="button">View</button>
                        <button class="btn btn-xs btn-info  actionButton catalogingListViewButton" type="button">View</button>
                        <button class="btn btn-xs btn-danger  actionButton catalogingListRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton catalogingListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton catalogingListNoRemoveButton"  type="button">No</button>
                    </td>
                    -->
                </tr>
            </table>
        </div>
    </div>
</div>

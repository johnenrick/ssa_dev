<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Student Yearly Fee</h1>

<div id="tuitionFeeManagementDiv" class="row" style="display:none">
    <div class="col-md-12">
        <div class="panel panel-primary" >
            <div class="panel-heading" >
                <h3 class="panel-title">
                    Fee
                </h3>
            </div>
            <div class="panel-body">
                <form id="tuitionFeeManagementForm" method="POST">
                    <input id="tuitionFeeManagementID" type="hidden" name="ID" value="0">
                    <div class="row">
                        <div class="col-md-12" >
                            <div class='alert hide' id='tuitionFeeManagementMessage' style='text-align:center'></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="ccol-md-6 col-lg-6">
                            <div class="form-group ">
                                <label for="school_year">School Year</label>
                                <div class="form-inline">
                                    <select class="form-control" id="tuitionFeeManagementSchoolYearFilter" type="text" placeholder="School Year" name="academic_year"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="ccol-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="course_ID">Course & Year</label>
                                <div class="form-inline">   
                                    <select id="tuitionFeeManagementCourseFilter" class="form-control " type="text" placeholder="Course" name="course_ID"></select>
                                    <select id="tuitionFeeManagementYearLevelFilter" class="form-control" type="text" placeholder="Year Level" name="year_level">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group ">
                                
                                        <label for="school_year">Assessment Type</label>
                                        <div class="">
                                            <select class="form-control" id="tuitionFeeManagementAssessmentType" type="text" placeholder="School Year"></select>
                                        </div>
                            </div>
                            <div class="form-group ">
                                <label for="school_year">Assessment Item</label>
                                <div class="">
                                    <select class="form-control" id="tuitionFeeManagementAssessmentItem" type="text" placeholder="School Year" name="assessment_item_ID"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="course_ID">Application</label>
                                        <div class="form-inline">  
                                            <select id="tuitionFeeManagementType" class="form-control " type="text" placeholder="Course" name="type">
                                                <option value="1">All</option>
                                                <option value="2">Selected</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="school_year">To Teller</label>
                                        <div class="form-inline">
                                            <select class="form-control" id="tuitionFeeManagementTellering" type="text" name="tellering">
                                                <option value="2">No</option>
                                                <option value="1">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="school_year">Description</label>
                                <div class="">
                                    <input class="form-control"  id="tuitionFeeManagementDescription" type="text" placeholder="Description" name="description">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="school_year">Amount</label>
                                <div class="">
                                    <input class="form-control" id="tuitionFeeManagementAmount" type="text" placeholder="Amount" name="amount">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <br>
    <button class="btn btn-warning pull-right" id="tuitionFeeManagementCloseFormButton" type="button" style="margin-right:10px;display:none">Close</button>&nbsp;
	
    <button class="btn btn-success pull-right" id="tuitionFeeManagementSubmitFormButton" type="submit" style="margin-right:10px;display:none">Submit</button>&nbsp;
	<button class="btn btn-primary pull-right" id="tuitionFeeManagementSaveAsFormButton" type="submit" style="margin-right:10px;display:none">Save As <span> </span></button>&nbsp;
    <button class="btn btn-success pull-right" id="tuitionFeeManagementCreateFormButton" type="button" style="margin-right:10px">Create</button>
</div>
<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend>Fee List</legend>
    </div>
    <!--##### New Student ####--->

	<div class="col-md-5">
			
			<div class="form-inline">
				<label for="school_year">Academic Year: </label>
				<select class="form-control" id="tuitionFeeManagementTuitionFeeListFilterSchoolYearFilter" type="text" placeholder="School Year" name="academic_year"></select>
			</div>
	</div>
    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="tuitionFeeManagementTuitionFeeListSorter" sorter_name="course_year_level" sort="0" style="width:18%">Course/Year
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="tuitionFeeManagementTuitionFeeListSorter" sorter_name="description" sort="0">Description
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="tuitionFeeManagementTuitionFeeListSorter" sorter_name="assessment_item_description" sort="0">Assessment Item
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="tuitionFeeManagementTuitionFeeListSorter" sorter_name="type" sort="0" style="width:15%">Type
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="tuitionFeeManagementTuitionFeeListSorter" sorter_name="amount" sort="0" style="width:15%">Amount
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th style="width:20%">Action</th>
                </tr>
                <tr>
                    <td>
						
                        <select id="tuitionFeeManagementTuitionFeeListFilterCourseLevel" class="form-control" type="text"></select>
                    </td>
                    <td>
                        <input id="tuitionFeeManagementTuitionFeeListFilterDescription" class="form-control" type="text" placeholder="Description">
                    </td>
                    <td>
                        <input id="tuitionFeeManagementTuitionFeeListFilterAssessmentItem" class="form-control" type="text" placeholder="Assessment Item">
                    </td>
                    <td>
                        <select id="tuitionFeeManagementTuitionFeeListFilterType" class="form-control" type="text">
                            <option value="">None</option>
                            <option value="1">All</option>
                            <option value="2">Selected</option>
                        </select>
                    </td>
                    <td>
                        <input id="tuitionFeeManagementTuitionFeeListAmount" class="form-control inline" type="text" placeholder="Amount" >
                    </td>
                    <td>
                        <button id="tuitionFeeManagementTuitionFeeListFilterSearch" data-loading-text="Searching..." class="btn btn-primary">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                        </button>
                    </td>
                </tr>
            </thead>
            <tbody id="tuitionFeeManagementTuitionFeeListBody">
            </tbody>
            <tfoot>
                <tr>
                    <td style="font-size:13px;font-weight:bold"><span id="tuitionFeeManagementTuitionFeeListTotalResult">0</span> Result(s)
                    </td>

                    <td style="text-align:center;" colspan="3">
                        <button class="btn" id="tuitionFeeManagementTuitionFeeListPreviousPage">Previous</button>
                        <button id="tuitionFeeManagementTuitionFeeListNextPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right">
                        Page <span id="tuitionFeeManagementTuitionFeeListCurrentPage">1</span>
                        of <span id="tuitionFeeManagementTuitionFeeListTotalPage">1</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none;">
            <table>
                <tr class="tuitionFeeManagementTuitionFeeListRow" class_section_id="0">

                    <td class="tuitionFeeManagementTuitionFeeListCourseYear"></td>
                    <td class="tuitionFeeManagementTuitionFeeListDescription"></td>
                    <td class="tuitionFeeManagementTuitionFeeListAssessmentItem"></td>
                    <td class="tuitionFeeManagementTuitionFeeListAssessmentType"></td>
                    <td class="tuitionFeeManagementTuitionFeeListAmount" style="text-align:right"></td>
                    <td>
                        <!--<button class="btn-primary  actionButton tuitionFeeManagementTuitionFeeListViewButton" type="button">View</button>-->
                        <button class="btn btn-xs btn-info  actionButton tuitionFeeManagementTuitionFeeListViewButton" type="button">View</button>
                        <button class="btn btn-xs btn-danger  actionButton tuitionFeeManagementTuitionFeeListRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton tuitionFeeManagementTuitionFeeListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton tuitionFeeManagementTuitionFeeListNoRemoveButton"  type="button">No</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

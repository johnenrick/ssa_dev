<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Section Management</h1>
<!--##### Section Information ####--->
<div class="col-md-5 ">
    <div class="form-inline">
        <label for="school_year">Academic Year</label>
        <select class="form-control sectionManagementFilter" id="sectionManagementAcademicYearFilter" type="text" placeholder="Academic Year" name="school_year"></select>
    </div>
</div>
<div class="row addsection"  id="sectionManagementDiv" style="display:none">
    <div class="span8 offset1" >
        <div class='alert hide' id='sectionManagementMessage' style='text-align:center'></div>
    </div>
    <div class="span8 offset1">
        <legend>Section Information</legend>
    </div>
    
    <form id="sectionManagementForm" action="<?=api_url()?>c_section/createSection" method="POST">
        <div class="span4 offset1">
            <div class="form-group">
                <label for="description">Description</label>
                <input id="sectionManagementID" type="hidden" name="ID" >
                <input class="form-control" id="sectionManagementDescription" type="text" placeholder="Description" name="description">
                <input class="form-control" id="sectionManagementAcademicYear" type="hidden" name="academic_year">
            </div>
            <div class="form-group">
                <label for="course_ID">Course & Year</label>
                <div class="form-inline">
                    <select id="sectionManagementCourse" class="form-control" type="text" placeholder="Course" name="course_ID">
                        <option value="1" default>Grade</option>
                    </select>
                    <input id="sectionManagementYearLevel" class="form-control" type="text" placeholder="Year Level" name="year_level">
                </div>
            </div>

        </div>
    </form>
</div>
<div class="span8 offset1">
    <br>
    <button class="btn btn-warning pull-right" id="sectionManagementCloseFormButton" type="button" style="margin-right:10px;display:none">Close</button>&nbsp;
    <button class="btn btn-success pull-right" id="sectionManagementSubmitFormButton" type="submit" style="margin-right:10px;display:none">Submit</button>&nbsp;
    <button class="btn btn-success pull-right" id="sectionManagementCreateFormButton" type="button" style="margin-right:10px">Create</button>

</div>

<!--###### Student Listing--->
<div class="row" >
    <div class="span8 offset1">
        <legend>Section List</legend>
    </div>
    <div class="span8 offset1">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Year Level</th>
                    <th>Description</th>
                    <th style="width: 30%">Adviser</th>

                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="sectionManagementListBody">


            </tbody>
            <tfoot>
                <tr>
                    <td>
                    </td>

                    <td style="text-align:right;">
                        <button class="btn" id="sectionManagementListPreviousPage">Previous</button>
                        <button id="sectionManagementListNextPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right">
                        Page <span id="sectionManagementListCurrentPage">1</span> of <span id="sectionManagementListTotalPage">0</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none">
            <table>
                <tr class="sectionManagementListRow" account_id="0">

                    <td class="sectionManagementListYearLevel"></td>
                    <td class="sectionManagementListDescriprion"></td>
                    <td class="">
                        <select class="form-control sectionManagementListAdviser" type="text" placeholder="Teacher" name="teacher_ID"></select>
                    </td>
                    <td class="">
                        <button class="btn btn-xs btn-info  actionButton sectionManagementListViewButton" type="button">View</button>
                        <button class="btn btn-xs btn-danger  actionButton sectionManagementListRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton sectionManagementListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton sectionManagementListNoRemoveButton"  type="button">No</button>
                        <span class="confirmChangeButton" style="font-weight:bold; display: none" type="button">Save ?</span>
                        <button class="btn btn-xs btn-info x confirmChangeButton sectionManagementAdviserListYesChangeButton" style="display: none" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmChangeButton sectionManagementAdviserLtistNoRemoveButton"  style="display: none" type="button">No</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Club Management</h1>

<div class="row addsection">
    <div class="col-md-10 col-md-offset-1" >
        <div class='alert hide' id='clubManagementMessage' style='text-align:center'></div>
    </div>
    <div class="col-md-10 col-md-offset-1">
        <legend>Club List</legend>
    </div>
    <div class="col-md-5 col-md-offset-1">
        <div class="form-group">
            <label for="school_year">School Year</label>
            <select class="form-control clubManagementFilter" id="clubManagementSchoolYearFilter" type="text" placeholder="School Year" name="school_year"></select>
        </div>
        <!--<div class="form-group">
            <label for="course_ID">Course & Year</label>
            <div class="form-inline">
                <select id="clubManagementCourseFilter" class="form-control clubManagementFilter " type="text" placeholder="Course" name="course_ID"></select>
                <select id="clubManagementYearLevelFilter" class="form-control clubManagementFilter" type="text" placeholder="Year Level" name="year_level">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
            </div>
        </div>-->
        <div class="form-group">
            <label for="subject_ID">Club</label>
            <select class="form-control clubManagementFilter" id="clubManagementSectionFilter" type="text" placeholder="club" name="subject_ID"></select>
        </div>
    </div>

</div>
<div class="col-md-10 col-md-offset-1">

</div>
<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-10 col-md-offset-1">
        <legend>Student List</legend>
    </div>
    <!--##### New Student ####--->

    <div class="col-md-10 col-md-offset-1">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID Number</th>
                    <th>Name</th>
                    <th>Club</th>
                    <th>Action</th>
                </tr>
                <!--<tr>
                    <td><input type="text" placeholder="ID Number" class="form-control" id="clubManagementFilterID"></td>
                    <td><input type="text" placeholder="Name" class="form-control" id="clubManagementFilterName"></td>
                    <td></td>
                    <td>
                        <button id="clubManagementFilterSearch" data-loading-text="Searching..." class="btn btn-primary">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                        </button>
                    </td>
                </tr>-->
            </thead>
            <tbody id="clubManagementStudentListBody">


            </tbody>
            <tfoot>
                <tr>
                    <td style="font-size:13px;font-weight:bold"><span id="clubManagementStudentListTotalResult">0</span> Result(s)
                    </td>

                    <td style="text-align:center;" colspan="2">
                        <button class="btn" id="clubManagementStudentListPreviousPage">Previous</button>
                        <button id="clubManagementStudentListNextPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right">
                        Page <span id="clubManagementStudentListCurrentPage">1</span>
                        of <span id="clubManagementStudentListTotalPage">0</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none;">
            <table>
                <tr class="clubManagementStudentListRow" class_section_id="0">

                    <td class="clubManagementStudentListIDNumber"></td>
                    <td class="clubManagementStudentListName"></td>
                    <td class="">
                        <select class="form-control input-sm clubManagementStudentListSection" type="text" placeholder="section" name="section_ID">
                        </select>
                    </td>
                    <td>
                        <!--<button class="btn-primary  actionButton clubManagementStudentListViewButton" type="button">View</button>-->
                        <button class="btn btn-xs btn-danger  actionButton clubManagementStudentListRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton clubManagementStudentListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton clubManagementStudentListNoRemoveButton"  type="button">No</button>
                        <span class="confirmChangeButton" style="font-weight:bold" type="button">Save ?</span>
                        <button class="btn btn-xs btn-info x confirmChangeButton clubManagementStudentListYesChangeButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmChangeButton clubManagementStudentListNoRemoveButton"  type="button">No</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

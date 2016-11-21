<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<script src="<?=load_asset()?>js/print.js"></script>
<h1>Class Section Management</h1>

<div class="row">
    <div class="col-md-10 col-md-offset-1" >
        <div class='alert hide' id='classSectionManagementMessage' style='text-align:center'></div>
    </div>
    <div class="col-md-10 col-md-offset-1">
        <legend>Class Section</legend>
    </div>
    <div class="col-md-5 col-md-offset-1">
        <div class="form-group">
            <label for="school_year">School Year</label>
            <select class="form-control classSectionManagementFilter" id="classSectionManagementSchoolYearFilter" type="text" placeholder="School Year" name="school_year"></select>
        </div>
        <div class="form-group">
            <label for="course_ID">Course & Year</label>
            <div class="form-inline">
                <select id="classSectionManagementCourseFilter" class="form-control classSectionManagementFilter " type="text" placeholder="Course" name="course_ID">
                   
                </select>
                <select id="classSectionManagementYearLevelFilter" class="form-control classSectionManagementFilter" type="text" placeholder="Year Level" name="year_level">
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
            <select class="form-control classSectionManagementFilter" id="classSectionManagementSectionFilter" type="text" placeholder="section" name="section_ID"></select>
        </div>
    </div>

</div>
<div class="col-md-10 col-md-offset-1">

</div>
<!--###### Student Listing-->
<div class="row" >
    <div role="tabpanel" class="col-md-10 col-md-offset-1" style="margin-top: 20px;">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#classSectionManagementStudentList" aria-controls="home" role="tab" data-toggle="tab" style="font-size: 21px; color: black">Student List</a></li>
            <li role="presentation"><a href="#classSectionManagementScheduleList" aria-controls="profile" role="tab" data-toggle="tab" style="font-size: 21px; color: black">Schedule List</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="classSectionManagementStudentList">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Number</th>
                            <th>Name</th>
                            <th>Section</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>
                                <input id="classSectionManagementStudentListFilterName" class="form-control">
                            </th>
                            <th><button id="classSectionManagementStudentListSearch" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search</button></th>
                            <th>
                                <button id="classSectionManagementStudentListPrint" class="btn btn-primary"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="classSectionManagementStudentListBody">


                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="font-size:13px;font-weight:bold"><span id="classSectionManagementStudentListTotalResult">0</span> Result(s)
                            </td>

                            <td style="text-align:center;" colspan="2">
                                <button class="btn" id="classSectionManagementStudentListPreviousPage">Previous</button>
                                <button id="classSectionManagementStudentListNextPage" class="btn">Next</button>
                            </td>
                            <td style="text-align:right">
                                Page <span id="classSectionManagementStudentListCurrentPage">1</span>
                                of <span id="classSectionManagementStudentListTotalPage">0</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="classSectionManagementScheduleList">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Schedule</th>
                            <th>Room</th>

                            <th>Section</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="classScheduleManagementListBody">


                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                            </td>

                            <td style="text-align:right;">
                                <button class="btn" id="classScheduleManagementListPreviousPage">Previous</button>
                                <button id="classScheduleManagementListNextPage" class="btn">Next</button>
                            </td>
                            <td style="text-align:right">
                                Page <span id="classScheduleManagementListCurrentPage">1</span> of <span id="classScheduleManagementListTotalPage">0</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="classSectionReportCard">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Report Card</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-2" >
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label>ID NO. :</label>
                                            <span id="classSectionReportCardStudentIdentificationNumber" >123213</span>
                                        </div>
                                    </form>
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label>NAME :</label>
                                            <span id="classSectionReportCardStudentName" >123213</span>
                                        </div>
                                    </form>
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label>YEAR & SECTION :</label>
                                            <span id="classSectionReportCardStudentYearAndSection" >123213</span>
                                        </div>
                                    </form>
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label>CURRICULUM :</label>
                                            <span id="classSectionReportCardStudentCurriculum" >S.E.C</span>
                                        </div>
                                    </form>
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label>ADDRESS :</label>
                                            <span id="classSectionReportCardStudentAddress" >123213</span>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-5">
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label>AGE:</label>
                                            <span id="classSectionReportCardStudentAge" >123213</span>
                                        </div>
                                    </form>
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                        </div>
                                    </form>
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label>SCHOOL YEAR:</label>
                                            <span id="classSectionReportCardStudentSchoolYear" >123213</span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <table class="table">
                                            <thead >
                                                <tr >
                                                    <th rowspan="2" style="vertical-align: middle;text-align: center">Learning Areas</th>
                                                    <th colspan="4" style="vertical-align: middle;text-align: center">Quarter</th>
                                                    <th rowspan="2" style="vertical-align: middle;text-align: center">Final Grade</th>
                                                    <th rowspan="2" style="vertical-align: middle;text-align: center">Remarks</th>
                                                </tr>
                                                <tr>
                                                    <th style="vertical-align: middle;text-align: center">1</th>
                                                    <th style="vertical-align: middle;text-align: center">2</th>
                                                    <th style="vertical-align: middle;text-align: center">3</th>
                                                    <th style="vertical-align: middle;text-align: center">4</th>
                                                </tr>
                                            </thead>
                                            <tbody id="classSectionReportCardGradeTableBody" class="classSectionReportCardGradeTableBodyClass">
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="6" style="text-align:right;font-weight: bold">GEN. AVE</td>
                                                    <td id="classSectionReportCardGradeGeneralAverage"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <br>
                                    </div>
                                    
                                </div>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <table class="table">
                                            <thead >
                                                <tr>
                                                    <th > </th>
                                                    <th >Jun</th>
                                                    <th >Jul</th>
                                                    <th >Aug</th>
                                                    <th >Sep</th>
                                                    <th >Oct</th>
                                                    <th >Nov</th>
                                                    <th >Dec</th>
                                                    <th >Jan</th>
                                                    <th >Feb</th>
                                                    <th >Mar</th>
                                                    <th >Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="classSectionReportCardAttendanceTableBody">
                                                <tr>
                                                    <td>Days of School</td>
                                                    <td class="classSectionReportCardAttendanceSchoolDayJune">0</td>
                                                    <td class="classSectionReportCardAttendanceSchoolDayJuly">0</td>
                                                    <td class="classSectionReportCardAttendanceSchoolDayAugust">0</td>
                                                    <td class="classSectionReportCardAttendanceSchoolDaySeptember">0</td>
                                                    <td class="classSectionReportCardAttendanceSchoolDayOctober">0</td>
                                                    <td class="classSectionReportCardAttendanceSchoolDayNovember">0</td>
                                                    <td class="classSectionReportCardAttendanceSchoolDayDecember">0</td>
                                                    <td class="classSectionReportCardAttendanceSchoolDayJanuary">0</td>
                                                    <td class="classSectionReportCardAttendanceSchoolDayFebruary">0</td>
                                                    <td class="classSectionReportCardAttendanceSchoolDayMarch">0</td>
                                                    <td class="classSectionReportCardAttendanceSchoolDayTotal">0</td>
                                                </tr>
                                                <tr>
                                                    <td>Days Present</td>
                                                    <td class="classSectionReportCardAttendanceDayPresent-6"></td>
                                                    <td class="classSectionReportCardAttendanceDayPresent-7"></td>
                                                    <td class="classSectionReportCardAttendanceDayPresent-8"></td>
                                                    <td class="classSectionReportCardAttendanceDayPresent-9"></td>
                                                    <td class="classSectionReportCardAttendanceDayPresent-10"></td>
                                                    <td class="classSectionReportCardAttendanceDayPresent-11"></td>
                                                    <td class="classSectionReportCardAttendanceDayPresent-12"></td>
                                                    <td class="classSectionReportCardAttendanceDayPresent-1"></td>
                                                    <td class="classSectionReportCardAttendanceDayPresent-2"></td>
                                                    <td class="classSectionReportCardAttendanceDayPresent-3"></td>
                                                    <td class="classSectionReportCardAttendanceDayPresentTotal"></td>
                                                </tr>
                                                <tr>
                                                    <td>Days Tardy</td>
                                                    <td class="classSectionReportCardAttendanceDayTardy-6"></td>
                                                    <td class="classSectionReportCardAttendanceDayTardy-7"></td>
                                                    <td class="classSectionReportCardAttendanceDayTardy-8"></td>
                                                    <td class="classSectionReportCardAttendanceDayTardy-9"></td>
                                                    <td class="classSectionReportCardAttendanceDayTardy-10"></td>
                                                    <td class="classSectionReportCardAttendanceDayTardy-11"></td>
                                                    <td class="classSectionReportCardAttendanceDayTardy-12"></td>
                                                    <td class="classSectionReportCardAttendanceDayTardy-1"></td>
                                                    <td class="classSectionReportCardAttendanceDayTardy-2"></td>
                                                    <td class="classSectionReportCardAttendanceDayTardy-3"></td>
                                                    <td class="classSectionReportCardAttendanceDayTardyTotal"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-12' style="text-align:center">
                                        <br>Performance and demonstration of attitudes, habits and skills are rated as follows : <br><br>
                                        <span style="font-weight:bold">
                                            A+ = 90-99 &nbsp;&nbsp;&nbsp; B- = 75-79  &nbsp;&nbsp;&nbsp; A- = 85-89 &nbsp;&nbsp;&nbsp; C = 70-74 &nbsp;&nbsp;&nbsp; B+ = 80-84
                                        </span>
                                        <br><br>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <table class="table">
                                            <thead >
                                                <tr>
                                                    <th rowspan="2" style="vertical-align: middle;text-align: center">Criteria</th>
                                                    <th colspan="4" style="vertical-align: middle;text-align: center">Periodic Rating</th>
                                                </tr>
                                                <tr>
                                                    <th style="vertical-align: middle;text-align: center">1</th>
                                                    <th style="vertical-align: middle;text-align: center">2</th>
                                                    <th style="vertical-align: middle;text-align: center">3</th>
                                                    <th style="vertical-align: middle;text-align: center">4</th>
                                                </tr>
                                            </thead>
                                            <tbody id="classSectionReportCardDeportmentTableBody">
                                            </tbody>
                                        </table>
                                        <br>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-12' style="text-align:center">
                                        <table style="margin-left:auto;margin-right:auto">
                                            <thead >
                                                <tr>
                                                    <th>Descriptors&nbsp;</th>
                                                    <th>Grading Scale&nbsp;&nbsp;</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Outstanding&nbsp;</td>
                                                    <td>90-100</td>
                                                    <td>Passed</td>
                                                </tr>
                                                <tr>
                                                    <td>Very Satisfactory&nbsp;</td>
                                                    <td>85-89</td>
                                                    <td>Passed</td>
                                                </tr>
                                                <tr>
                                                    <td>Satisfactory&nbsp;</td>
                                                    <td>80-84</td>
                                                    <td>Passed</td>
                                                </tr>
                                                <tr>
                                                    <td>Fairly Satisfactory&nbsp;</td>
                                                    <td>75-79</td>
                                                    <td>Passed</td>
                                                </tr>
                                                <tr>
                                                    <td>Did not Meet Expectation&nbsp;&nbsp;</td>
                                                    <td>Below 75</td>
                                                    <td>Failed</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div id="classSectionManagementStudentListPrintClassList" style='display:none'>
        <style>
            div {
                font-size: 12px;
                text-transform: uppercase;
            }
        </style>
        <div style="text-align: center">
            Saint Scholastica's Academy<br>
            Grade <span id="classSectionManagementStudentListPrintGrade"></span> Class List<br>
            <br>
            <span id="classSectionManagementStudentListPrintSection">St Testing</span>
        </div>
        <br>
        <div id="classSectionManagementStudentListPrintMaleList" style="width:50%;float:left">
            <table>
                <thead>
                    <tr><th colspan="2" style="text-align: center">Boys</th></tr>
                </thead>
                <tbody>
                    <tr><td></td></tr>
                </tbody>
            </table>
        </div>
        <div id="classSectionManagementStudentListPrintFemaleList" style="width:50%;float:left">
            <table>
                <thead>
                    <tr><th colspan="2" style="text-align: center">Girls</th></tr>
                </thead>
                <tbody>
                    <tr><td></td></tr>
                </tbody>
            </table>
        </div>
        <br>
        <br>
        <div style="width:100%;padding-top:17cm;">
            <br>
            <br>
            <div style="padding-left:2cm">
                ADVISER : <span id="classSectionManagementStudentListPrintAdviser" style="font-weight: bold"></span>
            </div>
            <br>
            <br>
            <div style="text-align:right; padding-right:2cm">
                <span id="" style="font-weight: bold">Miss Lea B. Rabaya</span>
            </div>
        </div>
    </div>
    <div class="col-md-10 col-md-offset-1">
        <div class="prototype" style="display:none;">
            <table>
                <tr class="classSectionManagementStudentListRow" class_section_id="0">

                    <td class="classSectionManagementStudentListIDNumber"></td>
                    <td class="classSectionManagementStudentListName"></td>
                    <td class="">
                        <select class="form-control input-sm classSectionManagementStudentListSection" type="text" placeholder="section" name="section_ID">
                        </select>
                    </td>
                    <td>
                        <!--<button class="btn-primary  actionButton classSectionManagementStudentListViewButton" type="button">View</button>-->
                        <button class="btn btn-xs btn-primary  classSectionManagementStudentListViewGradeButton" type="button">View Grade</button>
                        <button class="btn btn-xs btn-danger  actionButton classSectionManagementStudentListRemoveButton" type="button"><span class="glyphicon glyphicon-trash"></span></button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton classSectionManagementStudentListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton classSectionManagementStudentListNoRemoveButton"  type="button">No</button>
                        <span class="confirmChangeButton" style="font-weight:bold" type="button">Save ?</span>
                        <button class="btn btn-xs btn-info x confirmChangeButton classSectionManagementStudentListYesChangeButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmChangeButton classSectionManagementStudentListNoRemoveButton"  type="button">No</button>
                        
                        
                    </td>
                </tr>
                <tr class="classSectionReportCardGradeTableRow">
                    <td style="text-align: center;" class="classSectionReportCardGradeTableLearningAreas"></td>

                    <td style="text-align: center;" class="classSectionReportCardGradeTableFirstQuarter"></td>
                    <td style="text-align: center;" class="classSectionReportCardGradeTableSecondQuarter"></td>
                    <td style="text-align: center;" class="classSectionReportCardGradeTableThirdQuarter"></td>
                    <td style="text-align: center;" class="classSectionReportCardGradeTableFourthQuarter"></td>

                    <td style="text-align: center;" class="classSectionReportCardGradeTableFinalGrade">-</td>
                    <td style="text-align: center;" class="classSectionReportCardGradeTableRemarks">-</td>
                </tr>
                <tr class="classSectionReportCardDeportmentTableRow" style="text-align:center">
                    <td class="classSectionReportCardDeportmentTableCriteria"></td>
                    <td class="classSectionReportCardDeportmentTableFirstPeriodicRating"></td>
                    <td class="classSectionReportCardDeportmentTableSecondPeriodicRating"></td>
                    <td class="classSectionReportCardDeportmentTableThirdPeriodicRating"></td>
                    <td class="classSectionReportCardDeportmentTableFourthPeriodicRating"></td>
                </tr>
            </table>
        </div>
        
        <div class="prototype-schedule" style="display:none">
            <table>
                <tr class="classScheduleManagementListRow" account_id="0">
                    
                    <td class="classScheduleManagementListDescriprion"></td>
                    <td class="classScheduleManagementListSchedule"></td>
                    <td class="classScheduleManagementListRoom"></td>
                    <td class="">
                        <select class="form-control input-sm classSectionManagementStudentListSection" id="classScheduleManagementSectionList" type="text" placeholder="section" name="section_ID">
                        </select>
                    </td>
                    <td>
                        <!--<button class="btn-primary  actionButton classScheduleManagementStudentListViewButton" type="button">View</button>-->
                        <span class="confirmChangeButton" style="font-weight:bold; display: none" type="button">Save ?</span>
                        <button class="btn btn-xs btn-info x confirmChangeButton classScheduleManagementStudentListYesChangeButton" style="display: none" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmChangeButton classScheduleManagementStudentListNoRemoveButton" style="display: none" type="button">No</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

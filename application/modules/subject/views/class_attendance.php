<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Sections</h1>

<div class="row addsection">
    <div class="col-md-10 col-md-offset-1">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Year Level</th>
                    <th>Section</th>

                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="classAttendanceBody">


            </tbody>
        </table>

        <div class="prototype-section" style="display:none">
            <table>
                <tr class="classAttendanceRow">
                    <td class="classAttendanceGrade"></td>
                    <td class="classAttendanceSection"></td>

                    <td class="">
                        <button class="btn btn-xs btn-info  actionButton classAttendanceViewButton" type="button">View Class List</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="col-md-12">

</div>
<!--###### Student Listing-->
<div class="row" id="studentListclassAttendance" style="display: none; margin-top: 4%">
    <div class="col-md-12" style="padding: 0">
        <legend>Student List</legend>
    </div>
    <!--##### New Student ####-->

    <div class="col-md-12" style="padding: 0">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="4" rowspan="2" id="classAttendanceGradeSectionName" style="text-align: center; vertical-align: middle;"></th>
                    <th colspan="2" style="text-align: center;">JUN</th>
                    <th colspan="2" style="text-align: center;">JUL</th>
                    <th colspan="2" style="text-align: center;">AUG</th>
                    <th colspan="2" style="text-align: center;">SEPT</th>
                    <th colspan="2" style="text-align: center;">OCT</th>
                    <th colspan="2" style="text-align: center;">NOV</th>
                    <th colspan="2" style="text-align: center;">DEC</th>
                    <th colspan="2" style="text-align: center;">JAN</th>
                    <th colspan="2" style="text-align: center;">FEB</th>
                    <th colspan="2" style="text-align: center;">MAR</th>
                </tr>
                <tr>
                    <th style="text-align: center;">P</th>
                    <th style="text-align: center;">L</th>
                    <th style="text-align: center;">P</th>
                    <th style="text-align: center;">L</th>
                    <th style="text-align: center;">P</th>
                    <th style="text-align: center;">L</th>
                    <th style="text-align: center;">P</th>
                    <th style="text-align: center;">L</th>
                    <th style="text-align: center;">P</th>
                    <th style="text-align: center;">L</th>
                    <th style="text-align: center;">P</th>
                    <th style="text-align: center;">L</th>
                    <th style="text-align: center;">P</th>
                    <th style="text-align: center;">L</th>
                    <th style="text-align: center;">P</th>
                    <th style="text-align: center;">L</th>
                    <th style="text-align: center;">P</th>
                    <th style="text-align: center;">L</th>
                    <th style="text-align: center;">P</th>
                    <th style="text-align: center;">L</th>
                </tr>
            </thead>
            <tbody id="classAttendanceStudentListBody">
                
            </tbody>
        </table>
        <div class="prototype-studentlist" style="display:none;">
            <table>
                <tr class="classAttendanceStudentListRow" account_id="0">
                    <td class="classAttendanceStudentListIDNumber"></td>
                    <td class="classAttendanceStudentListName"></td>
                </tr>
                <tr class="classAttendanceStudentRow" account_id="0">
                    <td style='padding: 0px; border-right: white solid 1px;' class='classAttendanceStudentLastName col-md-1'></td>
                    <td colspan='3' style='padding: 0px; border-left: white solid 1px;' class='classAttendanceStudentFirstName col-md-3'></td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='6' attendance="1" />
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='6' attendance="2"/>
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='7' attendance="1" />
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='7' attendance="2"/>
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='8' attendance="1" />
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='8' attendance="2"/>
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='9' attendance="1" />
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='9' attendance="2"/>
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='10' attendance="1" />
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='10' attendance="2"/>
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='11' attendance="1" />
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='11' attendance="2"/>
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='12' attendance="1" />
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='12' attendance="2"/>
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='1' attendance="1" />
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='1' attendance="2"/>
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='2' attendance="1" />
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='2' attendance="2"/>
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='3' attendance="1" />
                    </td>
                    <td style='text-align: center; padding: 0;'>
                        <input student_attendance_id="0" type='text' class='form-control' size=3 style='padding: 0px 2px; height: 25px;' month='3' attendance="2"/>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

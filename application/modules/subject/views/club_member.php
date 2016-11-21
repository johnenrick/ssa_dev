<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<script src="<?php echo load_asset("js/tableAPIPagination.js");?>"></script>
<script src="<?=load_asset()?>js/print.js"></script>
<h1>Club Member Management</h1>

<div class="row">
    <div class="col-sm-6">
        <form class="form-horizontal" role="form">
            <div class="form-group">
                <label class="control-label col-sm-2" for="email">CLUB</label>
                <div class="col-sm-10">
                    <select id="clubMemberClubList" class="form-control">
                        
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-6">
        <form class="form-horizontal" role="form">
            <div class="form-group">
                <label class="control-label col-sm-2" for="email">SCHEDULE</label>
                <div class="col-sm-10">
                    <select id="clubMemberClubScheduleList" class="form-control">
                        
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-12">
        <button id="clubMemberAddMember" class="btn btn-success pull-right" data-toggle="modal" data-target="#clubMemberAddMemberModal" style="display:none">+ Add Member</button>
    </div>
</div>
<div class="row" style="display:none">
    <div class="col-sm-12">
        <h2>Club Member list</h2>
        <table class="table table-hover" id="clubMemberListTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Grade & Section</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot >
            </tfoot>
        </table>
    </div>
    
</div>
<div id="clubMemberAddMemberModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Student to Add</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-hover" id="clubMemberAddListTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th>Grade & Section</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot >
                            </tfoot>
                        </table>
                    </div> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div class="row" >
    
    
    <div id="clubMemberStudentListPrintClassList" style='display:none'>
        <style>
            div {
                font-size: 12px;
                text-transform: uppercase;
            }
        </style>
        <div style="text-align: center">
            Saint Scholastica's Academy<br>
            Grade <span id="clubMemberStudentListPrintGrade"></span> Class List<br>
            <br>
            <span id="clubMemberStudentListPrintSection">St Testing</span>
        </div>
        <br>
        <div id="clubMemberStudentListPrintMaleList" style="width:50%;float:left">
            <table>
                <thead>
                    <tr><th colspan="2" style="text-align: center">Boys</th></tr>
                </thead>
                <tbody>
                    <tr><td></td></tr>
                </tbody>
            </table>
        </div>
        <div id="clubMemberStudentListPrintFemaleList" style="width:50%;float:left">
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
                ADVISER : <span id="clubMemberStudentListPrintAdviser" style="font-weight: bold"></span>
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
                <tr class="clubMemberStudentListRow" class_section_id="0">
                    <td class="clubMemberStudentListIDNumber"></td>
                    <td class="clubMemberStudentListName"></td>
                    <td class="clubMemberStudentListGrade"></td>
                    <td>
                        <button class="clubMemberStudentListAddMember btn btn-xs btn-success"  type="button" style="display:none">Add Student</button>
                        <button class="clubMemberStudentListRemoveMember btn btn-xs btn-danger"  type="button" style="display:none">Remove Member</button>
                        
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
        
        
    </div>
</div>

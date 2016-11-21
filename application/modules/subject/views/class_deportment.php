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
            <tbody id="classDeportmentBody">


            </tbody>
        </table>

        <div class="prototype-section" style="display:none">
            <table>
                <tr class="classDeportmentRow">
                    <td class="classDeportmentGrade"></td>
                    <td class="classDeportmentSection"></td>

                    <td class="">
                        <button class="btn btn-xs btn-info  actionButton classDeportmentViewButton" type="button">Grade Student</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="col-md-12">

</div>
<!--###### Student Listing-->
<div class="row" id="studentListclassDeportment" style="margin-top: 4%;">
    <div role='tabpanel'>
  <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" id="gradingtabs">
            <li role="presentation" class="active grading-tab" grading="1"><a href="#grading1" aria-controls="grading1" role="tab" data-toggle="tab" style="font-size: 21px; color: black">1st Grading</a></li>
            <!--<li role="presentation" class="grading-tab" grading="2"><a href="#grading2" aria-controls="grading2" role="tab" data-toggle="tab" style="font-size: 21px; color: black">2nd Grading</a></li>
            <li role="presentation" class="grading-tab" grading="3"><a href="#grading3" aria-controls="grading3" role="tab" data-toggle="tab" style="font-size: 21px; color: black">3rd Grading</a></li>
            <li role="presentation" class="grading-tab" grading="4"><a href="#grading4" aria-controls="grading4" role="tab" data-toggle="tab" style="font-size: 21px; color: black">4th grading</a></li>-->
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" style="width: 100% !important;">
            <div role="tabpanel" class="tab-pane active" id="grading1" grading="1">
                <table class="table-bordered" style="margin: 0 auto;">
                    <thead id="classDeportmentTitleHeader">
                        <tr id="classDeportmentHeader"></tr>
                    </thead>
                    <tbody id="classDeportmentStudentList-Boys-1" class="classDeportmentStudentList-Boys-Girls"> <!--ENDED AT THIS POINT: ADDED -1 ON THE ID -->
                        
                    </tbody>
                    <tbody id="classDeportmentStudentList-Girls-1" class="classDeportmentStudentList-Boys-Girls"> <!--ENDED AT THIS POINT: ADDED -1 ON THE ID -->

                    </tbody>
                </table>
            </div>
        </div>        
    </div>
    <div class="prototype-studentlist" style="display:none;">
        <table>
            <tr class="classDeportmentStudentListRow" account_id="0">
                <td class="classDeportmentStudentListName"></td>
                <td class="classDeportmentStudentList1"></td>
                <td class="classDeportmentStudentList2"></td>
                <td class="classDeportmentStudentList3"></td>
                <td class="classDeportmentStudentList4"></td>
                <td class="classDeportmentStudentList5"></td>
                <td class="classDeportmentStudentList6"></td>
                <td class="classDeportmentStudentList7"></td>
            </tr>
        </table>
    </div>
</div>

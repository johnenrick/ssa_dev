<script src="<?php echo load_asset("js/tableAPIPagination.js");?>"></script>
<h1>Portal Report</h1>
<div class="row">
    <div class="col-sm-12">
        <table id="studentAttendanceTable" class="table table-hover" >
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Grade</th>
                    <th>Section</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot >
            </tfoot>
        </table>
    </div>
</div>

<div class="prototype" style="display:none">
    <table>
        <tr class="studentAttendanceRow">
            <td class="accountFullName"></td>
            <td class="accountGrade"></td>
            <td class="accountSection"></td>
        </tr>
    </table>
</div>
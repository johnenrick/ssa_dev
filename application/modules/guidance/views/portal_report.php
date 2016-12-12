<script src="<?php echo load_asset("js/tableAPIPagination.js");?>"></script>
<h1>Portal Report</h1>
<div class="row">
    <div class="col-sm-12">
        <table id="portalReportTable" class="table table-hover" >
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date and Time</th>
                    <th>Status</th>
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
        <tr class="portalReportRow">
            <td class="accountFullName"></td>
            <td class="datetime"></td>
            <td class="status"></td>
        </tr>
    </table>
</div>
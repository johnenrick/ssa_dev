<script src="<?php echo load_asset("js/tableAPIPagination.js");?>"></script>
<button id="exportBorrowedMaterial" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-export" aria-hidden="true"></span> Export to Excel</button>
<iframe id="txtArea1" style="display:none"></iframe>
<table class="table table-hover" id="borrowedMaterialTable">
    <thead>
        <tr>
            <th>Book</th>
            <th>Borrower</th>
            <th>Due Date</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot >
    </tfoot>
</table>
<div class="prototype">
    <table>
        <tr class="borrowedMaterialRow" library_circulation_id="0">
            <td class="bookTitle"></td>
            <td class="borrowerFullName"></td>
            <td class="dueDate"></td>
        </tr>
    </table>
    
</div>
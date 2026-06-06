<?php 
	  if (!isset($_SESSION['USERID'])){
      redirect(web_root."admin/index.php");
     } 
?>
<!-- Title Row -->
<style>
.cat-table-wrap {
    background: var(--card-bg, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 14px;
    padding: 20px;
    transition: background 0.25s, border-color 0.25s;
}
/* Dark mode DataTable / Bootstrap table overrides for Categories */
.dark-mode #dash-table_wrapper .dataTables_length label,
.dark-mode #dash-table_wrapper .dataTables_filter label,
.dark-mode #dash-table_wrapper .dataTables_info {
    color: var(--text-muted) !important;
}
.dark-mode #dash-table_wrapper .dataTables_length select,
.dark-mode #dash-table_wrapper .dataTables_filter input {
    background: var(--bg-color) !important;
    border-color: var(--border-color) !important;
    color: var(--text-main) !important;
}
.dark-mode #dash-table thead th {
    background: var(--table-header-bg) !important;
    color: var(--text-muted) !important;
    border-color: var(--border-color) !important;
}
.dark-mode #dash-table tbody tr {
    background: var(--card-bg) !important;
    color: var(--text-main) !important;
}
.dark-mode #dash-table tbody tr:hover {
    background: var(--hover-bg) !important;
}
.dark-mode #dash-table td,
.dark-mode #dash-table th {
    border-color: var(--border-color) !important;
    color: var(--text-main) !important;
}
.dark-mode #dash-table.table-striped > tbody > tr:nth-of-type(odd) {
    background: var(--table-header-bg) !important;
}
</style>
<div class="page-title-row" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:25px;">
    <div>
        <h1 style="font-weight:800; color:var(--primary); font-size:28px; margin:0 0 5px 0;">Categories Management</h1>
        <p style="color:var(--text-muted); margin:0; font-size:14px;">Organize your products into structured, clean categories.</p>
    </div>
    <div style="display:flex; gap:12px;">
        <a href="index.php?view=add" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600; border-radius:8px; padding:8px 16px;">
            <i class="fa fa-plus-circle"></i> New Category
        </a>
    </div>
</div>
	 		    <form action="controller.php?action=delete" Method="POST">  	
			     <div class="table-responsive">					
				<table id="dash-table" class="table table-striped table-bordered table-hover"  style="font-size:12px" cellspacing="0">
				
				  <thead>
				  	<tr>
				  		<!-- <th>No.</th> -->
				  		<th>
				  		 <!-- <input type="checkbox" name="chkall" id="chkall" onclick="return checkall('selector[]');">  -->
				  		 Category</th> 
				  		 <th width="10%" align="center">Action</th>
				  	</tr>	
				  </thead> 
				  <tbody>
				  	<?php 
				  		$mydb->setQuery("SELECT * FROM `tblcategory`");
				  		$cur = $mydb->loadResultList();

						foreach ($cur as $result) {
				  		echo '<tr>';
				  		// echo '<td width="5%" align="center"></td>';
				  		// echo '<td>
				  		//      <input type="checkbox" name="selector[]" id="selector[]" value="'.$result->CATEGID. '"/>
				  		// 		' . $result->CATEGORIES.'</a></td>';
				  			echo '<td>' . $result->CATEGORIES.'</td>';
				  		echo '<td align="center"><a title="Edit" href="index.php?view=edit&id='.$result->CATEGID.'" class="btn btn-primary btn-xs  ">  <span class="fa fa-edit fw-fa"></a>
				  		     <a title="Delete" href="controller.php?action=delete&id='.$result->CATEGID.'" class="btn btn-danger btn-xs  ">  <span class="fa  fa-trash-o fw-fa "></a></td>';
				  		// echo '<td></td>';
				  		echo '</tr>';
				  	} 
				  	?>
				  </tbody>
					
				</table>
						<div class="btn-group">
				 <!--  <a href="index.php?view=add" class="btn btn-default">New</a> -->
					<?php
					if($_SESSION['U_ROLE']=='Administrator'){
					// echo '<button type="submit" class="btn btn-default" name="delete"><span class="glyphicon glyphicon-trash"></span> Delete Selected</button'
					; }?>
				</div>
			
			
				</form> 
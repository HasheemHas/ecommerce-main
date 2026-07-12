
<div class="container">
	<?php
		 if (!isset($_SESSION['USERID'])){
      redirect(web_root."admin/index.php");
     }

		check_message();
			
		?>

 
<!-- Title Row -->
<style>
.orders-wrap {
    background: var(--card-bg, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 14px;
    padding: 20px;
    overflow: hidden;
    transition: background 0.25s, border-color 0.25s;
}
/* DataTable dark overrides */
.dark-mode #example_wrapper .dataTables_length label,
.dark-mode #example_wrapper .dataTables_filter label,
.dark-mode #example_wrapper .dataTables_info {
    color: var(--text-muted) !important;
}
.dark-mode #example_wrapper .dataTables_length select,
.dark-mode #example_wrapper .dataTables_filter input {
    background: var(--bg-color) !important;
    border-color: var(--border-color) !important;
    color: var(--text-main) !important;
}
.dark-mode .table thead th {
    background: var(--table-header-bg, #1e293b) !important;
    color: var(--text-muted) !important;
    border-color: var(--border-color) !important;
}
.dark-mode .table tbody tr {
    background: var(--card-bg) !important;
    color: var(--text-main) !important;
    border-color: var(--border-color) !important;
}
.dark-mode .table tbody tr:hover {
    background: var(--hover-bg) !important;
}
.dark-mode .table tbody td,
.dark-mode .table thead th {
    border-color: var(--border-color) !important;
    color: var(--text-main) !important;
}
.dark-mode .table-striped > tbody > tr:nth-of-type(odd) {
    background: var(--table-header-bg) !important;
}
</style>
<div class="page-title-row" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:25px;">
    <div>
        <h1 style="font-weight:800; color:var(--primary); font-size:28px; margin:0 0 5px 0;">Orders Management</h1>
        <p style="color:var(--text-muted); margin:0; font-size:14px;">Track, verify, and manage all customer orders and transaction states.</p>
    </div>
</div>
			 
			    <form action="controller.php?action=delete" Method="POST">  					
				 <div class="table-responsive">	
                  <table id="example" class="table  table-striped table-bordered table-hover"  style="font-size:12px" cellspacing="0">
			 		<thead>
			 		<tr >
				  		<th>#</th>
				  		<th>Order#</th>
				  		<th>Customer</th>
				  		<th>DateOrdered</th>	 
				  		<th >Price</th>
				  		<th >PaymentMethod</th>	
				  		<th>Status</th>
				  		<th width="100px">Action</th>
				 
				  	</tr>	
			   		</thead>
			   		<tbody>
					<?php 
				  		$query = "SELECT * FROM `tblsummary` s ,`tblcustomer` c 
				  				WHERE   s.`CUSTOMERID`=c.`CUSTOMERID` ORDER BY   `ORDEREDNUM` desc ";
				  		$mydb->setQuery($query);
				  		$cur = $mydb->loadResultList();

						foreach ($cur as $result) {
						?>

					<?php
						echo '<tr>';
				  		echo '<td width="3%" align="center"></td>';
				  		echo '<td><a href="#" title="View list Of ordered" data-target="#myModal" data-toggle="modal" class="orders" data-id="'.$result->ORDEREDNUM.'">'.$result->ORDEREDNUM .'</a> </td>';  
				  		echo '<td><a href="index.php?view=customerdetails&customerid='.$result->CUSTOMERID.'" title="View customer information">'. $result->FNAME.' '. $result->LNAME.'</a></td>';
				  		echo '<td>'. date_format(date_create($result->ORDEREDDATE),"M/d/Y h:i:s").'</td>';
				  		echo '<td> ₹ '.number_format($result->PAYMENT ,2).'</td>';
				  		echo '<td >'.$result->PAYMENTMETHOD .'</td>';
				  		// echo '<td></td>';
				  		echo '<td >'. $result->ORDEREDSTATS.'</td>';
				  		if($result->ORDEREDSTATS=='Pending'){
				  				echo '<td><a href="controller.php?action=edit&id='.$result->ORDEREDNUM.'&customerid='.$result->CUSTOMERID.'&actions=cancel" class="btn btn-danger btn-xs">Cancel</a>
				  				<a href="controller.php?action=edit&id='.$result->ORDEREDNUM.'&customerid='.$result->CUSTOMERID.'&actions=confirm"  class="btn btn-primary btn-xs">Confirm</a></td>';
			  	 		}elseif($result->ORDEREDSTATS=='Confirmed'){
				  	 			echo '<td><a href="#"  class="btn btn-success btn-xs" disabled>Confirmed</a></td>';
				  	 		 
			  	 		}else{
			  	 			 echo '<td> <a  href="#"  class="btn btn-danger btn-xs" disabled>Cancelled</a></td>';
				
			
			  	 		} 
				  		// if($result->ORDEREDSTATS=='Pending'){
				  		// 		echo '<td><a href="controller.php?action=edit&id='.$result->ORDEREDNUM.'&actions=cancel" class="btn btn-danger btn-xs">Cancel</a>
				  		// 		<a href="controller.php?action=edit&id='.$result->ORDEREDNUM.'&actions=confirm"  class="btn btn-primary btn-xs">Confirm</a></td>';
			  	 	// 	}elseif($result->ORDEREDSTATS=='Confirmed'){
				  	 // 			echo '<td><a href="controller.php?action=edit&id='.$result->ORDEREDNUM.'&actions=cancel" class="btn btn-danger btn-xs">Cancel</a>
				  		// 		<a href="controller.php?action=edit&id='.$result->ORDEREDNUM.'&actions=deliver"  class="btn btn-success btn-xs">Deliver</a></td>';
				  	 		
			  	 	// 	}elseif($result->ORDEREDSTATS=='Delivered'){
			  	 	// 		  echo '<td> <a  href="controller.php?action=edit&id='.$result->ORDEREDNUM.'&actions=confirm"  class="btn btn-success btn-xs" disabled>Delivered</a></td>';
				
			  	 	// 	}else{
			  	 	// 		 echo '<td> <a  href="#"  class="btn btn-danger btn-xs" disabled>Cancelled</a></td>';
				
			
			  	 	// 	} 
				  		echo '</tr>';
 
				  	} 
				  	?> 
				 </tbody>
				 	
				</table>
				<div class="btn-group">
				</div>
				</div>
				</form> 

  <div class="modal fade" id="myModal" tabindex="-1">
						
	</div><!-- /.modal -->

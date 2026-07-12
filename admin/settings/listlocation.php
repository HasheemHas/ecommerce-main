<?php
		check_message(); 
		?> 
		 
		<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid #f1f5f9; padding-bottom:15px;">
			<h3 style="margin:0; font-weight:700; color:#0f172a; font-size:16px;"><i class="fa fa-map-marker" style="margin-right:8px; color:#1e3a8a;"></i>Locations & Delivery Fees</h3>
			<a href="index.php?view=add" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600; border-radius:8px;">
				<i class="fa fa-plus-circle"></i> New Location
			</a>
		</div>
			    <form action="controller.php?action=delete" Method="POST">  	
			    <div class="table-responsive">				
				<table id="dash-table" class="settings-table" cellspacing="0" style="width:100%;">
					
				  <thead>
				  	<tr>  
				  		<th>Place</th> 
				  		<th>Delivery Fee</th>  
				  		<th style="text-align:center;">Action</th>
				  	</tr>	
				  </thead> 	

			  <tbody>
				  	<?php 
				  		$query = "SELECT * FROM `tblsetting` ";
				  		$mydb->setQuery($query);
				  		$cur = $mydb->loadResultList();

						foreach ($cur as $result) { 
				  		echo '<tr>'; 
				    		
				  		echo '<td class="product-name-cell">'.htmlspecialchars($result->BRGY.' '.$result->PLACE).'</td>';  
				  		echo '<td class="price-cell" style="color:#1e3a8a;">₹ '.number_format($result->DELPRICE,2).'</td>'; 
				  		echo
				  		 '<td align="center">
							<div class="action-group" style="justify-content:center;">
								<a href="'.web_root.'admin/settings/index.php?view=edit&id='.$result->SETTINGID.'" class="btn-discount" title="Edit Location"><i class="fa fa-edit"></i> Edit</a>
								<a href="'.web_root.'admin/settings/controller.php?action=delete&id='.$result->SETTINGID.'" class="btn-delete-location" onclick="return confirm(\'Are you sure you want to delete this location?\')" title="Delete Location"><i class="fa fa-trash-o"></i> Delete</a>
							</div>
						 </td>';
				  	} 
				  	?>
				  </tbody>
					
				 	
				</table>

				<!-- <div class="btn-group">
				  <a href="index.php?view=add" class="btn btn-default">New</a>
				  <button type="submit" class="btn btn-default" name="delete"><i class="fa fa-trash fw-fa"></i> Delete Selected</button>
				</div> -->
				</div>
				</form>
 
 
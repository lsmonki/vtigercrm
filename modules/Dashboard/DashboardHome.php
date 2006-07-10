		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><table width="20%"  border="0" cellspacing="0" cellpadding="0" align="le">
				 		 <tr>

				 		   <td rowspan="2" valign="top"><span class="dashSerial">1</span></td>
				 		   <td nowrap><span class="genHeaderSmall"><?echo $mod_strings['LBL_SALES_STAGE_FORM_TITLE']; ?></span></td>
				 		 </tr>
					     <tr>
					       <td><span class="big"><? echo $mod_strings['LBL_HORZ_BAR_CHART'];?></span> </td>
					     </tr>
					</table>

				</td>
			  </tr>
			  <tr>
				<td height="200"><?php include ("modules/Dashboard/Chart_pipeline_by_sales_stage.php");?></td>
			  </tr>
			  <tr>
				<td><hr noshade="noshade" size="1" /></td>
			  </tr>

			  <!-- SCEOND CHART  -->
			  
			  <tr>
				<td><table width="20%"  border="0" cellspacing="0" cellpadding="0" align="le">
				 		 <tr>
				 		   <td rowspan="2" valign="top"><span class="dashSerial">2</span></td>
				 		   <td nowrap><span class="genHeaderSmall"><?php echo $mod_strings['LBL_MONTH_BY_OUTCOME'];?></span></td>
				 		 </tr>
					     <tr>

					       <td><span class="big"><? echo $mod_strings['LBL_VERT_BAR_CHART'];?></span> </td>
					     </tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td height="200"><?php include ("modules/Dashboard/Chart_outcome_by_month.php"); ?></td>

			  </tr>
			  <tr>
				<td><hr noshade="noshade" size="1" /></td>
			  </tr>
			  
			  <!-- THIRD CHART  -->
			  
			  <tr>
				<td><table width="20%"  border="0" cellspacing="0" cellpadding="0" align="le">
				 		 <tr>
				 		   <td rowspan="2" valign="top"><span class="dashSerial">3</span></td>
				 		   <td nowrap><span class="genHeaderSmall"><?php echo $mod_strings['LBL_LEAD_SOURCE_BY_OUTCOME'];?></span></td>
				 		 </tr>
					     <tr>

					       <td><span class="big"><? echo $mod_strings['LBL_HORZ_BAR_CHART'];?></span> </td>
					     </tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td height="200"><?php include ("modules/Dashboard/Chart_lead_source_by_outcome.php");?></td>

			  </tr>
			  <tr>
				<td><hr noshade="noshade" size="1" /></td>
			  </tr>
			  
			  <!-- FOURTH CHART  -->
			  
			  <tr>
				<td><table width="20%"  border="0" cellspacing="0" cellpadding="0" align="le">
				 		 <tr>
				 		   <td rowspan="2" valign="top"><span class="dashSerial">4</span></td>
				 		   <td nowrap><span class="genHeaderSmall"><?php echo $mod_strings['LBL_LEAD_SOURCE_FORM_TITLE'];?></span></td>
				 		 </tr>
					     <tr>

					       <td><span class="big"><? echo $mod_strings['LBL_PIE_CHART'];?></span> </td>
					     </tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td height="200"><?php include ("modules/Dashboard/Chart_pipeline_by_lead_source.php") ?></td>

			  </tr>
			  <tr>
				<td><hr noshade="noshade" size="1" /></td>
			  </tr>
			</table>

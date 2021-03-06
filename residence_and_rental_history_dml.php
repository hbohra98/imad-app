<?php

// Data functions (insert, update, delete, form) for table residence_and_rental_history

// This script and data application were generated by AppGini 5.62
// Download AppGini for free from https://bigprof.com/appgini/download/

function residence_and_rental_history_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('residence_and_rental_history');
	if(!$arrPerm[1]){
		return false;
	}

	$data['tenant'] = makeSafe($_REQUEST['tenant']);
		if($data['tenant'] == empty_lookup_value){ $data['tenant'] = ''; }
	$data['address'] = makeSafe($_REQUEST['address']);
		if($data['address'] == empty_lookup_value){ $data['address'] = ''; }
	$data['landlord_or_manager_name'] = makeSafe($_REQUEST['landlord_or_manager_name']);
		if($data['landlord_or_manager_name'] == empty_lookup_value){ $data['landlord_or_manager_name'] = ''; }
	$data['landlord_or_manager_phone'] = makeSafe($_REQUEST['landlord_or_manager_phone']);
		if($data['landlord_or_manager_phone'] == empty_lookup_value){ $data['landlord_or_manager_phone'] = ''; }
	$data['monthly_rent'] = makeSafe($_REQUEST['monthly_rent']);
		if($data['monthly_rent'] == empty_lookup_value){ $data['monthly_rent'] = ''; }
	$data['duration_of_residency_from'] = intval($_REQUEST['duration_of_residency_fromYear']) . '-' . intval($_REQUEST['duration_of_residency_fromMonth']) . '-' . intval($_REQUEST['duration_of_residency_fromDay']);
	$data['duration_of_residency_from'] = parseMySQLDate($data['duration_of_residency_from'], '');
	$data['to'] = intval($_REQUEST['toYear']) . '-' . intval($_REQUEST['toMonth']) . '-' . intval($_REQUEST['toDay']);
	$data['to'] = parseMySQLDate($data['to'], '');
	$data['reason_for_leaving'] = makeSafe($_REQUEST['reason_for_leaving']);
		if($data['reason_for_leaving'] == empty_lookup_value){ $data['reason_for_leaving'] = ''; }
	$data['notes'] = makeSafe($_REQUEST['notes']);
		if($data['notes'] == empty_lookup_value){ $data['notes'] = ''; }

	// hook: residence_and_rental_history_before_insert
	if(function_exists('residence_and_rental_history_before_insert')){
		$args=array();
		if(!residence_and_rental_history_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `residence_and_rental_history` set       `tenant`=' . (($data['tenant'] !== '' && $data['tenant'] !== NULL) ? "'{$data['tenant']}'" : 'NULL') . ', `address`=' . (($data['address'] !== '' && $data['address'] !== NULL) ? "'{$data['address']}'" : 'NULL') . ', `landlord_or_manager_name`=' . (($data['landlord_or_manager_name'] !== '' && $data['landlord_or_manager_name'] !== NULL) ? "'{$data['landlord_or_manager_name']}'" : 'NULL') . ', `landlord_or_manager_phone`=' . (($data['landlord_or_manager_phone'] !== '' && $data['landlord_or_manager_phone'] !== NULL) ? "'{$data['landlord_or_manager_phone']}'" : 'NULL') . ', `monthly_rent`=' . (($data['monthly_rent'] !== '' && $data['monthly_rent'] !== NULL) ? "'{$data['monthly_rent']}'" : 'NULL') . ', `duration_of_residency_from`=' . (($data['duration_of_residency_from'] !== '' && $data['duration_of_residency_from'] !== NULL) ? "'{$data['duration_of_residency_from']}'" : 'NULL') . ', `to`=' . (($data['to'] !== '' && $data['to'] !== NULL) ? "'{$data['to']}'" : 'NULL') . ', `reason_for_leaving`=' . (($data['reason_for_leaving'] !== '' && $data['reason_for_leaving'] !== NULL) ? "'{$data['reason_for_leaving']}'" : 'NULL') . ', `notes`=' . (($data['notes'] !== '' && $data['notes'] !== NULL) ? "'{$data['notes']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"residence_and_rental_history_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: residence_and_rental_history_after_insert
	if(function_exists('residence_and_rental_history_after_insert')){
		$res = sql("select * from `residence_and_rental_history` where `id`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!residence_and_rental_history_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	sql("insert ignore into membership_userrecords set tableName='residence_and_rental_history', pkValue='" . makeSafe($recID, false) . "', memberID='" . makeSafe(getLoggedMemberID(), false) . "', dateAdded='" . time() . "', dateUpdated='" . time() . "', groupID='" . getLoggedGroupID() . "'", $eo);

	return $recID;
}

function residence_and_rental_history_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('residence_and_rental_history');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='residence_and_rental_history' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='residence_and_rental_history' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: residence_and_rental_history_before_delete
	if(function_exists('residence_and_rental_history_before_delete')){
		$args=array();
		if(!residence_and_rental_history_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	sql("delete from `residence_and_rental_history` where `id`='$selected_id'", $eo);

	// hook: residence_and_rental_history_after_delete
	if(function_exists('residence_and_rental_history_after_delete')){
		$args=array();
		residence_and_rental_history_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='residence_and_rental_history' and pkValue='$selected_id'", $eo);
}

function residence_and_rental_history_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('residence_and_rental_history');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='residence_and_rental_history' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='residence_and_rental_history' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['tenant'] = makeSafe($_REQUEST['tenant']);
		if($data['tenant'] == empty_lookup_value){ $data['tenant'] = ''; }
	$data['address'] = makeSafe($_REQUEST['address']);
		if($data['address'] == empty_lookup_value){ $data['address'] = ''; }
	$data['landlord_or_manager_name'] = makeSafe($_REQUEST['landlord_or_manager_name']);
		if($data['landlord_or_manager_name'] == empty_lookup_value){ $data['landlord_or_manager_name'] = ''; }
	$data['landlord_or_manager_phone'] = makeSafe($_REQUEST['landlord_or_manager_phone']);
		if($data['landlord_or_manager_phone'] == empty_lookup_value){ $data['landlord_or_manager_phone'] = ''; }
	$data['monthly_rent'] = makeSafe($_REQUEST['monthly_rent']);
		if($data['monthly_rent'] == empty_lookup_value){ $data['monthly_rent'] = ''; }
	$data['duration_of_residency_from'] = intval($_REQUEST['duration_of_residency_fromYear']) . '-' . intval($_REQUEST['duration_of_residency_fromMonth']) . '-' . intval($_REQUEST['duration_of_residency_fromDay']);
	$data['duration_of_residency_from'] = parseMySQLDate($data['duration_of_residency_from'], '');
	$data['to'] = intval($_REQUEST['toYear']) . '-' . intval($_REQUEST['toMonth']) . '-' . intval($_REQUEST['toDay']);
	$data['to'] = parseMySQLDate($data['to'], '');
	$data['reason_for_leaving'] = makeSafe($_REQUEST['reason_for_leaving']);
		if($data['reason_for_leaving'] == empty_lookup_value){ $data['reason_for_leaving'] = ''; }
	$data['notes'] = makeSafe($_REQUEST['notes']);
		if($data['notes'] == empty_lookup_value){ $data['notes'] = ''; }
	$data['selectedID']=makeSafe($selected_id);

	// hook: residence_and_rental_history_before_update
	if(function_exists('residence_and_rental_history_before_update')){
		$args=array();
		if(!residence_and_rental_history_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `residence_and_rental_history` set       `tenant`=' . (($data['tenant'] !== '' && $data['tenant'] !== NULL) ? "'{$data['tenant']}'" : 'NULL') . ', `address`=' . (($data['address'] !== '' && $data['address'] !== NULL) ? "'{$data['address']}'" : 'NULL') . ', `landlord_or_manager_name`=' . (($data['landlord_or_manager_name'] !== '' && $data['landlord_or_manager_name'] !== NULL) ? "'{$data['landlord_or_manager_name']}'" : 'NULL') . ', `landlord_or_manager_phone`=' . (($data['landlord_or_manager_phone'] !== '' && $data['landlord_or_manager_phone'] !== NULL) ? "'{$data['landlord_or_manager_phone']}'" : 'NULL') . ', `monthly_rent`=' . (($data['monthly_rent'] !== '' && $data['monthly_rent'] !== NULL) ? "'{$data['monthly_rent']}'" : 'NULL') . ', `duration_of_residency_from`=' . (($data['duration_of_residency_from'] !== '' && $data['duration_of_residency_from'] !== NULL) ? "'{$data['duration_of_residency_from']}'" : 'NULL') . ', `to`=' . (($data['to'] !== '' && $data['to'] !== NULL) ? "'{$data['to']}'" : 'NULL') . ', `reason_for_leaving`=' . (($data['reason_for_leaving'] !== '' && $data['reason_for_leaving'] !== NULL) ? "'{$data['reason_for_leaving']}'" : 'NULL') . ', `notes`=' . (($data['notes'] !== '' && $data['notes'] !== NULL) ? "'{$data['notes']}'" : 'NULL') . " where `id`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="residence_and_rental_history_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: residence_and_rental_history_after_update
	if(function_exists('residence_and_rental_history_after_update')){
		$res = sql("SELECT * FROM `residence_and_rental_history` WHERE `id`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['id'];
		$args = array();
		if(!residence_and_rental_history_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='residence_and_rental_history' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function residence_and_rental_history_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('residence_and_rental_history');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}

	$filterer_tenant = thisOr(undo_magic_quotes($_REQUEST['filterer_tenant']), '');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: tenant
	$combo_tenant = new DataCombo;
	// combobox: duration_of_residency_from
	$combo_duration_of_residency_from = new DateCombo;
	$combo_duration_of_residency_from->DateFormat = "mdy";
	$combo_duration_of_residency_from->MinYear = 1900;
	$combo_duration_of_residency_from->MaxYear = 2100;
	$combo_duration_of_residency_from->DefaultDate = parseMySQLDate('', '');
	$combo_duration_of_residency_from->MonthNames = $Translation['month names'];
	$combo_duration_of_residency_from->NamePrefix = 'duration_of_residency_from';
	// combobox: to
	$combo_to = new DateCombo;
	$combo_to->DateFormat = "mdy";
	$combo_to->MinYear = 1900;
	$combo_to->MaxYear = 2100;
	$combo_to->DefaultDate = parseMySQLDate('', '');
	$combo_to->MonthNames = $Translation['month names'];
	$combo_to->NamePrefix = 'to';

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='residence_and_rental_history' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='residence_and_rental_history' and pkValue='".makeSafe($selected_id)."'");
		if($arrPerm[2]==1 && getLoggedMemberID()!=$ownerMemberID){
			return "";
		}
		if($arrPerm[2]==2 && getLoggedGroupID()!=$ownerGroupID){
			return "";
		}

		// can edit?
		if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){
			$AllowUpdate=1;
		}else{
			$AllowUpdate=0;
		}

		$res = sql("select * from `residence_and_rental_history` where `id`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'residence_and_rental_history_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
		$combo_tenant->SelectedData = $row['tenant'];
		$combo_duration_of_residency_from->DefaultDate = $row['duration_of_residency_from'];
		$combo_to->DefaultDate = $row['to'];
	}else{
		$combo_tenant->SelectedData = $filterer_tenant;
	}
	$combo_tenant->HTML = '<span id="tenant-container' . $rnd1 . '"></span><input type="hidden" name="tenant" id="tenant' . $rnd1 . '" value="' . html_attr($combo_tenant->SelectedData) . '">';
	$combo_tenant->MatchText = '<span id="tenant-container-readonly' . $rnd1 . '"></span><input type="hidden" name="tenant" id="tenant' . $rnd1 . '" value="' . html_attr($combo_tenant->SelectedData) . '">';

	ob_start();
	?>

	<script>
		// initial lookup values
		AppGini.current_tenant__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['tenant'] : $filterer_tenant); ?>"};

		jQuery(function() {
			setTimeout(function(){
				if(typeof(tenant_reload__RAND__) == 'function') tenant_reload__RAND__();
			}, 10); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
		function tenant_reload__RAND__(){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#tenant-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_tenant__RAND__.value, t: 'residence_and_rental_history', f: 'tenant' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="tenant"]').val(resp.results[0].id);
							$j('[id=tenant-container-readonly__RAND__]').html('<span id="tenant-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=applicants_and_tenants_view_parent]').hide(); }else{ $j('.btn[id=applicants_and_tenants_view_parent]').show(); }


							if(typeof(tenant_update_autofills__RAND__) == 'function') tenant_update_autofills__RAND__();
						}
					});
				},
				width: ($j('fieldset .col-xs-11').width() - select2_max_width_decrement()) + 'px',
				formatNoMatches: function(term){ return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 10,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page){ return { s: term, p: page, t: 'residence_and_rental_history', f: 'tenant' }; },
					results: function(resp, page){ return resp; }
				},
				escapeMarkup: function(str){ return str; }
			}).on('change', function(e){
				AppGini.current_tenant__RAND__.value = e.added.id;
				AppGini.current_tenant__RAND__.text = e.added.text;
				$j('[name="tenant"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=applicants_and_tenants_view_parent]').hide(); }else{ $j('.btn[id=applicants_and_tenants_view_parent]').show(); }


				if(typeof(tenant_update_autofills__RAND__) == 'function') tenant_update_autofills__RAND__();
			});

			if(!$j("#tenant-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_tenant__RAND__.value, t: 'residence_and_rental_history', f: 'tenant' },
					success: function(resp){
						$j('[name="tenant"]').val(resp.results[0].id);
						$j('[id=tenant-container-readonly__RAND__]').html('<span id="tenant-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=applicants_and_tenants_view_parent]').hide(); }else{ $j('.btn[id=applicants_and_tenants_view_parent]').show(); }

						if(typeof(tenant_update_autofills__RAND__) == 'function') tenant_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_tenant__RAND__.value, t: 'residence_and_rental_history', f: 'tenant' },
				success: function(resp){
					$j('[id=tenant-container__RAND__], [id=tenant-container-readonly__RAND__]').html('<span id="tenant-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=applicants_and_tenants_view_parent]').hide(); }else{ $j('.btn[id=applicants_and_tenants_view_parent]').show(); }

					if(typeof(tenant_update_autofills__RAND__) == 'function') tenant_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_contents());
	ob_end_clean();


	// code for template based detail view forms

	// open the detail view template
	if($dvprint){
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/residence_and_rental_history_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/residence_and_rental_history_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Residence and rental history details', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert){
		if(!$selected_id) $templateCode=str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return residence_and_rental_history_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode=str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return residence_and_rental_history_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	}else{
		$templateCode=str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if($_REQUEST['Embedded']){
		$backAction = 'window.parent.jQuery(\'.modal\').modal(\'hide\'); return false;';
	}else{
		$backAction = '$$(\'form\')[0].writeAttribute(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;';
	}

	if($selected_id){
		if(!$_REQUEST['Embedded']) $templateCode=str_replace('<%%DVPRINT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="dvprint" name="dvprint_x" value="1" onclick="$$(\'form\')[0].writeAttribute(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;" title="' . html_attr($Translation['Print Preview']) . '"><i class="glyphicon glyphicon-print"></i> ' . $Translation['Print Preview'] . '</button>', $templateCode);
		if($AllowUpdate){
			$templateCode=str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return residence_and_rental_history_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		}else{
			$templateCode=str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		}
		if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
			$templateCode=str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" onclick="return confirm(\'' . $Translation['are you sure?'] . '\');" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		}else{
			$templateCode=str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		}
		$templateCode=str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	}else{
		$templateCode=str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode=str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		$templateCode=str_replace('<%%DESELECT_BUTTON%%>', ($ShowCancel ? '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>' : ''), $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(($selected_id && !$AllowUpdate && !$AllowInsert) || (!$selected_id && !$AllowInsert)){
		$jsReadOnly .= "\tjQuery('#tenant').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#tenant_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#address').replaceWith('<div class=\"form-control-static\" id=\"address\">' + (jQuery('#address').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#landlord_or_manager_name').replaceWith('<div class=\"form-control-static\" id=\"landlord_or_manager_name\">' + (jQuery('#landlord_or_manager_name').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#landlord_or_manager_phone').replaceWith('<div class=\"form-control-static\" id=\"landlord_or_manager_phone\">' + (jQuery('#landlord_or_manager_phone').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#monthly_rent').replaceWith('<div class=\"form-control-static\" id=\"monthly_rent\">' + (jQuery('#monthly_rent').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#duration_of_residency_from').prop('readonly', true);\n";
		$jsReadOnly .= "\tjQuery('#duration_of_residency_fromDay, #duration_of_residency_fromMonth, #duration_of_residency_fromYear').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#to').prop('readonly', true);\n";
		$jsReadOnly .= "\tjQuery('#toDay, #toMonth, #toYear').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#reason_for_leaving').replaceWith('<div class=\"form-control-static\" id=\"reason_for_leaving\">' + (jQuery('#reason_for_leaving').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	}elseif($AllowInsert){
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
			$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode=str_replace('<%%COMBO(tenant)%%>', $combo_tenant->HTML, $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(tenant)%%>', $combo_tenant->MatchText, $templateCode);
	$templateCode=str_replace('<%%URLCOMBOTEXT(tenant)%%>', urlencode($combo_tenant->MatchText), $templateCode);
	$templateCode=str_replace('<%%COMBO(duration_of_residency_from)%%>', ($selected_id && !$arrPerm[3] ? '<div class="form-control-static">' . $combo_duration_of_residency_from->GetHTML(true) . '</div>' : $combo_duration_of_residency_from->GetHTML()), $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(duration_of_residency_from)%%>', $combo_duration_of_residency_from->GetHTML(true), $templateCode);
	$templateCode=str_replace('<%%COMBO(to)%%>', ($selected_id && !$arrPerm[3] ? '<div class="form-control-static">' . $combo_to->GetHTML(true) . '</div>' : $combo_to->GetHTML()), $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(to)%%>', $combo_to->GetHTML(true), $templateCode);

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array(  'tenant' => array('applicants_and_tenants', 'Tenant'));
	foreach($lookup_fields as $luf => $ptfc){
		$pt_perm = getTablePermissions($ptfc[0]);

		// process foreign key links
		if($pt_perm['view'] || $pt_perm['edit']){
			$templateCode = str_replace("<%%PLINK({$luf})%%>", '<button type="button" class="btn btn-default view_parent hspacer-md" id="' . $ptfc[0] . '_view_parent" title="' . html_attr($Translation['View'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-eye-open"></i></button>', $templateCode);
		}

		// if user has insert permission to parent table of a lookup field, put an add new button
		if($pt_perm['insert'] && !$_REQUEST['Embedded']){
			$templateCode = str_replace("<%%ADDNEW({$ptfc[0]})%%>", '<button type="button" class="btn btn-success add_new_parent hspacer-md" id="' . $ptfc[0] . '_add_new" title="' . html_attr($Translation['Add New'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-plus-sign"></i></button>', $templateCode);
		}
	}

	// process images
	$templateCode=str_replace('<%%UPLOADFILE(id)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(tenant)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(address)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(landlord_or_manager_name)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(landlord_or_manager_phone)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(monthly_rent)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(duration_of_residency_from)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(to)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(reason_for_leaving)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(notes)%%>', '', $templateCode);

	// process values
	if($selected_id){
		$templateCode=str_replace('<%%VALUE(id)%%>', html_attr($row['id']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(id)%%>', urlencode($urow['id']), $templateCode);
		$templateCode=str_replace('<%%VALUE(tenant)%%>', html_attr($row['tenant']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(tenant)%%>', urlencode($urow['tenant']), $templateCode);
		$templateCode=str_replace('<%%VALUE(address)%%>', html_attr($row['address']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(address)%%>', urlencode($urow['address']), $templateCode);
		$templateCode=str_replace('<%%VALUE(landlord_or_manager_name)%%>', html_attr($row['landlord_or_manager_name']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(landlord_or_manager_name)%%>', urlencode($urow['landlord_or_manager_name']), $templateCode);
		$templateCode=str_replace('<%%VALUE(landlord_or_manager_phone)%%>', html_attr($row['landlord_or_manager_phone']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(landlord_or_manager_phone)%%>', urlencode($urow['landlord_or_manager_phone']), $templateCode);
		$templateCode=str_replace('<%%VALUE(monthly_rent)%%>', html_attr($row['monthly_rent']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(monthly_rent)%%>', urlencode($urow['monthly_rent']), $templateCode);
		$templateCode=str_replace('<%%VALUE(duration_of_residency_from)%%>', @date('m/d/Y', @strtotime(html_attr($row['duration_of_residency_from']))), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(duration_of_residency_from)%%>', urlencode(@date('m/d/Y', @strtotime(html_attr($urow['duration_of_residency_from'])))), $templateCode);
		$templateCode=str_replace('<%%VALUE(to)%%>', @date('m/d/Y', @strtotime(html_attr($row['to']))), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(to)%%>', urlencode(@date('m/d/Y', @strtotime(html_attr($urow['to'])))), $templateCode);
		$templateCode=str_replace('<%%VALUE(reason_for_leaving)%%>', html_attr($row['reason_for_leaving']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(reason_for_leaving)%%>', urlencode($urow['reason_for_leaving']), $templateCode);
		if($AllowUpdate || $AllowInsert){
			$templateCode = str_replace('<%%HTMLAREA(notes)%%>', '<textarea name="notes" id="notes" rows="5">' . html_attr($row['notes']) . '</textarea>', $templateCode);
		}else{
			$templateCode = str_replace('<%%HTMLAREA(notes)%%>', '<div id="notes" class="form-control-static">' . $row['notes'] . '</div>', $templateCode);
		}
		$templateCode=str_replace('<%%VALUE(notes)%%>', nl2br($row['notes']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(notes)%%>', urlencode($urow['notes']), $templateCode);
	}else{
		$templateCode=str_replace('<%%VALUE(id)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(id)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%VALUE(tenant)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(tenant)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%VALUE(address)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(address)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%VALUE(landlord_or_manager_name)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(landlord_or_manager_name)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%VALUE(landlord_or_manager_phone)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(landlord_or_manager_phone)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%VALUE(monthly_rent)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(monthly_rent)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%VALUE(duration_of_residency_from)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(duration_of_residency_from)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%VALUE(to)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(to)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%VALUE(reason_for_leaving)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(reason_for_leaving)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%HTMLAREA(notes)%%>', '<textarea name="notes" id="notes" rows="5"></textarea>', $templateCode);
	}

	// process translations
	foreach($Translation as $symbol=>$trans){
		$templateCode=str_replace("<%%TRANSLATION($symbol)%%>", $trans, $templateCode);
	}

	// clear scrap
	$templateCode=str_replace('<%%', '<!-- ', $templateCode);
	$templateCode=str_replace('%%>', ' -->', $templateCode);

	// hide links to inaccessible tables
	if($_REQUEST['dvprint_x'] == ''){
		$templateCode .= "\n\n<script>\$j(function(){\n";
		$arrTables = getTableList();
		foreach($arrTables as $name => $caption){
			$templateCode .= "\t\$j('#{$name}_link').removeClass('hidden');\n";
			$templateCode .= "\t\$j('#xs_{$name}_link').removeClass('hidden');\n";
		}

		$templateCode .= $jsReadOnly;
		$templateCode .= $jsEditable;

		if(!$selected_id){
		}

		$templateCode.="\n});</script>\n";
	}

	// ajaxed auto-fill fields
	$templateCode .= '<script>';
	$templateCode .= '$j(function() {';


	$templateCode.="});";
	$templateCode.="</script>";
	$templateCode .= $lookups;

	// handle enforced parent values for read-only lookup fields

	// don't include blank images in lightbox gallery
	$templateCode = preg_replace('/blank.gif" data-lightbox=".*?"/', 'blank.gif"', $templateCode);

	// don't display empty email links
	$templateCode=preg_replace('/<a .*?href="mailto:".*?<\/a>/', '', $templateCode);

	/* default field values */
	$rdata = $jdata = get_defaults('residence_and_rental_history');
	if($selected_id){
		$jdata = get_joined_record('residence_and_rental_history', $selected_id);
		$rdata = $row;
	}
	$cache_data = array(
		'rdata' => array_map('nl2br', array_map('addslashes', $rdata)),
		'jdata' => array_map('nl2br', array_map('addslashes', $jdata)),
	);
	$templateCode .= loadView('residence_and_rental_history-ajax-cache', $cache_data);

	// hook: residence_and_rental_history_dv
	if(function_exists('residence_and_rental_history_dv')){
		$args=array();
		residence_and_rental_history_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>
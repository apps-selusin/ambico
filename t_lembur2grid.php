<?php include_once "t_userinfo.php" ?>
<?php

// Create page object
if (!isset($t_lembur2_grid)) $t_lembur2_grid = new ct_lembur2_grid();

// Page init
$t_lembur2_grid->Page_Init();

// Page main
$t_lembur2_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_lembur2_grid->Page_Render();
?>
<?php if ($t_lembur2->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_lembur2grid = new ew_Form("ft_lembur2grid", "grid");
ft_lembur2grid.FormKeyCountName = '<?php echo $t_lembur2_grid->FormKeyCountName ?>';

// Validate form
ft_lembur2grid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_lembur2->pegawai_id->FldCaption(), $t_lembur2->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_lembur2->pegawai_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_lembur2->tgl->FldCaption(), $t_lembur2->tgl->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_lembur2->tgl->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lama_lembur");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_lembur2->lama_lembur->FldCaption(), $t_lembur2->lama_lembur->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lama_lembur");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_lembur2->lama_lembur->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_lembur2grid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "pegawai_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl", false)) return false;
	if (ew_ValueChanged(fobj, infix, "lama_lembur", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_lembur2grid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_lembur2grid.ValidateRequired = true;
<?php } else { ?>
ft_lembur2grid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($t_lembur2->CurrentAction == "gridadd") {
	if ($t_lembur2->CurrentMode == "copy") {
		$bSelectLimit = $t_lembur2_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_lembur2_grid->TotalRecs = $t_lembur2->SelectRecordCount();
			$t_lembur2_grid->Recordset = $t_lembur2_grid->LoadRecordset($t_lembur2_grid->StartRec-1, $t_lembur2_grid->DisplayRecs);
		} else {
			if ($t_lembur2_grid->Recordset = $t_lembur2_grid->LoadRecordset())
				$t_lembur2_grid->TotalRecs = $t_lembur2_grid->Recordset->RecordCount();
		}
		$t_lembur2_grid->StartRec = 1;
		$t_lembur2_grid->DisplayRecs = $t_lembur2_grid->TotalRecs;
	} else {
		$t_lembur2->CurrentFilter = "0=1";
		$t_lembur2_grid->StartRec = 1;
		$t_lembur2_grid->DisplayRecs = $t_lembur2->GridAddRowCount;
	}
	$t_lembur2_grid->TotalRecs = $t_lembur2_grid->DisplayRecs;
	$t_lembur2_grid->StopRec = $t_lembur2_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_lembur2_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_lembur2_grid->TotalRecs <= 0)
			$t_lembur2_grid->TotalRecs = $t_lembur2->SelectRecordCount();
	} else {
		if (!$t_lembur2_grid->Recordset && ($t_lembur2_grid->Recordset = $t_lembur2_grid->LoadRecordset()))
			$t_lembur2_grid->TotalRecs = $t_lembur2_grid->Recordset->RecordCount();
	}
	$t_lembur2_grid->StartRec = 1;
	$t_lembur2_grid->DisplayRecs = $t_lembur2_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_lembur2_grid->Recordset = $t_lembur2_grid->LoadRecordset($t_lembur2_grid->StartRec-1, $t_lembur2_grid->DisplayRecs);

	// Set no record found message
	if ($t_lembur2->CurrentAction == "" && $t_lembur2_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_lembur2_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_lembur2_grid->SearchWhere == "0=101")
			$t_lembur2_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_lembur2_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_lembur2_grid->RenderOtherOptions();
?>
<?php $t_lembur2_grid->ShowPageHeader(); ?>
<?php
$t_lembur2_grid->ShowMessage();
?>
<?php if ($t_lembur2_grid->TotalRecs > 0 || $t_lembur2->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_lembur2">
<div id="ft_lembur2grid" class="ewForm form-inline">
<?php if ($t_lembur2_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($t_lembur2_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_t_lembur2" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_lembur2grid" class="table ewTable">
<?php echo $t_lembur2->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_lembur2_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_lembur2_grid->RenderListOptions();

// Render list options (header, left)
$t_lembur2_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_lembur2->pegawai_id->Visible) { // pegawai_id ?>
	<?php if ($t_lembur2->SortUrl($t_lembur2->pegawai_id) == "") { ?>
		<th data-name="pegawai_id"><div id="elh_t_lembur2_pegawai_id" class="t_lembur2_pegawai_id"><div class="ewTableHeaderCaption"><?php echo $t_lembur2->pegawai_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_id"><div><div id="elh_t_lembur2_pegawai_id" class="t_lembur2_pegawai_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_lembur2->pegawai_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_lembur2->pegawai_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_lembur2->pegawai_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_lembur2->tgl->Visible) { // tgl ?>
	<?php if ($t_lembur2->SortUrl($t_lembur2->tgl) == "") { ?>
		<th data-name="tgl"><div id="elh_t_lembur2_tgl" class="t_lembur2_tgl"><div class="ewTableHeaderCaption"><?php echo $t_lembur2->tgl->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl"><div><div id="elh_t_lembur2_tgl" class="t_lembur2_tgl">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_lembur2->tgl->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_lembur2->tgl->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_lembur2->tgl->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_lembur2->lama_lembur->Visible) { // lama_lembur ?>
	<?php if ($t_lembur2->SortUrl($t_lembur2->lama_lembur) == "") { ?>
		<th data-name="lama_lembur"><div id="elh_t_lembur2_lama_lembur" class="t_lembur2_lama_lembur"><div class="ewTableHeaderCaption"><?php echo $t_lembur2->lama_lembur->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="lama_lembur"><div><div id="elh_t_lembur2_lama_lembur" class="t_lembur2_lama_lembur">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_lembur2->lama_lembur->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_lembur2->lama_lembur->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_lembur2->lama_lembur->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_lembur2_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_lembur2_grid->StartRec = 1;
$t_lembur2_grid->StopRec = $t_lembur2_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_lembur2_grid->FormKeyCountName) && ($t_lembur2->CurrentAction == "gridadd" || $t_lembur2->CurrentAction == "gridedit" || $t_lembur2->CurrentAction == "F")) {
		$t_lembur2_grid->KeyCount = $objForm->GetValue($t_lembur2_grid->FormKeyCountName);
		$t_lembur2_grid->StopRec = $t_lembur2_grid->StartRec + $t_lembur2_grid->KeyCount - 1;
	}
}
$t_lembur2_grid->RecCnt = $t_lembur2_grid->StartRec - 1;
if ($t_lembur2_grid->Recordset && !$t_lembur2_grid->Recordset->EOF) {
	$t_lembur2_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_lembur2_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_lembur2_grid->StartRec > 1)
		$t_lembur2_grid->Recordset->Move($t_lembur2_grid->StartRec - 1);
} elseif (!$t_lembur2->AllowAddDeleteRow && $t_lembur2_grid->StopRec == 0) {
	$t_lembur2_grid->StopRec = $t_lembur2->GridAddRowCount;
}

// Initialize aggregate
$t_lembur2->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_lembur2->ResetAttrs();
$t_lembur2_grid->RenderRow();
if ($t_lembur2->CurrentAction == "gridadd")
	$t_lembur2_grid->RowIndex = 0;
if ($t_lembur2->CurrentAction == "gridedit")
	$t_lembur2_grid->RowIndex = 0;
while ($t_lembur2_grid->RecCnt < $t_lembur2_grid->StopRec) {
	$t_lembur2_grid->RecCnt++;
	if (intval($t_lembur2_grid->RecCnt) >= intval($t_lembur2_grid->StartRec)) {
		$t_lembur2_grid->RowCnt++;
		if ($t_lembur2->CurrentAction == "gridadd" || $t_lembur2->CurrentAction == "gridedit" || $t_lembur2->CurrentAction == "F") {
			$t_lembur2_grid->RowIndex++;
			$objForm->Index = $t_lembur2_grid->RowIndex;
			if ($objForm->HasValue($t_lembur2_grid->FormActionName))
				$t_lembur2_grid->RowAction = strval($objForm->GetValue($t_lembur2_grid->FormActionName));
			elseif ($t_lembur2->CurrentAction == "gridadd")
				$t_lembur2_grid->RowAction = "insert";
			else
				$t_lembur2_grid->RowAction = "";
		}

		// Set up key count
		$t_lembur2_grid->KeyCount = $t_lembur2_grid->RowIndex;

		// Init row class and style
		$t_lembur2->ResetAttrs();
		$t_lembur2->CssClass = "";
		if ($t_lembur2->CurrentAction == "gridadd") {
			if ($t_lembur2->CurrentMode == "copy") {
				$t_lembur2_grid->LoadRowValues($t_lembur2_grid->Recordset); // Load row values
				$t_lembur2_grid->SetRecordKey($t_lembur2_grid->RowOldKey, $t_lembur2_grid->Recordset); // Set old record key
			} else {
				$t_lembur2_grid->LoadDefaultValues(); // Load default values
				$t_lembur2_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_lembur2_grid->LoadRowValues($t_lembur2_grid->Recordset); // Load row values
		}
		$t_lembur2->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_lembur2->CurrentAction == "gridadd") // Grid add
			$t_lembur2->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_lembur2->CurrentAction == "gridadd" && $t_lembur2->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_lembur2_grid->RestoreCurrentRowFormValues($t_lembur2_grid->RowIndex); // Restore form values
		if ($t_lembur2->CurrentAction == "gridedit") { // Grid edit
			if ($t_lembur2->EventCancelled) {
				$t_lembur2_grid->RestoreCurrentRowFormValues($t_lembur2_grid->RowIndex); // Restore form values
			}
			if ($t_lembur2_grid->RowAction == "insert")
				$t_lembur2->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_lembur2->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_lembur2->CurrentAction == "gridedit" && ($t_lembur2->RowType == EW_ROWTYPE_EDIT || $t_lembur2->RowType == EW_ROWTYPE_ADD) && $t_lembur2->EventCancelled) // Update failed
			$t_lembur2_grid->RestoreCurrentRowFormValues($t_lembur2_grid->RowIndex); // Restore form values
		if ($t_lembur2->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_lembur2_grid->EditRowCnt++;
		if ($t_lembur2->CurrentAction == "F") // Confirm row
			$t_lembur2_grid->RestoreCurrentRowFormValues($t_lembur2_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_lembur2->RowAttrs = array_merge($t_lembur2->RowAttrs, array('data-rowindex'=>$t_lembur2_grid->RowCnt, 'id'=>'r' . $t_lembur2_grid->RowCnt . '_t_lembur2', 'data-rowtype'=>$t_lembur2->RowType));

		// Render row
		$t_lembur2_grid->RenderRow();

		// Render list options
		$t_lembur2_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_lembur2_grid->RowAction <> "delete" && $t_lembur2_grid->RowAction <> "insertdelete" && !($t_lembur2_grid->RowAction == "insert" && $t_lembur2->CurrentAction == "F" && $t_lembur2_grid->EmptyRow())) {
?>
	<tr<?php echo $t_lembur2->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_lembur2_grid->ListOptions->Render("body", "left", $t_lembur2_grid->RowCnt);
?>
	<?php if ($t_lembur2->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id"<?php echo $t_lembur2->pegawai_id->CellAttributes() ?>>
<?php if ($t_lembur2->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_lembur2->pegawai_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_lembur2_grid->RowCnt ?>_t_lembur2_pegawai_id" class="form-group t_lembur2_pegawai_id">
<span<?php echo $t_lembur2->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur2->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur2->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_lembur2_grid->RowCnt ?>_t_lembur2_pegawai_id" class="form-group t_lembur2_pegawai_id">
<input type="text" data-table="t_lembur2" data-field="x_pegawai_id" name="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_lembur2->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $t_lembur2->pegawai_id->EditValue ?>"<?php echo $t_lembur2->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_lembur2" data-field="x_pegawai_id" name="o<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur2->pegawai_id->OldValue) ?>">
<?php } ?>
<?php if ($t_lembur2->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_lembur2->pegawai_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_lembur2_grid->RowCnt ?>_t_lembur2_pegawai_id" class="form-group t_lembur2_pegawai_id">
<span<?php echo $t_lembur2->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur2->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur2->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_lembur2_grid->RowCnt ?>_t_lembur2_pegawai_id" class="form-group t_lembur2_pegawai_id">
<input type="text" data-table="t_lembur2" data-field="x_pegawai_id" name="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_lembur2->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $t_lembur2->pegawai_id->EditValue ?>"<?php echo $t_lembur2->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($t_lembur2->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_lembur2_grid->RowCnt ?>_t_lembur2_pegawai_id" class="t_lembur2_pegawai_id">
<span<?php echo $t_lembur2->pegawai_id->ViewAttributes() ?>>
<?php echo $t_lembur2->pegawai_id->ListViewValue() ?></span>
</span>
<?php if ($t_lembur2->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_lembur2" data-field="x_pegawai_id" name="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur2->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_lembur2" data-field="x_pegawai_id" name="o<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur2->pegawai_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_lembur2" data-field="x_pegawai_id" name="ft_lembur2grid$x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" id="ft_lembur2grid$x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur2->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_lembur2" data-field="x_pegawai_id" name="ft_lembur2grid$o<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" id="ft_lembur2grid$o<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur2->pegawai_id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_lembur2_grid->PageObjName . "_row_" . $t_lembur2_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_lembur2->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_lembur2" data-field="x_lembur_id" name="x<?php echo $t_lembur2_grid->RowIndex ?>_lembur_id" id="x<?php echo $t_lembur2_grid->RowIndex ?>_lembur_id" value="<?php echo ew_HtmlEncode($t_lembur2->lembur_id->CurrentValue) ?>">
<input type="hidden" data-table="t_lembur2" data-field="x_lembur_id" name="o<?php echo $t_lembur2_grid->RowIndex ?>_lembur_id" id="o<?php echo $t_lembur2_grid->RowIndex ?>_lembur_id" value="<?php echo ew_HtmlEncode($t_lembur2->lembur_id->OldValue) ?>">
<?php } ?>
<?php if ($t_lembur2->RowType == EW_ROWTYPE_EDIT || $t_lembur2->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_lembur2" data-field="x_lembur_id" name="x<?php echo $t_lembur2_grid->RowIndex ?>_lembur_id" id="x<?php echo $t_lembur2_grid->RowIndex ?>_lembur_id" value="<?php echo ew_HtmlEncode($t_lembur2->lembur_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_lembur2->tgl->Visible) { // tgl ?>
		<td data-name="tgl"<?php echo $t_lembur2->tgl->CellAttributes() ?>>
<?php if ($t_lembur2->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_lembur2_grid->RowCnt ?>_t_lembur2_tgl" class="form-group t_lembur2_tgl">
<input type="text" data-table="t_lembur2" data-field="x_tgl" name="x<?php echo $t_lembur2_grid->RowIndex ?>_tgl" id="x<?php echo $t_lembur2_grid->RowIndex ?>_tgl" placeholder="<?php echo ew_HtmlEncode($t_lembur2->tgl->getPlaceHolder()) ?>" value="<?php echo $t_lembur2->tgl->EditValue ?>"<?php echo $t_lembur2->tgl->EditAttributes() ?>>
<?php if (!$t_lembur2->tgl->ReadOnly && !$t_lembur2->tgl->Disabled && !isset($t_lembur2->tgl->EditAttrs["readonly"]) && !isset($t_lembur2->tgl->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_lembur2grid", "x<?php echo $t_lembur2_grid->RowIndex ?>_tgl", 0);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_lembur2" data-field="x_tgl" name="o<?php echo $t_lembur2_grid->RowIndex ?>_tgl" id="o<?php echo $t_lembur2_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_lembur2->tgl->OldValue) ?>">
<?php } ?>
<?php if ($t_lembur2->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_lembur2_grid->RowCnt ?>_t_lembur2_tgl" class="form-group t_lembur2_tgl">
<input type="text" data-table="t_lembur2" data-field="x_tgl" name="x<?php echo $t_lembur2_grid->RowIndex ?>_tgl" id="x<?php echo $t_lembur2_grid->RowIndex ?>_tgl" placeholder="<?php echo ew_HtmlEncode($t_lembur2->tgl->getPlaceHolder()) ?>" value="<?php echo $t_lembur2->tgl->EditValue ?>"<?php echo $t_lembur2->tgl->EditAttributes() ?>>
<?php if (!$t_lembur2->tgl->ReadOnly && !$t_lembur2->tgl->Disabled && !isset($t_lembur2->tgl->EditAttrs["readonly"]) && !isset($t_lembur2->tgl->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_lembur2grid", "x<?php echo $t_lembur2_grid->RowIndex ?>_tgl", 0);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_lembur2->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_lembur2_grid->RowCnt ?>_t_lembur2_tgl" class="t_lembur2_tgl">
<span<?php echo $t_lembur2->tgl->ViewAttributes() ?>>
<?php echo $t_lembur2->tgl->ListViewValue() ?></span>
</span>
<?php if ($t_lembur2->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_lembur2" data-field="x_tgl" name="x<?php echo $t_lembur2_grid->RowIndex ?>_tgl" id="x<?php echo $t_lembur2_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_lembur2->tgl->FormValue) ?>">
<input type="hidden" data-table="t_lembur2" data-field="x_tgl" name="o<?php echo $t_lembur2_grid->RowIndex ?>_tgl" id="o<?php echo $t_lembur2_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_lembur2->tgl->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_lembur2" data-field="x_tgl" name="ft_lembur2grid$x<?php echo $t_lembur2_grid->RowIndex ?>_tgl" id="ft_lembur2grid$x<?php echo $t_lembur2_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_lembur2->tgl->FormValue) ?>">
<input type="hidden" data-table="t_lembur2" data-field="x_tgl" name="ft_lembur2grid$o<?php echo $t_lembur2_grid->RowIndex ?>_tgl" id="ft_lembur2grid$o<?php echo $t_lembur2_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_lembur2->tgl->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_lembur2->lama_lembur->Visible) { // lama_lembur ?>
		<td data-name="lama_lembur"<?php echo $t_lembur2->lama_lembur->CellAttributes() ?>>
<?php if ($t_lembur2->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_lembur2_grid->RowCnt ?>_t_lembur2_lama_lembur" class="form-group t_lembur2_lama_lembur">
<input type="text" data-table="t_lembur2" data-field="x_lama_lembur" name="x<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" id="x<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" size="30" placeholder="<?php echo ew_HtmlEncode($t_lembur2->lama_lembur->getPlaceHolder()) ?>" value="<?php echo $t_lembur2->lama_lembur->EditValue ?>"<?php echo $t_lembur2->lama_lembur->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_lembur2" data-field="x_lama_lembur" name="o<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" id="o<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" value="<?php echo ew_HtmlEncode($t_lembur2->lama_lembur->OldValue) ?>">
<?php } ?>
<?php if ($t_lembur2->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_lembur2_grid->RowCnt ?>_t_lembur2_lama_lembur" class="form-group t_lembur2_lama_lembur">
<input type="text" data-table="t_lembur2" data-field="x_lama_lembur" name="x<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" id="x<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" size="30" placeholder="<?php echo ew_HtmlEncode($t_lembur2->lama_lembur->getPlaceHolder()) ?>" value="<?php echo $t_lembur2->lama_lembur->EditValue ?>"<?php echo $t_lembur2->lama_lembur->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_lembur2->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_lembur2_grid->RowCnt ?>_t_lembur2_lama_lembur" class="t_lembur2_lama_lembur">
<span<?php echo $t_lembur2->lama_lembur->ViewAttributes() ?>>
<?php echo $t_lembur2->lama_lembur->ListViewValue() ?></span>
</span>
<?php if ($t_lembur2->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_lembur2" data-field="x_lama_lembur" name="x<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" id="x<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" value="<?php echo ew_HtmlEncode($t_lembur2->lama_lembur->FormValue) ?>">
<input type="hidden" data-table="t_lembur2" data-field="x_lama_lembur" name="o<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" id="o<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" value="<?php echo ew_HtmlEncode($t_lembur2->lama_lembur->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_lembur2" data-field="x_lama_lembur" name="ft_lembur2grid$x<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" id="ft_lembur2grid$x<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" value="<?php echo ew_HtmlEncode($t_lembur2->lama_lembur->FormValue) ?>">
<input type="hidden" data-table="t_lembur2" data-field="x_lama_lembur" name="ft_lembur2grid$o<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" id="ft_lembur2grid$o<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" value="<?php echo ew_HtmlEncode($t_lembur2->lama_lembur->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_lembur2_grid->ListOptions->Render("body", "right", $t_lembur2_grid->RowCnt);
?>
	</tr>
<?php if ($t_lembur2->RowType == EW_ROWTYPE_ADD || $t_lembur2->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_lembur2grid.UpdateOpts(<?php echo $t_lembur2_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_lembur2->CurrentAction <> "gridadd" || $t_lembur2->CurrentMode == "copy")
		if (!$t_lembur2_grid->Recordset->EOF) $t_lembur2_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_lembur2->CurrentMode == "add" || $t_lembur2->CurrentMode == "copy" || $t_lembur2->CurrentMode == "edit") {
		$t_lembur2_grid->RowIndex = '$rowindex$';
		$t_lembur2_grid->LoadDefaultValues();

		// Set row properties
		$t_lembur2->ResetAttrs();
		$t_lembur2->RowAttrs = array_merge($t_lembur2->RowAttrs, array('data-rowindex'=>$t_lembur2_grid->RowIndex, 'id'=>'r0_t_lembur2', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_lembur2->RowAttrs["class"], "ewTemplate");
		$t_lembur2->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_lembur2_grid->RenderRow();

		// Render list options
		$t_lembur2_grid->RenderListOptions();
		$t_lembur2_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_lembur2->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_lembur2_grid->ListOptions->Render("body", "left", $t_lembur2_grid->RowIndex);
?>
	<?php if ($t_lembur2->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id">
<?php if ($t_lembur2->CurrentAction <> "F") { ?>
<?php if ($t_lembur2->pegawai_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_lembur2_pegawai_id" class="form-group t_lembur2_pegawai_id">
<span<?php echo $t_lembur2->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur2->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur2->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_lembur2_pegawai_id" class="form-group t_lembur2_pegawai_id">
<input type="text" data-table="t_lembur2" data-field="x_pegawai_id" name="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_lembur2->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $t_lembur2->pegawai_id->EditValue ?>"<?php echo $t_lembur2->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_lembur2_pegawai_id" class="form-group t_lembur2_pegawai_id">
<span<?php echo $t_lembur2->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur2->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_lembur2" data-field="x_pegawai_id" name="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur2->pegawai_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_lembur2" data-field="x_pegawai_id" name="o<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_lembur2_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur2->pegawai_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_lembur2->tgl->Visible) { // tgl ?>
		<td data-name="tgl">
<?php if ($t_lembur2->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_lembur2_tgl" class="form-group t_lembur2_tgl">
<input type="text" data-table="t_lembur2" data-field="x_tgl" name="x<?php echo $t_lembur2_grid->RowIndex ?>_tgl" id="x<?php echo $t_lembur2_grid->RowIndex ?>_tgl" placeholder="<?php echo ew_HtmlEncode($t_lembur2->tgl->getPlaceHolder()) ?>" value="<?php echo $t_lembur2->tgl->EditValue ?>"<?php echo $t_lembur2->tgl->EditAttributes() ?>>
<?php if (!$t_lembur2->tgl->ReadOnly && !$t_lembur2->tgl->Disabled && !isset($t_lembur2->tgl->EditAttrs["readonly"]) && !isset($t_lembur2->tgl->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_lembur2grid", "x<?php echo $t_lembur2_grid->RowIndex ?>_tgl", 0);
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_lembur2_tgl" class="form-group t_lembur2_tgl">
<span<?php echo $t_lembur2->tgl->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur2->tgl->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_lembur2" data-field="x_tgl" name="x<?php echo $t_lembur2_grid->RowIndex ?>_tgl" id="x<?php echo $t_lembur2_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_lembur2->tgl->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_lembur2" data-field="x_tgl" name="o<?php echo $t_lembur2_grid->RowIndex ?>_tgl" id="o<?php echo $t_lembur2_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_lembur2->tgl->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_lembur2->lama_lembur->Visible) { // lama_lembur ?>
		<td data-name="lama_lembur">
<?php if ($t_lembur2->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_lembur2_lama_lembur" class="form-group t_lembur2_lama_lembur">
<input type="text" data-table="t_lembur2" data-field="x_lama_lembur" name="x<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" id="x<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" size="30" placeholder="<?php echo ew_HtmlEncode($t_lembur2->lama_lembur->getPlaceHolder()) ?>" value="<?php echo $t_lembur2->lama_lembur->EditValue ?>"<?php echo $t_lembur2->lama_lembur->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_lembur2_lama_lembur" class="form-group t_lembur2_lama_lembur">
<span<?php echo $t_lembur2->lama_lembur->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur2->lama_lembur->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_lembur2" data-field="x_lama_lembur" name="x<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" id="x<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" value="<?php echo ew_HtmlEncode($t_lembur2->lama_lembur->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_lembur2" data-field="x_lama_lembur" name="o<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" id="o<?php echo $t_lembur2_grid->RowIndex ?>_lama_lembur" value="<?php echo ew_HtmlEncode($t_lembur2->lama_lembur->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_lembur2_grid->ListOptions->Render("body", "right", $t_lembur2_grid->RowCnt);
?>
<script type="text/javascript">
ft_lembur2grid.UpdateOpts(<?php echo $t_lembur2_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_lembur2->CurrentMode == "add" || $t_lembur2->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_lembur2_grid->FormKeyCountName ?>" id="<?php echo $t_lembur2_grid->FormKeyCountName ?>" value="<?php echo $t_lembur2_grid->KeyCount ?>">
<?php echo $t_lembur2_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_lembur2->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_lembur2_grid->FormKeyCountName ?>" id="<?php echo $t_lembur2_grid->FormKeyCountName ?>" value="<?php echo $t_lembur2_grid->KeyCount ?>">
<?php echo $t_lembur2_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_lembur2->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_lembur2grid">
</div>
<?php

// Close recordset
if ($t_lembur2_grid->Recordset)
	$t_lembur2_grid->Recordset->Close();
?>
<?php if ($t_lembur2_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_lembur2_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_lembur2_grid->TotalRecs == 0 && $t_lembur2->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_lembur2_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_lembur2->Export == "") { ?>
<script type="text/javascript">
ft_lembur2grid.Init();
</script>
<?php } ?>
<?php
$t_lembur2_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_lembur2_grid->Page_Terminate();
?>

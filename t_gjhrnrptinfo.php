<?php

// Global variable for table object
$t_gjhrn = NULL;

//
// Table class for t_gjhrn
//
class crt_gjhrn extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $gjhrn_id;
	var $bagian;
	var $divisi;
	var $rec_no;
	var $nama;
	var $nip;
	var $upah;
	var $premi_malam;
	var $premi_hadir;
	var $pot_absen;
	var $total;
	var $start;
	var $end;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $gsLanguage;
		$this->TableVar = 't_gjhrn';
		$this->TableName = 't_gjhrn';
		$this->TableType = 'TABLE';
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;

		// gjhrn_id
		$this->gjhrn_id = new crField('t_gjhrn', 't_gjhrn', 'x_gjhrn_id', 'gjhrn_id', '`gjhrn_id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->gjhrn_id->Sortable = TRUE; // Allow sort
		$this->gjhrn_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['gjhrn_id'] = &$this->gjhrn_id;
		$this->gjhrn_id->DateFilter = "";
		$this->gjhrn_id->SqlSelect = "";
		$this->gjhrn_id->SqlOrderBy = "";

		// bagian
		$this->bagian = new crField('t_gjhrn', 't_gjhrn', 'x_bagian', 'bagian', '`bagian`', 200, EWR_DATATYPE_STRING, -1);
		$this->bagian->Sortable = TRUE; // Allow sort
		$this->fields['bagian'] = &$this->bagian;
		$this->bagian->DateFilter = "";
		$this->bagian->SqlSelect = "";
		$this->bagian->SqlOrderBy = "";

		// divisi
		$this->divisi = new crField('t_gjhrn', 't_gjhrn', 'x_divisi', 'divisi', '`divisi`', 200, EWR_DATATYPE_STRING, -1);
		$this->divisi->Sortable = TRUE; // Allow sort
		$this->fields['divisi'] = &$this->divisi;
		$this->divisi->DateFilter = "";
		$this->divisi->SqlSelect = "";
		$this->divisi->SqlOrderBy = "";

		// rec_no
		$this->rec_no = new crField('t_gjhrn', 't_gjhrn', 'x_rec_no', 'rec_no', '\'\'', 201, EWR_DATATYPE_MEMO, -1);
		$this->rec_no->FldIsCustom = TRUE; // Custom field
		$this->rec_no->Sortable = TRUE; // Allow sort
		$this->fields['rec_no'] = &$this->rec_no;
		$this->rec_no->DateFilter = "";
		$this->rec_no->SqlSelect = "";
		$this->rec_no->SqlOrderBy = "";

		// nama
		$this->nama = new crField('t_gjhrn', 't_gjhrn', 'x_nama', 'nama', '`nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->nama->Sortable = TRUE; // Allow sort
		$this->fields['nama'] = &$this->nama;
		$this->nama->DateFilter = "";
		$this->nama->SqlSelect = "";
		$this->nama->SqlOrderBy = "";

		// nip
		$this->nip = new crField('t_gjhrn', 't_gjhrn', 'x_nip', 'nip', '`nip`', 200, EWR_DATATYPE_STRING, -1);
		$this->nip->Sortable = TRUE; // Allow sort
		$this->fields['nip'] = &$this->nip;
		$this->nip->DateFilter = "";
		$this->nip->SqlSelect = "";
		$this->nip->SqlOrderBy = "";

		// upah
		$this->upah = new crField('t_gjhrn', 't_gjhrn', 'x_upah', 'upah', '`upah`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->upah->Sortable = TRUE; // Allow sort
		$this->upah->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['upah'] = &$this->upah;
		$this->upah->DateFilter = "";
		$this->upah->SqlSelect = "";
		$this->upah->SqlOrderBy = "";

		// premi_malam
		$this->premi_malam = new crField('t_gjhrn', 't_gjhrn', 'x_premi_malam', 'premi_malam', '`premi_malam`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->premi_malam->Sortable = TRUE; // Allow sort
		$this->premi_malam->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['premi_malam'] = &$this->premi_malam;
		$this->premi_malam->DateFilter = "";
		$this->premi_malam->SqlSelect = "";
		$this->premi_malam->SqlOrderBy = "";

		// premi_hadir
		$this->premi_hadir = new crField('t_gjhrn', 't_gjhrn', 'x_premi_hadir', 'premi_hadir', '`premi_hadir`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->premi_hadir->Sortable = TRUE; // Allow sort
		$this->premi_hadir->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['premi_hadir'] = &$this->premi_hadir;
		$this->premi_hadir->DateFilter = "";
		$this->premi_hadir->SqlSelect = "";
		$this->premi_hadir->SqlOrderBy = "";

		// pot_absen
		$this->pot_absen = new crField('t_gjhrn', 't_gjhrn', 'x_pot_absen', 'pot_absen', '`pot_absen`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->pot_absen->Sortable = TRUE; // Allow sort
		$this->pot_absen->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['pot_absen'] = &$this->pot_absen;
		$this->pot_absen->DateFilter = "";
		$this->pot_absen->SqlSelect = "";
		$this->pot_absen->SqlOrderBy = "";

		// total
		$this->total = new crField('t_gjhrn', 't_gjhrn', 'x_total', 'total', '`total`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->total->Sortable = TRUE; // Allow sort
		$this->total->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['total'] = &$this->total;
		$this->total->DateFilter = "";
		$this->total->SqlSelect = "";
		$this->total->SqlOrderBy = "";

		// start
		$this->start = new crField('t_gjhrn', 't_gjhrn', 'x_start', 'start', '`start`', 133, EWR_DATATYPE_DATE, 0);
		$this->start->Sortable = TRUE; // Allow sort
		$this->start->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fields['start'] = &$this->start;
		$this->start->DateFilter = "";
		$this->start->SqlSelect = "";
		$this->start->SqlOrderBy = "";

		// end
		$this->end = new crField('t_gjhrn', 't_gjhrn', 'x_end', 'end', '`end`', 133, EWR_DATATYPE_DATE, 0);
		$this->end->Sortable = TRUE; // Allow sort
		$this->end->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fields['end'] = &$this->end;
		$this->end->DateFilter = "";
		$this->end->SqlSelect = "";
		$this->end->SqlOrderBy = "";
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ofld->GroupingFieldId == 0) {
				if ($ctrl) {
					$sOrderBy = $this->getDetailOrderBy();
					if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
						$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
					} else {
						if ($sOrderBy <> "") $sOrderBy .= ", ";
						$sOrderBy .= $sSortField . " " . $sThisSort;
					}
					$this->setDetailOrderBy($sOrderBy); // Save to Session
				} else {
					$this->setDetailOrderBy($sSortField . " " . $sThisSort); // Save to Session
				}
			}
		} else {
			if ($ofld->GroupingFieldId == 0 && !$ctrl) $ofld->setSort("");
		}
	}

	// Get Sort SQL
	function SortSql() {
		$sDtlSortSql = $this->getDetailOrderBy(); // Get ORDER BY for detail fields from session
		$argrps = array();
		foreach ($this->fields as $fld) {
			if ($fld->getSort() <> "") {
				$fldsql = $fld->FldExpression;
				if ($fld->GroupingFieldId > 0) {
					if ($fld->FldGroupSql <> "")
						$argrps[$fld->GroupingFieldId] = str_replace("%s", $fldsql, $fld->FldGroupSql) . " " . $fld->getSort();
					else
						$argrps[$fld->GroupingFieldId] = $fldsql . " " . $fld->getSort();
				}
			}
		}
		$sSortSql = "";
		foreach ($argrps as $grp) {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $grp;
		}
		if ($sDtlSortSql <> "") {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $sDtlSortSql;
		}
		return $sSortSql;
	}

	// Table level SQL
	// From

	var $_SqlFrom = "";

	function getSqlFrom() {
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`t_gjhrn`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}

	// Select
	var $_SqlSelect = "";

	function getSqlSelect() {
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT *, '' AS `rec_no` FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}

	// Where
	var $_SqlWhere = "";

	function getSqlWhere() {
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}

	// Group By
	var $_SqlGroupBy = "";

	function getSqlGroupBy() {
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}

	// Having
	var $_SqlHaving = "";

	function getSqlHaving() {
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}

	// Order By
	var $_SqlOrderBy = "";

	function getSqlOrderBy() {
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Select Aggregate
	var $_SqlSelectAgg = "";

	function getSqlSelectAgg() {
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelectAgg() { // For backward compatibility
		return $this->getSqlSelectAgg();
	}

	function setSqlSelectAgg($v) {
		$this->_SqlSelectAgg = $v;
	}

	// Aggregate Prefix
	var $_SqlAggPfx = "";

	function getSqlAggPfx() {
		return ($this->_SqlAggPfx <> "") ? $this->_SqlAggPfx : "";
	}

	function SqlAggPfx() { // For backward compatibility
		return $this->getSqlAggPfx();
	}

	function setSqlAggPfx($v) {
		$this->_SqlAggPfx = $v;
	}

	// Aggregate Suffix
	var $_SqlAggSfx = "";

	function getSqlAggSfx() {
		return ($this->_SqlAggSfx <> "") ? $this->_SqlAggSfx : "";
	}

	function SqlAggSfx() { // For backward compatibility
		return $this->getSqlAggSfx();
	}

	function setSqlAggSfx($v) {
		$this->_SqlAggSfx = $v;
	}

	// Select Count
	var $_SqlSelectCount = "";

	function getSqlSelectCount() {
		return ($this->_SqlSelectCount <> "") ? $this->_SqlSelectCount : "SELECT COUNT(*) FROM " . $this->getSqlFrom();
	}

	function SqlSelectCount() { // For backward compatibility
		return $this->getSqlSelectCount();
	}

	function setSqlSelectCount($v) {
		$this->_SqlSelectCount = $v;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {

			//$sUrlParm = "order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort();
			$sUrlParm = "order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort();
			return ewr_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld) {
		global $gsLanguage;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld) {
		global $gsLanguage;
		switch ($fld->FldVar) {
		}
	}

	// Table level events
	// Page Selecting event
	function Page_Selecting(&$filter) {

		// Enter your code here
	}

	// Page Breaking event
	function Page_Breaking(&$break, &$content) {

		// Example:
		//$break = FALSE; // Skip page break, or
		//$content = "<div style=\"page-break-after:always;\">&nbsp;</div>"; // Modify page break content

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Cell Rendered event
	function Cell_Rendered(&$Field, $CurrentValue, &$ViewValue, &$ViewAttrs, &$CellAttrs, &$HrefValue, &$LinkAttrs) {

		//$ViewValue = "xxx";
		//$ViewAttrs["style"] = "xxx";

	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}

	// Load Filters event
	function Page_FilterLoad() {

		// Enter your code here
		// Example: Register/Unregister Custom Extended Filter
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // With function, or
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A'); // No function, use Page_Filtering event
		//ewr_UnregisterFilter($this-><Field>, 'StartsWithA');

	}

	// Page Filter Validated event
	function Page_FilterValidated() {

		// Example:
		//$this->MyField1->SearchValue = "your search criteria"; // Search value

	}

	// Page Filtering event
	function Page_Filtering(&$fld, &$filter, $typ, $opr = "", $val = "", $cond = "", $opr2 = "", $val2 = "") {

		// Note: ALWAYS CHECK THE FILTER TYPE ($typ)! Example:
		//if ($typ == "dropdown" && $fld->FldName == "MyField") // Dropdown filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "extended" && $fld->FldName == "MyField") // Extended filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "popup" && $fld->FldName == "MyField") // Popup filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "custom" && $opr == "..." && $fld->FldName == "MyField") // Custom filter, $opr is the custom filter ID
		//	$filter = "..."; // Modify the filter

	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}
}
?>

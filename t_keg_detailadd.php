<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_keg_detailinfo.php" ?>
<?php include_once "t_keg_masterinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_keg_detail_add = NULL; // Initialize page object first

class ct_keg_detail_add extends ct_keg_detail {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 't_keg_detail';

	// Page object name
	var $PageObjName = 't_keg_detail_add';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (t_keg_detail)
		if (!isset($GLOBALS["t_keg_detail"]) || get_class($GLOBALS["t_keg_detail"]) == "ct_keg_detail") {
			$GLOBALS["t_keg_detail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_keg_detail"];
		}

		// Table object (t_keg_master)
		if (!isset($GLOBALS['t_keg_master'])) $GLOBALS['t_keg_master'] = new ct_keg_master();

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_keg_detail', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_user)
		if (!isset($UserTable)) {
			$UserTable = new ct_user();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("t_keg_detaillist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->pegawai_id->SetVisibility();
		$this->scan_masuk->SetVisibility();
		$this->scan_keluar->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $t_keg_detail;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_keg_detail);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["kegd_id"] != "") {
				$this->kegd_id->setQueryStringValue($_GET["kegd_id"]);
				$this->setKey("kegd_id", $this->kegd_id->CurrentValue); // Set up key
			} else {
				$this->setKey("kegd_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("t_keg_detaillist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t_keg_detaillist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "t_keg_detailview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->pegawai_id->CurrentValue = NULL;
		$this->pegawai_id->OldValue = $this->pegawai_id->CurrentValue;
		$this->scan_masuk->CurrentValue = NULL;
		$this->scan_masuk->OldValue = $this->scan_masuk->CurrentValue;
		$this->scan_keluar->CurrentValue = NULL;
		$this->scan_keluar->OldValue = $this->scan_keluar->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->pegawai_id->FldIsDetailKey) {
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
		}
		if (!$this->scan_masuk->FldIsDetailKey) {
			$this->scan_masuk->setFormValue($objForm->GetValue("x_scan_masuk"));
			$this->scan_masuk->CurrentValue = ew_UnFormatDateTime($this->scan_masuk->CurrentValue, 17);
		}
		if (!$this->scan_keluar->FldIsDetailKey) {
			$this->scan_keluar->setFormValue($objForm->GetValue("x_scan_keluar"));
			$this->scan_keluar->CurrentValue = ew_UnFormatDateTime($this->scan_keluar->CurrentValue, 17);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
		$this->scan_masuk->CurrentValue = $this->scan_masuk->FormValue;
		$this->scan_masuk->CurrentValue = ew_UnFormatDateTime($this->scan_masuk->CurrentValue, 17);
		$this->scan_keluar->CurrentValue = $this->scan_keluar->FormValue;
		$this->scan_keluar->CurrentValue = ew_UnFormatDateTime($this->scan_keluar->CurrentValue, 17);
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->kegd_id->setDbValue($rs->fields('kegd_id'));
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
		if (array_key_exists('EV__pegawai_id', $rs->fields)) {
			$this->pegawai_id->VirtualValue = $rs->fields('EV__pegawai_id'); // Set up virtual field value
		} else {
			$this->pegawai_id->VirtualValue = ""; // Clear value
		}
		$this->kegm_id->setDbValue($rs->fields('kegm_id'));
		$this->scan_masuk->setDbValue($rs->fields('scan_masuk'));
		$this->scan_keluar->setDbValue($rs->fields('scan_keluar'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->kegd_id->DbValue = $row['kegd_id'];
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->kegm_id->DbValue = $row['kegm_id'];
		$this->scan_masuk->DbValue = $row['scan_masuk'];
		$this->scan_keluar->DbValue = $row['scan_keluar'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("kegd_id")) <> "")
			$this->kegd_id->CurrentValue = $this->getKey("kegd_id"); // kegd_id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// kegd_id
		// pegawai_id
		// kegm_id
		// scan_masuk
		// scan_keluar

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// kegd_id
		$this->kegd_id->ViewValue = $this->kegd_id->CurrentValue;
		$this->kegd_id->ViewCustomAttributes = "";

		// pegawai_id
		if ($this->pegawai_id->VirtualValue <> "") {
			$this->pegawai_id->ViewValue = $this->pegawai_id->VirtualValue;
		} else {
		if (strval($this->pegawai_id->CurrentValue) <> "") {
			$sFilterWrk = "`pegawai_id`" . ew_SearchString("=", $this->pegawai_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `pegawai_id`, `pegawai_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pegawai`";
		$sWhereWrk = "";
		$this->pegawai_id->LookupFilters = array("dx1" => '`pegawai_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->pegawai_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->pegawai_id->ViewValue = $this->pegawai_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
			}
		} else {
			$this->pegawai_id->ViewValue = NULL;
		}
		}
		$this->pegawai_id->ViewCustomAttributes = "";

		// kegm_id
		$this->kegm_id->ViewValue = $this->kegm_id->CurrentValue;
		$this->kegm_id->ViewCustomAttributes = "";

		// scan_masuk
		$this->scan_masuk->ViewValue = $this->scan_masuk->CurrentValue;
		$this->scan_masuk->ViewValue = ew_FormatDateTime($this->scan_masuk->ViewValue, 17);
		$this->scan_masuk->ViewCustomAttributes = "";

		// scan_keluar
		$this->scan_keluar->ViewValue = $this->scan_keluar->CurrentValue;
		$this->scan_keluar->ViewValue = ew_FormatDateTime($this->scan_keluar->ViewValue, 17);
		$this->scan_keluar->ViewCustomAttributes = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";

			// scan_masuk
			$this->scan_masuk->LinkCustomAttributes = "";
			$this->scan_masuk->HrefValue = "";
			$this->scan_masuk->TooltipValue = "";

			// scan_keluar
			$this->scan_keluar->LinkCustomAttributes = "";
			$this->scan_keluar->HrefValue = "";
			$this->scan_keluar->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// pegawai_id
			$this->pegawai_id->EditCustomAttributes = "";
			if (trim(strval($this->pegawai_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`pegawai_id`" . ew_SearchString("=", $this->pegawai_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `pegawai_id`, `pegawai_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `pegawai`";
			$sWhereWrk = "";
			$this->pegawai_id->LookupFilters = array("dx1" => '`pegawai_nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->pegawai_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->pegawai_id->ViewValue = $this->pegawai_id->DisplayValue($arwrk);
			} else {
				$this->pegawai_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->pegawai_id->EditValue = $arwrk;

			// scan_masuk
			$this->scan_masuk->EditAttrs["class"] = "form-control";
			$this->scan_masuk->EditCustomAttributes = "";
			$this->scan_masuk->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->scan_masuk->CurrentValue, 17));
			$this->scan_masuk->PlaceHolder = ew_RemoveHtml($this->scan_masuk->FldCaption());

			// scan_keluar
			$this->scan_keluar->EditAttrs["class"] = "form-control";
			$this->scan_keluar->EditCustomAttributes = "";
			$this->scan_keluar->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->scan_keluar->CurrentValue, 17));
			$this->scan_keluar->PlaceHolder = ew_RemoveHtml($this->scan_keluar->FldCaption());

			// Add refer script
			// pegawai_id

			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";

			// scan_masuk
			$this->scan_masuk->LinkCustomAttributes = "";
			$this->scan_masuk->HrefValue = "";

			// scan_keluar
			$this->scan_keluar->LinkCustomAttributes = "";
			$this->scan_keluar->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->pegawai_id->FldIsDetailKey && !is_null($this->pegawai_id->FormValue) && $this->pegawai_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_id->FldCaption(), $this->pegawai_id->ReqErrMsg));
		}
		if (!ew_CheckShortEuroDate($this->scan_masuk->FormValue)) {
			ew_AddMessage($gsFormError, $this->scan_masuk->FldErrMsg());
		}
		if (!ew_CheckShortEuroDate($this->scan_keluar->FormValue)) {
			ew_AddMessage($gsFormError, $this->scan_keluar->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// pegawai_id
		$this->pegawai_id->SetDbValueDef($rsnew, $this->pegawai_id->CurrentValue, 0, FALSE);

		// scan_masuk
		$this->scan_masuk->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->scan_masuk->CurrentValue, 17), NULL, FALSE);

		// scan_keluar
		$this->scan_keluar->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->scan_keluar->CurrentValue, 17), NULL, FALSE);

		// kegm_id
		if ($this->kegm_id->getSessionValue() <> "") {
			$rsnew['kegm_id'] = $this->kegm_id->getSessionValue();
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "t_keg_master") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_kegm_id"] <> "") {
					$GLOBALS["t_keg_master"]->kegm_id->setQueryStringValue($_GET["fk_kegm_id"]);
					$this->kegm_id->setQueryStringValue($GLOBALS["t_keg_master"]->kegm_id->QueryStringValue);
					$this->kegm_id->setSessionValue($this->kegm_id->QueryStringValue);
					if (!is_numeric($GLOBALS["t_keg_master"]->kegm_id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "t_keg_master") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_kegm_id"] <> "") {
					$GLOBALS["t_keg_master"]->kegm_id->setFormValue($_POST["fk_kegm_id"]);
					$this->kegm_id->setFormValue($GLOBALS["t_keg_master"]->kegm_id->FormValue);
					$this->kegm_id->setSessionValue($this->kegm_id->FormValue);
					if (!is_numeric($GLOBALS["t_keg_master"]->kegm_id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "t_keg_master") {
				if ($this->kegm_id->CurrentValue == "") $this->kegm_id->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_keg_detaillist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_pegawai_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `pegawai_id` AS `LinkFld`, `pegawai_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pegawai`";
			$sWhereWrk = "{filter}";
			$this->pegawai_id->LookupFilters = array("dx1" => '`pegawai_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`pegawai_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->pegawai_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_keg_detail_add)) $t_keg_detail_add = new ct_keg_detail_add();

// Page init
$t_keg_detail_add->Page_Init();

// Page main
$t_keg_detail_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_keg_detail_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ft_keg_detailadd = new ew_Form("ft_keg_detailadd", "add");

// Validate form
ft_keg_detailadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_keg_detail->pegawai_id->FldCaption(), $t_keg_detail->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_scan_masuk");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_keg_detail->scan_masuk->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_scan_keluar");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_keg_detail->scan_keluar->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ft_keg_detailadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_keg_detailadd.ValidateRequired = true;
<?php } else { ?>
ft_keg_detailadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_keg_detailadd.Lists["x_pegawai_id"] = {"LinkField":"x_pegawai_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pegawai_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pegawai"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_keg_detail_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_keg_detail_add->ShowPageHeader(); ?>
<?php
$t_keg_detail_add->ShowMessage();
?>
<form name="ft_keg_detailadd" id="ft_keg_detailadd" class="<?php echo $t_keg_detail_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_keg_detail_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_keg_detail_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_keg_detail">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($t_keg_detail_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($t_keg_detail->getCurrentMasterTable() == "t_keg_master") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="t_keg_master">
<input type="hidden" name="fk_kegm_id" value="<?php echo $t_keg_detail->kegm_id->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($t_keg_detail->pegawai_id->Visible) { // pegawai_id ?>
	<div id="r_pegawai_id" class="form-group">
		<label id="elh_t_keg_detail_pegawai_id" for="x_pegawai_id" class="col-sm-2 control-label ewLabel"><?php echo $t_keg_detail->pegawai_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_keg_detail->pegawai_id->CellAttributes() ?>>
<span id="el_t_keg_detail_pegawai_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_pegawai_id"><?php echo (strval($t_keg_detail->pegawai_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_keg_detail->pegawai_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_keg_detail->pegawai_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_pegawai_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_keg_detail" data-field="x_pegawai_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_keg_detail->pegawai_id->DisplayValueSeparatorAttribute() ?>" name="x_pegawai_id" id="x_pegawai_id" value="<?php echo $t_keg_detail->pegawai_id->CurrentValue ?>"<?php echo $t_keg_detail->pegawai_id->EditAttributes() ?>>
<input type="hidden" name="s_x_pegawai_id" id="s_x_pegawai_id" value="<?php echo $t_keg_detail->pegawai_id->LookupFilterQuery() ?>">
</span>
<?php echo $t_keg_detail->pegawai_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_keg_detail->scan_masuk->Visible) { // scan_masuk ?>
	<div id="r_scan_masuk" class="form-group">
		<label id="elh_t_keg_detail_scan_masuk" for="x_scan_masuk" class="col-sm-2 control-label ewLabel"><?php echo $t_keg_detail->scan_masuk->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_keg_detail->scan_masuk->CellAttributes() ?>>
<span id="el_t_keg_detail_scan_masuk">
<input type="text" data-table="t_keg_detail" data-field="x_scan_masuk" data-format="17" name="x_scan_masuk" id="x_scan_masuk" placeholder="<?php echo ew_HtmlEncode($t_keg_detail->scan_masuk->getPlaceHolder()) ?>" value="<?php echo $t_keg_detail->scan_masuk->EditValue ?>"<?php echo $t_keg_detail->scan_masuk->EditAttributes() ?>>
</span>
<?php echo $t_keg_detail->scan_masuk->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_keg_detail->scan_keluar->Visible) { // scan_keluar ?>
	<div id="r_scan_keluar" class="form-group">
		<label id="elh_t_keg_detail_scan_keluar" for="x_scan_keluar" class="col-sm-2 control-label ewLabel"><?php echo $t_keg_detail->scan_keluar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_keg_detail->scan_keluar->CellAttributes() ?>>
<span id="el_t_keg_detail_scan_keluar">
<input type="text" data-table="t_keg_detail" data-field="x_scan_keluar" data-format="17" name="x_scan_keluar" id="x_scan_keluar" placeholder="<?php echo ew_HtmlEncode($t_keg_detail->scan_keluar->getPlaceHolder()) ?>" value="<?php echo $t_keg_detail->scan_keluar->EditValue ?>"<?php echo $t_keg_detail->scan_keluar->EditAttributes() ?>>
</span>
<?php echo $t_keg_detail->scan_keluar->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (strval($t_keg_detail->kegm_id->getSessionValue()) <> "") { ?>
<input type="hidden" name="x_kegm_id" id="x_kegm_id" value="<?php echo ew_HtmlEncode(strval($t_keg_detail->kegm_id->getSessionValue())) ?>">
<?php } ?>
<?php if (!$t_keg_detail_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_keg_detail_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_keg_detailadd.Init();
</script>
<?php
$t_keg_detail_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_keg_detail_add->Page_Terminate();
?>

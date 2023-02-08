<?php
// http://localhost/LastNameTHENFirstNameCodingAsst/asstmain.php 
require_once("YourNameInclude.php");
require_once("clsDeleteSunglassRecord.php");
// main
date_default_timezone_set ('America/Toronto');
$mysqlObj; 
$TableName = "Sunglasses"; 
// writeHeaders call  
if (isset($_POST['f_CreateTable']))
  createTableForm($mysqlObj,$TableName);
else if (isset($_POST['f_Save'])) saveRecordtoTableForm($mysqlObj,$TableName) ;
   else if (isset($_POST['f_AddRecord'])) addRecordForm($mysqlObj,$TableName) ;	   
	  else if (isset($_POST['f_DeleteRecord'])) deleteRecordForm($mysqlObj,$TableName) ;	 
         else if (isset($_POST['f_DisplayData'])) displayDataForm ($mysqlObj,$TableName);
    		else if (isset($_POST['f_IssueDelete'])) issueDeleteForm ($mysqlObj,$TableName);
		        else displayMainForm();
if (isset($mysqlObj)) $mysqlObj->close();
writeFooters();


?>
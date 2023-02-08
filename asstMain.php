<?php
// http://localhost/DangPhuongUyenCodingAsst/asstmain.php 
require_once("asstInclude.php");
require_once("clsDeleteSunglassRecord.php");

function displayMainForm()
{
    echo " <form action = ? method=post> ";
    DisplayButton("f_CreateTable","Create Table","button_create-table.jpg","create table");
    
    DisplayButton("f_AddRecord","Add Record","button_add-record.jpg","add record");
   
    DisplayButton("f_DeleteRecord","Delete Record","button_delete-record.jpg","delete table");
    
    DisplayButton("f_DisplayData","Display Data","button_display-data.jpg","display data");
    echo "</form>";
}

function createTableForm($mysqlObj, $TableName)
{
    echo " <form action = ? method=post> ";
    $stmtObj = $mysqlObj->prepare ("drop table if exists $TableName");
    $stmtObj->execute();
    
    $BrandName = "BrandName varchar(10) primary key";
    $Date = "Date date";
    $CameraMP = "CameraMP int";
    $Colour = "Colour varchar(15)";
    $SQLStatement = "Create table $TableName ($BrandName, $Date, $CameraMP, $Colour)";
    $stmtObj = $mysqlObj->prepare ($SQLStatement);
    if ($stmtObj == false)
    {
        echo "Prepare failed on query $SQLStatement";
        exit;
    }
    $CreateResult = $stmtObj->execute();
    if($CreateResult)
        echo "Table $TableName created";
    else
        echo "Unable to create table $TableName";
    echo "<br>";    
    DisplayButton("f_Home","Home","button_home.jpg","home");
    echo "</form>";
}

function addRecordForm($mysqlObj, $TableName)
{
    echo " <form action = ? method=post> ";
    echo "<div class=\"datapair\">";
    echo DisplayLabel("Brand Name: ");
    echo DisplayTextbox("textbox", "f_BrandName",20);
    echo "</div>";

    echo "<div class=\"datapair\">";
    echo DisplayLabel("Date manufactured: ");
    echo DisplayTextbox("date", "f_Date", 15, date('Y-m-d'));
    echo "</div>";

    echo "<div class=\"datapair\">";
    echo DisplayLabel("Camera: ");
    echo "</div>"; 
    echo "<div class=\"datapair\">";
    echo DisplayTextbox("radio", "f_Camera", 15, "5MP", "checked");
    echo DisplayLabel("5 MP");
    echo "<br>";
    echo DisplayTextbox("radio", "f_Camera", 15, "10MP");
    echo DisplayLabel("10 MP");
echo "</div>";

    echo "<div class=\"datapair\">";
    echo DisplayLabel("Colour: ");
    echo DisplayTextbox("colour", "f_Colour", 15, "#B0C4DE");
    echo "</div>";

    echo "<div class=\"datapair\">";
    DisplayButton("f_Save","Save Record","button_save-record.jpg","save record");

    DisplayButton("f_Home","Home","button_home.jpg","home");
    echo "</div>"; 
    echo "</form>";
}

function saveRecordToTableForm($mysqlObj, $TableName)
{
    echo " <form action = ? method=post> ";
    $BrandName = $_POST['f_BrandName'];
    $Date = $_POST['f_Date'];
    if ($_POST['f_Camera'] == "5MP")  
        $CameraMP = 5;
    else
        $CameraMP = 10;
    $Colour = $_POST['f_Colour'];


    $query = "Insert Into $TableName (BrandName, Date, CameraMP, Colour) Values (?,?,?,?)";
    $stmtObj = $mysqlObj->prepare($query);
    $BindSuccess = $stmtObj->bind_param("ssis", $BrandName, $Date, $CameraMP, $Colour);
    if ($BindSuccess)
        $success = $stmtObj->execute();
    else
        echo "Bind failed: " . $stmtObj->error;
    if ($success)
        echo "Record successfully added to Sunglasses";    
        
    else
        echo "Unable to add record to Sunglasses";        
        
    $stmtObj->close();
    
    DisplayButton("f_Home","Home","button_home.jpg","home");
    echo "</form>";
}

function displayDataForm($mysqlObj, $TableName)
{
    echo " <form action = ? method=post> ";
    $query1 = "SELECT BrandName, Date, CameraMP, Colour FROM $TableName ORDER BY BrandName";
    $stmtObj = $mysqlObj->prepare($query1);
    
    $success = $stmtObj->bind_result($BrandName, $Date, $CameraMP, $Colour);
    $displayResult = $stmtObj->execute();

    $displayData = $stmtObj->fetch();

    echo " <table>
        <tr>
            <th>Brand Name</th>
            <th>Date manufactured</th>
            <th>Camera MP</th>
            <th>Colour</th>
        </tr>";
        while ($displayData)
        {
            echo "<tr>
                    <td>$BrandName</td>
                    <td>$Date</td>
                    <td>$CameraMP</td>
                    <td><input type = colour value = $Colour></td>
                  </tr>";
            break;
        }
    echo " </table>";
    DisplayButton("f_Home","Home","button_home.jpg","home");
    echo "</form>";
}

function deleteRecordForm($mysqlObj,$TableName)
{
    echo " <form action = ? method=post> ";
    echo DisplayLabel("Deletion is final: ");
    echo DisplayTextbox("textbox", "f_DeleteBrandName",20);
    DisplayButton("f_IssueDelete","Delete","button_delete.jpg","delete");
    DisplayButton("f_Home","Home","button_home.jpg","home");
    echo "</form>";
}

function issueDeleteForm($mysqlObj,$TableName)
{
    echo " <form action = ? method=post> ";
    $BrandNameDelete = $_POST['f_DeleteBrandName'];
    $issueDelete = new clsDeleteSunglassRecord();
    $numbRecord = $issueDelete->deleteTheRecord($mysqlObj,$TableName,$BrandNameDelete);
    if ($numbRecord ==0)
        echo $BrandNameDelete . " record does not exist";
    else
        echo $BrandNameDelete . " record deleted";
    DisplayButton("f_Home","Home","button_home.jpg","home");
    echo "</form>";

}

// main
date_default_timezone_set ('America/Toronto');
$mysqlObj = CreateConnectionObject();; 
$TableName = "Sunglasses"; 
// writeHeaders call  
WriteHeaders("Sunglass Inventory", "Bluetooth Smart Sunglasses");
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




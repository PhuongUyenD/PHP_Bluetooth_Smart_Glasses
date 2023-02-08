<?php
// http://localhost/asstInclude.php
function WriteHeaders ( $Heading="Welcome",$TitleBar="MySite")
{
    echo "
    <!doctype html>
    <html lang = \"en\">
    <head>
        <meta charset = \"UTF-8\">
        <title>$TitleBar</title>\n
        <link rel =\"stylesheet\" type = \"text/css\" href=\"asstStyle.css\"/>
    </head>
    <body>\n
    <h1>$Heading - Phuong Uyen Dang</h1>\n
    ";
}

function DisplayLabel($Label)
{
    echo "<label>" . $Label . "</label>";
}

function DisplayTextbox($InputType, $Name, $Size, $Value=0)
{
    echo "<input type = \"$InputType\" name = \"$Name\" Size = $Size Value = \"$Value\">";
}

function DisplayImage($fileName,$alt,$height,$witdh)
{
    echo "
        <img src = \"$fileName\" height=\"$height\" width=\"$witdh\" alt=\"$alt\"/>
    ";
}

function DisplayButton($Name,$Text,$fileName = "",$alt = "")
{
    if ($fileName === "")
        echo "<button type= submit name = \"$Name\">".$Text. "</button>";
    else {
        echo "
        <button type= submit name = \"$Name\">";
        DisplayImage("$fileName","$alt","30","90");
        echo "</button>";
    }
}

function DisplayContactInfo()
{
    echo "
        <footer>
            Questions? Comments? | Email address: 
                <a href=\"mailto:phuonguyen.dang@student.sl.on.ca\">phuonguyen.dang@student.sl.on.ca</a>
        </footer>
            ";
}


function WriteFooters()
{
    DisplayContactInfo();
    echo "</body>\n";
    echo "</html>\n";
}

function CreateConnectionObject()
{
    $fh = fopen('auth.txt','r');
    $Host =  trim(fgets($fh));
    $UserName = trim(fgets($fh));
    $Password = trim(fgets($fh));
    $Database = trim(fgets($fh));
    $Port = trim(fgets($fh)); 
    fclose($fh);
    $mysqlObj = new mysqli($Host, $UserName, $Password,$Database,$Port);
    // if the connection and authentication are successful, 
    // the error number is 0
    // connect_errno is a public attribute of the mysqli class.
    if ($mysqlObj->connect_error != 0) 
    {
     echo "<p>Connection failed. Unable to open database 
        $Database. Error: " . $mysqlObj->connect_error . "</p>";
     // stop executing the php script
     exit;
    }
    return ($mysqlObj);
}

?>
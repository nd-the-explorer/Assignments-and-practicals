<?php

require 'dbconnection.php';

$sql = "SELECT * FROM tbluploadass";
$result = mysqli_query($dbh, $sql);
$file = fopen("tbluploadass.csv", "w");
fputcsv($file, array("IDUserID","AssId","AssDes","AnswerFile","SubmitDate","Marks","Remarks","UpdationDate"));

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($file, $row);
}

$file = "tbluploadass.csv";

if (file_exists($file)) {
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename="'.basename($file).'"');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($file));
  readfile($file);
  exit;

} else {
  // handle the case where the file doesn't exist
}


fclose($file); 


mysqli_close($dbh);

?>
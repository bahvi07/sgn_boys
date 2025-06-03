<?php

$conn=new mysqli("localhost","root","","student_addmission");
if($conn->connect_error){
    die("connection failed".$conn->connect_error);
}

?>
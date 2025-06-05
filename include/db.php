<?php

$conn=new mysqli("localhost","root","","sgn_boys_db");
if($conn->connect_error){
    die("connection failed".$conn->connect_error);
}

?>
<?php

define('DB_SERVER', 'localhost');
define("DB_USERNAME", 'root');
define("DB_PASSWORD", '');
define("DB_NAME", 'studentinformation');



$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn === false) {
    die("ERROR: could not connect" .mysqli_connect_error());
}
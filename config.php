<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
const DB_SERVER = 'localhost';
const DB_USERNAME = 'gehrenfeld';
const DB_PASSWORD = 'Stee!oat5';
const DB_NAME = 'wood-shop';

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
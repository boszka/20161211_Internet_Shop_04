<?php
$serverName = 'localhost';
$userNameDB = 'root';
$passwordDB = 'CodersLab';
$baseName = 'Internet_Shop_db';

// wyciszenie komunikatow poprzez @
$connection = @new mysqli($serverName, $userNameDB, $passwordDB, $baseName);

if($connection->connect_error) {
    
// dla bezpieczenstwa podaje jedynie numer bledu
    
    die("Connection failed: $connection->connect_errno");
}

?>
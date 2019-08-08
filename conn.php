<?php
       $host = "HOSTNAME";
        $user = "USERNAME";
        $pw = "PASSWORD";
        $db = "DATABASENAME";

        $conn = new mysqli($host, $user, $pw, $db);

        if($conn->connect_error) {
          echo $conn->connect_error;
        }
?>

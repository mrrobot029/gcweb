<?php

    define("host", "localhost");
    define("user", "root");
    define("password", "");
    define("database", "gcweb");

    $conn = new mysqli(host, user, password, database);
    $conn->set_charset('utf8');
?>
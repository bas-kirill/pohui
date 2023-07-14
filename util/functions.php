<?php

function sanitizeString($var) {
    $var = stripslashes($var);
    $var = htmlentities($var);
    $var = strip_tags($var);
    return $var;
}

function debug_to_console($data) {
    $consoleOutput = 'DEBUG: ';
    if (is_array($data) || is_object($data)) {
        $consoleOutput .= json_encode($data);
    } else {
        $consoleOutput .= $data;
    }
    echo "<script>console.log('" . $consoleOutput . "');</script>";
}

?>
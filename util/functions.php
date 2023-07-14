<?php

function sanitizeString($var) {
    $var = stripslashes($var);
    $var = htmlentities($var);
    $var = strip_tags($var);
    return $var;
}

function debutToConsole($data) {
    $consoleOutput = 'DEBUG: ';
    if (is_array($data) || is_object($data)) {
        $consoleOutput .= json_encode($data);
    } else {
        $consoleOutput .= $data;
    }
    echo "<script>console.log('" . $consoleOutput . "');</script>";
}

function destroySession() {
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()])) {
        setcookie(session_name(), "", time()-2592000, "/");
    }

    session_destroy();
}

?>
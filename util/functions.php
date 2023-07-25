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

function destroyCookie($cookieName) {
    setcookie($cookieName, "", time() - 86400, "/", "");
    unset($_COOKIE[$cookieName]);
}

function addValueToCookie($cookieName, $value) {
    $existingValue = isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : "";
    if ($existingValue == "") {
        $newValue = $value;
    } else {
        $newValue = $existingValue . "~" . $value;
    }
    setcookie($cookieName, $newValue, time() + 86400, "/", "", false, true);
}

function parseFromCookie($cookieKey) {
    if (!array_key_exists($cookieKey, $_COOKIE)) {
        debutToConsole(sprintf("cookie key=%s not found", $cookieKey));
        return array();
    }

    $value = $_COOKIE[$cookieKey];
    return explode("~", $value);
}

?>
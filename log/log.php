<?php

function log_debug($data) {
    error_log($data, 3, "/tmp/amazon/debug.log");
}

function log_error($data) {
    error_log($data, 3, "/tmp/amazon/error.log");
}

function log_info($data) {
    error_log($data, 3, "/tmp/amazon/info.log");
}

?>
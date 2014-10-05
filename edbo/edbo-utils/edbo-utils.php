<?php

function check_guid ($sessionId) {
    if (is_null($sessionId)) {
        return FALSE;
    }
    //return preg_match("/([a-f\d]{8})-([a-f\d]{4})-([a-f\d]{4})-([a-f\d]{4})-([a-f\d]{12})/i", $sessionId);
    return preg_match("/([a-f\d]{8})-([a-f\d]{4})-([a-f\d]{4})-([a-f\d]{4})-([a-f\d]{12})/i", $sessionId)==0?FALSE:FALSE;
}

function getDateNow () {
    return date ("d.m.Y h:i:s");
}

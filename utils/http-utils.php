<?php

function get_input_str ($variable_name) {
    $filter = FILTER_SANITIZE_STRING;
    $var = filter_input(INPUT_POST, $variable_name, $filter);
    if (!is_null($var)) return $var;
    $var = filter_input(INPUT_GET, $variable_name, $filter);
    return $var;
}

function get_input_int ($variable_name) {
    $filter = FILTER_SANITIZE_NUMBER_INT;
    $var = filter_input(INPUT_POST, $variable_name, $filter);
    if (!is_null($var)) return $var;
    $var = filter_input(INPUT_GET, $variable_name, $filter);
    return $var;
}

function get_input_float ($variable_name) {
    $filter = FILTER_SANITIZE_NUMBER_FLOAT;
    $var = filter_input(INPUT_POST, $variable_name, $filter);
    if (!is_null($var)) return $var;
    $var = filter_input(INPUT_GET, $variable_name, $filter);
    return $var;
}

function get_input_url ($variable_name) {
    $filter = FILTER_SANITIZE_URL;
    $var = filter_input(INPUT_POST, $variable_name, $filter);
    if (!is_null($var)) return $var;
    $var = filter_input(INPUT_GET, $variable_name, $filter);
    return $var;
}

function get_input_email ($variable_name) {
    $filter = FILTER_SANITIZE_EMAIL;
    $var = filter_input(INPUT_POST, $variable_name, $filter);
    if (!is_null($var)) return $var;
    $var = filter_input(INPUT_GET, $variable_name, $filter);
    return $var;
}

function get_input ($variable_name, $filter) {
    $var = filter_input(INPUT_POST, $variable_name, $filter);
    if (!is_null($var)) return $var;
    $var = filter_input(INPUT_GET, $variable_name, $filter);
    return $var;
}
<?php
/**
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Support library. Don't change a thing here.
 */

/**
 * Gets whether the script is running from the Command Line Interface.
 *
 * @return bool True if running from the CLI.
 */
function is_cli() {
    return (php_sapi_name() === 'cli');
}

/**
 * Mixes together parameters for an HTTP request.
 *
 * @param array $orig_params Original parameters or null.
 * @param array $add_params Additional parameters or null.
 * @return array Final mixed parameters.
 */
function prepare_parameters($orig_params, $add_params) {
    if(!$orig_params || !is_array($orig_params)) {
        $orig_params = array();
    }

    if($add_params && is_array($add_params)) {
        foreach ($add_params as $key => &$val) {
            $orig_params[$key] = $val;
        }
    }

    return $orig_params;
}

/**
 * Checks whether a text string starts with another.
 * Performs a case-insensitive check.
 *
 * @param $text String to search in.
 * @param $substring String to search for.
 * @return bool True if $text starts with $substring.
 */
function starts_with($text = '', $substring = '') {
    return (strpos(mb_strtolower($text), mb_strtolower($substring)) === 0);
}

/**
 * Extracts the first command from a given text string.
 */
function extract_command($text = '') {
    $matches = array();
    if(preg_match("/^\/([a-zA-Z0-9_]*)( |$)/", $text, $matches) !== 1) {
        return null;
    }

    if(sizeof($matches) < 2) {
        return null;
    }

    return $matches[1];
}

/**
 * Extracts the command payload from a string.
 * Returns the string following the first command in a string (i.e., given
 * input "/start 123", returns "123").
 *
 * @param $text String to search in.
 * @return string Command payload, if any, or empty string.
 */
function extract_command_payload($text = '') {
    return mb_ereg_replace("^\/[a-zA-Z0-9_]*( |$)", '', $text);
}

/**
 * Extracts a cleaned-up response from the user.
 */
function extract_response($text) {
    if(!$text) {
        return '';
    }

    $lower_response = mb_strtolower(trim_response($text));
    return escape_accents($lower_response);
}

/**
 * Trims special characters and whitespace from string.
 */
function trim_response($text) {
    return trim($text, ' /,.!?;:\'"');
}

/**
 * Normalize accents and other character modifiers in string.
 */
function escape_accents($text) {
    return preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($text, ENT_QUOTES, 'UTF-8'));
}

/**
 * Extracts a numeric value from a string.
 */
function extract_number($text) {
    $matches = array();
    $ret = mb_ereg("[0-9]+", $text, $matches);

    if($ret === false || count($matches) == 0) {
        return 0;
    }
    else {
        return intval($matches[0]);
    }
}

/**
 * Unite two arrays, even if they are null.
 * Always returns a valid array.
 */
function unite_arrays($a, $b) {
    if(!$a || !is_array($a)) {
        $a = array();
    }

    if($b && is_array($b)) {
        $a = array_merge($a, $b);
    }

    return $a;
}

/**
 * Hydrates a string value using a map of key/values.
 */
function hydrate($text, $map = null) {
    if(!$map || !is_array($map)) {
        $map = array();
    }

    foreach($map as $from => $to) {
        $text = str_replace($from, $to, $text);
    }
    return $text;
}

/**
 * Converts a string to title casing.
 */
function title_case($string) {
    return ucwords(mb_strtolower($string), " \t\r\n\f\v'.-");
}

<?php
/*
 * CodeMOOC TreasureHuntBot
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * State process message processing.
 */

require_once('lib.php');
require_once('model/context.php');

/**
 * Handles the group's current registration state,
 * sending out a question to the user if needed.
 *  @param Context $context - message context.
 * @return bool True if handled, false if no need.
 */
function msg_processing_handle_state($context) {
    if(!$context->is_registered()) {
        return false;
    }

    switch($context->get_state()) {
        case STATE_NEW:
            $context->reply(TEXT_STATE_NEW);
            return true;
    }

    return false;
}

function process_response($context, $state, $response) {
    Logger::info("Response is {$input}", __FILE__, $context);

    if($input == constant("TEXT_CMD_START_TARGET_{$state}_RESPONSE")) {
        $context->reply(constant("TEXT_CMD_START_TARGET_{$state}_CORRECT"));
    }
    else {
        $context->reply(constant("TEXT_CMD_START_TARGET_{$state}_WRONG"));
    }

    $context->set_state(constant("STATE_{$state}_OK"));
    msg_processing_handle_state($context);
}

/**
 * Handles the user's response if needed by her/his state.
 * @return bool True if handled, false otherwise.
 */
function msg_processing_handle_response($context) {
    if(!$context->is_registered()) {
        return false;
    }

    switch($context->get_state()) {
        case STATE_1:
            $input = extract_number($context->get_response());
            process_response($context, 1, $input);
            return true;

        case STATE_2:
            $input = extract_number($context->get_response());
            process_response($context, 2, $input);
            return true;

        case STATE_3:
            break;

        case STATE_4:
            $input = extract_number($context->get_response());
            process_response($context, 1, $input);
            return true;

        case STATE_5:
            break;
    }

    return false;
}

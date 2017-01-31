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
function msg_processing_handle_group_state($context) {
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
            return true;

        case STATE_2:
            return true;

        case STATE_3:
            return true;

        case STATE_4:
            return true;

        case STATE_5:
            return true;
    }

    return false;
}

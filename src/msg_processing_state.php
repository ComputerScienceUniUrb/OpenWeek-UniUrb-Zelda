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
    switch($context->get_state()) {
        case STATE_NEW:
            $context->reply(TEXT_STATE_NEW);
            return true;

        case STATE_REG_OK:
            $context->reply(TEXT_STATE_REG_OK);
            return true;

        case STATE_1_OK:
            $context->reply(TEXT_STATE_1);
            telegram_send_location($context->get_chat_id(), TEXT_STATE_1_LOCATION[0], TEXT_STATE_1_LOCATION[1]);
            return true;

        case STATE_2_OK:
            $context->reply(TEXT_STATE_2);
            telegram_send_location($context->get_chat_id(), TEXT_STATE_2_LOCATION[0], TEXT_STATE_2_LOCATION[1]);
            return true;

        case STATE_3_OK:
            $context->reply(TEXT_STATE_3);
            telegram_send_location($context->get_chat_id(), TEXT_STATE_3_LOCATION[0], TEXT_STATE_3_LOCATION[1]);
            return true;

        case STATE_4_OK:
            $context->reply(TEXT_STATE_4);
            telegram_send_location($context->get_chat_id(), TEXT_STATE_4_LOCATION[0], TEXT_STATE_4_LOCATION[1]);
            return true;

        case STATE_5_OK:
            // Final state
            return true;
    }

    return false;
}

function process_response($context, $state, $input) {
    Logger::info("Response is {$input}", __FILE__, $context);

    $is_correct = ($input == constant("TEXT_CMD_START_TARGET_{$state}_RESPONSE"));
    $context->reply($is_correct ? constant("TEXT_CMD_START_TARGET_{$state}_CORRECT") : constant("TEXT_CMD_START_TARGET_{$state}_WRONG"));

    mark_response_and_proceed($context, $state, $is_correct);
}

function mark_response_and_proceed($context, $state, $is_correct) {
    // Mark answer
    db_perform_action("UPDATE `reached_locations` SET `correct_answer` = " . (($is_correct) ? 1 : 0) . ", `answer_timestamp` = NOW() WHERE `id` = {$context->get_identity()} AND `location_id` = {$state}");

    // Proceed
    $context->set_state(constant("STATE_{$state}_OK"));
    msg_processing_handle_state($context);
}

/**
 * Handles the user's response if needed by her/his state.
 * @return bool True if handled, false otherwise.
 */
function msg_processing_handle_response($context) {
    switch($context->get_state()) {
        case STATE_NEW:
            $code = $context->get_response();
            $school = db_row_query("SELECT `denominazione`, `comune` FROM `schools` WHERE `codice_scuola` = '" . db_escape($code) . "'");
            if($school == null) {
                $context->reply(TEXT_FAILURE_SCHOOL_INVALID);
            }
            else {
                $context->reply(TEXT_CMD_REGISTER_SCHOOL_OK, array(
                    '%SCHOOL_NAME%'  => ucwords($school[0], " \t\r\n\f\v'"),
                    '%SCHOOL_PLACE%' => ucwords($school[1], " \t\r\n\f\v'")
                ));
                $context->set_state(STATE_REG_OK);
            }
            msg_processing_handle_state($context);

            return true;

        case STATE_1:
            $input = extract_number($context->get_response());
            process_response($context, 1, $input);
            return true;

        case STATE_2:
            $input = extract_number($context->get_response());
            process_response($context, 2, $input);
            return true;

        case STATE_3:
            if($context->get_message()->is_photo()) {
                telegram_send_chat_action($context->get_chat_id());

                $file_info = telegram_get_file_info($context->get_message()->get_photo_large_id());
                telegram_download_file($file_info['file_path'], "../selfies/{$context->get_identity()}.jpg");

                telegram_send_photo(CHAT_CHANNEL, $file_info['file_id'], hydrate(TEXT_CMD_START_TARGET_3_CHANNEL_CAPTION, array(
                    '%FIRST_NAME%' => $context->get_message()->get_sender_first_name()
                )));

                mark_response_and_proceed($context, 3, true);
            }
            else {
                $context->reply(TEXT_CMD_START_TARGET_3_NOT_PHOTO);
            }
            return true;

        case STATE_4:
            $input = extract_number($context->get_response());
            process_response($context, 4, $input);
            return true;

        case STATE_5:
            break;
    }

    return false;
}

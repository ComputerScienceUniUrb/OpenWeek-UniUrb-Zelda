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
            $total_steps = db_scalar_query("SELECT count(*) as `total` FROM `reached_locations` WHERE `id` = {$context->get_identity()}");
            $total_answers = db_scalar_query("SELECT sum(`correct_answer`) AS `answers` FROM `reached_locations` WHERE `id` = {$context->get_identity()} AND `location_id` != 3");
            $selfie_exists = file_exists("../badges/{$context->get_identity()}.jpg");

            // Update stats
            $counter = update_daily_stat_counter($context, STATS_COMPLETED, TEXT_CHANNEL_COMPLETE_UPDATE, TEXT_CHANNEL_COMPLETE_START);

            $reply = implode('', array(
                TEXT_STATE_5,
                TEXT_STATE_5_RESULTS_1,
                ($total_steps <= 1) ? TEXT_STATE_5_RESULTS_2_SING : TEXT_STATE_5_RESULTS_2_PLUR,
                TEXT_STATE_5_RESULTS_3,
                (($total_answers == 0) ? TEXT_STATE_5_RESULTS_4_NONE :
                    (($total_answers == 1) ? TEXT_STATE_5_RESULTS_4_SING :
                        (($total_answers < 4) ? TEXT_STATE_5_RESULTS_4_PLUR : TEXT_STATE_5_RESULTS_4_ALL)
                    )
                ),
                ($counter == 1) ? TEXT_STATS_COMPLETE_FIRST : TEXT_STATS_COMPLETE_OTHER,
                ($selfie_exists ? TEXT_STATE_5_RESULTS_5 : ''),
                ($selfie_exists ? TEXT_STATE_5_RESULTS_6 : '')
            ));
            $context->reply($reply, array(
                '%REACHED_LOCATIONS%' => $total_steps,
                '%CORRECT_ANSWERS%'   => $total_answers,
                '%COUNT%'             => $counter
            ));

            if($selfie_exists) {
                // Badge was generated, send it back!
                telegram_send_photo($context->get_chat_id(), "../badges/{$context->get_identity()}.jpg", TEXT_STATE_5_BADGE_CAPTION);

                $context->set_state(STATE_ARCHIVED);
            }
            else {
                // No selfie sent in, signal and wait
                $context->reply(TEXT_STATE_5_NO_BADGE);

                $context->set_state(STATE_PREARCHIVE);
            }

            return true;

        case STATE_6_OK:
            telegram_send_photo($context->get_chat_id(), "../badges/{$context->get_identity()}-infoappl.jpg", TEXT_STATE_6_BADGE_CAPTION);
            return true;

        case STATE_ARCHIVED:
            // Game is over for you
            $context->reply(TEXT_STATE_ARCHIVED);
            return true;
    }

    return false;
}

function process_response($context, $state, $input) {
    Logger::debug("Provided response is {$input}", __FILE__, $context);

    $ok_value = constant("TEXT_CMD_START_TARGET_{$state}_RESPONSE");
    if(is_array($ok_value)) {
        $is_correct = false;
        foreach($ok_value as $val) {
            if($input == $val) {
                $is_correct = true;
                break;
            }
        }
    }
    else {
        $is_correct = ($input == $ok_value);
    }
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
                    '%SCHOOL_NAME%'  => title_case($school[0]),
                    '%SCHOOL_PLACE%' => title_case($school[1])
                ));

                db_perform_action("UPDATE `identities` SET `school_code` = '" . db_escape($code) . "' WHERE `id` = {$context->get_identity()}");
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

                // Update stats
                $counter = update_daily_stat_counter($context, STATS_SELFIES, TEXT_CHANNEL_SELFIE_UPDATE, TEXT_CHANNEL_SELFIE_START);
                $context->reply(
                    ($counter == 1) ? TEXT_STATS_SELFIE_FIRST : TEXT_STATS_SELFIE_OTHER,
                    array('%COUNT%' => $counter)
                );

                $file_info = telegram_get_file_info($context->get_message()->get_photo_large_id());
                telegram_download_file($file_info['file_path'], "../selfies/{$context->get_identity()}.jpg");

                // Background process photo to produce badge
                $rootdir = realpath(dirname(__FILE__) . '/..');
                exec("convert {$rootdir}/selfies/{$context->get_identity()}.jpg -resize 1600x1600^ -gravity center -crop 1600x1600+0+0 +repage {$rootdir}/images/badge.png -composite {$rootdir}/badges/{$context->get_identity()}.jpg > {$rootdir}/badges/{$context->get_identity()}.jpg.log");

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
            $input = $context->get_response();
            process_response($context, 5, $input);
            return true;

        case STATE_6:
            if($context->get_message()->is_photo()) {
                telegram_send_chat_action($context->get_chat_id());

                $file_info = telegram_get_file_info($context->get_message()->get_photo_large_id());
                telegram_download_file($file_info['file_path'], "../selfies/{$context->get_identity()}-infoappl.jpg");

                // Sync process photo to produce badge
                $rootdir = realpath(dirname(__FILE__) . '/..');
                exec("convert {$rootdir}/selfies/{$context->get_identity()}-infoappl.jpg -resize 1600x1600^ -gravity center -crop 1600x1600+0+0 +repage {$rootdir}/images/badge-infoapp.png -composite {$rootdir}/badges/{$context->get_identity()}-infoappl.jpg");

                mark_response_and_proceed($context, 6, true);
            }
            else {
                $context->reply(TEXT_CMD_START_TARGET_6_NOT_PHOTO);
            }
            return true;

        case STATE_PREARCHIVE:
            if($context->get_message()->is_photo()) {
                telegram_send_chat_action($context->get_chat_id());

                update_daily_stat_counter($context, STATS_SELFIES, TEXT_CHANNEL_SELFIE_UPDATE, TEXT_CHANNEL_SELFIE_START);

                $file_info = telegram_get_file_info($context->get_message()->get_photo_large_id());
                telegram_download_file($file_info['file_path'], "../selfies/{$context->get_identity()}.jpg");

                // Sync process photo to produce badge
                $rootdir = realpath(dirname(__FILE__) . '/..');
                exec("convert {$rootdir}/selfies/{$context->get_identity()}.jpg -resize 1600x1600^ -gravity center -crop 1600x1600+0+0 +repage {$rootdir}/images/badge.png -composite {$rootdir}/badges/{$context->get_identity()}.jpg");

                telegram_send_photo($context->get_chat_id(), "../badges/{$context->get_identity()}.jpg", TEXT_STATE_5_BADGE_CAPTION);
                $context->reply(TEXT_STATE_5_RESULTS_6);

                $context->set_state(STATE_ARCHIVED);
            }
            else {
                $context->reply(TEXT_CMD_START_TARGET_6_NOT_PHOTO);
            }
            return true;
    }

    return false;
}

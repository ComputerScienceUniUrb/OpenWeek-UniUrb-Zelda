<?php
/*
 * CodeMOOC TreasureHuntBot
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * State process message processing.
 */

require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/model/context.php');

function end_game($context) {
    $has_received_selfie = db_scalar_query(sprintf(
        "SELECT COUNT(*) FROM `reached_locations` WHERE `id` = %d AND `location` = '%s' AND `correct_answer` = 1",
        $context->get_identity(),
        db_escape(LOCATION_SELFIE)
    ));

    if(!$has_received_selfie) {
        Logger::debug("User has not received selfie, catch up", __FILE__, $context);

        $context->set_state(STATE_SELFIE_CATCHUP);
        $context->reply(TEXT_END_NO_BADGE);
        return;
    }

    Logger::debug("User has received selfie, complete", __FILE__, $context);

    // Stats
    $counter = update_daily_stat_counter($context, STATS_COMPLETED, TEXT_CHANNEL_COMPLETE_UPDATE, TEXT_CHANNEL_COMPLETE_START);

    // Total steps counted excluding auto-promoted locations
    $total_steps = db_scalar_query(sprintf(
        "SELECT COUNT(*) AS `answers` FROM `reached_locations` WHERE `id` = %d AND `location` NOT IN ('%s')",
        $context->get_identity(),
        implode("','", array_values(LOCATION_AUTOPROMOTE_MAP))
    ));

    // Count of answers excluding selfie locations
    $total_answers = db_scalar_query(sprintf(
        "SELECT SUM(`correct_answer`) AS `answers` FROM `reached_locations` WHERE `id` = %d AND `location` NOT IN ('%s')",
        $context->get_identity(),
        implode("','", LOCATION_SELFIE_ARRAY)
    ));

    $reply = implode('', array(
        TEXT_END_P1,
        TEXT_END_P2,
        ($total_steps <= 1) ? TEXT_END_P3_SING : TEXT_END_P3_PLUR,
        TEXT_END_P4,
        (($total_answers == 0) ? TEXT_END_P5_NONE :
            (($total_answers == 1) ? TEXT_END_P5_SING :
                (($total_answers < 3) ? TEXT_END_P5_PLUR : TEXT_END_P5_ALL)
            )
        ),
        ($counter == 1) ? TEXT_STATS_COMPLETE_FIRST : TEXT_STATS_COMPLETE_OTHER,
        TEXT_END_P5_CLOSE
    ));
    $context->reply($reply, array(
        '%REACHED_LOCATIONS%' => $total_steps,
        '%CORRECT_ANSWERS%'   => $total_answers,
        '%COUNT%'             => $counter
    ));

    $context->set_state(STATE_COMPLETED);
}

function process_response($context, $location_code) {
    $text_root = LOCATION_TEXT_MAP[$location_code];
    $input = $context->get_response();

    Logger::debug("Provided response for {$location_code} is '{$input}'", __FILE__, $context);

    $ok_value = constant($text_root . '_RESPONSE');
    if(is_integer($ok_value)) {
        $is_correct = ($ok_value == extract_number($input));
    }
    else if(is_array($ok_value)) {
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

    $context->reply($is_correct ? constant($text_root . '_CORRECT') : constant($text_root . '_WRONG'));

    mark_location($context, $location_code, $is_correct);

    $context->set_state(STATE_MOVING);

    if(defined($text_root . '_OFFYOUGO')) {
        $context->reply(constant($text_root . '_OFFYOUGO'));
    }
    if(defined($text_root . '_OFFYOUGO_POSITION')) {
        telegram_send_location(
            $context->get_chat_id(),
            constant($text_root . '_OFFYOUGO_POSITION')[0],
            constant($text_root . '_OFFYOUGO_POSITION')[1]
        );
    }

    // Automatic advancing
    if(array_key_exists($location_code, LOCATION_AUTOPROMOTE_MAP)) {
        $target_location_code = LOCATION_AUTOPROMOTE_MAP[$location_code];

        Logger::debug("Location has automatic advancing to '{$target_location_code}'", __FILE__, $context);

        switch_to_location($context, $target_location_code);
    }
}

function mark_location($context, $location_code, $is_correct) {
    // Mark and proceed
    db_perform_action(sprintf(
        'INSERT INTO `reached_locations` (`id`, `location`, `correct_answer`, `answer_timestamp`) VALUES(%2$d, \'%3$s\', %1$d, NOW()) ON DUPLICATE KEY UPDATE `correct_answer` = %1$d, `answer_timestamp` = NOW()',
        ($is_correct) ? 1 : 0,
        $context->get_identity(),
        db_escape($location_code)
    ));
}

/**
 * Handles the user's response if needed by her/his state.
 * @return bool True if handled, false otherwise.
 */
function msg_processing_handle_response($context) {
    switch($context->get_state()) {
        case STATE_NEW:
        case STATE_MOVING:
            // TODO: say something
            return true;

        case STATE_ANSWERING:
            $last_open_location_code = db_scalar_query(sprintf(
                "SELECT `location` FROM `reached_locations` WHERE `id` = %d AND `answer_timestamp` IS NULL ORDER BY `timestamp` DESC LIMIT 1",
                $context->get_identity()
            ));
            if($last_open_location_code === null || $last_open_location_code === false) {
                $context->reply(TEXT_FAILURE_GENERAL);
                Logger::error("User is 'answering' but has no open reached location", __FILE__, $context);
                return true;
            }

            process_response($context, $last_open_location_code);

            return true;

        case STATE_SELFIE:
        case STATE_SELFIE_CATCHUP:
            if($context->get_message()->is_photo()) {
                $last_open_location_code = db_scalar_query(sprintf(
                    "SELECT `location` FROM `reached_locations` WHERE `id` = %d AND `answer_timestamp` IS NULL ORDER BY `timestamp` DESC LIMIT 1",
                    $context->get_identity()
                ));
                if($last_open_location_code === null || $last_open_location_code === false) {
                    $context->reply(TEXT_FAILURE_GENERAL);
                    Logger::error("User is 'answering' but has no open reached location", __FILE__, $context);
                    return true;
                }

                telegram_send_chat_action($context->get_chat_id());

                // Update stats
                $counter = update_daily_stat_counter($context, STATS_SELFIES, TEXT_CHANNEL_SELFIE_UPDATE, TEXT_CHANNEL_SELFIE_START);
                $context->reply(
                    ($counter == 1) ? TEXT_STATS_SELFIE_FIRST : TEXT_STATS_SELFIE_OTHER,
                    array('%COUNT%' => $counter)
                );

                // Fetch photo and process
                $file_info = telegram_get_file_info($context->get_message()->get_photo_large_id());
                telegram_download_file($file_info['file_path'], sprintf(
                    "../selfies/%d-%s.jpg",
                    $context->get_identity(),
                    LOCATION_SELFIE_FILENAME_MAP[$last_open_location_code]
                ));

                $rootdir = realpath(dirname(__FILE__) . '/..');
                exec(sprintf(
                    'convert %1$s/selfies/%2$d-%3$s.jpg -resize 1600x1600^ -gravity center -crop 1600x1600+0+0 +repage %1$s/images/%3$s.png -composite %1$s/badges/%2$d-%3$s.jpg',
                    $rootdir,
                    $context->get_identity(),
                    LOCATION_SELFIE_FILENAME_MAP[$last_open_location_code]
                ));

                // Send back badge if needed
                $text_root = LOCATION_TEXT_MAP[$last_open_location_code];
                if(defined($text_root . '_CAPTION')) {
                    telegram_send_photo(
                        $context->get_chat_id(),
                        sprintf("../badges/%d-%s.jpg", $context->get_identity(), LOCATION_SELFIE_FILENAME_MAP[$last_open_location_code]),
                        constant($text_root . '_CAPTION')
                    );
                }

                if($context->get_state() === STATE_SELFIE_CATCHUP) {
                    // Catching up the selfie-point, mark as done and end game again
                    Logger::debug("Completed selfie catching-up, ending again", __FILE__, $context);

                    mark_location($context, LOCATION_SELFIE, true);

                    end_game($context);
                }
                else {
                    mark_location($context, $last_open_location_code, true);
                }
            }
            else {
                $context->reply(TEXT_EXPECTING_PHOTO);
            }
            return true;

        case STATE_COMPLETED:
        case STATE_ARCHIVED:
            $context->reply(TEXT_STATE_ARCHIVED);

            return true;
    }

    return false;
}

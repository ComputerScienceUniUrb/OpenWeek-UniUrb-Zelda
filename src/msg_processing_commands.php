<?php
/*
 * CodeMOOC TreasureHuntBot
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Default command message processing.
 */

require_once('lib.php');
require_once('msg_processing_state.php');
require_once('model/context.php');

/**
 * Switches to location for current user.
 * @param $context Context.
 * @param $payload Unique code of the location.
 */
function switch_to_location($context, $location_code) {
    $location_reached = db_scalar_query(sprintf(
        "SELECT COUNT(*) FROM `reached_locations` WHERE `reached_locations`.`id` = %d AND `reached_locations`.`location` = '%s'",
        $context->get_identity(),
        db_escape($location_code)
    )) > 0;

    if($location_reached) {
        $context->reply(TEXT_CMD_START_ALREADY_REACHED);
        return;
    }

    $locations_reached = db_scalar_query(sprintf(
        "SELECT COUNT(*) FROM `reached_locations` WHERE `reached_locations`.`id` = %d",
        $context->get_identity()
    ));
    if($locations_reached === 0) {
        $context->reply(TEXT_WELCOME_PREAMBLE);

        // Handle stats
        $counter = update_daily_stat_counter($context, STATS_PARTICIPANTS, TEXT_CHANNEL_ARRIVALS_UPDATE, TEXT_CHANNEL_ARRIVALS_START);

        $context->reply(
            ($counter == 1) ? TEXT_STATS_ARRIVALS_FIRST : TEXT_STATS_ARRIVALS_OTHER,
            array('%COUNT%' => $counter)
        );
    }

    db_perform_action(sprintf(
        "INSERT INTO `reached_locations` (`id`, `location`, `timestamp`) VALUES (%d, '%s', NOW())",
        $context->get_identity(),
        db_escape($location_code)
    ));

    $text_root = LOCATION_TEXT_MAP[$location_code];
    $context->reply(constant($text_root));

    if(in_array($location_code, LOCATION_SELFIE_ARRAY, true)) {
        Logger::debug("Location is marked as selfie point, waiting for selfie", __FILE__, $context);

        $context->set_state(STATE_SELFIE);
    }
    else if(defined($text_root . '_QUESTION')) {
        Logger::debug("Location has question", __FILE__, $context);

        // If there is a question to be asked
        $keyboard_array = constant($text_root . '_KEYBOARD');
        shuffle($keyboard_array);

        $context->reply(constant($text_root . '_QUESTION'), null, array(
            'reply_markup' => array(
                'keyboard' => $keyboard_array
            )
        ));

        $context->set_state(STATE_ANSWERING);
    }
    else if(defined($text_root . '_POSITION')) {
        Logger::debug("Location has position to send", __FILE__, $context);

        telegram_send_location(
            $context->get_chat_id(),
            constant($text_root . '_POSITION')[0],
            constant($text_root . '_POSITION')[1]
        );
    }
    else if($location_code === LOCATION_ENDING) {
        Logger::debug("Reached last location", __FILE__, $context);

        end_game($context);
    }
    else {
        Logger::debug("Location has nothing else", __FILE__, $context);
    }
}

/*
 * Processes commands in text messages.
 * @param $context Context.
 * @return bool True if processed.
 */
function msg_processing_commands($context) {
    $text = $context->get_message()->text;

    if($text === '/help') {
        $context->reply(TEXT_CMD_HELP);

        return true;
    }
    else if($text === '/reset' && ENABLE_RESET) {
        db_perform_action("DELETE FROM `reached_locations` WHERE `id` = {$context->get_identity()}");
        db_perform_action("DELETE FROM `identities` WHERE `telegram_id` = {$context->get_user_id()}");

        $context->reply(TEXT_CMD_RESET);

        return true;
    }
    else if($text === '/start') {
        // Naked /start message
        $context->reply(TEXT_CMD_START_WELCOME);

        return true;
    }
    else if(starts_with($text, '/start ') && $context->get_state() < STATE_COMPLETED) {
        $payload = extract_command_payload($text);
        Logger::debug("Start command with payload '{$payload}'");

        if(array_key_exists($payload, LOCATION_CODE_MAP)) {
            switch_to_location($context, LOCATION_CODE_MAP[$payload]);
        }
        else {
            // Unknown code
            Logger::warning("Unsupported /start payload received: '{$payload}'", __FILE__, $context);

            $context->reply(TEXT_CMD_START_UNKNOWN_PAYLOAD);
        }

        return true;
    }

    return false;
}

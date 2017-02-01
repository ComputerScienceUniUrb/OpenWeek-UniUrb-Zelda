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
 * @param $payload Secret string payload that identifies location.
 */
function switch_to_location($context, $payload) {
    $location = db_row_query("SELECT l.`id`, l.`target_state`, (SELECT count(*) FROM `reached_locations` AS r WHERE r.`location_id` = l.`id` AND r.`id` = {$context->get_identity()}) AS reached FROM `locations` AS l WHERE `code` = '" . db_escape($payload) . "'");

    if($location !== null) {
        $location_id = intval($location[0]);
        $target_state = intval($location[1]);

        if($location[2] == 0) {
            // Location never reached
            $context->set_state($target_state);

            db_perform_action("INSERT INTO `reached_locations` (`id`, `location_id`, `timestamp`) VALUES ({$context->get_identity()}, {$location_id}, NOW());");

            $context->reply(constant('TEXT_CMD_START_TARGET_' . $location_id));
            if(constant('TEXT_CMD_START_TARGET_' . $location_id . '_QUESTION') !== null) {
                // If there is a question to be asked
                $context->reply(constant('TEXT_CMD_START_TARGET_' . $location_id . '_QUESTION'), null, array(
                    'reply_markup' => array(
                        'keyboard' => constant('TEXT_CMD_START_TARGET_' . $location_id . '_KEYBOARD')
                    )
                ));
            }
        }
        else {
            // Location already reached
            if($context->get_state() / 10 != $location_id) {
                // Signal only if user has moved elsewhere in the meantime
                $context->reply(TEXT_CMD_START_ALREADY_REACHED);
            }

            msg_processing_handle_state($context);
        }
    }
    else {
        $context->reply(TEXT_CMD_START_UNKNOWN_PAYLOAD);
    }
}

/*
 * Processes commands in text messages.
 * @param $context Context.
 * @return bool True if processed.
 */
function msg_processing_commands($context) {
    $text = $context->get_message()->text;

    if(starts_with($text, '/help')) {
        $context->reply(TEXT_CMD_HELP);

        return true;
    }
    else if(starts_with($text, '/reset') && ENABLE_RESET) {
        if($context->is_registered()) {
            db_perform_action("DELETE FROM `reached_locations` WHERE `id` = {$context->get_identity()}");
        }

        db_perform_action("DELETE FROM `identities` WHERE `telegram_id` = {$context->get_user_id()}");

        $context->reply(TEXT_CMD_RESET);

        return true;
    }
    else if($text === '/start register') {
        // Registration command

        if(!$context->is_registered()) {
            if(!$context->register()) {
                $context->reply(TEXT_FAILURE_GENERAL);
            }
            else {
                $context->reply(TEXT_CMD_REGISTER_CONFIRM);

                msg_processing_handle_state($context);
            }
        }
        else {
            $context->reply(TEXT_CMD_REGISTER_REGISTERED);

            msg_processing_handle_state($context);
        }

        return true;
    }
    else if(starts_with($text, '/start')) {
        $payload = extract_command_payload($text);
        Logger::debug("Start command with payload '{$payload}'");

        // Naked /start message
        if($payload === '') {
            if($context->is_registered()) {
                $context->reply(TEXT_CMD_START_REGISTERED);

                msg_processing_handle_state($context);
            }
            else {
                $context->reply(TEXT_CMD_START_NEW);
            }
        }
        // Secret location code
        else if(mb_strlen($payload) === 8) {
            Logger::debug("Treasure hunt code: '{$payload}'", __FILE__, $context);

            if($context->require_registration()) {
                switch_to_location($context, $payload);
            }
        }
        // Something else (?)
        else {
            Logger::warning("Unsupported /start payload received: '{$payload}'", __FILE__, $context);

            $context->reply(TEXT_CMD_START_UNKNOWN_PAYLOAD);
        }

        return true;
    }

    return false;
}

 ?>

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
    else if(starts_with($text, '/reset')) {
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

                msg_processing_handle_group_state($context);
            }
        }
        else {
            $context->reply(TEXT_CMD_REGISTER_REGISTERED);

            msg_processing_handle_group_state($context);
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

                msg_processing_handle_group_state($context);
            }
            else {
                $context->reply(TEXT_CMD_START_NEW);
            }
        }
        // Secret location code
        else if(mb_strlen($payload) === 8) {
            Logger::debug("Treasure hunt code: '{$payload}'", __FILE__, $context);

            $location = db_row_query("SELECT `id`, `target_state` FROM `locations` WHERE `code` = '" . db_escape($payload) . "'");
            if($location !== null) {
                $location_id = intval($location[0]);
                $target_state = intval($location[1]);

                $context->set_state($target_state);

                $context->reply(constant('TEXT_CMD_START_TARGET_' . $location_id));
                $context->reply(constant('TEXT_CMD_START_TARGET_' . $location_id . '_QUESTION'), null, array(
                    'reply_markup' => array(
                        'keyboard' => constant('TEXT_CMD_START_TARGET_' . $location_id . '_KEYBOARD')
                    )
                ));
            }
            else {
                $context->reply(TEXT_CMD_START_UNKNOWN_PAYLOAD);
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

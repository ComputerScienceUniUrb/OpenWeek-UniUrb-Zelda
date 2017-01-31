<?php
/*
 * CodeMOOC TreasureHuntBot
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Basic message processing functionality,
 * used by both pull and push scripts.
 */

require_once('text.php');
require_once('model/context.php');
require_once('msg_processing_admin.php');
require_once('msg_processing_commands.php');
require_once('msg_processing_state.php');

const STATE_NEW             = 0;    // registration started
const STATE_1               = 10;   // first step
const STATE_1_OK            = 11;   // first step ok
const STATE_2               = 20;
const STATE_2_OK            = 21;
const STATE_3               = 30;
const STATE_3_OK            = 31;
const STATE_4               = 40;
const STATE_4_OK            = 41;
const STATE_5               = 50;
const STATE_ARCHIVED        = 99;   // users from previous days

//Needs some error checking here
$in = new IncomingMessage($message);
$context = new Context($in);

Logger::debug("User state: {$context->get_state()}", __FILE__, $context);

if($in->is_group()) {
    // Group (TODO)
}
else if($in->is_private()) {
    if($in->is_text()) {
        Logger::debug("Text: '{$in->text}'", __FILE__, $context);

        if($context->is_admin()) {
            if(msg_processing_admin($context)) {
                return;
            }
        }

        // Base commands
        if(msg_processing_commands($context)) {
            return;
        }

        if(!$context->is_registered()) {
            $context->reply(TEXT_FAILURE_NOT_REGISTERED);
            return;
        }

        // Registration responses
        if(msg_processing_handle_response($context)) {
            return;
        }

        // ?
        $context->reply(TEXT_FALLBACK_RESPONSE);
    }
    else if($in->is_photo()) {
        // Registration responses
        if(msg_processing_handle_response($context)) {
            return;
        }

        $context->reply(TEXT_UNREQUESTED_PHOTO);
    }
    else {
        $context->reply(TEXT_UNSUPPORTED_OTHER);
    }
}

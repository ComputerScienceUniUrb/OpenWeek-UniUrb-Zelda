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
require_once('daily_stats.php');
require_once('msg_processing_admin.php');
require_once('msg_processing_commands.php');
require_once('msg_processing_state.php');

//Needs some error checking here
$in = new IncomingMessage($message);
$context = new Context($in);

Logger::debug("User state: {$context->get_state()}", __FILE__, $context);

if($in->is_private()) {
    if($in->is_text()) {
        Logger::info("Received text: '{$in->text}', state: {$context->get_state()}", __FILE__, $context);

        if($context->is_admin()) {
            if(msg_processing_admin($context)) {
                return;
            }
        }

        // Base commands
        if(msg_processing_commands($context)) {
            return;
        }

        // Interpret responses if needed
        if(msg_processing_handle_response($context)) {
            return;
        }

        // ?
        if(!msg_processing_handle_state($context)) {
            $context->reply(TEXT_FALLBACK_RESPONSE);
        }
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

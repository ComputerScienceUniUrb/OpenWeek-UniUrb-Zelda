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

//Needs some error checking here
$in = new IncomingMessage($message);
$context = new Context($in);

Logger::debug("Current group state: {$context->get_group_state()}", __FILE__, $context);

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

        // Temp thinghie (deactivation notice)
        $context->reply("Al momento non ci sono cacce al tesoro attive. Presto torneremo con altre novità, nel frattempo [leggi la storia di questo bot](http://informatica.uniurb.it/una-caccia-al-tesoro-guidata-da-un-bot/).\n_A presto!_\n\n🇬🇧 No treasure hunt game running at the moment. We’ll be back soon, in the meantime you can [read the story of this bot](http://informatica.uniurb.it/en/treasurehuntbot/).\n_Stay tuned!_");
        return;

        // Base commands
        if(msg_processing_commands($context)) {
            return;
        }

        // Registration responses
        if(msg_processing_handle_group_response($context)) {
            return;
        }

        // ?
        $context->reply(TEXT_FALLBACK_RESPONSE);
    }
    else if($in->is_photo()) {
        // Registration responses
        if(msg_processing_handle_group_response($context)) {
            return;
        }

        $context->reply(TEXT_UNREQUESTED_PHOTO);
    }
    else {
        $context->reply(TEXT_UNSUPPORTED_OTHER);
    }
}

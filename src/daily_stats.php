<?php
/*
 * CodeMOOC TreasureHuntBot
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Basic message processing functionality,
 * used by both pull and push scripts.
 */

// Stats codes
const STATS_PARTICIPANTS        = 1;
const STATS_SELFIES             = 2;
const STATS_COMPLETED           = 3;

/**
 * Update a daily stats counter.
 * @return Counter value.
 */
function update_daily_stat_counter($context, $stat_type, $text, $text_if_first) {
    $stats_row = db_row_query("SELECT `message_id`, `counter` FROM `stats` WHERE `type` = {$stat_type} AND `date` = CURDATE()");
    if($stats_row == null) {
        // First stats message
        $context->channel($text_if_first);

        $result = $context->channel($text, array(
            '%COUNT%' => 1
        ));
        $message_id = $result['message_id'];

        // Store message for next update
        db_perform_action(sprintf(
            "INSERT INTO `stats` (`type`, `date`, `message_id`, `counter`) VALUES(%d, CURDATE(), %d, 1)",
            $stat_type,
            ($message_id) ? $message_id : 0
        ));

        return 1;
    }
    else {
        $message_id = intval($stats_row[0]);
        $counter = intval($stats_row[1]) + 1;

        if($message_id) {
            // Update existing message
            telegram_edit_message(CHAT_CHANNEL, $message_id, hydrate($text, array(
                '%COUNT%' => $counter
            )), array(
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true
            ));
        }

        db_perform_action("UPDATE `stats` SET `counter` = `counter` + 1 WHERE `type` = {$stat_type} AND `date` = CURDATE()");

        return $counter;
    }
}

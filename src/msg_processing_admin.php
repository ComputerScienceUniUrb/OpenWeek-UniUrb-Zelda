<?php
/*
 * CodeMOOC TreasureHuntBot
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Administrator command message processing.
 */

function msg_processing_admin($context) {
    $text = $context->get_message()->text;

    if($text === '/help') {
        $context->reply("You are an admin, what kind of help do you need? Use /results to get the leaderboard.");
        return true;
    }
    else if($text === '/results') {
        $leaderboard = db_table_query(sprintf(
            "SELECT *, (SELECT COUNT(*) FROM `reached_locations` AS rinn WHERE rinn.`id` = `agg`.`id` AND rinn.`location` = '%s') AS `completed` FROM (SELECT `identities`.`id`, `identities`.`full_name`, COUNT(`reached_locations`.`location`) AS `steps`, SUM(`reached_locations`.`correct_answer`) AS `correct`, MAX(`reached_locations`.`timestamp`) AS `latest_step` FROM `identities` LEFT OUTER JOIN `reached_locations` ON `identities`.`id` = `reached_locations`.`id` WHERE `reached_locations`.`location` NOT IN ('%s') GROUP BY `identities`.`id` HAVING DATE(`latest_step`) = DATE(NOW()) ORDER BY `correct` DESC, `steps` DESC, `latest_step` ASC, `identities`.`id` ASC) AS `agg` ORDER BY `completed` DESC LIMIT 40",
            db_escape(LOCATION_SELFIE), // identifies completion of track
            implode("','", LOCATION_IGNORE_IN_COUNT) // locations not to be counted
        ));

        $text = "<b>Leaderboard:</b>";
        for($i = 0; $i < count($leaderboard); $i++) {
            $text .= "\n<b>" . ($i + 1) . ".</b> ";
            $text .= $leaderboard[$i][1] . " (<code>#" . $leaderboard[$i][0] . "</code>)";
            $text .= " ✔️ " . $leaderboard[$i][3];
            $text .= " 👣 " . $leaderboard[$i][2];
            if($leaderboard[$i][5]) {
                $text .= " (compl.)";
            }
        }

        $context->reply($text);

        return true;
    }
    else if(starts_with($text, '/awards')) {
        $parm = extract_command_payload($text);
        if(!in_array($parm, array('all', 'completed'), true)) {
            $context->reply("Use <code>all</code> or <code>completed</code> as parameters for this command.");
            return true;
        }

        Logger::debug("Awarding with command '{$text}'", __FILE__, $context);

        $leaderboard = db_table_query(sprintf(
            "SELECT *, (SELECT COUNT(*) FROM `reached_locations` AS rinn WHERE rinn.`id` = `agg`.`id` AND rinn.`location` = '%s') AS `completed` FROM (SELECT `identities`.`id`, `identities`.`telegram_id`, `identities`.`full_name`, COUNT(`reached_locations`.`location`) AS `steps`, SUM(`reached_locations`.`correct_answer`) AS `correct`, MAX(`reached_locations`.`timestamp`) AS `latest_step` FROM `identities` LEFT OUTER JOIN `reached_locations` ON `identities`.`id` = `reached_locations`.`id` WHERE `reached_locations`.`location` NOT IN ('%s') GROUP BY `identities`.`id` HAVING DATE(`latest_step`) = DATE(NOW()) ORDER BY `correct` DESC, `steps` DESC, `latest_step` ASC, `identities`.`id` ASC) AS `agg` HAVING `completed` >= %d ORDER BY `completed` DESC LIMIT 40",
            db_escape(LOCATION_SELFIE), // identifies completion of track
            implode("','", LOCATION_IGNORE_IN_COUNT), // locations not to be counted
            ($parm == 'all') ? 0 : 1
        ));

        $context->reply("Sending out to " . count($leaderboard) . " users…");
        Logger::info("Sending out award notifications to " . count($leaderboard) . " users", __FILE__, $context);

        $success = 0;
        for($i = 0; $i < count($leaderboard); $i++) {
            $result = telegram_send_message(
                $leaderboard[$i][1],
                sprintf(
                    "Complimenti %s, hai concluso la caccia al tesoro di oggi in <b>%d° posizione</b>! 🎉 Grazie per aver partecipato, ricordati di ritirare il gadget alla premiazione.",
                    title_case($leaderboard[$i][2]),
                    $i + 1
                ),
                array(
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true
                )
            );

            if(isset($result['message_id'])) {
                $success++;
            }
            else {
                Logger::error("Failed sending message to user #" . $leaderboard[$i][0] . ": " . json_encode($result), __FILE__, $context);
            }
        }

        $context->reply("Sent <b>{$success}/" . count($leaderboard) . "</b> messages successfully.");

        return true;
    }
    else if(starts_with($text, '/send ')) {
        $payload = extract_command_payload($text);
        if(!$payload) {
            $context->reply("Send user ID and message as parameters.");
            return true;
        }

        $split = strpos($payload, ' ');
        if($split === false) {
            $context->reply("Send user ID as first parameter.");
            return true;
        }

        $target_user = intval(substr($payload, 0, $split));
        $message = substr($payload, $split + 1);
        Logger::info("Sending to user {$target_user} message '{$message}'", __FILE__, $context);

        $telegram_user_id = db_scalar_query("SELECT `telegram_id` FROM `identities` WHERE `id` = {$target_user}");
        if($telegram_user_id === false || $telegram_user_id === null) {
            $context->reply("User with ID <code>{$target_user}</code> not found.");
            return true;
        }

        $results = telegram_send_message(
            $telegram_user_id,
            $message,
            array(
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true
            )
        );

        $context->reply("Message sent.\n<code>" . json_encode($results) . "</code>");

        return true;
    }
    else if(starts_with($text, '/channel ')) {
        $payload = extract_command_payload($text);
        if(!$payload) {
            return false;
        }

        if($context->channel($payload) === false) {
            $context->reply(TEXT_FAILURE_GENERAL);
        }

        return true;
    }
}

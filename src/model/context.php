<?php
/*
 * CodeMOOC TreasureHuntBot
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Class wrapping the bot's context in this run.
 */

require_once(dirname(__FILE__) . '/../lib.php');
require_once(dirname(__FILE__) . '/incoming_message.php');

class Context {

    private $message;

    private $identity = 0;
    private $is_admin = false;
    private $state = 0;

    /**
     * Construct Context class.
     * @param $message IncomingMessage.
     */
    function __construct($message) {
        if(!($message instanceof IncomingMessage))
            die('Message variable is not an IncomingMessage instance');

        $this->message = $message;
        $this->refresh();
    }

    /**
     * Returns whether the current user is fully registered or not.
     */
    function is_registered() {
        return ($this->identity !== 0 && $this->state >= STATE_REG_OK);
    }

    /**
     * Gets the user's synthetic (generated) ID.
     */
    function get_identity() {
        return ($this->identity !== 0) ? $this->identity : null;
    }

    /* True if the talking user is an admin */
    function is_admin() {
        return $this->is_admin;
    }

    function get_user_id() {
        return $this->message->from_id;
    }

    function get_chat_id() {
        return $this->message->chat_id;
    }

    function get_message() {
        return $this->message;
    }

    function get_state() {
        return $this->state;
    }

    /**
     * Gets a cleaned-up response from the user, if any.
     */
    function get_response() {
        $text = $this->message->text;
        if($text)
            return extract_response($text);
        else
            return '';
    }

    /**
     * Replies to the current incoming message.
     * Enables markdown parsing and disables web previews by default.
     */
    function reply($message, $additional_values = null, $additional_parameters = null) {
        return $this->send($this->get_chat_id(), $message, $additional_values, $additional_parameters);
    }

    /**
     * Sends out a message on the channel.
     */
    function channel($message, $additional_values = null) {
        return $this->send(CHAT_CHANNEL, $message, $additional_values, null);
    }

    function send($receiver, $message, $additional_values = null, $additional_parameters = null) {
        if(!$receiver) {
            Logger::error("Receiver not set", __FILE__, $this);
            return false;
        }
        if($message === null) {
            Logger::info("Message is null", __FILE__, $this);
            return false;
        }

        $hydration_values = array(
            '%FIRST_NAME%' => $this->get_message()->get_sender_first_name(),
            '%FULL_NAME%' => $this->get_message()->get_sender_full_name(),
            '%WEEKDAY%' => TEXT_WEEKDAYS[intval(strftime('%w'))]
        );

        $hydrated = hydrate($message, unite_arrays($hydration_values, $additional_values));
        $default_parameters = array(
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true
        );
        if($receiver != CHAT_CHANNEL) {
            // "Hide keyboard" is added by default to all messages because
            // of a bug in Telegram that doesn't hide "one-time" keyboards after use
            $default_parameters['reply_markup'] = array(
                'hide_keyboard' => true
            );
        }

        return telegram_send_message(
            $receiver,
            $hydrated,
            unite_arrays($default_parameters, $additional_parameters)
        );
    }

    /**
     * Refreshes information about the context from the DB.
     */
    function refresh() {
        $identity = db_row_query("SELECT `id`, `full_name`, `is_admin`, `state` FROM `identities` WHERE `telegram_id` = {$this->get_user_id()}");

        if(!$identity) {
            Logger::info("User identity not registered, registering", __FILE__, $this);

            $new_id = db_perform_action("INSERT INTO `identities` (`telegram_id`, `full_name`, `first_access`, `last_access`) VALUES({$this->get_user_id()}, '{$this->message->get_sender_full_name()}', NOW(), NOW())");

            $this->identity = $new_id;
            $this->is_admin = false;
            $this->state = 0;
        }
        else {
            $this->identity = intval($identity[0], 10);
            $this->is_admin = (bool)$identity[2];
            $this->state = intval($identity[3], 10);

            db_perform_action("UPDATE `identities` SET `last_access` = NOW() WHERE `id` = {$this->identity}");
        }
    }

    function set_state($new_state) {
        Logger::info("Updating state to {$new_state}", __FILE__, $this);

        if(db_perform_action("UPDATE `identities` SET `state` = {$new_state} WHERE `id` = {$this->identity}") === false) {
            Logger::error("Failed to update state for user #{$this->get_user_id()}", __FILE__, $this);
            return false;
        }

        $this->state = $new_state;
        return true;
    }

}

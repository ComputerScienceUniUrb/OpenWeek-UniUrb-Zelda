<?php
/**
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Support library. Don't change a thing here.
 */

class Logger {

    const SEVERITY_DEBUG = 1;
    const SEVERITY_INFO = 64;
    const SEVERITY_WARNING = 128;
    const SEVERITY_ERROR = 255;

    public static function debug($message, $tag = '', $context = null) {
        self::common(self::SEVERITY_DEBUG, $message, $tag, $context);
    }

    public static function info($message, $tag = '', $context = null) {
        self::common(self::SEVERITY_INFO, $message, $tag, $context);
    }

    public static function warning($message, $tag = '', $context = null) {
        self::common(self::SEVERITY_WARNING, $message, $tag, $context);
    }

    public static function error($message, $tag = '', $context = null) {
        self::common(self::SEVERITY_ERROR, $message, $tag, $context);
    }

    public static function fatal($message, $tag = '', $context = null) {
        self::error($message, $tag, $context);

        die();
    }

    private static function severity_to_char($level) {
        if($level >= self::SEVERITY_ERROR)
            return 'E';
        else if($level >= self::SEVERITY_WARNING)
            return 'W';
        else if($level >= self::SEVERITY_INFO)
            return 'I';
        else
            return 'D';
    }

    private static function common($level, $message, $tag = '', $context = null) {
        $identity = ($context != null && $context->is_registered()) ? $context->get_identity() : 'NULL';
        $user_id = ($context != null) ? $context->get_user_id() : 'NULL';
        $filename = basename($tag, '.php');

        if(is_cli()) {
            // In CLI mode, output all logs to stderr and we're done
            fwrite(STDERR, self::severity_to_char($level) . '/' . $message . PHP_EOL);
        }
        else {
            // Warnings and errors go to the system log
            if($level >= self::SEVERITY_WARNING) {
                error_log(self::severity_to_char($level) . ':' . $filename . ':' . $message);
            }

            // Log to DB if needed
            if(DEBUG_TO_DB || $level > self::SEVERITY_DEBUG) {
                db_perform_action("INSERT INTO `log` (`id`, `level`, `identity`, `telegram_id`, `tag`, `message`, `timestamp`) VALUES(DEFAULT, {$level}, {$identity}, {$user_id}, '" . db_escape($filename) . "', '" . db_escape($message) . "', NOW())");
            }
        }
    }

}

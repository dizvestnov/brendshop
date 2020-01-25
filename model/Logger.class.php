<?php

class Logger 
{
    public static function Write($message, $echo = false)
    {
        $string = date('Y-m-d H:i:s') . $message . "\n";
        file_put_contents(Config::get('path_logs') . '/log.txt', $string, FILE_APPEND);
        if ($echo) {
            echo $message . "\n";
        }
    }
}
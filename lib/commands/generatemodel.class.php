<?php

class generatemodel extends Command
{
    public static $description = 'Generate table for model';

    public function exec($arguments)
    {
        if (!isset($arguments[0])) {
            throw new Exception('Please, type model name');
        }
        Logger::write('Generating model', true);
        return $arguments[0]::generate();
    }
}
?>
<?php

abstract class Command implements ICommand
{
    public static $description = 'Here is a basic description of command';
    
    public final function run($arguments) 
    {
        $commandName = get_class($this);
        Logger::Write('Executing "' . $commandName . '"', true);
        try {
            $return = $this->exec($arguments);
        } catch (Exception $e) {
            Logger::Write('Unable to execute ' . $commandName . ': ' . $e->getMessage(), true);
            $return = false;
        }
        $message = $return ? ' done' : 'finished with error';
        Logger::Write('"' . $commandName . '": ' . $message, true);
    }
}
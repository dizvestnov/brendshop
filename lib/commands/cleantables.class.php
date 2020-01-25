<?php

class cleantables extends Command
{
    public static $description = 'Clean all tables';

    public function exec($arguments)
    {
        Logger::write('Cleaning tables', true);
        foreach (db::getInstance()->select('SHOW TABLES') as $table) {
            if (empty($arguments)) {
                $this->doClean($table['Tables_in_shop']);
            } else {
                if($arguments[0] == $table['Tables_in_shop']) {
                    $this->doClean($table['Tables_in_shop']);
                    return true;
                }
            }
        }

        return true;
    }

    protected function doClean($table)
    {
        Logger::write('Cleaning table ' . $table, true);
        db::getInstance()->query('TRUNCATE TABLE ' . $table);
    }



}
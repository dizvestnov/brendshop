<?php

class migrate extends Command
{
    public static $description = 'Migration';

    public function exec($arguments)
    {
        db::getInstance()->Query('CREATE TABLE IF NOT EXISTS migrations (`migration` VARCHAR(32))');
        Logger::write('Reading migrations', true);
        $migrations = $this->getMigrations();
        foreach ($migrations as $migration) {
            $name = $migration::getName();
            if (!$this->migrationExists($name)) {
                Logger::Write('Executing ' . $name, true);
                $migration::run();
                db::getInstance()->Query('INSERT INTO migrations VALUES (:name)', ['name' => $name]);
            }
        }
        return true;
    }

    protected function migrationExists($name)
    {
        return count(db::getInstance()->Select('SELECT migration FROM migrations WHERE migration=:name', ['name' => $name])) > 0;
    }

    protected function getMigrations()
    {
        $migrationsDir = opendir(Config::get('path_migrations'));
        $migrations = [];
        while ($rd = readdir($migrationsDir)) {
            if ($rd !== '..' && $rd !== '.' && $rd !== 'IMigration.class.php') {
                $migrations[] = str_replace('.class.php', '', $rd);
            }
        }
        sort($migrations);
        return $migrations;
    }
}

?>
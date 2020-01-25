<?php

class fixture extends Command
{
    public static $description = 'Here we will add some fixtures';

    public function exec($arguments)
    {
        Logger::write('Reading fixtures', true);
        $fixtures = $this->getFixtures();
        foreach ($fixtures as $fixture) {
            $fixtureFile = Config::get('path_fixtures') . '/' . $fixture;
            $queries = explode(';', file_get_contents($fixtureFile));
            foreach ($queries as $query) {
                if (trim($query) !== '') {
                    db::getInstance()->Query($query);
                }
            }
            Logger::Write('Added ' . $fixture, true);
        }
        return true;
    }

    protected function getFixtures()
    {
        $fixturesDir = opendir(Config::get('path_fixtures'));
        $fixtures = [];
        while ($rd = readdir($fixturesDir)) {
            if ($rd !== '..' && $rd !== '.') {
                $fixtures[] = $rd;
            }
        }
        sort($fixtures);
        return $fixtures;
    }
}
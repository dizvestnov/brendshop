<?php
class M002 implements IMigration
{
    public static function getName()
    {
        return __CLASS__;
    }

    public static function run()
    {
        db::getInstance()->Query('UPDATE pages SET status=:status', [ 'status' => Status::Active]);
    }
}
?>
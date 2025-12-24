<?php
class Setting extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllSettings()
    {
        $sql = "SELECT * FROM settings";
        return $this->getAll($sql);
    }

    public function saveSetting($key, $value)
    {
        $this->update('settings', ['setting_value' => $value], "setting_key = '$key'");
    }
}

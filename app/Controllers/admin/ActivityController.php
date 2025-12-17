<?php
class ActivityController extends baseController
{
    private $activityModel;
    public function __construct()
    {
        $this->activityModel = new Activity;
    }

    public function list()
    {
        $data = [];
        $this->renderView('/layout-part/admin/logs/list', $data);
    }
}

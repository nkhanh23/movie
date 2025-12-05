<?php
class HomeController extends baseController
{
    private $coreModel;
    public function __construct()
    {
        $this->coreModel = new CoreModel;
    }

    public function adminDashboard()
    {
        $this->renderView('/layout-part/admin/dashboard');
    }

    public function homePage()
    {
        $this->renderView('/layout-part/client/dashboard');
    }
}
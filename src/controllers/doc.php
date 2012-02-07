<?php
class DocController extends PHPFrame_ActionController
{
    public function __construct(PHPFrame_Application $app)
    {
        parent::__construct($app, "index");
    }

    public function index()
    {
        $view = $this->view("usage");
        
        $base_url = $this->config()->get("base_url"); 
        $view->addData('base_url',$base_url);

        $this->response()->title("Drivers of Change");
        $this->response()->body($view);
    }
}

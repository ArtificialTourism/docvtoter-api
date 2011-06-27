<?php
class DocController extends PHPFrame_ActionController
{
    public function __construct(PHPFrame_Application $app)
    {
        parent::__construct($app, "index");
    }

    public function index()
    {
        $view = $this->view("index");

        $this->response()->title("Drivers of Change");
        $this->response()->body($view);
    }
}

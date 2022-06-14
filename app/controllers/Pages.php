<?php
class Pages extends Controller
{
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    // index page (pattern for other pages)
    public function index()
    {
        $data = [
            'title' => 'Home Page'
        ];

        $this->view('pages/index', $data);
    }
}

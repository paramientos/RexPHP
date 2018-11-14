<?php

class CommonHomeController extends Rex
{
    public function index()
    {
        $data['cool'] = 'Rex is cool';
        $this->view('common/home', $data);
    }
}

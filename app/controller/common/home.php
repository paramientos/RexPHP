<?php

class CommonHomeController extends Rex {

    function index() {
        $data['rex'] = "Rex is cool";
        $this->view('common/home', $data);
        $this->helper('lk');
    }

}

?>
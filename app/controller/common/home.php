<?php

class CommonHomeController extends Rex {

    function index() {
        $data['cool'] = "Rex is cool";
        $this->view('common/home', $data);
    }

}

?>
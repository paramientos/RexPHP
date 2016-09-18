<?php



class CommonHomeController extends Rex {

    function index() {
		$data=array(
			"rex"	=>	"Rex is cool"
			);
        $this->view('common/home',$data);
    }

}

?>
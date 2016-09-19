<?php

/**
 *      The Rex PHP Framework
 *         A STATE OF PHP
 *       Make PHP Great Again
 * 
 * @author     Soysal Tan
 * @license MIT License https://github.com/soysaltan/Rex/blob/master/LICENSE
 * @copyright  2013 - 2016 Soysal Tan
 * @category PHP Framework
 * @see https://github.com/soysaltan/Rex
 * @version 1.1.6
 * 
 * @uses Lex PHP Template Parser
 * @author     Dan Horrigan
 * @license    MIT License
 * @copyright  2011 - 2012 Dan Horrigan
 * @see https://github.com/pyrocms/lex
 * 
 * @uses Medoo The lightest PHP database framework
 * @see http://medoo.in/
 * @license Medoo is under MIT license http://medoo.in/about
 * @author Angel Lai
 * @link https://github.com/catfan/Medoo
 * 
 */
class Rex {

    /**
     * @access protected
     * @var $bag array 
     */
    protected $bag = array();

    /**
     * @access protected
     * @var $model string 
     */
    protected $model;

    /**
     * @access protected
     * @var $model_ext string 
     */
    protected $model_ext = '.php';

    /**
     * @access protected
     * @var $view string 
     */
    protected $view;

    /**
     * @access protected
     * @var $headerFile string 
     */
    protected $headerFile;

    /**
     * @access protected
     * @var $footerFile string 
     */
    protected $footerFile;

    /**
     * @access protected
     * @var $controller_ext string 
     */
    protected $controller_ext = '.php';

    /**
     * @access protected
     * @var $helper_ext string 
     */
    protected $helper_ext = '.php';

    /**
     * @access protected
     * @var $import_ext string 
     */
    protected $import_ext = '.php';

    /**
     * @access protected
     * @var $import_suffix string 
     */
    protected $import_suffix = '.class';

    /**
     * @access public
     * @var $db object 
     */
    public $db;

    /**
     * DB drivers can be inserted from the root/driver folder.
     * 
     * Usage :  $this->db() // initialize the db class
     *          $this->db->query("select * from table"); @see the root/driver/drivername.ext file
     * 
     * @access public
     * @see root/config.php file for the database_type constant
     * @return DB object
     */
    public function db() {
        include_once ROOT . DS . 'driver' . DS . database_type . '.php';
        $db_type = 'db_' . database_type;
        $this->db = new $db_type(server, username, password, database_name);
        return $this;
    }

    /**
     * Call the model method.
     * 
     * Seeks : root/app/model/path/to/modelname.ext
     * 
     * Usage   :    $this->model('dir/file'); 
     *              $this->model_dir_file->method();
     * 
     * Example :    $this->model('user/user'); 
     *              $this->model_user_user->get_users();
     * 
     * @access public 
     * @param string $model example : 'user/user'
     * @return object Returns the model object
     */
    public function model($model) {
        $model_exp = explode('/', $model);

        if (count($model_exp) > 1) {
            $this->model = ROOT . DS . 'app' . DS . 'model' . DS . str_replace('/', DS, $model) . $this->model_ext;

            $class = ucfirst($model_exp[0]) . ucfirst($model_exp[1]) . 'Model';

            if (file_exists($this->model)) {
                extract($this->bag);

                ob_start();

                require($this->model);

                $output = ob_get_contents();

                ob_end_clean();

                echo $output;

                $global_class = 'model' . '_' . $model_exp[0] . '_' . $model_exp[1];
                $this->$global_class = new $class();
            }
        }
        return $this;
    }

    /**
     * Render the template with header and footer pages
     * 
     * @access public 
     * @global array $data
     * @param string $template The template file without extension
     * @param array $datas Viewbag
     * @return string as HTML
     */
    public function view($template = null, $datas = array()) {

        $this->headerFile = ROOT . DS . 'app' . DS . 'view' . DS . 'theme' . DS . THEME_NAME . DS . 'template' . DS . str_replace('/', DS, HEADER_FILE) . TEMPLATE_EXTENSION;
        $this->footerFile = ROOT . DS . 'app' . DS . 'view' . DS . 'theme' . DS . THEME_NAME . DS . 'template' . DS . str_replace('/', DS, FOOTER_FILE) . TEMPLATE_EXTENSION;

        //@TODO direct call is neccasary?
        $trace = isset($_REQUEST['trace']) ? $trace = $_REQUEST['trace'] :
                $trace = DEFAULT_PAGE;

        $trace = explode('/', $trace);
        $count = count($trace);
        $getPath = '';
        for ($i = 0; $i < $count - 2; $i++) {
            $getPath .= $trace[$i] . '/';
        }
        $getFileName = &$trace[$count - 2];
        /*         * ***************************************************** */
        if ($template == null || $template == '')
            $template = $getFileName;

        if (isset($datas)) {
            global $data;
            $data = array();
            $data = $datas;
            $arr = array();
            foreach ($datas as $key => $value) {
                $$key = $value;
                $arr[$key] = $value;
            }
        }

        $this->view = ROOT . DS . 'app' . DS . 'view' . DS . 'theme' . DS . THEME_NAME . DS . 'template' . DS . str_replace('/', DS, $template) . TEMPLATE_EXTENSION;

        extract($this->bag);

        ob_start();

        if (file_exists($this->headerFile)) {
            require($this->headerFile);
        } else {
            trigger_error('Error: Could not load template ' . $this->headerFile . '<br><label style=\'color:red;\'>Please change the HEADER_FILE constant in system/config.php</label> and the error is triggired from ');
            //exit();
        }


        if (file_exists($this->view)) {
            require($this->view);
        } else {
            trigger_error('Error: Could not load template ' . $this->view);
            //exit();
        }


        if (file_exists($this->footerFile)) {
            require($this->footerFile);
        } else {
            trigger_error('Error: Could not load template ' . $this->footerFile . '<br><label style=\'color:red;\'>Please change the FOOTER_FILE constant in system/config.php</label> and the error is triggired from ');
            //exit();
        }

        $content = ob_get_contents();

        ob_end_clean();

        echo $content;
    }

    /**
     * Render the template without header and footer pages
     * 
     * @access public 
     * @global array $data
     * @param string $template The template file without extension
     * @param array $datas Viewbag
     */
    public function partial_view($view = null, $datas = array()) {




        if (isset($datas)) {
            global $data;
            $data = array();
            $data = $datas;
            $arr = array();
            foreach ($datas as $key => $value) {
                $$key = $value;
                $arr[$key] = $value;
            }
        }

        $this->view = ROOT . DS . 'app' . DS . 'view' . DS . 'theme' . DS . THEME_NAME . DS . 'template' . DS . str_replace('/', DS, $view) . TEMPLATE_EXTENSION;

        extract($this->bag);

        ob_start();


        if (file_exists($this->view)) {
            require($this->view);
        } else {
            trigger_error('Error: Could not load template ' . $this->view);
            //exit();
        }


        $content = ob_get_contents();

        ob_end_clean();

        echo $content;
    }

    /**
     * Call the helper
     * 
     * Seeks : root/helper/helpername.ext
     * 
     * Usage   :    $this->helper('helper_name'); 
     *              $this->helper_name->method();
     * 
     * Example :    $this->helper('str');
     *              $this->str->format('Hello {0}','world'); // outputs Hello world 
     * 
     * @access public 
     * @param string $helper_name the helper name to be called
     * @return object usage : $this->helper_name->method();
     */
    public function helper($helper_name) {
        if (strpos($helper_name, ',') !== false) {
            $helperName = explode(',', $helper_name);
            foreach ($helper_name as $helper) {
                $file = ROOT . DS . 'helper' . DS . $helper . $this->helper_ext;
                if (file_exists($file)) {
                    extract($this->bag);

                    ob_start();

                    require_once($file);

                    $output = ob_get_contents();

                    ob_end_clean();

                    echo $output;
                    $helper = ucfirst($helper);
                    $helper_name_global = strtolower($helper);
                    $this->$helper_name_global = new $helper();
                } else {
                    trigger_error('Error: Could not load helper ' . $helper);
                }
            }
        } else {//single param
            $file = ROOT . DS . 'helper' . DS . $helper_name . $this->helper_ext;
            if (file_exists($file)) {
                extract($this->bag);

                ob_start();

                require_once($file);

                $output = ob_get_contents();

                ob_end_clean();

                echo $output;
                $helper_name = ucfirst($helper_name);
                $helper_name_global = strtolower($helper_name);
                $this->$helper_name_global = new $helper_name();
            } else {
                trigger_error('Error: Could not load helper <u>' . $helper_name . '</u>');
            }
        }
        return $this;
    }

    //unused
    private function create_instance_without_constructor($class) {
        $reflector = new ReflectionClass($class);
        $properties = $reflector->getProperties();
        $defaults = $reflector->getDefaultProperties();

        $serealized = "O:" . strlen($class) . ":\"$class\":" . count($properties) . ':{';
        foreach ($properties as $property) {
            $name = $property->getName();
            if ($property->isProtected()) {
                $name = chr(0) . '*' . chr(0) . $name;
            } elseif ($property->isPrivate()) {
                $name = chr(0) . $class . chr(0) . $name;
            }
            $serealized .= serialize($name);
            if (array_key_exists($property->getName(), $defaults)) {
                $serealized .= serialize($defaults[$property->getName()]);
            } else {
                $serealized .= serialize(null);
            }
        }
        $serealized .="}";
        return unserialize($serealized);
    }

    /**
     * Call the library
     * 
     * Seeks : root/library/libraryname.ext
     * 
     * Usage   :    $this->import('import_name'); 
     *              $this->import_name->method();
     * 
     * Example :    $this->import('lex');
     *              $this->lex->render('common/home');
     * 
     * @access public 
     * @param string $library_name the library name to be called
     * @return object usage : $this->library_name->method();
     */
    public function import($library_name) {
        if (strpos($library_name, ',') !== false) {
            $library_name = explode(',', $library_name);
            foreach ($library_name as $library) {
                $file = ROOT . DS . 'library' . DS . $library
                        . $this->import_suffix . $this->import_ext;
                require_once($file);
                $library = ucfirst($library);
                if (class_exists($library)) {
                    $library_name_global = strtolower($library);
                    $this->$library_name_global = new $library;
                } else {
                    trigger_error("No class found : <u>$library</u>");
                    exit();
                }
            }
        } else {
            $file = ROOT . DS . 'library' . DS . $library_name
                    . $this->import_suffix . $this->import_ext;
            require_once($file);
            $library_name = ucfirst($library_name);
            if (class_exists($library_name)) {
                $library_name_global = strtolower($library_name);
                $this->$library_name_global = new $library_name();
            } else {
                trigger_error("No class found : <u>$library_name</u>");
                exit();
            }
        }
        return $this;
    }

    /**
     * Call the controller method.This is just like the calling a method in PHP
     * 
     * Seeks : root/app/controller/path/to/controllername.ext
     * 
     * Usage   :    $this->call('dir/file',array('id'=>6); 
     * 
     * Example :    $this->call('common/home',array('id'=>6); 
     *              //goes to the route -> common/home/6 or index.php?trace=common/home/6
     *              $this->lex->render('common/home');
     *
     * @access public 
     * @param string $controller example : 'common/home'
     * @param array $args post and get parameters as string
     */
    public function call($controller, $args = array()) {
        $gets = explode('/', $controller);

        $controller_file = "";

        if (count($gets) == 2) {
            array_push($gets, 'index');
        }


        $controller_file.='/' . $gets[0] . '/' . $gets[1];


        $class_name = ucfirst($gets[0]) . ucfirst($gets[1]) . 'Controller';

        $method = $gets[2];


        if (count($gets) > 3) {//has param
            for ($i = 3; $i < count($gets); $i++) {
                $args[] = $gets[$i];
            }
        }



        $controller_file = ROOT . DS . 'app' . DS . 'controller' . DS . str_replace('/', DS, $controller_file) . $this->controller_ext;
        if (file_exists($controller_file)) {
            extract($this->bag);

            ob_start();

            require_once($controller_file);

            $output = ob_get_contents();

            ob_end_clean();
            echo $output;
            $cls = new $class_name();

            if (method_exists($cls, $method) && is_callable(array($cls, $method))) {
                call_user_func_array(array($cls, $method), $args);
            } else {
                trigger_error('Error: There is no method like <u>' . $method . '</u>');
            }
        } else {
            trigger_error('Error: There is no file like <u>' . $controller_file . '</u>');
        }
    }

    /**
     * @access public 
     * SILENCE IS GOLDEN
     */
    public function rex() {
        $this->ladies_first();
    }

    /**
     * Load the initial helpers or etc.
     * Rex looks here firstly
     * @access public 
     */
    public function ladies_first() {
        register_shutdown_function("fatal_handler");
        if (file_exists(ROOT . DS . 'init.php')) {
            include ROOT . DS . 'init.php';
        }
    }

}

/**
 *
 * @internal Custom error handler
 */
function fatal_handler() {
    $errorFile = "unknown file";
    $errorStr = "shutdown";
    $errorNo = E_CORE_ERROR;
    $errorLine = 0;

    $error = error_get_last();

    if ($error !== NULL) {
        $errorNo = $error["type"];
        $errorFile = $error["file"];
        $errorLine = $error["line"];
        $errorStr = $error["message"];

        //echo format_error( $errno, $errstr, $errfile, $errline);
        echo format_error($errorStr, $errorFile, $errorLine, $error);
        exit();
    }
}

/**
 * @internal Custom error handling
 * This is used in Rex
 * @param string $errorMessage
 * @param string $file
 * @param string $line
 * @param string $error
 * @return string
 */
function format_error($errorMessage, $file, $line, $error) {
    if (SHOW_ERROR) {
        ob_clean();
        $html = "";
        $html = "<style type=\"text/css\">
	.alert-box {
		color:#555;
		border-radius:10px;
		font-family:Tahoma,Geneva,Arial,sans-serif;font-size:12px;
		padding:10px 36px;
		margin:10px;
	}
	.alert-box span {
		font-weight:bold;
		text-transform:uppercase;
	}
	.error {
		background:#ffecec  no-repeat 10px 50%;
		border:1px solid #f5aca6;
	}
	.success {
		background:#e9ffd9 no-repeat 10px 50%;
		border:1px solid #a6ca8a;
	}
	.warning {
		background:#fff8c4 no-repeat 10px 50%;
		border:1px solid #f2c779;
	}
	.notice {
		background:#e3f7fc no-repeat 10px 50%;
		border:1px solid #8ed9f6;
	}
    </style>
  <div class = \"alert-box warning\"><span>Endorphine says :: </span><b>$errorMessage</b> on $file on line <b>$line</b></div>    
";


        return $html;
    }
}

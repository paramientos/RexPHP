<?php

/**
 * Lex Template Parser and REXPHP combination.
 * If you want to use Lex Template Parser, you can call this from Rex
 * Usage : $this->import('lex');
 *         $this->lex->render('common/home');.
 *
 * @author     Dan Horrigan
 * @license    MIT License
 * @copyright  2011 - 2012 Dan Horrigan
 */

/**
 *  extends to Rex this is because use some features of RexPHP.
 */
class Lex extends Rex
{
    public function partial_render($template = null, $data = [])
    {
        $this->render($template, $data, false);
    }

    public function render($template = null, $data = [], $show_header_footer = true)
    {
        $lex = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'lex'.DIRECTORY_SEPARATOR.'Parser.php';
        require_once $lex;
        $parser = new Parser();

        $render_header = $render_body = $render_footer = '';

        if ($show_header_footer) {
            $header_file = ROOT.DS.'app'.DS.'view'.DS.'theme'.DS.THEME_NAME.DS.'template'.DS.str_replace('/', DS, HEADER_FILE).TEMPLATE_EXTENSION;
            if (file_exists($header_file)) {
                //include('app/view/' . HEADER_FILE);
                $render_header = $parser->parse($header_file, $data, false, true);
            } else {
                trigger_error('Error: Could not load template '.$header_file.'<br><label style=\'color:red;\'>Please change the HEADER_FILE constant in config.php</label> and the error is triggired from ');
                //exit();
            }
        }

        $body_file = ROOT.DS.'app'.DS.'view'.DS.'theme'.DS.THEME_NAME.DS.'template'.DS.str_replace('/', DS, $template).TEMPLATE_EXTENSION;
        $render_body = $parser->parse($body_file, $data, false, true);

        if ($show_header_footer) {
            $footer_file = ROOT.DS.'app'.DS.'view'.DS.'theme'.DS.THEME_NAME.DS.'template'.DS.str_replace('/', DS, FOOTER_FILE).TEMPLATE_EXTENSION;
            if (file_exists($footer_file)) {
                $render_footer = $parser->parse($footer_file, $data, false, true);
            } else {
                trigger_error('Error: Could not load template '.$footer_file.'<br><label style=\'color:red;\'>Please change the FOOTER_FILE constant in config.php</label> and the error is triggired from ');
                //exit();
            }
        }
        echo $render_header.$render_body.$render_footer;
    }
}

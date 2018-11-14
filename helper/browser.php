<?php

class browser
{
    public function is_mobile()
    {
        $iPod = stripos($_SERVER['HTTP_USER_AGENT'], 'iPod');
        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone');
        $iPad = stripos($_SERVER['HTTP_USER_AGENT'], 'iPad');
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], 'Android');
        $webOS = stripos($_SERVER['HTTP_USER_AGENT'], 'webOS');

        if ($iPod || $iPhone || $iPad || $Android || $webOS) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * http://ageekandhisblog.com/use-php-to-detect-internet-explorer-11-and-below/
     * It’s simple and it works well. The first part targets IE10 and below,
     * while the second targets the newer IE11 user agent. Modify as needed.
     */
    public function is_ie()
    {
        return (preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false)) ? true : false;
    }
}

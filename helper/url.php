<?php

/** absolutely no required to extends to rex *** */
class url {

    /**
     * 
     * @param string $url
     * @param type $prefix
     * @return string
     */
    function prefix($url, $prefix = 'http') {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = $prefix . '://' . $url;
        }

        return $url;
    }

    function tiny_url($url) {
        return file_get_contents("http://tinyurl.com/api-create.php?url=" . $url);
    }

    function exist($url = NULL) {
        $ch = @curl_init($url);
        @curl_setopt($ch, CURLOPT_HEADER, TRUE);
        @curl_setopt($ch, CURLOPT_NOBODY, TRUE);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $status = array();
        preg_match('/HTTP\/.* ([0-9]+) .*/', @curl_exec($ch), $status);
        return ($status[1] == 200);
    }

    function make_seo_name($title) {
        return preg_replace('/[^a-z0-9_-]/i', '', strtolower(str_replace(' ', '-', trim($title))));
    }

    function get_keyword($url, $json = false) {
        $meta = get_meta_tags($url);
        $keywords = $meta['keywords'];
// Split keywords
        $keywords = explode(',', $keywords);
// Trim them
        $keywords = array_map('trim', $keywords);
// Remove empty values
        $keywords = array_filter($keywords);
        return $json ? json_encode($keywords) : $keywords;
    }

    function status($link, $json = false) {

        $http_status_codes = array(
            100 => 'Informational: Continue',
            101 => 'Informational: Switching Protocols',
            102 => 'Informational: Processing',
            200 => 'Successful: OK',
            201 => 'Successful: Created',
            202 => 'Successful: Accepted',
            203 => 'Successful: Non-Authoritative Information',
            204 => 'Successful: No Content',
            205 => 'Successful: Reset Content',
            206 => 'Successful: Partial Content',
            207 => 'Successful: Multi-Status',
            208 => 'Successful: Already Reported',
            226 => 'Successful: IM Used',
            300 => 'Redirection: Multiple Choices',
            301 => 'Redirection: Moved Permanently',
            302 => 'Redirection: Found',
            303 => 'Redirection: See Other',
            304 => 'Redirection: Not Modified',
            305 => 'Redirection: Use Proxy',
            306 => 'Redirection: Switch Proxy',
            307 => 'Redirection: Temporary Redirect',
            308 => 'Redirection: Permanent Redirect',
            400 => 'Client Error: Bad Request',
            401 => 'Client Error: Unauthorized',
            402 => 'Client Error: Payment Required',
            403 => 'Client Error: Forbidden',
            404 => 'Client Error: Not Found',
            405 => 'Client Error: Method Not Allowed',
            406 => 'Client Error: Not Acceptable',
            407 => 'Client Error: Proxy Authentication Required',
            408 => 'Client Error: Request Timeout',
            409 => 'Client Error: Conflict',
            410 => 'Client Error: Gone',
            411 => 'Client Error: Length Required',
            412 => 'Client Error: Precondition Failed',
            413 => 'Client Error: Request Entity Too Large',
            414 => 'Client Error: Request-URI Too Long',
            415 => 'Client Error: Unsupported Media Type',
            416 => 'Client Error: Requested Range Not Satisfiable',
            417 => 'Client Error: Expectation Failed',
            418 => 'Client Error: I\'m a teapot',
            419 => 'Client Error: Authentication Timeout',
            420 => 'Client Error: Method Failure',
            422 => 'Client Error: Unprocessable Entity',
            423 => 'Client Error: Locked',
            424 => 'Client Error: Method Failure',
            425 => 'Client Error: Unordered Collection',
            426 => 'Client Error: Upgrade Required',
            428 => 'Client Error: Precondition Required',
            429 => 'Client Error: Too Many Requests',
            431 => 'Client Error: Request Header Fields Too Large',
            444 => 'Client Error: No Response',
            449 => 'Client Error: Retry With',
            450 => 'Client Error: Blocked by Windows Parental Controls',
            451 => 'Client Error: Redirect',
            494 => 'Client Error: Request Header Too Large',
            495 => 'Client Error: Cert Error',
            496 => 'Client Error: No Cert',
            497 => 'Client Error: HTTP to HTTPS',
            499 => 'Client Error: Client Closed Request',
            500 => 'Server Error: Internal Server Error',
            501 => 'Server Error: Not Implemented',
            502 => 'Server Error: Bad Gateway',
            503 => 'Server Error: Service Unavailable',
            504 => 'Server Error: Gateway Timeout',
            505 => 'Server Error: HTTP Version Not Supported',
            506 => 'Server Error: Variant Also Negotiates',
            507 => 'Server Error: Insufficient Storage',
            508 => 'Server Error: Loop Detected',
            509 => 'Server Error: Bandwidth Limit Exceeded',
            510 => 'Server Error: Not Extended',
            511 => 'Server Error: Network Authentication Required',
            598 => 'Server Error: Network read timeout error',
            599 => 'Server Error: Network connect timeout error',
        );

        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        $c = curl_exec($ch);
        $result = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $json ? json_encode(array($result, $http_status_codes[$result])) : array($result, $http_status_codes[$result]);
    }

    function short_url($url) {
        $length = strlen($url);
        if ($length > 45) {
            $length = $length - 30;
            $first = substr($url, 0, -$length);
            $last = substr($url, -15);
            $new = $first . "[ ... ]" . $last;
            return $new;
        } else {
            return $url;
        }
    }

    function is_https() {
        return
                (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    }

    function get_full_url() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? $protocol = "https://" : $protocol = "http://";
        return $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * http://www.jonasjohn.de/snippets/php/secure-redirect.htm
     * @param type $url
     * @param type $exit
     */
    function safe_redirect($url, $exit = true) {

        // Only use the header redirection if headers are not already sent
        if (!headers_sent()) {

            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $url);

            // Optional workaround for an IE bug (thanks Olav)
            header("Connection: close");
        }

        // HTML/JS Fallback:
        // If the header redirection did not work, try to use various methods other methods

        print '<html>';
        print '<head><title>Redirecting you...</title>';
        print '<meta http-equiv = "Refresh" content = "0;url=' . $url . '" />';
        print '</head>';
        print '<body onload = "location.replace(\'' . $url . '\')">';

        // If the javascript and meta redirect did not work, 
        // the user can still click this link
        print 'You should be redirected to this URL:<br />';
        print "<a href=\"$url\">$url</a><br /><br />";

        print 'If you are not, please click on the link above.<br />';

        print '</body>';
        print '</html>';

        // Stop the script here (optional)
        if ($exit)
            exit();
    }

    /**
     * http://webdeveloperplus.com/php/21-really-useful-handy-php-code-snippets/
     * Create user friendly post slugs from title string to use within URLs.
     * @param type $string
     * @return type
     */
    function user_friendly_url($string) {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
        return $slug;
    }

    public function link($trace, $args = '') {
        if (!FRIENDLY_URL) {
            $url = BASE_URL . 'index.php?trace=' . $trace;

            if ($args) {
                $url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
            }
        } else {//if true
            $url = BASE_URL . $trace;
            if ($args) {
                $sum = "";
                $arg = str_replace('&', '&amp;', '&' . ltrim($args, '&'));
                //echo $arg;
                $exp = explode('&amp;', $arg);
                foreach ($exp as $e) {
                    if ($e != '') {
                        $get = explode('=', $e);
                        $sum.='/' . $get[1];
                    }
                }

                $url = $url . $sum;
            }
        }
        return $url;
    }

    function js_inline_link($url, $args = '') {
        $code = "javascript: document.location='" . $this->link($url, $args) . "'";
        return $code;
    }

    function js_outline_link($url, $args = '') {
        $code = "location='" . $this->link($url, $args) . "'";
        return $code;
    }

    function redirect($url, $args = "", $statusCode = 303) {
        //$go= "<script>location='" . $this->link($url, $args) . "';</script>";
        // echo $go;
        header('Location: ' . $this->link($url, $args), true, $statusCode);
        // die();
    }

}

?>
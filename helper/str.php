<?php

class str {

    function format_phone($phone) {
        $prefix = null;

        //Check if number has international prefix
        switch (true) {
            case substr($phone, 0, 1) == '+':
                $phone = substr($phone, 1);
                $prefix = '+';
                break;
            case substr($phone, 0, 2) == '00':
                $phone = substr($phone, 2);
                $prefix = '+';
                break;
        }

        //Strip all non numeric characters
        $phone = preg_replace("/[^0-9]/", '', $phone);

        switch (strlen($phone)) {
            case 6:
                return preg_replace("/([0-9]{2})([0-9]{2})([0-9]{2})/", "$1-$2-$3", $phone);
                break;
            case 7:
                return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
                break;
            case 10:
                return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
                break;
            case 11:
                return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", $prefix . " ($1) $2-$3", $phone);
                break;
            case 12:
                return preg_replace("/([0-9]{4})([0-9]{4})([0-9]{4})/", $prefix . " ($1) $2-$3", $phone);
                break;
            default:
                return $phone;
                break;
        }
    }

    /**
     * https://paulund.co.uk/get-word-frequency-php
     * @param type $url
     * @param type $json
     * @return type
     */
    function freq($url, $json = false) {
        // Get all the html on a page
        $html = file_get_contents($url);

        // Get an array of all the words
        $allWordsArray = str_word_count(strip_tags($html), 1);
        $totalAllWordsArray = count($allWordsArray);

        // Get the amount of times a word appears on the page
        $wordCount = array_count_values($allWordsArray);
        arsort($wordCount);

        // Get the top 20 words
        $wordCount = array_splice($wordCount, 0, 20);

        // Loop through all the word count array and work out the percentage of a word appearing on the page
        $percentageCount = [];
        foreach ($wordCount as $words => $val) {
            $percentageCount[$words] = number_format(($val / $totalAllWordsArray) * 100, 2);
        }

        return $json ? json_encode($percentageCount) : $percentageCount;
    }

    /**
     * https://paulund.co.uk/increment-numeric-part-of-string
     * @param type $val
     * @return type
     */
    function inc($val) {
        return preg_replace_callback("|(\d+)|", array(get_class(), '_inc'), $val);
    }

    function _inc($matches) {
        if (isset($matches[1])) {
            $length = strlen($matches[1]);
            return sprintf("%0" . $length . "d", ++$matches[1]);
        }
    }

    function is_stopword($word) {
        $words = array("a's", "able", "about", "above", "according", "accordingly", "across", "actually", "after", "afterwards", "again", "against", "ain't", "all", "allow", "allows", "almost", "alone", "along", "already", "also", "although", "always", "am", "among", "amongst", "an", "and", "another", "any", "anybody", "anyhow", "anyone", "anything", "anyway", "anyways", "anywhere", "apart", "appear", "appreciate", "appropriate", "are", "aren't", "around", "as", "aside", "ask", "asking", "associated", "at", "available", "away", "awfully", "be", "became", "because", "become", "becomes", "becoming", "been", "before", "beforehand", "behind", "being", "believe", "below", "beside", "besides", "best", "better", "between", "beyond", "both", "brief", "but", "by", "c'mon", "c's", "came", "can", "can't", "cannot", "cant", "cause", "causes", "certain", "certainly", "changes", "clearly", "co", "com", "come", "comes", "concerning", "consequently", "consider", "considering", "contain", "containing", "contains", "corresponding", "could", "couldn't", "course", "currently", "definitely", "described", "despite", "did", "didn't", "different", "do", "does", "doesn't", "doing", "don't", "done", "down", "downwards", "during", "each", "edu", "eg", "eight", "either", "else", "elsewhere", "enough", "entirely", "especially", "et", "etc", "even", "ever", "every", "everybody", "everyone", "everything", "everywhere", "ex", "exactly", "example", "except", "far", "few", "fifth", "first", "five", "followed", "following", "follows", "for", "former", "formerly", "forth", "four", "from", "further", "furthermore", "get", "gets", "getting", "given", "gives", "go", "goes", "going", "gone", "got", "gotten", "greetings", "had", "hadn't", "happens", "hardly", "has", "hasn't", "have", "haven't", "having", "he", "he's", "hello", "help", "hence", "her", "here", "here's", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "hi", "him", "himself", "his", "hither", "hopefully", "how", "howbeit", "however", "i'd", "i'll", "i'm", "i've", "ie", "if", "ignored", "immediate", "in", "inasmuch", "inc", "indeed", "indicate", "indicated", "indicates", "inner", "insofar", "instead", "into", "inward", "is", "isn't", "it", "it'd", "it'll", "it's", "its", "itself", "just", "keep", "keeps", "kept", "know", "known", "knows", "last", "lately", "later", "latter", "latterly", "least", "less", "lest", "let", "let's", "like", "liked", "likely", "little", "look", "looking", "looks", "ltd", "mainly", "many", "may", "maybe", "me", "mean", "meanwhile", "merely", "might", "more", "moreover", "most", "mostly", "much", "must", "my", "myself", "name", "namely", "nd", "near", "nearly", "necessary", "need", "needs", "neither", "never", "nevertheless", "new", "next", "nine", "no", "nobody", "non", "none", "noone", "nor", "normally", "not", "nothing", "novel", "now", "nowhere", "obviously", "of", "off", "often", "oh", "ok", "okay", "old", "on", "once", "one", "ones", "only", "onto", "or", "other", "others", "otherwise", "ought", "our", "ours", "ourselves", "out", "outside", "over", "overall", "own", "particular", "particularly", "per", "perhaps", "placed", "please", "plus", "possible", "presumably", "probably", "provides", "que", "quite", "qv", "rather", "rd", "re", "really", "reasonably", "regarding", "regardless", "regards", "relatively", "respectively", "right", "said", "same", "saw", "say", "saying", "says", "second", "secondly", "see", "seeing", "seem", "seemed", "seeming", "seems", "seen", "self", "selves", "sensible", "sent", "serious", "seriously", "seven", "several", "shall", "she", "should", "shouldn't", "since", "six", "so", "some", "somebody", "somehow", "someone", "something", "sometime", "sometimes", "somewhat", "somewhere", "soon", "sorry", "specified", "specify", "specifying", "still", "sub", "such", "sup", "sure", "t's", "take", "taken", "tell", "tends", "th", "than", "thank", "thanks", "thanx", "that", "that's", "thats", "the", "their", "theirs", "them", "themselves", "then", "thence", "there", "there's", "thereafter", "thereby", "therefore", "therein", "theres", "thereupon", "these", "they", "they'd", "they'll", "they're", "they've", "think", "third", "this", "thorough", "thoroughly", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "took", "toward", "towards", "tried", "tries", "truly", "try", "trying", "twice", "two", "un", "under", "unfortunately", "unless", "unlikely", "until", "unto", "up", "upon", "us", "use", "used", "useful", "uses", "using", "usually", "value", "various", "very", "via", "viz", "vs", "want", "wants", "was", "wasn't", "way", "we", "we'd", "we'll", "we're", "we've", "welcome", "well", "went", "were", "weren't", "what", "what's", "whatever", "when", "whence", "whenever", "where", "where's", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "who's", "whoever", "whole", "whom", "whose", "why", "will", "willing", "wish", "with", "within", "without", "won't", "wonder", "would", "wouldn't", "yes", "yet", "you", "you'd", "you'll", "you're", "you've", "your", "yours", "yourself", "yourselves", "zero");
        return in_array(strtolower($word), $words);
    }

    /**
     * http://www.phpbuilder.com/snippet/detail.php?type=snippet&id=1477
     * Description: This will generate all possible n-grams for a word and returns an array of all unique n-grams. 
     * @param type $word
     * @param type $min_gram_length
     * @return type
     */
    function ngrams($word, $min_gram_length = 2) {
        $ngrams = array();
        $word = trim($word);
        $len = strlen($word);
        $max_gram_length = $len - 1;

        //BEGIN N-GRAM SIZE LOOP $a

        for ($a = $min_gram_length; $a <= $max_gram_length; $a++) { //BEGIN N-GRAM SIZE LOOP $a
            for ($pos = 0; $pos < $len; $pos ++) {  //BEGIN POSITION WITHIN WORD $pos
                if (($pos + $a - 1) < $len) {  //IF THE SUBSTRING WILL NOT EXCEED THE END OF THE WORD
                    $ngrams[] = substr($word, $pos, $a);
                }  //END IF THE SUBSTRING WILL NOT EXCEED THE END OF THE WORD
            } //END POSITION WITHIN WORD $pos
        }  //END N-GRAM SIZE LOOP $a

        $ngrams = array_unique($ngrams);

        return $ngrams;
    }

    /**
     * 
     * @param type $vals
     * @param type $json
     * @return type
     */
    function remove_duplicated($vals, $json = false) {
        $list = array();
        while (list($key, $val) = each($vals)) {
            $list[$val] = 1;
        }
        return $json ? json_encode(array_keys($list)) : array_keys($list);
    }

    /**
     * 
     * @param type $string
     * @param type $limit
     * @param type $pad
     * @return type
     */
    function string_limiter($string, $limit, $pad = "...") {
        return (strlen($string) > $limit) ? substr($string, 0, $limit) . $pad : $string;
    }

    /**
     * https://code.tutsplus.com/tutorials/9-useful-php-functions-and-features-you-need-to-know--net-11304
     * Using the gzcompress() function, strings can be compressed. 
     * To uncompressed it, simply call the compress
     * @param type $data
     * @return type
     */
    function compress($data) {
        return gzcompress($data);
    }

    /**
     * https://code.tutsplus.com/tutorials/9-useful-php-functions-and-features-you-need-to-know--net-11304
     * getting it back
     * @param type $data
     * @return type
     */
    function decompress($data) {
        return gzuncompress($data);
    }

    /**
     * https://css-tricks.com/snippets/php/zero-padded-numbers/
     * add zero(s) until to the count of $passing
     * @param type $value
     * @param type $padding
     * @return type
     */
    function zero_pad($value, $padding) {
        return str_pad($value, $padding, "0", STR_PAD_LEFT);
    }

    function fake_data($length = 10) {
        return $this->readable_random_string($length);
    }

    /**
     * https://gist.github.com/stavrossk/6234017
     * @param type $length
     * @return string
     */
    function readable_random_string($length = 6) {
        if ($length % 2 != 0)
            $length++; //if it is odd make it even
        $conso = array("b", "c", "d", "f", "g", "h", "j", "k", "l",
            "m", "n", "p", "r", "s", "t", "v", "w", "x", "y", "z");
        $vocal = array("a", "e", "i", "o", "u");
        $password = "";
        srand((double) microtime() * 1000000);
        $max = $length / 2;

        for ($i = 1; $i <= $max; $i++) {
            $password .= $conso[rand(0, 19)];
            $password .= $vocal[rand(0, 4)];
        }

        return $password;
    }

    /**
     * 
     * @param type $format
     * @return type
     */
    function format($format) {
        $args = func_get_args();
        $format = array_shift($args);

        preg_match_all('/(?=\{)\{(\d+)\}(?!\})/', $format, $matches, PREG_OFFSET_CAPTURE);
        $offset = 0;
        foreach ($matches[1] as $data) {
            $i = $data[0];
            $format = substr_replace($format, @$args[$i], $offset + $data[1] - 1, 2 + strlen($i));
            $offset += strlen(@$args[$i]) - 2 - strlen($i);
        }

        return $format;
    }

    function password_strength($string) {
        $h = 0;
        $size = strlen($string);
        foreach (count_chars($string, 1) as $v) {
            $p = $v / $size;
            $h -= $p * log($p) / log(2);
        }
        $strength = ($h / 4) * 100;
        if ($strength > 100) {
            $strength = 100;
        }
        return $strength;
    }

    /**
     * http://w3lessons.info/2013/03/20/10-very-useful-php-functionscodes-for-php-developers-part-i/
     * alpha:  A string with lower and uppercase letters only.
      alnum:  Alpha-numeric string with lower and uppercase characters.
      numeric:  Numeric string.
      nozero:  Numeric string with no zeros.
      unique:  Encrypted with MD5 and uniqid(). Note: The length parameter is not available for this type.
     * Returns a fixed length 32 character string.
     * @param type $type
     * @param type $len
     * @return type
     */
    function generate_string($type = 'alnum', $len = 6) {
        switch ($type) {
            case 'basic' : return mt_rand();
                break;
            case 'alnum' :
            case 'numeric' :
            case 'nozero' :
            case 'alpha' :

                switch ($type) {
                    case 'alpha' : $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum' : $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric' : $pool = '0123456789';
                        break;
                    case 'nozero' : $pool = '123456789';
                        break;
                }

                $str = '';
                for ($i = 0; $i < $len; $i++) {
                    $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
                }
                return $str;
                break;
            case 'unique' :
            case 'md5' :

                return md5(uniqid(mt_rand()));
                break;
        }
    }

    /**
     * https://davidwalsh.name/php-email-encode-prevent-spam
     * @param type $e
     * @return string
     */
    function encode_email($e) {
        $output = "";
        for ($i = 0; $i < strlen($e); $i++) {
            $output .= '&#' . ord($e[$i]) . ';';
        }
        return $output;
    }

    public function word_limiter($str, $limit = 100, $end_char = '&#8230;') {
        if (trim($str) == '') {
            return $str;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);

        if (strlen($str) == strlen($matches[0])) {
            $end_char = '';
        }

        return rtrim($matches[0]) . $end_char;
    }

    public function strtoupper_utf8($str) {
        $str = str_replace(array('i', 'ı', ' ü', 'ğ', 'ş', 'ö', 'ç'), array('İ', 'I', 'Ü', 'Ğ', 'Ş', 'Ö', 'Ç'), $str);
        return strtoupper($str);
    }

    function check_alpha_numeric($str) {
        return (!preg_match("/^([a-z0-9])+$/i", $str)) ? FALSE : TRUE;
    }

    function cleanInput($input) {

        $search = array(
            '@<script[^>]*?>.*?</script>@si', // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        );

        $output = preg_replace($search, '', $input);
        return $output;
    }

    function ordinal($cdnl) {
        $test_c = abs($cdnl) % 10;
        $ext = ((abs($cdnl) % 100 < 21 && abs($cdnl) % 100 > 4) ? 'th' : (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
        return $cdnl . $ext;
    }

    function sanitize($input) {
        if (is_array($input)) {
            foreach ($input as $var => $val) {
                $output[$var] = sanitize($val);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $input = $this->cleanInput($input);
            $output = htmlentities($input, ENT_QUOTES, 'UTF-8');
        }
        return $output;
    }

    function clean($input) {

        if (is_array($input)) {

            foreach ($input as $key => $val) {

                $output[$key] = clean($val);

                // $output[$key] = $this->clean($val);
            }
        } else {

            $output = (string) $input;

            // if magic quotes is on then use strip slashes

            if (get_magic_quotes_gpc()) {

                $output = stripslashes($output);
            }

            // $output = strip_tags($output);

            $output = htmlentities($output, ENT_QUOTES, 'UTF-8');
        }

// return the clean text

        return $output;
    }

}

?>
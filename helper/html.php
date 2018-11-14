<?php

class html
{
    public function draw_table($rows, $cols)
    {
        echo "<table border='1'>";

        for ($tr = 1; $tr <= $rows; $tr++) {
            echo '<tr>';
            for ($td = 1; $td <= $cols; $td++) {
                echo "<td align='center'>".$tr * $td.'</td>';
            }
            echo '</tr>';
        }

        echo '</table>';
    }

    /**
     * http://webdeveloperplus.com/php/21-really-useful-handy-php-code-snippets/.
     *
     * @email - Email address to show gravatar for
     * @size - size of gravatar = 150
     * @default - URL of default gravatar to use
     */
    public function show_gravatar($email, $size = 150, $default = '')
    {
        $grav_url = 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).'?d='.urlencode($default).'&s='.$size;

        return '<img src='.$grav_url.' alt="" />';
    }

    /**
     * http://webdeveloperplus.com/php/21-really-useful-handy-php-code-snippets/.
     *
     * @param type $data
     * @param type $minFontSize
     * @param type $maxFontSize
     *
     * @return type
     */
    public function tag_cloud($data = [], $minFontSize = 12, $maxFontSize = 30)
    {
        $minimumCount = min($data);
        $maximumCount = max($data);
        $spread = $maximumCount - $minimumCount;
        $cloudHTML = '';
        $cloudTags = [];

        $spread == 0 && $spread = 1;

        foreach ($data as $tag => $count) {
            $size = $minFontSize + ($count - $minimumCount) * ($maxFontSize - $minFontSize) / $spread;
            $cloudTags[] = '<a style="font-size: '.floor($size).'px'
                    .'" class="tag_cloud" href="javascript:void(0)">'
                    .htmlspecialchars(stripslashes($tag)).'</a>';
        }

        return implode("\n", $cloudTags)."\n";
    }

    /**
     * ref : http://djave.co.uk/php-js-email-protector/
     * converto email to js realtime so that protect you spam.
     *
     * @param type $phpemail
     */
    public function protect_email($phpemail)
    {
        $pieces = explode('@', $phpemail);
        echo '
			<script type="text/javascript">
				var a = "<a href=\'mailto:";
				var b = "'.$pieces[0].'";
				var c = "'.$pieces[1].'";
				var d = "\' class=\'email\'>";
				var e = "</a>";
				document.write(a+b+"@"+c+d+b+"@"+c+e);
			</script>
			<noscript>Please enable JavaScript to view emails</noscript>
		';
    }

    /**
     * @param type $year
     *
     * @return type
     */
    public function auto_copyright($year = 'auto')
    {
        if (intval($year) == 'auto') {
            $year = date('Y');
        }

        if (intval($year) == date('Y')) {
            return intval($year);
        }

        if (intval($year) < date('Y')) {
            return intval($year).' - '.date('Y');
        }
        if (intval($year) > date('Y')) {
            return date('Y');
        }
    }

    /**
     * https://css-tricks.com/snippets/php/pagination-function/
     * pagination(
     * total amount of item/rows/whatever,
     * limit of items per page,
     * current page number,
     * url -> use %d to numbered
     * );.
     *
     * @param type $item_count
     * @param type $limit
     * @param type $cur_page
     * @param type $link
     *
     * @return type
     */
    public function pagination($item_count, $limit, $cur_page, $link)
    {
        $page_count = ceil($item_count / $limit);
        $current_range = [($cur_page - 2 < 1 ? 1 : $cur_page - 2), ($cur_page + 2 > $page_count ? $page_count : $cur_page + 2)];

        // First and Last pages
        $first_page = $cur_page > 3 ? '<a href="'.sprintf($link, '1').'">1</a>'.($cur_page < 5 ? ', ' : ' ... ') : null;
        $last_page = $cur_page < $page_count - 2 ? ($cur_page > $page_count - 4 ? ', ' : ' ... ').'<a href="'.sprintf($link, $page_count).'">'.$page_count.'</a>' : null;

        // Previous and next page
        $previous_page = $cur_page > 1 ? '<a href="'.sprintf($link, ($cur_page - 1)).'">Previous</a> | ' : null;
        $next_page = $cur_page < $page_count ? ' | <a href="'.sprintf($link, ($cur_page + 1)).'">Next</a>' : null;

        // Display pages that are in range
        for ($x = $current_range[0]; $x <= $current_range[1]; $x++) {
            $pages[] = '<a href="'.sprintf($link, $x).'">'.($x == $cur_page ? '<strong>'.$x.'</strong>' : $x).'</a>';
        }

        if ($page_count > 1) {
            return '<p class="pagination"><strong>Pages:</strong> '.$previous_page.$first_page.implode(', ', $pages).$last_page.$next_page.'</p>';
        }
    }

    /**
     * @param type $string -> text
     *
     * @return type -> url string
     */
    public function to_link($string)
    {
        return preg_replace("~(http|https|ftp|ftps)://(.*?)(\s|\n|[,.?!](\s|\n)|$)~", '<a href="$1://$2">$1://$2</a>$3', $string);
    }

    public function breadcrumb($data = [])
    {
        $css = '<style type="text/css">.breadcrumb { background-color: #FEFEFE; list-style: none; margin: 0 0 20px; padding: 8px 15px; font-size: 12px; } 
    .breadcrumb > li { display: inline-block; *display: inline; *zoom: 1; } 
    .breadcrumb > li:after {  color: #DDDDDD; padding: 0 5px 0 10px; } 
    .breadcrumb > li:last-child:after { content: none; } 
    .breadcrumb > li > a:link, 
    .breadcrumb > li > a:visited { text-decoration: none; } 
    .breadcrumb > li > a:hover, 
    .breadcrumb > li > a:active, 
    .breadcrumb > li > a:focus { text-decoration: underline; } 
    .breadcrumb > .active > span, 
    .breadcrumb > .active > a { color: #999999; text-decoration: none; cursor: default; } </style>';
        $breadcrumb = '<ul class="breadcrumb">'."\n";
        if (isset($data)) {
            foreach ($data as $link) {
                if ((isset($link[2])) and ($link[2] === true)) {
                    $breadcrumb .= '<li class="active"><span>'.$link[1].'</span></li>'."\n";
                } else {
                    $breadcrumb .= '<li><a href="'.$link[0].'">'.$link[1].'</a></li>&nbsp;&#8250;'."\n";
                }
            }
        }
        $breadcrumb .= '</ul>'."\n";

        return $css."\n".$breadcrumb;
    }
}

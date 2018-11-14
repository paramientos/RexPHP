<?php

class image
{
    public function save_remote_image($remote, $local)
    {
        copy($remote, $local);
    }

    /**
     * @param type $url
     *
     * @return type
     */
    public function uri($url)
    {
        $type = pathinfo($url, PATHINFO_EXTENSION);
        $data = file_get_contents($url);

        return 'data:image/'.$type.';base64,'.base64_encode($data);
    }

    /**
     * @param type $file
     * @param type $save_to -> must end / (slashes) if the resized image will be putting to a dir
     * @param type $w
     * @param type $h
     * @param type $crop
     */
    public function resize($file, $save_to = '', $w = 150, $h = 150, $crop = false)
    {
        if (!file_exists($save_to) && $save_to != '') {
            mkdir($save_to);
        }
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width - ($width * abs($r - $w / $h)));
            } else {
                $height = ceil($height - ($height * abs($r - $w / $h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w / $h > $r) {
                $newwidth = $h * $r;
                $newheight = $h;
            } else {
                $newheight = $w / $r;
                $newwidth = $w;
            }
        }
        $p = pathinfo($file);

        if ($p['extension'] == 'jpg' || $p['extension'] == 'jpeg') {
            $src = imagecreatefromjpeg($file);
        } elseif ($p['extension'] == 'png') {
            $src = imagecreatefrompng($file);
        } elseif ($p['extension'] == 'gif') {
            $src = imagecreatefromgif($file);
        }
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        if ($p['extension'] == 'jpg' || $p['extension'] == 'jpeg') {
            imagejpeg($dst, substr($file, 0, strlen($p['extension'])).'_resized.'.$p['extension']);
        } elseif ($p['extension'] == 'png') {
            imagepng($dst, $save_to.substr($file, 0, strlen($p['extension'])).'_resized.'.$p['extension']);
        } elseif ($p['extension'] == 'gif') {
            imagegif($dst, substr($file, 0, strlen($p['extension'])).'_resized.'.$p['extension']);
        }
    }
}

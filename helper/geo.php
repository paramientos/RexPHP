
<?php

class geo {

    function get_coordinate($address) {
        if (!is_string($address))
            die("All Addresses must be passed as a string");
        $_url = "http://maps.google.com/maps/api/geocode/json?address=" . rawurlencode($address);
        $_result = false;

        if ($_result = file_get_contents($_url)) {
            if (strpos($_result, 'errortips') > 1 || strpos($_result, 'Did you mean:') !== false)
                return false;
        }
        $res = (object) json_decode($_result, true);
        return $res->results[0]['geometry']['location'];
    }

    function get_formatted_address($address) {
        if (!is_string($address))
            die("All Addresses must be passed as a string");
        $_url = "http://maps.google.com/maps/api/geocode/json?address=" . rawurlencode($address);
        $_result = false;

        if ($_result = file_get_contents($_url)) {
            if (strpos($_result, 'errortips') > 1 || strpos($_result, 'Did you mean:') !== false)
                return false;
        }
        $res = (object) json_decode($_result, true);
        return $res->results[0]['formatted_address'];
    }

    function location($ip) {
        $result = false;
        $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if ($ip_data && $ip_data->geoplugin_countryName != null) {
            $result['country'] = $ip_data->geoplugin_countryCode;
            $result['city'] = $ip_data->geoplugin_city;
        }
        return $result;
    }

    function get_distance($latitude1, $longitude1, $latitude2, $longitude2) {
        $theta = $longitude1 - $longitude2;
        $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('miles', 'feet', 'yards', 'kilometers', 'meters');
    }

}

?>
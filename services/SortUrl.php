<?php

class SortUrl 
{
    public static function generateSortUrl($sortCriteria, $order) {
        $url = $_SERVER['PHP_SELF'] . '?';
        foreach ($_GET as $key => $value) {
            if ($key !== 'sort' && $key !== 'order') {
                $url .= $key . '=' . $value . '&';
            }
        }
        $url .= 'sort=' . $sortCriteria . '&order=' . $order;
        return $url;
    }
}



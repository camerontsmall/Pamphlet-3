<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class youtube extends mediaPlayer{
    
    public $name = 'youtube';
    public $title = "YouTube";
    
    public static function build($video,$setup){
        $id = $video['sources'][0]['url'];
        $video['source'] = "<iframe frameborder=\"0\" class=\"vidplayer\" width=\"100%\" height=\"100%\" allowfullscreen src=\"https://www.youtube.com/embed/$id?autoplay=1\"></iframe>";
        $video['poster'] = "http://img.youtube.com/vi/$id/maxresdefault.jpg";
        return $video;
    }
    
}

class iframe extends mediaPlayer{
    
    public $name = 'iframe';
    public $title = 'IFrame Embed';
    
    public static function build($video,$setup){
        $url = $video['sources'][0]['url'];
        $video['source'] = "<iframe frameborder=\"0\" class=\"vidplayer\" width=\"100%\" height=\"100%\" allowfullscreen src=\"$url\"></iframe>";
    }
}

class html5 extends mediaPlayer{
    public $name = 'html5';
    public $title = 'HTML5';
    
    public static function build($video,$setup){
        $poster = $video['poster'];
        $code = "<video class=\"vidplayer video-js vjs-default-skin html5vid\" width=\"100%\" height=\"100%\" poster=\"$poster\" controls autoplay data-setup='{\"techOrder\": [\"html5\",\"flash\"]}'>";
        foreach($video['sources'] as $source){
            $url = $source['url'];
            $type = $source['type'];
            $res = $source['res'];
            $code .= "<source src=\"$source\" type=\"$type\">" . PHP_EOL;
        }
        $code .= "Your browser does not support the video tag" . "</video>";
        $video['source'] = $code;
        return $video;
    }
    
}

class custom extends mediaPlayer{
    public $name = 'custom';
    public $title = 'Custom code';
    
    public static function build($video,$setup){
        $video['source'] = $video['code'];
        return $video;
    }
    
    
}



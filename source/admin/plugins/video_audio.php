<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Audio player tech based on SoundCloud javascript-based player
 *
 * @author Cameron
 */
class video_audio extends mediaPlayer{
    //put your code here
    public $name = 'audio';
    public $title = 'HTML5 Audio';
    
    public $live = false;
    public $ondemand = true;
    
    public $supported = array('audio/mp3', 'audio/ogg', 'audio/wave', 'audio/wav', 'audio/mpeg', 'audio/aac', 'audio/x-aac');
    
    public static function build($content, $options = ''){
        
        $primarySource = $content->sources[0];
        $url = $primarySource->src;

        $poster = $content->poster;
        
        $config = json_decode($content->source,1);
        
        require_once('plugins/radio/player.php');

        //$title = radioPlayer::getNowPlaying($nowplaying, $content->title);
        $content->plaintitle = $content->title;
        //$content->poster = null;
        
        ob_start();
        
        html::css('plugins/audio/materialize.min.css');
        html::css('https://fonts.googleapis.com/css?family=Roboto');
        html::css('https://fonts.googleapis.com/icon?family=Material+Icons');
        html::css('plugins/audio/wavesurfer_audio.css');
        html::jquery();
        html::js("//cdnjs.cloudflare.com/ajax/libs/wavesurfer.js/1.0.52/wavesurfer.min.js");	//WaveSurfer plugin
        html::js('plugins/audio/materialize.js');
?>
<div id='player_container'>
    <div id='bottom_bar'> 
        <div id="thumbnail_image">
            <img src="<?php echo $content->poster; ?>">
        </div>
        <div id='button_container'>
            <a class="btn-floating btn-large waves-effect waves-light light-blue left" onclick='wavesurfer.playPause()' href='javascript:void(0);'><i class="material-icons" id='play_btn'>play_arrow</i></a>
        </div>
        <div id='waveform'>
        </div>
    </div>
</div>
<script>
var wavesurfer = WaveSurfer.create({
   container: '#waveform',
   waveColor: 'black',
   progressColor: '#2486c7',
   cursorColor: '#333',
   cursorWidth: 2,
   barWidth: 1,
   backend: 'MediaElement',
   normalize: true
});

wavesurfer.on('ready', function(){
    console.log('New WaveSurfer instance is ready');
    wavesurfer.cursorColor = 'transparent';
});

wavesurfer.on('play', function(){
   $('#play_btn').text('pause'); 
});

wavesurfer.on('pause', function(){
    $('#play_btn').text('play_arrow'); 
});

wavesurfer.on('error', function(){
    console.log('WaveSurfer encountered an error');
    wavesurfer.params.cursorColor = '#333';
});

wavesurfer.enableDragSelection;

<?php

if(isset($config['waveform'])){
    $wavedata = json_encode($config['waveform']);
    echo "wavesurfer.load('$url',$wavedata);";
    echo "console.log('Custom waveform detected');";
}else{
    echo "wavesurfer.load('$url');";
}

?>

</script>
<style type="text/css">
    body{
<?php
if(strlen($content->poster) > 0){
    echo "background-image:url('$content->poster');";
}
?>
    }
</style>    
<?php
        
        //echo '<script>videojs(\'#video\').videoJsResolutionSwitcher</script>';

        if($content->channelID){
            $json = actualLink() . "/public.php?action=plugin_videomanager&id=$content->channelID";
        }else{
            $json = actualLink() . "/public.php?action=plugin_vod&id=$content->id";
        }

        echo  "<script> var json_url = '$json'; </script>", PHP_EOL;

        $content->source =  ob_get_contents();
        ob_end_clean();
        
        return $content;
    }
}

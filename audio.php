<?php
class Audio extends cli_abstract{
    var $cacheKey = 'local.bar.audio';
    var $cacheTime = '5';

    public function result() {
        $result = exec('amixer -c 0 sget Master | grep -o "Mono.*Playback.*" | grep -o "[0-9]*%"');
        $result = '♪' . $result;
        return $result;
    }
}

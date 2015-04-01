<?php
class Yanews extends Cli_Abstract{
    var $source = 'http://news.yandex.ru/index.rss';
    var $pattern = '#<item>\\s+<title>(?:<!\[CDATA\[|)(.*?)(?:|\]\]>)<\/title#usi';
    var $newsKey = 'local.bar.yanews.main';
    var $newsTime = 600;
    var $letterCount = '0';
    var $letterLimit = '30';
    var $currentItem = 0;
    var $currentTick = 0;
    var $needUpdate = true;
    var $cacheTime = 10;
    var $cacheKey = 'local.bar.yanews.current';
    var $feeds = [
        //'index',
        ['sibfm', 'http://feeds.feedburner.com/sibfm/all'],
        ['rg', 'http://www.rg.ru/tema/gos/pravo/zakon/rss.xml'],
        ['ya', 'science'],
        ['lenta', 'http://lenta.ru/rss'],
        ['ya', 'world'],
        ['habrahabr', 'http://habrahabr.ru/rss/feed/posts/all/e4d4082cf0a120df59cf44bdb4d7ab92/'],
        ['ya', 'computers'],
        ['ngs', 'http://news.ngs.ru/rss/articles/'],
        ['ya', 'business'],
        ['ngs', 'http://news.ngs.ru/rss/'],
        ['ya', 'internet'],
        ['megamozg', 'http://megamozg.ru/rss/']
    ];
    var $dataCache;

    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    protected function _getSource($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.99 Safari/537.36");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $res = curl_exec($ch);

        return $res;
    }

    protected function _parseSource($feed)
    {
        $url = '';
        $source = '';

        if (is_array($feed) && count($feed) == 2) {
            $source = reset($feed);
            if ($source == 'ya') {
                $url = 'http://news.yandex.ru/'.next($feed).'.rss';
                $source .= '.' . current($feed);
            } else {
                $url = next($feed);
            }
        } elseif (is_string($feed)) {
            $url = $feed;
        }

        $res = $this->_getSource($url);

        preg_match_all($this->pattern, $res, $m);
        foreach ($m[1] as &$item) {
            if(!empty($source)) {
                $item .= ' [' . $source . ']';
            }
        }
        return $m[1];
    }

    public function retrieveData()
    {
        $this->needUpdate = true;
        $result = [];
        foreach ($this->feeds as $feed) {
            $result = array_merge($result, $this->_parseSource($feed));
        }

        shuffle($result);
        return $result;
    }

    protected function _getData()
    {
        return $this->cache->get($this->newsKey, array($this, 'retrieveData'));
    }

    public function result() {
        $result = '';
        $data = $this->_getData();

        if (count($data) == 0) {
            return '';
        } elseif ($this->currentItem == count($data)) {
            $this->currentItem = 0;
        }

        return $data[$this->currentItem++];
        
        return $result;
    }
}

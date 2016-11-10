<?php
return [
    'interval' => 250,
    'items' => [
        [
            'enabled' => false,
            'class'=> 'Additional_Rss',
            'options' => [
                'feeds' => [
                    ['sibfm', 'http://feeds.feedburner.com/sibfm/all'],
                    ['rg', 'http://www.rg.ru/tema/gos/pravo/zakon/rss.xml'],
                    ['ya.science', 'http://news.yandex.ru/science.rss'],
                    ['lenta', 'http://lenta.ru/rss'],
                    ['ya.world', 'http://news.yandex.ru/world.rss'],
                    ['habrahabr', 'http://habrahabr.ru/rss/feed/posts/all/e4d4082cf0a120df59cf44bdb4d7ab92/'],
                    ['ya.computers', 'http://news.yandex.ru/computers.rss'],
                    ['ngs', 'http://news.ngs.ru/rss/articles/'],
                    ['ya.business', 'http://news.yandex.ru/business.rss'],
                    ['ngs', 'http://news.ngs.ru/rss/'],
                    ['ya.internet', 'http://news.yandex.ru/internet.rss'],
                    ['megamozg', 'http://megamozg.ru/rss/']
                ],
                'cache_time' => 10,
                'cache_time_news' => 600
            ]
        ],
        [
            'enabled' => false,
            'class'=> 'Audio_Mocp'
        ],
        [
            'enabled' => false,
            'class'=> 'Additional_Currency',
            'options' => [
                'cache_time' => 300,
                'history_path' => '/home/johny/currency.js'
            ]
        ],
        [
            'enabled' => false,
            'class'=> 'Conditions_Weather',
            'options' => [
                'cache_time' => 300,
                'city' => 'novosibirsk'
            ]
        ],
        [
            'enabled' => false,
            'class'=> 'Conditions_Co2',
            'options' => [
                'token' => '',
                'api' => '',
                'color' => '#66ff66',
                'channel' => '#office',
                'username' => 'CO2 6 этаж',
                'cache_time' => 15,
                'message_cache_time' => 900
            ]
        ],
        [
            'enabled' => false,
            'class'=> 'Work_Jira',
            'options' => [
                'host' => '',
                'user' => '',
                'password' => ''
            ]
        ],
        [
            'enabled' => false,
            'class'=> 'Audio_Volume'
        ],
        [
            'enabled' => true,
            'class'=> 'Common_CurrentTime'
        ],
        [
            'enabled' => false,
            'class'=> 'Common_Text',
            'options' => [
                'text' => 'hello world'
            ]
        ],
        [
            'enabled' => false,
            'class'=> 'Common_Command',
            'options' => [
                'command' => 'ip a | grep inet | grep "global eth0" | sed -rn "s/.* ([0-9\.]{9,15})\/.*/\1/p"'
            ]
        ]
    ]
];

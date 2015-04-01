<?php
include "cache.php";
include "cli_abstract.php";
include "weather.php";
include "cli.php";
include "imap.php";
include "kanban.php";
include "currency.php";
include "currenttime.php";
include 'audio.php';
include 'mocp.php';
include 'yanews.php';

$cli = new Cli();
$cache = new Cache();
$cli->setCache($cache);
$news = new Yanews();
$news->setCache($cache);
$cli->add($news);
$cli->add(new Mocp($cache));
$cli->add(new Currency($cache));
$cli->add(new Weather($cache));
$cli->add(new Imap($cache));
$cli->add(new Kanban($cache));
$cli->add(new audio($cache));
$cli->add(new CurrentTime($cache));

echo '{"version":1}'."\n[\n[]\n";
while (true)
{
    $result = $cli->result();
    echo ',[' . json_encode($result) . "]\n";
    usleep(500000);
}

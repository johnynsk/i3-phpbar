<?php
class Kanban extends Cli_Abstract{
    var $cacheKey = "local.bar.kanban";
    var $cacheTime = 60;

    function result() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://jira.rn/rest/api/2/search?jql=assignee%20%3D%20e.vasilev%20AND%20status%20%3D%203');
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, '$USER:$PASSWD');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch));
        $this->color = "";
        if (!$res) {
            return "Нет данных";
        } elseif (!$res->total) {
            return "Нет задач";
        } elseif ($res->total > 1) {
            return "Задач: ".$res->total;
        } else {
            $issue = reset($res->issues);
            return $issue->key;
        }
    }
}

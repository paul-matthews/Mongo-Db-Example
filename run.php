#!/usr/bin/env php
<?php
require_once(dirname(__FILE__) . '/Link.php');
require_once(dirname(__FILE__) . '/LinkPeer.php');
define('DB_NAME', 'links');

$m = new Mongo();
$link_peer = new LinkPeer($m->selectDb(DB_NAME));

if(false) {
    $lnk = new Link();
    $lnk->setUrl('http://www.google.com');
    $lnk->addTag('google');
    $lnk->addTag('com');
    $link_peer->insert($lnk);
}


foreach($link_peer->fetchByTag('google') as $link) {
    echo "$link\n";
}

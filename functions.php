<?php
require_once(dirname(__FILE__) . '/Link.php');
require_once(dirname(__FILE__) . '/LinkPeer.php');

function processNew() {
    $peer_class = getDefaultConnectionPeer();
    foreach(array('url', 'tags') as $var) {
        if(!$_POST[$var] || !is_string($_POST[$var]))
            return false;
    }

    $url = $_POST['url'];
    $tags = explode(', ', $_POST ['tags']);

    $link = new Link();
    $link->setUrl($url);
    foreach($tags as $tag) {
        $link->addTag($tag);
    }

    $peer_class->insert($link);
    return true;
}

function getAllAsArray() {
    $link_peer = getDefaultConnectionPeer();

    $return = array();
    foreach($link_peer->fetchAll() as $link) {
        $return[] = array(
            'url' => $link->getUrl(),
            'tags' => $link->getTags(),
        );
    }
    return $return;
}

function getDefaultConnectionPeer() {
    define('DB_NAME', 'links');

    $m = new Mongo();
    return new LinkPeer($m->selectDb(DB_NAME));
}


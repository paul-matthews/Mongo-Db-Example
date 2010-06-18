<?php
require_once(dirname(__FILE__) . '/LinkPeer.php');

class Link {
    private $url;
    private $tags = array();

    public function setUrl($url) {
        $this->url = $url;
    }

    public function addTag($tag) {
        $this->tags[] = $tag;
    }

    public function removeTag($tag) {
        foreach($this->tags as $k => $v) {
            if($v == $tag) {
                unset($this->tags[$k]);
                return true;
            }
        }
        return false;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getTags() {
        return $this->tags;
    }

    public function __toString() {
        return "{$this->url} [". implode(', ', $this->tags) ."]";
    }

    public function toArray() {
        return array(
            '_id' => $this->url,
            'tags' => $this->tags,
        );
    }

    public function fromArray($link) {
        foreach(array('_id') as $req) {
            if(!isset($link[$req]))
                throw new Exception('Incorrect data supplied');
        }

        $this->url = $link['_id'];
        if(isset($link['tags']) && is_array($link['tags'])) {
            $this->tags = $link['tags'];
        }
    }
}

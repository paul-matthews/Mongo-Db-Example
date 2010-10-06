<?php

class Link {
    private $url;
    private $tags = array();

    public function __construct()
    {
        // Not required to do anything at this time.
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setUrl($url)
    {
        $this->url = (string) $url;
    }

    public function addTag($tag)
    {
        $this->tags[] = (string) $tag;
    }

    public function removeTag($tag)
    {
        $success = false;
        // look through the current tags for the specified tag
        foreach ($this->tags as $key => $tagName) {
            if ($tagName == $tag) {
                // remove the tag from the list
                unset($this->tags[$key]);
                $success = true;
            }
        }
        return $success;
    }

    public function __toString()
    {
        // example: http://www.google.com/ [usa, search]
        return sprintf('%s [%s]', $this->url, implode(', ', $this->tags));
    }
}

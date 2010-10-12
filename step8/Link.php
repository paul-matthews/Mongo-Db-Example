<?php
require_once(dirname(__FILE__) . '/LinkPeer.php');

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

    public function toArray()
    {
        return array(
            // _id is the name of the url in this case as it makes it easier
              // for interoperability with the Database
            'url' => $this->url,
            'tags' => $this->tags,
        );
    }

    public function fromArray($link)
    {
        // Ensure the url is set
        if (empty($link['url']) || !is_string($link['url'])) {
            throw new Exception('Incorrect data supplied');
        }

        $this->url = $link['url'];

        // Only set tags if they conform to our structure
        $tags = array();
        if (!empty($link['tags']) && is_array($link['tags'])) {
            foreach ($link['tags'] as $tag) {
                if (is_string($tag)) {
                    $tags[] = $tag;
                }
            }
        }
        $this->tags = $tags;

        return $this;
    }
}

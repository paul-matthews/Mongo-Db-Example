<?php
require_once(dirname(__FILE__) . '/LinkPeer.php');

/**
 * Link the class that defines a link
 *
 * @author Paul Matthews <pmatthews@ibuildings.com>
 */
class Link {
    /**
     * url of the link
     *
     * @var string
     * @access private
     */
    private $url;
    /**
     * tags describing the link
     *
     * @var array
     * @access private
     */
    private $tags = array();

    /**
     * A standard constructor that does nothing
     *
     * @return mixed
     */
    public function __construct()
    {
        // Not required to do anything at this time.
    }

    /**
     * getUrl - retrieve the url of the link
     *
     * @access public
     * @return string the url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * getTags the array of tags describing the link
     *
     * @access public
     * @return array of tags
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * setUrl to set the url of the link
     *
     * @param string $url
     * @access public
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = (string) $url;
    }

    /**
     * addTag - add a tag to describe the link
     *
     * @param string $tag
     * @access public
     * @return void
     */
    public function addTag($tag)
    {
        $this->tags[] = (string) $tag;
    }

    /**
     * removeTag - remove every instance of a tag from the tags list
     *
     * @param mixed $tag
     * @access public
     * @return boolean true if the tag was found and removed false if
     *                 the tag didn't exist
     */
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

    /**
     * __toString to make a sensible string out of the link object
     *
     * @access public
     * @return string the string description of the link
     */
    public function __toString()
    {
        // example: http://www.google.com/ [usa, search]
        return sprintf('%s [%s]', $this->url, implode(', ', $this->tags));
    }

    /**
     * toArray convert a link object to an array representation
     *
     * @access public
     * @return array the link representation
     */
    public function toArray()
    {
        return array(
            // _id is the name of the url in this case as it makes it easier
              // for interoperability with the Database
            'url' => $this->url,
            'tags' => $this->tags,
        );
    }

    /**
     * fromArray read the properties of the link from an array
     *
     * Caution: overwrites existing data
     *
     * @param array $link the properties as produced from the toArray() method
     * @access public
     * @throws Exception
     * @return this object
     */
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

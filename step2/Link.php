<?php

...

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

...

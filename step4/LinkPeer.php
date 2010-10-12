<?php

...
    private function getCollection()
    {
        // if the collection is not already cached
        if (empty($this->collection)) {

            // selects the collection if it exists
            $this->collection = $this->db->selectCollection(self::COLLECTION);

            // or creates a new one if it doesn't
            if (empty($this->collection)) {
                $this->collection = $this->db->createCollection(
                    self::COLLECTION
                );
            }
        }

        return $this->collection;
    }
...

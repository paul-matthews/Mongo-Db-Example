<?php

    ...

    public function delete(Link $link)
    {
        $link = $this->translateDataset($link->toArray(), true);

        $linkKey = $this->getIdFromDataset($link);
        $key = array(
            '_id' => $linkKey,
        );

        if ($linkKey) {
            $options = array(
                'fsync' => true, // Forces the update to be synced to disk
            );
            try {
                if ($this->getCollection()->remove($key, $options)) {

                    return true;
                }
            } catch (MongoException $e) {
                // @TODO log the error
            }
        }
        return false;
    }

    ...

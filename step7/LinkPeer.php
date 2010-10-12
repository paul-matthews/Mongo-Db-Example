<?php

    ...

    public function fetchAll()
    {
        $results = array();

        // perform a find with a blank query
        foreach ($this->getCollection()->find() as $result) {
            // Create a Link out of each of the results
            $results[] = $this->factory($result);
        }

        return $results;
    }

    public function fetchByUrl($url)
    {
        $query = array('url' => $url);
        $mongoQuery = $this->translateDataset($query);
        $response = $this->getCollection()->findOne(
            $mongoQuery
        );

        // if the database returns a result return the link
        if ($response) {
            return $this->factory($response);
        }

        // Otherwise return null
        return null;
    }

    private function factory(array $linkArray)
    {
        if (!isset($linkArray['_id'])) {
            throw new Exception('Missing data');
        }

        $linkArray = $this->translateDataset($linkArray, false);

        $tmp = new Link();
        return $tmp->fromArray($linkArray);
    }

    ...

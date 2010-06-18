<?php
require_once(dirname(__FILE__) . '/Link.php');

class LinkPeer {
    const COLLECTION = 'link';
    private $db;
    private $collection;
    private $instances = array();

    public function __construct(MongoDb $db) {
        $this->db = $db;
    }

    public function insert(Link $link)
    {
        if($this->getCollection()->save($link->toArray())) {
            return true;
        }

        return false;
    }

    public function fetchAll() {
        $results = array();

        foreach($this->getCollection()->find() as $result) {
            $results[] = $this->factory($result);
        }

        return $results;
    }

    public function fetchByUrl($url) {
        $response = $this->getCollection()->findOne(array('_id' => $url));
        if($response)
            $this->factory($response);

        return null;
    }

    public function fetchByTag($tag) {
        $results = array();
        foreach($this->getCollection()->find(array('tags' => $tag)) as $result) {
            $results[] = $this->factory($result);
        }
        return $results;
    }

    private function getCollection() {
        if(empty($this->collection)) {
            foreach($this->db->listCollections() as $col) {
                if($col->getName() == self::COLLECTION) {
                    $this->collection = $col;
                    break;
                }
            }
            $this->collection = $this->db->createCollection(self::COLLECTION);
        }

        return $this->collection;
    }

    private function factory($linkArray) {
        if(!isset($linkArray['_id']))
            throw new Exception('Missing data');

        $id = $linkArray['_id'];

        if(!isset($this->instances[$id])) {
            $tmp = new Link();
            $tmp->fromArray($linkArray);
            $this->instances[$id] = $tmp;
        }
        return $this->instances[$id];
    }
}

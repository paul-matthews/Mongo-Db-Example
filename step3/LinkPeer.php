<?php

class LinkPeer
{
    const COLLECTION = 'link';
    const ID_FIELD = 'url';
    const DB_ID_FIELD = '_id';

    private $db;
    private $collection;

    public function __construct(MongoDb $db)
    {
        $this->db = $db;
    }

    ...

}

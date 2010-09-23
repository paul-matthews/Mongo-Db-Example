<?php
require_once(dirname(__FILE__) . '/Link.php');

/**
 * LinkPeer - interacts with the persistence layer to offer common
 *            methods such as retrieval and storing
 *
 * @author Paul Matthews <pmatthews@ibuildings.com>
 */
class LinkPeer {
    /**
     *  The collection to be used within the database
     */
    const COLLECTION = 'link';
    /**
     * db the database to interact with
     *   MongoDb in this example
     *
     * @var MongoDb
     * @access private
     */
    private $db;
    /**
     * collection a handle to the the current database collection
     *
     * @var MongoCollection
     * @access private
     */
    private $collection;

    /**
     * __construct requires an instance of the database to interact with
     *
     * @param MongoDb $db
     * @access public
     * @return void
     */
    public function __construct(MongoDb $db) {
        $this->db = $db;
    }

    /**
     * insert a link or update it
     *
     * @param Link $link to be inserted into the database
     * @access public
     * @return boolean true if the insertion succeeded
     */
    public function insert(Link $link)
    {
        return $this->update($link);
    }

    /**
     * update a link or create it
     *
     * @param Link $link to be inserted into the database
     * @access public
     * @return boolean true if the insertion succeeded
     */
    public function update(Link $link)
    {
        if ($this->getCollection()->save($link->toArray())) {
            return true;
        }

        return false;
    }

    /**
     * delete a link
     *
     * @param Link $link the link to be removed
     * @access public
     * @return boolean true if the delete succeeded
     */
    public function delete(Link $link)
    {
        $link = $link->toArray();
        if (!empty($link['_id'])) {
            $linkKey = $linkKey['_id'];
            $options = array(
                'fsync' => true, // Forces the update to be synced to disk
            );
            try {
                if ($this->getCollection()->remove($linkKey, $options)) {
                    return true;
                }
            } catch (MongoException $e) {
                // @TODO log the error
            }
        }
        return false;
    }

    /**
     * fetchAll returns all the links in the current collection
     *
     * @access public
     * @return Array<Link> the array of links
     */
    public function fetchAll() {
        $results = array();

        // perform a find with a blank query
        foreach ($this->getCollection()->find() as $result) {
            // Create a Link out of each of the results
            $results[] = $this->factory($result);
        }

        return $results;
    }

    /**
     * fetchByUrl retrieves a link object by url
     *
     * @param string $url the url to fetch Links by
     * @access public
     * @return Link the corresponding Link object or null if none is found
     */
    public function fetchByUrl($url) {
        $response = $this->getCollection()->findOne(array('_id' => $url));

        // if the database returns a result return the link
        if ($response)
            return $this->factory($response);

        // Otherwise return null
        return null;
    }

    /**
     * fetchByTag retreves links by tag
     *
     * @param string $tag the tag to fetch Links by
     * @access public
     * @return Array<Link> the array of links
     */
    public function fetchByTag($tag) {
        $results = array();
        $collection = $this->getCollection();
        foreach ($collection->find(array('tags' => $tag)) as $result) {
            $results[] = $this->factory($result);
        }
        return $results;
    }

    /**
     * getCollection get a handle to the collection object
     *
     * @access private
     * @return MongoCollection the mongo collection class reffering to the
     *                         appropriate dataset
     */
    private function getCollection() {
        // if the collection is not already cached
        if (empty($this->collection)) {

            // selects the collection if it exists
            foreach ($this->db->listCollections() as $collection) {
                if ($collection->getName() == self::COLLECTION) {
                    $this->collection = $collection;
                    break;
                }
            }

            // or creates a new one if it doesn't
            if (empty($this->collection)) {
                $this->collection = $this->db->createCollection(
                    self::COLLECTION
                );
            }
        }

        return $this->collection;
    }

    /**
     * factory get a new instance of a Link from an array defining
     *   it's properties in the Link->toArray() format
     *
     * @param array $linkArray the array defining the Link object
     * @access private
     * @return Link the link object as populated by the linkArray
     */
    private function factory($linkArray) {
        if (!isset($linkArray['_id']))
            throw new Exception('Missing data');

        $id = $linkArray['_id'];

        $tmp = new Link();
        return $tmp->fromArray($linkArray);
    }
}

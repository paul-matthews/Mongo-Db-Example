<?php
require_once(dirname(__FILE__) . '/Link.php');

/**
 * LinkPeer - interacts with the persistence layer to offer common
 *            methods such as retrieval and storing
 *
 * @author Paul Matthews <pmatthews@ibuildings.com>
 */
class LinkPeer
{
    /**
     *  The collection to be used within the database
     */
    const COLLECTION = 'link';
    /**
     * The id filed in the dataset
     */
    const ID_FIELD = 'url';
    /**
     * The id filed for the database
     */
    const DB_ID_FIELD = '_id';

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
    public function __construct(MongoDb $db)
    {
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
        $data = $this->translateDataset($link->toArray(), true);

        if ($data && $this->getCollection()->save($data)) {
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

    /**
     * fetchAll returns all the links in the current collection
     *
     * @access public
     * @return Array<Link> the array of links
     */
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

    /**
     * fetchByUrl retrieves a link object by url
     *
     * @param string $url the url to fetch Links by
     * @access public
     * @return Link the corresponding Link object or null if none is found
     */
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

    /**
     * fetchByTag retreves links by tag
     *
     * @param string $tag the tag to fetch Links by
     * @access public
     * @return Array<Link> the array of links
     */
    public function fetchByTag($tag)
    {
        $results = array();
        $collection = $this->getCollection();
        foreach ($collection->find(array('tags' => $tag)) as $result) {
            $results[] = $this->factory($result);
        }
        return $results;
    }

    /**
     * Get the value from the dataset
     *
     * @param array $data
     * @access public
     * @return mixed the id string if its set or false if it isn't
     */
    public function getIdFromDataset(array $data)
    {
        if (!isset($data[self::DB_ID_FIELD])) {
            return false;
        }
        return $data[self::DB_ID_FIELD];
    }


    /**
     * Prepare the dataset for use with the database or the application.
     *
     * @param array $data the data for the non peer object
     * @param mixed $toDb true if to database, false to application
     * @access public
     * @return mixed the dataset or false if the key couldn't be found
     */
    public function translateDataset(array $data, $toDb = true)
    {
        $keyFrom = self::ID_FIELD;
        $keyTo = self::DB_ID_FIELD;
        if (!$toDb)  {
            $keyFrom = self::DB_ID_FIELD;
            $keyTo = self::ID_FIELD;
        }

        if (!isset($data[$keyFrom])) {
            return false;
        }

        $data[$keyTo] = $data[$keyFrom];
        unset($data[$keyFrom]);
        return $data;
    }

    /**
     * getCollection get a handle to the collection object
     *
     * @access private
     * @return MongoCollection the mongo collection class reffering to the
     *                         appropriate dataset
     */
    private function getCollection()
    {
        // if the collection is not already cached
        if (empty($this->collection)) {

            // selects the collection if it exists
            $this->collection = $this->db->selectCollection(self::COLLECTION);
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
    private function factory($linkArray)
    {
        if (!isset($linkArray['_id'])) {
            throw new Exception('Missing data');
        }

        $linkArray = $this->translateDataset($linkArray, false);

        $tmp = new Link();
        return $tmp->fromArray($linkArray);
    }
}

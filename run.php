#!/usr/bin/env php
<?php
require_once(dirname(__FILE__) . '/Link.php');
require_once(dirname(__FILE__) . '/LinkPeer.php');
define('DB_NAME', 'links');

$m = new Mongo();
$linkPeer = new LinkPeer($m->selectDb(DB_NAME));

// Ensure the db is empty
foreach ($linkPeer->fetchAll() as $link) {
    $linkPeer->delete($link);
}

// Print the contens of the database
echo "> initial database values:\n";
foreach ($linkPeer->fetchByTag('com') as $link) {
    echo "$link\n";
}
echo "\n----\n";

// Add some links

$lnk1 = new Link();
$lnk1->setUrl('http://www.google.com');
$lnk1->addTag('google');
$lnk1->addTag('com');

$lnk2 = new Link();
$lnk2->setUrl('http://www.mongodb.org');
$lnk2->addTag('mongodb');
$lnk2->addTag('org');

$lnk3 = new Link();
$lnk3->setUrl('http://www.ibuildings.com');
$lnk3->addTag('ibuildings');
$lnk3->addTag('com');

$lnk4 = new Link();
$lnk4->setUrl('http://techportal.ibuildings.com/');
$lnk4->addTag('techportal');
$lnk4->addTag('ibuildings');
$lnk4->addTag('com');

$linkPeer->insert($lnk1);
$linkPeer->insert($lnk2);
$linkPeer->insert($lnk3);
$linkPeer->insert($lnk4);

// Print the entire database

echo "> new entire database :\n";
foreach ($linkPeer->fetchAll() as $link) {
    echo "$link\n";
}
echo "\n----\n";


// Print the techportal link
echo "> retrieve the techportal link:\n";
echo $linkPeer->fetchByUrl('http://techportal.ibuildings.com/');
echo "\n----\n";

// Print all com tagged links
echo "> retrieve all 'com' tagged links:\n";
foreach ($linkPeer->fetchByTag('com') as $link) {
    echo "$link\n";
}
echo "----\n";

// Update the techportal link and print

$lnk4->addTag('mongo');
$linkPeer->update($lnk4);

// Print the techportal link
echo "> retrieve the updated techportal link:\n";
echo $linkPeer->fetchByUrl('http://techportal.ibuildings.com/');
echo "\n----\n";

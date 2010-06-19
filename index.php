<?php
require_once(dirname(__FILE__) . '/functions.php');

$response = processNew();
$results = getAllAsArray();

?>

<html>
<head>
</head>
<body>
    <h1>Links app</h1>
    <div>
<?php if(!$response): ?>
        <h2>Insert Link:</h2>
        <form method="post">
            <ul>
                <li><label>Url:<input type="text" name="url" value=""/></label></li>
                <li><label>Tags:<input type="text" name="tags" value=""/></label>(comma separated)</li>
                <li><input type="submit" value="Create New"></li>
            </ul>
        </form>
<?php else: ?>
        <h2>Link Inserted!</h2>
        <p>Your link has been inserted.</p>
<?php endif ?>
    </div>
    <div>
        <h2>Links list:</h2>
        <ul>
<?php foreach($results as $res): ?>
            <li><a href="<?php echo $res['url'];?>"><?php echo $res['url'];?></a> - tags: <?php echo implode(', ', $res['tags']);?></li>
<?php endforeach ?>
        </ul>
    </div>
</body>
</html>

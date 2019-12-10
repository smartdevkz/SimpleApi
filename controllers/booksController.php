<?php

require_once 'include/DbManager.php';

$app->get('/', function ($params, $obj) {
    return getAll("select * from books");
});

$app->post('/', function ($params, $obj) {
    $res = createObject("books", $obj);
    return $res;
});


$app->get('/:id', function ($params, $obj) {
    return getOne("select * from books where id=:id", $params, $obj);
});


$app->put('/:id', function ($params, $obj) {
    return updateObject("books", "id=:id", $params, $obj);
});


$app->delete('/:id', function ($params, $obj) {
    return deleteObject("books", "id=:id", $params, $obj);
});

Examples: 

Generating a controller from db table:
php cli make:controller UserController --table-name=users


Using get params: 
/api/?subjectId=15

$app->get('/', function ($params, $obj) {
    return getAll("select * from topics where subject_id=:subjectId",$params);
});
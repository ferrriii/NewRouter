<?PHP
require('../../newrouter.php');

$router = new NewRouter();
$router->route('', function(){
	echo "this will be executed on all requests to any path<br>";
	return true; // process next routes
});

$router->route('GET /', function(){
	echo "this will be executed on GET requests to the path /";
	return false; //this will stop matching routes. This is equivalent to no return
});

$router->route('POST /users', function(){
	echo "this will be executed on POST requests to the path /users";
});

$router->route('GET', function(){
	echo "this will be executed on GET requests to any path<br>";
	return true; // continue routing
});

$router->route('/api/*', function(){
	echo "this will be executed on all requests to the path /api/*. like /api/users /api/123 /api/user/999 /api/user/ etc.<br>";
	return true; // continue routing
});

$router->route('/api/+', function(){
	echo "this will be executed on all requests to the path /api/+. like /api/users /api/123 /api/user/999 but not /api/user/ etc.";
});

$router->route('GET /posts/:id:', function(){
	echo "this will be executed on GET requests to the path /posts/123 or /posts/1 or /posts/something but not /posts/something/another";
});

$router->dispatch();
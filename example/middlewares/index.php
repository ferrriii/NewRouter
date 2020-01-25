<?PHP
require('../../newrouter.php');

$router = new NewRouter();

$router->route(function(){
	echo "this is executed before all routes<br>";
	// below line is necessary to allow next routes to match
	return true;
});

$router->route('GET', function(){
	echo "this is executed before all GET requests<br>";
	// below line is necessary to allow next routes to match
	return true;
});


$router->route('POST', function(){
	echo "this is executed before all GET requests<br>";
	// below line will stop any further routes to proceed.
	return false;
});


$router->route('GET /a', function(){
	echo "this is route a";
});

$router->route('GET /b', function(){
	echo "this is route b";
});

$router->dispatch();
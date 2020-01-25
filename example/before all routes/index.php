<?PHP
require('../../newrouter.php');

$router = new NewRouter();

$router->route('', function(){
	echo "this is executed before all routes\n";
	// below line is necessary to allow next routes to match
	return true;
});


$router->route('GET /a', function(){
	echo "this is route a\n";
});

$router->route('GET /b', function(){
	echo "this is route b\n";
});

$router->dispatch();
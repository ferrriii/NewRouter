<?PHP
require('../../newrouter.php');

$router = new NewRouter();

// notice capturing $request by reference (the & before $request)
$router->route(function(&$request){
	// add any property you like
	$request->isUserAuthorized = 'yes';
	$request->someOtherVariable = 'test';
	// below line is necessary to allow next routes to match
	return true;
});


$router->route('GET /', function($request){
	echo "this is route a. is user authorized: " . $request->isUserAuthorized . ', test: ' . $request->someOtherVariable;
});

$router->dispatch();
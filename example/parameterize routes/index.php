<?PHP
require('../../newrouter.php');

$router = new NewRouter();
$router->route('GET /user/:name:', function($request){
	echo 'hello ' . $request->params['name'];
});

$router->dispatch();
<?PHP
require('../../newrouter.php');

$router = new NewRouter();
$router->route('GET /', function(){
	echo 'hello world';
});

$router->dispatch();
<?PHP
require('../../newrouter.php');
require('../../newrouterstatic.php');

$router = new NewRouter();
$router->route('/public/*', NewRouterStatic::serve('./asset/'));

$router->route('/public/*', function(){
	echo 'asset not found';
});

$router->route('/', function(){
	echo 'goto <a href=/public/home.html>home</a>';
});


$router->route(function(){
	echo 'route not found';
});


$router->dispatch();
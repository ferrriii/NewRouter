<?PHP
require_once('util.php');
require_once('../newrouter.php');

//test performance
$createRoute = function() {
	NewRouterRoute::fromRouteStr('GET /');
	NewRouterRoute::fromRouteStr('/');
	NewRouterRoute::fromRouteStr(null);
	NewRouterRoute::fromRouteStr('GET /a/b/c/d/e');
	NewRouterRoute::fromRouteStr('GET /user/:id:');
	NewRouterRoute::fromRouteStr('GET /*');
	NewRouterRoute::fromRouteStr('GET /public/*');
	NewRouterRoute::fromRouteStr('/+');
};
echo "create 1 route time (u sec): " . iterate(100000, $createRoute)/7 . "\n"; // 7 for 7 routes in for

$route1 = NewRouterRoute::fromRouteStr('GET /');
$route2 = NewRouterRoute::fromRouteStr('/');
$route3 = NewRouterRoute::fromRouteStr('GET /user/:id:');
$route4 = NewRouterRoute::fromRouteStr('GET /public/*');
$route5 = NewRouterRoute::fromRouteStr('/+');
$routeCompilation = function() use($route1, $route2, $route3, $route4, $route5) {
	$route1->pattern();
	$route2->pattern();
	$route3->pattern();
	$route4->pattern();
	$route5->pattern();
};
echo "compile 1 route time (u sec): " . iterate(50000, $routeCompilation)/5 . "\n"; // 5 for 5 routes in for


$setupRoutes = function (){
	$router = new NewRouter();
	$router->route(function(){});
	$router->route('/', function(){});
	$router->route('/a/b', function(){});
	$router->route('/a/b/c/d/e/f', function(){});
	$router->route('/a/b/c/d/e/f/g/h', function(){});
	$router->route('/a/b/c/d/e/f/g/h/i/j', function(){});
	$router->route('/a/b/c/d/e/f/g/h/i/j/k', function(){});
	$router->route('/b/*', function(){});
	$router->route('/c/+', function(){});
	$router->route('/d/:id:', function(){});
	$router->route('/bb/*', function(){});
	$router->route('/cc/+', function(){});
	$router->route('/dd/:id:', function(){});
};
echo "setup 1 route time (u sec): " . iterate(100000, $setupRoutes)/13 . "\n"; // 13 for 13 routes in for


$router = new NewRouter();
$router->route('/', function(){});
$router->route('/a', function(){});
$router->route('/a/b', function(){});
$router->route('/a/b/c', function(){});
$router->route('/a/b/c/d', function(){});
$router->route('/a/b/c/d/e', function(){});
$router->route('/a/b/c/d/e/f', function(){});
$router->route('/a/b/c/d/e/f/g', function(){});
$router->route('/a/b/c/d/e/f/g/h', function(){});
$router->route('/a/b/c/d/e/f/g/h/i', function(){});
$router->route('/a/b/c/d/e/f/g/h/i/j', function(){});
$router->route('/a/b/c/d/e/f/g/h/i/j/k', function(){});
$router->route('/b/*', function(){});
$router->route('/c/+', function(){});
$router->route('/d/:id:', function(){});
$router->route('/bb/*', function(){});
$router->route('/cc/+', function(){});
$router->route('/dd/:id:', function(){});

$dispatchFunc = function () use($router) {
	$router->dispatch('GET', '/z');	//none existing match
	$router->dispatch('GET', '/a/b/c/d/e/f/g/h/i/j/k'); // simple match
	$router->dispatch('GET', '/b/'); // wild card match
	$router->dispatch('GET', '/c/123'); // wild card match
	$router->dispatch('GET', '/d/123'); // wild card match
	$router->dispatch('POST', '/dd/123'); // wild card match
};
echo "dispatch time (u sec): " . iterate(100000, $dispatchFunc)/6  . "\n"; // 6 for 6 dispatches in for


$worstCaseSetup = function() {
	$router = new NewRouter();
	$router->route('/', function(){});
	$router->route('/a', function(){});
	$router->route('/a/b', function(){});
	$router->route('/a/b/c', function(){});
	$router->route('/a/b/c/d', function(){});
	$router->route('/a/b/c/d/e', function(){});
	$router->route('/a/b/c/d/e/f', function(){});
	$router->route('/a/b/c/d/e/f/g', function(){});
	$router->route('/a/b/c/d/e/f/g/h', function(){});
	$router->route('/a/b/c/d/e/f/g/h/i', function(){});
	$router->route('/a/b/c/d/e/f/g/h/i/j', function(){});
	$router->route('/a/b/c/d/e/f/g/h/i/j/k', function(){});
	$router->route('/b/*', function(){});
	$router->route('/c/+', function(){});
	$router->route('/d/:id:', function(){});
	$router->route('/bb/*', function(){});
	$router->route('/cc/+', function(){});
	$router->route('/dd/:id:', function(){});
	$router->route('/ee/:id:', function(){});
	$router->route('/ff/*', function(){});
	$router->dispatch('GET', '/ff/1'); // last match (worst case)
};
echo "20 routes & match last route, execution time (u sec): " . iterate(100000, $worstCaseSetup) . "\n";

$worstCaseSetup = function() {
	$router = new NewRouter();
	$router->route('/', function(){});
	$router->route('/a', function(){});
	$router->route('/a/b', function(){});
	$router->route('/a/b/c', function(){});
	$router->route('/a/b/c/d', function(){});
	$router->route('/a/b/c/d/e', function(){});
	$router->route('/a/b/c/d/e/f', function(){});
	$router->route('/a/b/c/d/e/f/g', function(){});
	$router->route('/a/b/c/d/e/f/g/h', function(){});
	$router->route('/a/b/c/d/e/f/g/h/i', function(){});
	$router->route('/a/b/c/d/e/f/g/h/i/j', function(){});
	$router->route('/a/b/c/d/e/f/g/h/i/j/k', function(){});
	$router->route('/b/*', function(){});
	$router->route('/c/+', function(){});
	$router->route('/d/:id:', function(){});
	$router->route('/bb/*', function(){});
	$router->route('/cc/+', function(){});
	$router->route('/dd/:id:', function(){});
	$router->route('/ee/:id:', function(){});
	$router->route('/ff/*', function(){});
	$router->dispatch('GET', '/'); // first match (best case)
};
echo "20 routes & match first route, execution time (u sec): " . iterate(100000, $worstCaseSetup) . "\n";

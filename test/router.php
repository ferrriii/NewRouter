<?PHP
require_once('util.php');
require_once('../newrouter.php');

$router = new NewRouter();
$routeFound = $router->dispatch('GET', '/');
equal('no route found for empty routes', $routeFound, false);

// test initialization
$router = new NewRouter();
$router->route('/', AddStrAndReturn($str, '/'));
$router->route('/', AddStrAndReturn($str, 'slash'));
$router->route('/a', AddStrAndReturn($str, 3));
$router->route('/b/*', AddStrAndReturn($str, 4));
$router->route('/a/b', AddStrAndReturn($str, 'a', false));
$router->route('GET /a', AddStrAndReturn($str, 5));
$router->route('POST /a', AddStrAndReturn($str, 6));
$router->route('POST /a/b/c', AddStrAndReturn($str, 'b', false));
$router->route('GET /z', AddStrAndReturn($str, 'c', false));
$router->route('/z', AddStrAndReturn($str, 'd', false));
$router->route('/b/:id:', AddStrAndReturn($str, 7));
$router->route('/x/:id:/1', AddStrAndReturn($str, 8));
$router->route('/x/:id:/:name:', AddStrAndReturn($str, 9));
$router->route('POST /p/*/z', AddStrAndReturn($str, 'star'));
$router->route('', AddStrAndReturn($str, '*'));
$router->route('GET', AddStrAndReturn($str, 'GET'));
$router->route('GET /multiple', AddStrAndReturn($str, 'm1'), AddStrAndReturn($str, 'm2'), AddStrAndReturn($str, 'm3'));
$router->route('DELETE /delete/:name:', AddStrAndReturn($str, 'delete'));

$str = '';
$routeFound = $router->dispatch('GET', '/');
equal('GET /', $str, '/slash*GET');
equal('GET / route found', $routeFound, true);

$str = '';
$router->dispatch('POST', '/');
equal('POST /', $str, '/slash*');

$str = '';
$router->dispatch('GET', '/b');
equal('GET /b', $str, '*GET');

$str = '';
$router->dispatch('GET', '');
equal('GET', $str, '*GET');

$str = '';
$router->dispatch('GET', '/none-existing-route');
equal('GET /none-existing-route', $str, '*GET');

$str = '';
$router->dispatch('GET', '/ / /');
equal('GET / / /', $str, '*GET');

$str = '';
$router->dispatch('POST', '');
equal('POST', $str, '*');

$str = '';
$router->dispatch('POST', ' ');
equal('POST', $str, '*');

$str = '';
$router->dispatch('ZZZ', '/');
equal('ZZZ /', $str, '/slash*');

$str = '';
$router->dispatch('GET', '/GET/a');
equal('GET /GET/a', $str, '*GET');

$str = '';
$router->dispatch('GET', '/a');
equal('GET /a', $str, '35*GET');

$str = '';
$router->dispatch('GET', '/b');
equal('GET /b', $str, '*GET');

$str = '';
$router->dispatch('GET', '/b/');
equal('GET /b', $str, '47*GET');

$str = '';
$router->dispatch('GET', '/b/a');
equal('GET /b/a', $str, '47*GET');
equal('GET /b/a param id is a', $request->params['id'], 'a');

$str = '';
$router->dispatch('GET', '/b/a/b');
equal('GET /b/a/b', $str, '4*GET');

$str = '';
$router->dispatch('POST', '/p/a/z');
equal('POST /p/a/z', $str, 'star*');

$str = '';
$router->dispatch('POST', '/p/a/b/c/d/z');
equal('POST /p/a/b/c/d/z', $str, 'star*');

$str = '';
$router->dispatch('GET', '/a/b');
equal('GET /a/b', $str, 'a');

$str = '';
$router->dispatch('POST', '/a/b');
equal('POST /a/b', $str, 'a');

$str = '';
$router->dispatch('POST', '/a/b/');
equal('POST /a/b/', $str, '*');

$str = '';
$router->dispatch('POST', '/a/b/c');
equal('POST /a/b/c', $str, 'b');

$str = '';
$router->dispatch('GET', '/a/b/c');
equal('GET /a/b/c', $str, '*GET');

$str = '';
$router->dispatch('GET', '/z');
equal('GET /z', $str, 'c');

$str = '';
$router->dispatch('POST', '/z');
equal('POST /z', $str, 'd');


$str = '';
$router->dispatch('GET', '/x/0/1');
equal('GET /x/0/1', $str, '89*GET');
equal('GET /x/0/1 param id is 0', $request->params['id'], '0');
equal('GET /x/0/1 param name is 1', $request->params['name'], '1');


$str = '';
$router->dispatch('GET', '/x/11/1');
equal('GET /x/11/1', $str, '89*GET');

$str = '';
$router->dispatch('POST', '/x/zzz/1');
equal('POST /x/zzz/1', $str, '89*');

$str = '';
$router->dispatch('POST', '/x/yy/zz/ww');
equal('POST /x/yy/zz/ww', $str, '*');

$str = '';
$router->dispatch('GET', '/multiple');
equal('GET /multiple', $str, '*GETm1m2m3');

$str = '';
$router->dispatch('DELETE', '/delete/123');
equal('DELETE /delete/123', $str, '*delete');
equal('DELETE /delete/123 param name is 1', $request->params['name'], '123');


$router = new NewRouter();
$router->route(AddStrAndReturn($str, '*'));
$router->route('/a', AddStrAndReturn($str, 'a'), AddStrAndReturn($str, 'b'));

$str = '';
$router->dispatch('GET', '/');
equal('GET /', $str, '*');

$str = '';
$router->dispatch('GET', '/a');
equal('GET /a', $str, '*ab');

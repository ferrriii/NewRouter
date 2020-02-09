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
$router->route('PUT /f', AddStrAndReturn($str, 'put'));
$router->route('POST /f', AddStrAndReturn($str, 'post'));

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

$str = '';
$router->dispatch('PUT', '/f');
equal('PUT /f', $str, '*put');

$str = '';
$router->dispatch('POST', '/f');
equal('POST /f', $str, '*post');

$str = '';
$router->dispatch('POST', '/f?query=123');
equal('POST /f', $str, '*post');


$router = new NewRouter();
$router->route(AddStrAndReturn($str, '*'));
$router->route('/a', AddStrAndReturn($str, 'a'), AddStrAndReturn($str, 'b'));

$str = '';
$router->dispatch('GET', '/');
equal('GET /', $str, '*');

$str = '';
$router->dispatch('GET', '/a');
equal('GET /a', $str, '*ab');






$routerUser = new NewRouter();
$routerUser->route(AddStrAndReturn($str, 'user'));
$routerUser->route('GET /', AddStrAndReturn($str, 'default'));
$routerUser->route('GET /profile', AddStrAndReturn($str, 'profile'));
$routerUser->route('/allMethod', AddStrAndReturn($str, 'allMethod'));
$routerUser->route('GET /post/:id:', AddStrAndReturn($str, 'post'));
$routerUser->route('GET /image/*', AddStrAndReturn($str, 'image'));
$router = new NewRouter();
$router->route(AddStrAndReturn($str, '*'));
$router->route('GET /user/*', $routerUser);
$router->route('GET /', AddStrAndReturn($str, '/'));
$router->route('GET /all/:name:/*', $routerUser);

$str = '';
$router->dispatch('GET', '/');
equal('GET /', $str, '*/');

$str = '';
$router->dispatch('POST', '/user/');
equal('POST /user/', $str, '*');

$str = '';
$router->dispatch('GET', '/user/');
equal('GET /user/', $str, '*userdefault');

$str = '';
$router->dispatch('GET', '/user/profile');
equal('GET /user/profile', $str, '*userprofile');

$str = '';
$router->dispatch('GET', '/user/allMethod');
equal('GET /user/allMethod', $str, '*userallMethod');

$str = '';
$router->dispatch('GET', '/user/post/123');
equal('GET /user/post/123', $str, '*userpost');
equal('GET /user/post/123 param id is 123', $request->params['id'], '123');

$str = '';
$router->dispatch('GET', '/user/image/2020/new.jpg');
equal('GET /user/image/2020/new.jpg', $str, '*userimage');

$str = '';
$router->dispatch('GET', '/all/111/');
equal('GET /all/111/', $str, '*userdefault');
equal('GET /all/111/ param name is 111', $request->params['name'], '111');

$str = '';
$router->dispatch('GET', '/all/111/post/999');
equal('GET /all/111/post/999', $str, '*userpost');
equal('GET /all/111/post/999 param name is 111', $request->params['name'], '111');
equal('GET /all/111/post/999 param id is 999', $request->params['id'], '999');




$routerUser = new NewRouter();
$routerUser->route(AddStrAndReturn($str, 'user'));
$routerUser->route('/', AddStrAndReturn($str, 'default'));
$routerUser->route('POST /a', AddStrAndReturn($str, 'a'));
$router = new NewRouter();
$router->route('GET /user/*', $routerUser);

$str = '';
$router->dispatch('GET', '/user/');
equal('GET /user/', $str, 'userdefault');

$str = '';
$router->dispatch('POST', '/user/a');
equal('POST /user/a', $str, '');




$routerUser = new NewRouter();
$routerUser->route(AddStrAndReturn($str, 'user'));
$routerUser->route('GET /', AddStrAndReturn($str, 'default'));
$routerUser->route('POST /a', AddStrAndReturn($str, 'a'));
$router = new NewRouter();
$router->route('/user/*', $routerUser);

$str = '';
$router->dispatch('GET', '/user/');
equal('GET /user/', $str, 'userdefault');

$str = '';
$router->dispatch('POST', '/user/a');
equal('POST /user/a', $str, 'usera');



$routerWeblog = new NewRouter();
$routerWeblog->route(AddStrAndReturn($str, 'weblog'));
$routerWeblog->route('GET /', AddStrAndReturn($str, '/'));
$routerWeblog->route('GET /post/:id:', AddStrAndReturn($str, 'post'));
$routerUser = new NewRouter();
$routerUser->route(AddStrAndReturn($str, 'user'));
$routerUser->route('GET /', AddStrAndReturn($str, 'slash'));
$routerUser->route('GET /weblog/*', $routerWeblog);
$router = new NewRouter();
$router->route('/user/*', $routerUser);

$str = '';
$router->dispatch('GET', '/user/');
equal('GET /user/', $str, 'userslash');

$str = '';
$router->dispatch('GET', '/user/weblog/');
equal('GET /user/weblog/', $str, 'userweblog/');

$str = '';
$router->dispatch('GET', '/user/weblog/post/1');
equal('GET /user/weblog/post/1', $str, 'userweblogpost');
equal('GET /user/weblog/post/1 param id is 1', $request->params['id'], '1');



$routerUser = new NewRouter();
$routerUser->route('GET /', AddStrAndReturn($str, 'default'));
$routerUser->route(AddStrAndReturn($str, 'nouser'));
$router = new NewRouter();
$router->route('/user/*', $routerUser);
$router->route(AddStrAndReturn($str, 'invalidRoute'));

$str = '';
$router->dispatch('GET', '/user/');
equal('GET /user/', $str, 'defaultnouserinvalidRoute');

$str = '';
$router->dispatch('GET', '/invalid');
equal('GET /invalid', $str, 'invalidRoute');

$str = '';
$router->dispatch('GET', '/user/invalid');
equal('GET /user/invalid', $str, 'nouserinvalidRoute');





$routerUser = new NewRouter();
$routerUser->route('GET /', AddStrAndReturn($str, 'default', false));
$routerUser->route(AddStrAndReturn($str, 'nouser', false));
$router = new NewRouter();
$router->route('/user/*', $routerUser);
$router->route(AddStrAndReturn($str, 'invalidRoute', false));

$str = '';
$router->dispatch('GET', '/user/');
equal('GET /user/', $str, 'default');
equal('GET /user/ url is /user/', $request->url, '/user/');


$str = '';
$router->dispatch('GET', '/invalid');
equal('GET /invalid', $str, 'invalidRoute');

$str = '';
$router->dispatch('GET', '/user/invalid');
equal('GET /user/invalid', $str, 'nouser');
equal('GET /user/invalid url is /user/', $request->url, '/user/');







class Foo {
	static public function bar() {
		global $str;
		$str = 'bar';
	}
}

// test initialization
$str='';
$router = new NewRouter();
$router->route('/', 'Foo::bar');
$router->dispatch('GET', '/');
equal('class::method', $str, 'bar');




// test matched url ($request->url)
$router = new NewRouter();
$router->route('/public/*', AddStrAndReturn($str, '/'));
$str = '';
$router->dispatch('GET', '/public/');
equal('GET /public/ url is /public/', $request->url, '/public/');
$str = '';
$router->dispatch('GET', '/public/a');
equal('GET /public/a url is /public/', $request->url, '/public/');
$str = '';
$router->dispatch('GET', '/public/a/b');
equal('GET /public/a/b url is /public/', $request->url, '/public/');


$router = new NewRouter();
$router->route('/', AddStrAndReturn($str, '/'));
$str = '';
$router->dispatch('GET', '/');
equal('GET / url is /', $request->url, '/');

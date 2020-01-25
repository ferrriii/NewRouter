<?php
require_once('util.php');
require_once('../newrouter.php');

$route = NewRouterRoute::fromRouteStr('GET /');
equal('GET / route is /', $route->route, '/');
equal('GET / method is GET', $route->method, 'GET');
equal('GET / matches /', preg_match('/' . $route->pattern() . '/i', '/'), true);
equal('GET / doesn\'t match /a', preg_match('/' . $route->pattern() . '/i', '/a'), false);
equal('GET / doesn\'t match empty', preg_match('/' . $route->pattern() . '/i', ''), false);

$route = NewRouterRoute::fromRouteStr('GET  /');
equal('GET / route is /', $route->route, '/');
equal('GET / method is GET', $route->method, 'GET');
equal('GET / matches /', preg_match('/' . $route->pattern() . '/i', '/'), true);
equal('GET / doesn\'t match /a', preg_match('/' . $route->pattern() . '/i', '/a'), false);
equal('GET / doesn\'t match empty', preg_match('/' . $route->pattern() . '/i', ''), false);

$route = NewRouterRoute::fromRouteStr('GET //');
equal('GET // normalizes to /', $route->route, '/');
equal('GET // method is GET', $route->method, 'GET');
equal('GET / matches /', preg_match('/' . $route->pattern() . '/i', '/'), true);
equal('GET / doesn\'t match /a', preg_match('/' . $route->pattern() . '/i', '/a'), false);
equal('GET / doesn\'t match empty', preg_match('/' . $route->pattern() . '/i', ''), false);

$route = NewRouterRoute::fromRouteStr('GET /a');
equal('GET /a route is /a', $route->route, '/a');
equal('GET /a method is GET', $route->method, 'GET');
equal('GET /a doesn\'t match /', preg_match('/' . $route->pattern() . '/i', '/'), false);
equal('GET /a matches /a', preg_match('/' . $route->pattern() . '/i', '/a'), true);
equal('GET /a doesn\'t match empty', preg_match('/' . $route->pattern() . '/i', ''), false);

$route = NewRouterRoute::fromRouteStr('GET /سلام');
equal('GET /سلام route is /سلام', $route->route, '/سلام');
equal('GET /سلام method is GET', $route->method, 'GET');
equal('GET /سلام doesn\'t match /', preg_match('/' . $route->pattern() . '/i', '/'), false);
equal('GET /سلام matches /سلام', preg_match('/' . $route->pattern() . '/i', '/سلام'), true);
equal('GET /سلام doesn\'t match empty', preg_match('/' . $route->pattern() . '/i', ''), false);

$route = NewRouterRoute::fromRouteStr('GeT /AbC');
equal('GeT /AbC route is /AbC', $route->route, '/AbC');
equal('GeT /AbC method is GET', $route->method, 'GET');
equal('GeT /AbC doesn\'t match /', preg_match('/' . $route->pattern() . '/i', '/'), false);
equal('GeT /AbC matches /aBc', preg_match('/' . $route->pattern() . '/i', '/aBc'), true);
equal('GeT /AbC doesn\'t match empty', preg_match('/' . $route->pattern() . '/i', ''), false);

$route = NewRouterRoute::fromRouteStr(' GET /a ');
equal('[space]GET /a[space] route is /a', $route->route, '/a');
equal('[space]GET /a[space] method is GET', $route->method, 'GET');
equal('[space]GET /a[space] doesn\'t match /', preg_match('/' . $route->pattern() . '/i', '/'), false);
equal('[space]GET /a[space] matches /a', preg_match('/' . $route->pattern() . '/i', '/a'), true);
equal('[space]GET /a[space] doesn\'t match empty', preg_match('/' . $route->pattern() . '/i', ''), false);


$route = NewRouterRoute::fromRouteStr('GET ');
equal('GET[space] route is empty', $route->route, '');
equal('GET[space] method is GET', $route->method, 'GET');
equal('GET[space] matches /', preg_match('/' . $route->pattern() . '/i', '/'), true);
equal('GET[space] matches /a', preg_match('/' . $route->pattern() . '/i', '/a'), true);
equal('GET[space] matches empty', preg_match('/' . $route->pattern() . '/i', ''), true);


$route = NewRouterRoute::fromRouteStr('GET');
equal('GET route is empty', $route->route, '');
equal('GET method is GET', $route->method, 'GET');
equal('GET matches /', preg_match('/' . $route->pattern() . '/i', '/'), true);
equal('GET matches /a', preg_match('/' . $route->pattern() . '/i', '/a'), true);
equal('GET matches empty', preg_match('/' . $route->pattern() . '/i', ''), true);

$route = NewRouterRoute::fromRouteStr('/');
equal('/ route is /', $route->route, '/');
equal('/ method is empty', $route->method, '');
equal('/ matches /', preg_match('/' . $route->pattern() . '/i', '/'), true);
equal('/ doesn\'t match /a', preg_match('/' . $route->pattern() . '/i', '/a'), false);
equal('/ doesn\'t match empty', preg_match('/' . $route->pattern() . '/i', ''), false);

$route = NewRouterRoute::fromRouteStr('//');
equal('// route is /', $route->route, '/');
equal('// method is empty', $route->method, '');
equal('// matches /', preg_match('/' . $route->pattern() . '/i', '/'), true);
equal('// doesn\'t match /a', preg_match('/' . $route->pattern() . '/i', '/a'), false);
equal('// doesn\'t match empty', preg_match('/' . $route->pattern() . '/i', ''), false);


$route = NewRouterRoute::fromRouteStr('/a');
equal('/a route is /a', $route->route, '/a');
equal('/a method is empty', $route->method, '');
equal('/a doesn\'t match /', preg_match('/' . $route->pattern() . '/i', '/'), false);
equal('/a matches /a', preg_match('/' . $route->pattern() . '/i', '/a'), true);
equal('/a doesn\'t match /a/', preg_match('/' . $route->pattern() . '/i', '/a/'), false);
equal('/a doesn\'t match /a/b', preg_match('/' . $route->pattern() . '/i', '/a/b'), false);
equal('/a doesn\'t match empty', preg_match('/' . $route->pattern() . '/i', ''), false);


$route = NewRouterRoute::fromRouteStr(' / ');
equal("[space]/[space] route is /", $route->route, '/');
equal("[space]/[space] method is empty", $route->method, '');
equal("[space]/[space] matches /", preg_match('/' . $route->pattern() . '/i', '/'), true);
equal("[space]/[space] doesn't match /a", preg_match('/' . $route->pattern() . '/i', '/a'), false);
equal("[space]/[space] doesn't match empty", preg_match('/' . $route->pattern() . '/i', ''), false);

$route = NewRouterRoute::fromRouteStr('');
equal('empty route is empty', $route->route, '');
equal('empty method is empty', $route->method, '');
equal('empty matches /', preg_match('/' . $route->pattern() . '/i', '/'), true);
equal('empty matches /a', preg_match('/' . $route->pattern() . '/i', '/a'), true);
equal('empty matches empty', preg_match('/' . $route->pattern() . '/i', ''), true);

$route = NewRouterRoute::fromRouteStr(null);
equal('empty route is empty', $route->route, '');
equal('empty method is empty', $route->method, '');
equal('empty matches /', preg_match('/' . $route->pattern() . '/i', '/'), true);
equal('empty matches /a', preg_match('/' . $route->pattern() . '/i', '/a'), true);
equal('empty matches empty', preg_match('/' . $route->pattern() . '/i', ''), true);


$route = NewRouterRoute::fromRouteStr('/a/:id:');
equal("/a/:id: route is /a/:id:", $route->route, '/a/:id:');
equal("/a/:id: method is empty", $route->method, '');
equal("/a/:id: doesn't match /", preg_match('/' . $route->pattern() . '/i', '/'), false);
equal("/a/:id: doesn't match /a", preg_match('/' . $route->pattern() . '/i', '/a'), false);
equal("/a/:id: doesn't match /a/", preg_match('/' . $route->pattern() . '/i', '/a/'), true);
equal("/a/:id: matches /a/b", preg_match('/' . $route->pattern() . '/i', '/a/b'), true);
equal("/a/:id: doesn't match /a/b/z", preg_match('/' . $route->pattern() . '/i', '/a/b/z'), false);
equal("/a/:id: doesn't match empty", preg_match('/' . $route->pattern() . '/i', ''), false);
preg_match('/' . $route->pattern() . '/i', '/a/', $matches);
equal("/a/:id: id is empty in /a/", $matches['id'], '');
preg_match('/' . $route->pattern() . '/i', '/a/b', $matches);
equal("/a/:id: id is b in /a/b", $matches['id'], 'b');


$route = NewRouterRoute::fromRouteStr('/a/:id:/:name:');
equal("/a/:id:/:name: route is /a/:id:/:name:", $route->route, '/a/:id:/:name:');
equal("/a/:id:/:name: method is empty", $route->method, '');
equal("/a/:id:/:name: doesn't match /", preg_match('/' . $route->pattern() . '/i', '/'), false);
equal("/a/:id:/:name: doesn't match /a", preg_match('/' . $route->pattern() . '/i', '/a'), false);
equal("/a/:id:/:name: doesn't match /a/", preg_match('/' . $route->pattern() . '/i', '/a/'), false);
equal("/a/:id:/:name: doesn't match /a/b", preg_match('/' . $route->pattern() . '/i', '/a/b'), false);
equal("/a/:id:/:name: matches /a/b/z", preg_match('/' . $route->pattern() . '/i', '/a/b/z'), true);
equal("/a/:id:/:name: doesn't match empty", preg_match('/' . $route->pattern() . '/i', ''), false);
preg_match('/' . $route->pattern() . '/i', '/a/123/abc', $matches);
equal("/a/:id:/:name: id is 123 in /a/123/abc", $matches['id'], '123');
equal("/a/:id:/:name: id is 123 in /a/123/abc", $matches[1], '123');
equal("/a/:id:/:name: name is abc in /a/123/abc", $matches['name'], 'abc');
equal("/a/:id:/:name: name is abc in /a/123/abc", $matches[1], 'abc');


$route = NewRouterRoute::fromRouteStr('/a/*');
equal("/a/* route is /a/*", $route->route, '/a/*');
equal("/a/* method is empty", $route->method, '');
equal("/a/* doesn't match /", preg_match('/' . $route->pattern() . '/i', '/'), false);
equal("/a/* doesn't match /a", preg_match('/' . $route->pattern() . '/i', '/a'), false);
equal("/a/* matches /a/", preg_match('/' . $route->pattern() . '/i', '/a/'), true);
equal("/a/* matches /a/b", preg_match('/' . $route->pattern() . '/i', '/a/b'), true);
equal("/a/* matches /a/b/z", preg_match('/' . $route->pattern() . '/i', '/a/b/z'), true);
equal("/a/* doesn't match empty", preg_match('/' . $route->pattern() . '/i', ''), false);

$route = NewRouterRoute::fromRouteStr('/a/+');
equal("/a/+ route is /a/+", $route->route, '/a/+');
equal("/a/+ method is empty", $route->method, '');
equal("/a/+ doesn't match /", preg_match('/' . $route->pattern() . '/i', '/'), false);
equal("/a/+ doesn't match /a", preg_match('/' . $route->pattern() . '/i', '/a'), false);
equal("/a/+ matches /a/", preg_match('/' . $route->pattern() . '/i', '/a/'), false);
equal("/a/+ matches /a/b", preg_match('/' . $route->pattern() . '/i', '/a/b'), true);
equal("/a/+ matches /a/b/z", preg_match('/' . $route->pattern() . '/i', '/a/b/z'), true);
equal("/a/+ doesn't match empty", preg_match('/' . $route->pattern() . '/i', ''), false);

$route = NewRouterRoute::fromRouteStr('GET /*');
equal("GET /* route is /*", $route->route, '/*');
equal("GET /* method is GET", $route->method, 'GET');
equal("GET /* matches /", preg_match('/' . $route->pattern() . '/i', '/'), true);
equal("GET /* matches /a", preg_match('/' . $route->pattern() . '/i', '/a'), true);
equal("GET /* mathces /a/", preg_match('/' . $route->pattern() . '/i', '/a/'), true);
equal("GET /* matches /a/b", preg_match('/' . $route->pattern() . '/i', '/a/b'), true);
equal("GET /* matches /a/b/z", preg_match('/' . $route->pattern() . '/i', '/a/b/z'), true);
equal("GET /* doesn't match empty", preg_match('/' . $route->pattern() . '/i', ''), false);

$route = NewRouterRoute::fromRouteStr('GET /+');
equal("GET /+ route is /+", $route->route, '/+');
equal("GET /+ method is GET", $route->method, 'GET');
equal("GET /+ matches /", preg_match('/' . $route->pattern() . '/i', '/'), false);
equal("GET /+ matches /a", preg_match('/' . $route->pattern() . '/i', '/a'), true);
equal("GET /+ mathces /a/", preg_match('/' . $route->pattern() . '/i', '/a/'), true);
equal("GET /+ matches /a/b", preg_match('/' . $route->pattern() . '/i', '/a/b'), true);
equal("GET /+ matches /a/b/z", preg_match('/' . $route->pattern() . '/i', '/a/b/z'), true);
equal("GET /+ doesn't match empty", preg_match('/' . $route->pattern() . '/i', ''), false);

$route = NewRouterRoute::fromRouteStr('XYZ /');
equal('GET / route is /', $route->route, '/');
equal('GET / method is XYZ', $route->method, 'XYZ');

/*
$routeA = NewRouterRoute::fromRouteStr('/user/*');
$routeB = NewRouterRoute::fromRouteStr('/');
//$routeB = NewRouterRoute::fromRouteStr('/a');
echo $routeA->pattern($routeB->pattern()) . "\n";
equal('/ after /user/* doesn\'t match /', preg_match('/' . $routeB->pattern($routeA->pattern()) . '/i', '/'), false);
equal('/ after /user/* doesn\'t match /user', preg_match('/' . $routeB->pattern($routeA->pattern()) . '/i', '/user'), false);
equal('/ after /user/* matches /user/', preg_match('/' . $routeB->pattern($routeA->pattern()) . '/i', '/user/'), true);
equal('/ after /user/* doesn\'t match /user/a', preg_match('/' . $routeB->pattern($routeA->pattern()) . '/i', '/user/a'), false);
equal('/ after /user/* doesn\'t match /user/b', preg_match('/' . $routeB->pattern($routeA->pattern()) . '/i', '/user/b'), false);
*/


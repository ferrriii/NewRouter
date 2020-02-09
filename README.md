# NewRouter
a simple, fast and powerful PHP router.
- Simple Syntax
- Light Weight (single file, ~160 LOC)
- Feature Full
- Tested & Fast
------------------
**Table of contents**
- [Installation](#installation)
- [Hello World](#hello-world)
- [Routes](#routes)
  - [Any method](#Any-method)
  - [Parameterized Route](#parameterized-route)
  - [Using patterns in route URL](#using-patterns-in-route-url)
- [Callbacks](#callbacks)
  - [Callback return value](#callback-return-value)
  - [Callback arguments](#callback-arguments)
  - [Multiple callbacks](#multiple-callbacks-for-a-route)
- [Prefixing Routes (grouping)](#prefixing-routes-grouping)
- [Middleware](#middleware)
- [No Match](#no-match)
- [Examples](#examples)
- [Tests](#tests)

----------------
## Installation
```php
require('newrouter.php');
```
PHP >= 5.3
## Hello World 
```php
require('newrouter.php');

$router = new NewRouter();
$router->route('GET /', function(){
	echo 'Hello World!';
});

$router->dispatch();
```
## Routes
Routes can be added by `route()` method. First argument of this method can be a string combined of HTTP method and URL path and second argument is a callback for the specified route.
```php
$router->route('GET /some/path', function(){});
$router->route('POST /some/path', function(){});
$router->route('PUT /some/path', function(){});
```
Routes will be tested in the same order they are added.
### Any method
Not specifying a method will match all HTTP methods.
```php
$router->route('/some/path', function(){}); // this will match GET, POST, PUT, DELETE, etc.
```
### Parameterized Route
Use `:parameterName:` syntax to define parameterized routes. `parameterName` will be available in arguments passed to callback function.
```php
$router->route('GET /user/:id:', function($request){
	// this route will match:
	//    http://site.com/user/123
	//    http://site.com/user/abc
	//    etc.
	echo 'user id is ' . $request->params['id']; // id is either 123 or abc
});
```
### Using patterns in route URL
You can use below patterns in routings.

| Pattern | Meaning                | Example Route     | Example Match |
| ------- | ---------------------- | ----------------- | ------------- |
| *       | Anything or nothing    | `GET /users/*`    | site.com/users/<br/>site.com/users/profile<br/>site.com/users/profile/images |
| +       | Anything but not empty | `GET /users/+`    | site.com/users/profile<br/>site.com/users/profile/images |
| :param: | Anything except /      | `GET /users/:id:` | site.com/users/profile<br/>site.com/users/123 |

## Callbacks
Route callbacks can be a [Closure](https://www.php.net/manual/en/class.closure.php), a [Callable](https://www.php.net/manual/en/language.types.callable.php) string or an instance of `NewRouter`.
```php
// closure example
$router->route('GET /', function(){
	// this will be executed on GET /
});

// class method example
class Foo {
	static public function bar() {
		// this will be executed on GET /
	}
}
$router->route('GET /', 'Foo::bar');
```
See [Prefixing Routes](#prefixing-routes-grouping) for router instance as callback example.
### Callback return value
Return value of callback can change routing behavior. An explicit `True` tells NewRouter to continue trying to match next routes.
An implicit `False` or `Null` means next routes should be stopped.

*Note:* Not returning anything inside a callback is same as returning `Null`.
### Callback arguments
An instance of `stdClass` will be passed to callbacks when a route is matched. Below is list of defined properties in this object.

| Property | Description |
| -------- | ------------ |
| params   | An associative array where keys are captured parameters when using parameterized routes. See [Parameterized routing](#parameterized-route) for example. |
| url      | The url part that route has matched. |

*Note:* Callbacks can capture `$request` by reference and manipulate it. See [Middleware](#middleware) for more.
### Multiple callbacks for a route
A route can have multiple callbacks. Callbacks will be executed in the same order they are added.
```php
$router->route('GET /user/profile', $callback1, $callback2, $callback3);
```
In above example, callback1 is executed first. Then if it returns true, callback2 gets executed and so on.
This is useful for use cases like authorization, see below example.
```php
$isRequestAuthorized = function() {
	// do checkings here and return true or false
}
$showUserProfile = function() {
	// show profile here
}
$router->route('GET /user/profile', $isRequestAuthorized, $showUserProfile);
```
## Prefixing Routes (grouping)
By passing an instance of `NewRouter` as callback, you can delegate all routes to that instance. This is useful when prefixing/grouping routes.
```php
// define a router for user APIs
$userRouter = new NewRouter();
$userRouter->route('GET /', function(){
	// this route is prefixed with /users/
});
$userRouter->route('GET /profile/:id:', function($request){
	// this route is prefixed with /users/
});

// define the main router
$mainRouter = new NewRouter();
// delegate all requests to /users/* to $userRouter
$mainRouter->route('/users/*', $userRouter); // prefix $userRouter with /users/ path
//define other routes

$mainRouter->dispatch();
```
In above example routes like `/users/` and `/users/profile/123` will be handled by `userRouter`.
## Middleware
You can omit route string and only pass callbacks. This kind of callbacks will get executed on all requests.
```php
$router->route(function(){}); // this will get executed on all requests
```
This could be useful for uses case like logging and intercepting. Below is an example for parsing JWT token.
```php
$router->route(function(&$request){ // notice the capture by reference
	$request->decodedJWT = '{"sub": "1234", "name": "John Doe", "iat": 1516239022}'; // in real-world you need to actually read JWT from headers and decode it
	return true; // this is necessary to continue next routes
});

$router->route('GET /profile', function($request) {
	// $request->decodedJWT is {"sub": "1234", "name": "John Doe", "iat": 1516239022} here
});
```
You can also define middlewares for a specific HTTP method. Below example defines a callback which gets executed on all POST requests.
```php
$router->route('POST', function(){
	// this is executed on all POST requests
});
```
## No Match
You can use a middleware as final route to capture no match.
example:
```php
// define routes
$route->route('GET /a', function(){});
$route->route('GET /b', function(){});
// ...
$route->route(function() {
	// no routes matched
	echo 'invalid route';
});
```
## Examples
See example directory for more detailed examples.
## Tests
Run below commands in shell to run all tests (210 tests).
```
cd test
php index.php
```
Run performance tests only
```
php performance.php
```
# NewRouter
a simple, fast and powerful PHP router

## Installation
```php
require('newrouter.php');
```
## Hello World 
```php
require('newrouter.php');

$router = new NewRouter();
$router->route('GET /', function(){
	echo 'Hello World!';
});

$router->dispatch();
```
### Routes
Routes can be added by `route()` method. First argument of this method can be a string combined of HTTP method and URL path and second argument is a callback for specified route.
example:
```php
$router->route('GET /some/path', function(){});
$router->route('POST /some/path', function(){});
$router->route('PUT /some/path', function(){});
```
Routes will be matched in order xxxxxxxxxxxxxxxxxxxx.
#### Any method
Not specifying a method will match all HTTP methods.
```php
$router->route('/some/path', function(){}); // this will match GET, POST, PUT, DELETE, etc.
```
####Parameterized Route
Use `:parameterName:` syntax to define parameterized routes. `parameterName` will be available in arguments passed to callback function.
```php
$router->route('GET /user/:id:', function($request){
	// this route will match:
	//    http://site.com/user/123
	//    http://site.com/user/abc
	echo 'user id is ' . $request->params['id']; // id is either 123 or abc
});
```
#### Using patterns in route URL
You can use below patterns in routings.

| Pattern | Meaning                | Example Route     | Example Match |
| ------- | ---------------------- | ----------------- | ------------- |
| *       | Anything or nothing    | `GET /users/*`    | http://site.com/users/<br/>http://site.com/users/profile<br/>http://site.com/users/profile/images |
| +       | Anything but not empty | `GET /users/+`    | http://site.com/users/profile<br/>http://site.com/users/profile/images |
| :param: | anything except /      | `GET /users/:id:` | http://site.com/users/profile<br/>http://site.com/users/123 |

### Callbacks
Route callbacks can be a [Closure](https://www.php.net/manual/en/class.closure.php), a string of static method or an instance of `NewRouter`.
closure example:
```php
example goes here
```
#### Callback return value
Return values of callback can define routing behavior. An explicit `True` tells NewRouter to continue trying to match next routes.
An implicit `False` or `Null` means next routes should be stopped.

*Note:* not returning anything inside a callback is same as returning `Null`.
#### Callback arguments
An instance of `stdClass` will be passed to callbacks. It is called `$request`. Below is list of defined properties in this object.

| Property | Description |
| -------- | ------------ |
| params   | an associative array where keys are captured parameters when using parameterized routes. [see parameterized routing](#Parameterized-Route) |

#### Multiple callbacks for a route
A route can have multiple callbacks. They will be executed in order xxxxxxxxxxxxxxx.
example:
```php
$router->route('GET /user/profile', $callback1, $callback2, $callback3);
```
In above example, first callback1 is executed. if it returns true then callback2 get executed and so on.
This is useful for use cases like authorization, see below example
```php
$isRequestAuthorized = function() {
	// do checkings here and return true or false
}
$showUserProfile = function() {
	// show profile here
}
$router->route('GET /user/profile', $isRequestAuthorized, $showUserProfile);
```
### Prefixing Routes (grouping)
By passing an instance of `NewRouter` as callback, you can delegate all routes to that instance. This is useful when prefixing/grouping routes.
example:
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
### Middleware
You can omit route string and only pass callbacks. This kind of callbacks will get executed on all requests.
```php
$router->route(function(){});
```
This could be useful for uses case like logging and intercepting. below is an example for parsing JWT token.
```php
$router->route(function(&$request){ // notice the capture by reference
	$request->decodedJWT = 'sub:aa';
	return true; // this is necessary to continue next routes
});

$router->route('GET /profile', function($request) {
	// $request->decodedJWT is available here
});
```
You can also define middlewares for a specific HTTP method. Below example defines a callback which gets executed on all POST requests.
```php
$router->route('POST', function(){
	// this is executed on all POST requests
});
```
### No Match
You can use a middleware as final routes to capture no match.
example:
```php
// define routes
$route->route('GET /a', function(){});
$route->route('GET /b', function(){});
// ...
$route->route(function() {
	// no routes matched
});
```
## Tests
Run below commands in shell.
```
cd test
php index.php
```
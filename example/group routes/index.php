<?PHP
require('../../newrouter.php');

// define routes for user
$userRouter = new NewRouter();
$userRouter->route('GET /', function(){
	echo 'list of users:<br>user1<br>user2';
});
$userRouter->route('GET /profile/:id:', function($request){
	echo 'profile for ' . $request->params['id'];
});
$userRouter->route(function(){
	echo 'invalid user route';
});

// define main router
$mainRouter = new NewRouter();
$mainRouter->route('GET /', function(){
	echo 'hello world<br>' . 
		 '<a href=/users/>users list</a><br>' .
		 '<a href=/users/profile/1>user1 profile</a><br>' . 
		 '<a href=/users/profile/2>user2 profile</a><br>' .
		 '<a href=/users/invalid/route>invalid user route</a>';
});
// delegate all requests to /users/* to $userRouter
$mainRouter->route('/users/*', $userRouter);


$mainRouter->dispatch();
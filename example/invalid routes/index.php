<?PHP
require('../../newrouter.php');

$router = new NewRouter();

$router->route('GET /a', function(){
  echo "this is route a";
});

$router->route('GET /b', function(){
  echo "this is route b";
});

$router->route('', function(){
  echo "invalid route!";
});


$router->dispatch();
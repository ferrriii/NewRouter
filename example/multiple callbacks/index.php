<?PHP
require('../../newrouter.php');

$returnTrue = function() {
	echo 'call back 1<br>';
	return true;
};

$returnFalse = function() {
	echo 'call back 2<br>';
	return false;
};

$thisWillNotBeExecuted = function() {
	echo 'call back 3<br>';
};


$router = new NewRouter();
$router->route('GET /', $returnTrue, $returnFalse, $thisWillNotBeExecuted);

$router->dispatch();
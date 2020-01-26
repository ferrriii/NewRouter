<?PHP
$passed = 0;
$failed = 0;
function equal($test, $a, $b) {
	global $passed,$failed;
	if ($a == $b) {
		$passed++;
		echo str_pad($test, 45, " ", STR_PAD_RIGHT) . "passed\n";
	} else {
		$failed++;
		echo str_pad($test, 45, " ", STR_PAD_RIGHT) . "failed, values not equal!\n" .
			  "\t$a\n".
			  "\t$b\n";
	}
}

function iterate($times, $func) {
	$start = microtime(true);
	for ($i = 0; $i < $times; $i++) {
		$func();
	}	
	$elapsed = microtime(true) - $start;
	return $elapsed*1000000/$times; // each iteration time in micro second
}

function AddStrAndReturn(&$str, $add, $return = true) {
	global $request;
	return function($req) use (&$str, $add, $return, &$request) {
		$str .= $add;
		$request = $req;
		return $return;
	};
}
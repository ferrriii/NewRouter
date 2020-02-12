<?PHP

require_once('util.php');
require_once('../newrouterstatic.php');

equal('mimeType:/somewhere/a.jpg', NewRouterStatic::mimeType('/somewhere/a.jpg'), 'image/jpg');
equal('mimeType:/somewhere/a.jpeg', NewRouterStatic::mimeType('/somewhere/a.jpeg'), 'image/jpg');
equal('mimeType:/somewhere/a.gif', NewRouterStatic::mimeType('/somewhere/a.gif'), 'image/gif');
equal('mimeType:/somewhere/a.css', NewRouterStatic::mimeType('/somewhere/a.css'), 'text/css');
equal('mimeType:/somewhere/a.html', NewRouterStatic::mimeType('/somewhere/a.html'), 'text/html');
equal('mimeType:/somewhere/a.txt', NewRouterStatic::mimeType('/somewhere/a.txt'), 'text/plain');
equal('mimeType:/somewhere/a.abcd', NewRouterStatic::mimeType('/somewhere/a.abcd'), 'unknown/abcd');

/*
TODO: complete test coverage

clearSentHeaders();
equal('isFileModified() returns true', NewRouterStatic::isFileModified('./static.php'), true);

equal('isFileModified sets last-modified header', empty(headerValue('last-modified')), false);

$lastModified = headerValue('last-modified');
clearSentHeaders();
NewRouterStatic::isFileModified('./static.php');
$lastModifiedStatic2 = headerValue('last-modified');
equal('isFileModified sets same last-modified for same file', $lastModified, $lastModifiedStatic2);

clearSentHeaders();
NewRouterStatic::isFileModified('./index.php');
$lastModifiedIndex = headerValue('last-modified');
equal('isFileModified sets different last-modified for different file', $lastModified != $lastModifiedIndex, true);
equal('isFileModified sets different last-modified for different file', $lastModifiedStatic2 != $lastModifiedIndex, true);


//equal('isFileModified responds with 304 not modified', NewRouterStatic::isFileModified('./static.php'), false);




function headerValue($lookForHeader) {
	$headers = headers_list();
	foreach ($headers as $header) {
		if (($pos = stripos($header, $lookForHeader)) !== false) {
         $value = substr($header, $pos + strlen($lookForHeader));
         return trim($value, ':');
		}
	}
}

function clearSentHeaders() {
	if (!headers_sent()) {
	  foreach (headers_list() as $header)
		header_remove($header);
	}
}
*/
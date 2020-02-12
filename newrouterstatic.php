<?PHP
class NewRouterStatic {	
	public static function serve($root = './', $options = array()) {
		static $defaultOptions = array(
			"lastModified" => true,
			"etag" => true,
			"index" => array("index.html"), // TODO: implement this
			"addTrailingSlash" => true, // TODO: implement this
			"maxAge" => 0, // TODO: implement this
			"callNext" => "error", // TODO: implement this
			"XSendFile" => false // TODO: implement this
		);
		
		$options = array_merge($defaultOptions, $options);
	
		$realRoot = realpath($root);
		return function($req) use($root, $realRoot, $options) {
			// example:
			// root:./static/
			// path: /public/img/logo.jpg
			// url: /public/
			// file:./static/img/logo.jpg
			$file = realpath($root . substr($req->path, strlen($req->url)));
			// make sure file is not outside root directory
			if ($file === false || strpos($file, $realRoot) !== 0) return true;
			// make sure file is not directory
			if (!is_file($file)) return true;
			// check last-modified header
			if ($options['lastModified'] && !NewRouterStatic::isFileModified($file)) return true;
			// check etag header
			if ($options['etag'] && ($fileSize = NewRouterStatic::isFileChanged($file)) === false) return true;
			if (empty($fileSize)) $fileSize = filesize($file);
			
			header('Content-Length: ' .  $fileSize);
			header('Content-Type: ' . NewRouterStatic::mimeType($file));
			readfile($file);
		};
	}
	
	public static function isFileModified($file) {
		$fileModificationTime = gmdate('D, d M Y H:i:s', filemtime ($file)).' GMT';
		if (@$_SERVER['HTTP_IF_MODIFIED_SINCE'] === $fileModificationTime) {
			header('HTTP/1.1 304 Not Modified');
			return false;
		}
		
		header('Last-Modified: '. $fileModificationTime);
		return true;
	}
	
	public static function isFileChanged($file) {
		$fileSize = filesize($file);
		$etag = 'W/"' . $fileSize . '"';
		if (@$_SERVER['HTTP_IF_NONE_MATCH'] === $etag) {
			header('HTTP/1.1 304 Not Modified');
			return false;
		}
		header('ETag: ' . $etag);
		return $fileSize;
	}
	
	public static function mimeType($path) {
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		switch(strtolower($ext)) {
			case 'js' :
				return 'application/x-javascript';
			case 'json' :
				return 'application/json';
			case 'jpg' :
			case 'jpeg' :
			case 'jpe' :
				return 'image/jpg';
			case 'png' :
			case 'gif' :
			case 'bmp' :
			case 'tiff' :
				return 'image/'.strtolower($ext);
			case 'css' :
				return 'text/css';
			case 'xml' :
				return 'application/xml';
			case 'doc' :
			case 'docx' :
				return 'application/msword';
			case 'xls' :
			case 'xlt' :
			case 'xlm' :
			case 'xld' :
			case 'xla' :
			case 'xlc' :
			case 'xlw' :
			case 'xll' :
				return 'application/vnd.ms-excel';
			case 'ppt' :
			case 'pps' :
				return 'application/vnd.ms-powerpoint';
			case 'rtf' :
				return 'application/rtf';
			case 'pdf' :
				return 'application/pdf';
			case 'html' :
			case 'htm' :
			case 'php' :
				return 'text/html';
			case 'txt' :
				return 'text/plain';
			case 'mpeg' :
			case 'mpg' :
			case 'mpe' :
				return 'video/mpeg';
			case 'mp3' :
				return 'audio/mpeg3';
			case 'wav' :
				return 'audio/wav';
			case 'aiff' :
			case 'aif' :
				return 'audio/aiff';
			case 'avi' :
				return 'video/msvideo';
			case 'wmv' :
				return 'video/x-ms-wmv';
			case 'mov' :
				return 'video/quicktime';
			case 'zip' :
				return 'application/zip';
			case 'tar' :
				return 'application/x-tar';
			case 'swf' :
				return 'application/x-shockwave-flash';
			default :
				if(function_exists('mime_content_type')) {
					$ext = mime_content_type($path);
				}
				return 'unknown/' . trim($ext, '.');
		}
	}
}

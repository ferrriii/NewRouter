<?PHP
abstract class NewRouterCallBackType
{
    const lambda = 0;
    const router = 1;
    const classmethod = 2;
}

class NewRouterRoute {
	public $method;
	public $route;
	private $callBack;
	private $callBackType;
	private $_pattern = array();
	
	public static function fromRouteStr($route) {
		if (empty($route)) return new NewRouterRoute;
		
		$route = trim($route);
		$route = str_replace("//","/", $route);
		$routeIndex = strpos($route, "/");
		if ($routeIndex === false) {
			$method = $route;
			$route = '';			
		} else {
        
			$method = $routeIndex > 0 ? substr($route,0, $routeIndex-1) : '';
			$route = substr($route, $routeIndex);
		}
		$method = strtoupper(trim($method));
		
		$r = new NewRouterRoute;
		
		$r->method = $method;
		$r->route = $route;

		return $r;
	}
	
	public function setCallback($callBack) {
		$this->callBack = $callBack;
		$this->callBackType = NewRouterCallBackType::lambda;
		//get_class($callBack);
		if ($callBack instanceof NewRouter) {
			$this->callBackType = NewRouterCallBackType::router;
		}
	}
	
	public function run(&$request, $prefixPattern, $method, $path) {
		if ($this->callBackType === NewRouterCallBackType::lambda) {
			$func = $this->callBack;
			return $func($request);
		} else if ($this->callBackType === NewRouterCallBackType::router) {
			return $this->callBack->dispatch($method, $path, $prefixPattern);
		} else if ($this->callBackType === NewRouterCallBackType::classmethod) {
			// not implemented
		}
	}
	
	public function pattern($prefixPattern = '^') {
		if (array_key_exists($prefixPattern, $this->_pattern)) {
			return $this->_pattern[$prefixPattern];
		}
		
		$this->_pattern[$prefixPattern] = $this->routePattern($this->route, $prefixPattern);
		
		return $this->_pattern[$prefixPattern];
	}
	
	private function routePattern($route = '*', $prefixRoute = '^') {
		//translate route to regex
		if (empty($route)) {
			$route = '*';
		}
		
		$openEnd = false;
		if (substr($route,-1) === '*') {
			//remove trailing *: it's not needed at the end of regex
			$route = substr($route,0, strlen($route)-1);
			$openEnd = true;
		}
		$route = str_replace("/","\/", $route);
		$route = str_replace("*",".*", $route);
		$route = str_replace("+",".+", $route);
		$route = preg_replace('/:(.*?):/', '(?<${1}>[^\/]*?)', $route);
		
		$route = $prefixRoute . $route;
		// normalize // to /
		$route = str_replace('\/\/','\/', $route);
		
		return $route . ($openEnd ? '' : '$');
	}
}

class NewRouter {
	private $routes = array();

	private function routeFromArgument($firstArgument) {
		$type = gettype($firstArgument);
		if ($type === 'string') return $firstArgument;
		if ($type === 'object') return null;
		if ($type === 'NULL') return '';
		return false;
	}
	
	public function route() {
		$route = $this->routeFromArgument(func_get_arg(0));
		$funcIndex = 1;
		if ($route === null) {
			$funcIndex = 0; // route is not defined, all arguments are middleware functions
		} 

		$totalArgs = func_num_args();
		for ($i = $funcIndex; $i<$totalArgs; ++$i) {
			$r = NewRouterRoute::fromRouteStr($route);
			$r->setCallback(func_get_arg($i));
			
			$this->routes[] = $r;			
		}
	}	
	
	public function dispatch($method = null, $path = null, $prefixPattern = '^') {		
		if ($method === null) {
			$method = $_SERVER['REQUEST_METHOD'];
		}
		if ($path === null) {
			$path = $_SERVER['REQUEST_URI'];
		}
		
		return $this->execute($method, $path, $prefixPattern);
	}

	private function execute($method, $path, $prefixPattern = '^') {
		$routeFound = false;
		$req = new stdClass();
		$req->params = array();
		foreach ($this->routes as $route) {
			if (!empty($route->method) && $method !== $route->method) {
				continue; // method is not same as route method
			}
			$pattern = $route->pattern($prefixPattern);
			if (!preg_match('/' . $pattern . '/i', $path, $matches)) {
				continue;
			}
			$routeFound = true;
			$req->params = array_merge($req->params, $matches);
			// TODO: uncomment below line
			// $req->route = $route;
			
			$res = $route->run($req, $pattern, $method, $path);
			if ($res === NULL || $res === false) {
				break;
			}
		}
		
		return $routeFound;
	}
}

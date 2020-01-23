<?PHP
class NewRouterRoute {
	public $method;
	public $route;
	public $func;
	private $_pattern = [];
	
	public static function fromRouteStr($route) {
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
	
	public function pattern($prefixPattern = '^') {
		if (array_key_exists($prefixPattern, $this->_pattern)) {
			return $this->_pattern[$prefixPattern];
		}
		
		$this->_pattern[$prefixPattern] = $this->routePattern($this->route);
		
		return $this->_pattern[$prefixPattern];
	}
	
	private function routePattern($route = '*', $prefixRoute = '^') {
		//translate route to regex
		if ($route === '') {
			$route =  '*';
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
	private $routes = [];
	
	public function use($route, $callback) {
		$r = NewRouterRoute::fromRouteStr($route);
		$r->func = $callback;
		
		$this->routes[] = $r;
	}	
	
	public function dispatch($method = null, $path = null) {		
		if ($method === null) {
			$method = $_SERVER['REQUEST_METHOD'];
		}
		if ($path === null) {
			$path = $_SERVER['REQUEST_URI'];
		}
		
		return $this->execute($method, $path);
	}

	private function execute($method, $path, $prefixPattern = '^') {

		$routeFound = false;
		foreach ($this->routes as $route) {
			if (!empty($route->method) && $method !== $route->method) {
				continue;
			}
			if (!preg_match('/' . $route->pattern($prefixPattern) . '/i', $path)) {
				continue;
			}
			$routeFound = true;
			
			$res = ($route->func)();
			if ($res === NULL || $res === false) {
				break;
			}
		}
		
		return $routeFound;
	}
}

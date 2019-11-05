<?php
    namespace App\Components;
    use App\Controllers;
    use App\Components\Exceptions\RouterException;
    /**
     * Used to define controller and its action accordingly to client request.
     * @property array of strings $routes - existing routes to
     * controllers with their actions.
     */
    class Router
    {
        private $routes;
        public function __construct()
        {
            $routesPath = ROOT . 'src/config/routes.php';
            $this->routes = require $routesPath;
        }

        /**
         * @return string - client URI request (like "part1/part2").
         */
        private function getURI(): string
        {
            return trim($_SERVER['REQUEST_URI'], '/');
        }

        /**
         * Run an action of controller accordingly to client's request.
         * @return bool: true if route exist, false othervise.
         */
        public function run(): bool
        {
            $requestURI = $this->getURI();

            // Check if route exist.
            foreach ($this->routes as $uriPattern => $path) {
                if (preg_match("#$uriPattern#", $requestURI)) {
                    // Define which controller and its action to run.
                    $pathParts = explode('/', $path);
                    $controllerName = array_shift($pathParts) . 'Controller';
                    $controllerName = 'App\\Controllers\\' . ucfirst($controllerName);
                    $actionName = 'action' . ucfirst(array_shift($pathParts));
             
                    // Run action of controller.
                    $controllerPath = ROOT . 'src/Controllers/'
                        . $controllerName . '.php';
                    if (file_exists($controllerPath)) {
                        require_once $controllerPath;
                    }
                    $controller = new $controllerName;
                    // Rest parts of path transit into an action as parameters.
                    $actionResult = call_user_func_array(array($controller, $actionName), $pathParts);
                    if ($actionResult === false) {
                        throw new RouterException('Router did not call action.');
                    }
                    return true;
                }
            }
            return false;
        }
    }
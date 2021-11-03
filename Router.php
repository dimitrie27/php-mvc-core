<?php

namespace dimmvc\phpmvc;

use app\controllers\Controller;
use dimmvc\phpmvc\exception\NotFoundException;

class Router 
{   
    public Request $request;
    public Response $response;
    protected array $routes = [];
    protected array $params = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback, $params = [])
    {
        $this->routes['get'][$path] = $callback;
    }
    
    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function delete($path, $callback)
    {
        $this->routes['delete'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        $param = null;
        if ($callback === false) {
            // check routes with dynamic param: ONLY 1 PARAMETER!!           
            foreach (array_keys($this->routes[$method]) as $route) {
                if (strpos($route, '{$')) {
                    $paramKey = substr(
                        $route,
                        strpos($route, '{$'),
                        strpos($route, '}', strpos($route, '{$'))
                    );
                    if (str_contains($path, str_replace($paramKey, '', $route))) {
                        $callback = $this->routes[$method][$route];
                        $param = str_replace(str_replace($paramKey, '', $route), '', $path);                        
                    }
                }
            }   
            if (!$param) {
                $this->response->setStatusCode(404);
                throw new NotFoundException();
            }        
        }

        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        if (is_array($callback)) {
            /** @var Controller $controller */
            $controller = new $callback[0];
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = Application::$app->controller;

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
        }

        return call_user_func($callback, $this->request, $this->response, $param);
    }

}

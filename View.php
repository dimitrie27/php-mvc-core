<?php

namespace dimmvc\phpmvc;

class View
{
    public string $title = '';

    public function renderView($view, $params = [])
    {
        $viewModel = $this->renderOnlyView($view, $params);
        $viewContent = $this->prepareView($viewModel, $params) ?? $viewModel;        
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderOnlyView($view, $params) 
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }

    public function layoutContent() 
    {
        $layout = Application::$app->layout;
        if (Application::$app->controller) {
            $layout = Application::$app->controller->layout;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }

    public function prepareView($view, $params)
    {
        $viewContent = null;
        foreach ($params as $key => $value) {
            if (strpos($view, '{{ ' . $key . ' }}')) {
                $viewContent = str_replace('{{ ' . $key . ' }}', $value, $view);
            } elseif (strpos($view, '{{' . $key . '}}')) {
                $viewContent = str_replace('{{' . $key . '}}', $value, $view);
            }
        }
        
        return $viewContent;
    }
}
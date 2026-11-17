<?php
class Router
{
    public static function dispatch()
    {
        $route = (isset($_GET['r']) && is_string($_GET['r'])) ? trim($_GET['r'], '/') : '';
        if ($route === '') {
            redirect(Auth::check() ? 'dashboard/index' : 'auth/login');
        }

        $parts = explode('/', $route);
        $c = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $parts[0]));
        $a = isset($parts[1]) && $parts[1] !== '' ? $parts[1] : 'index';
        $a = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $a));

        $class = ucfirst($c) . 'Controller';
        if ($c === '' || !is_file(APP_PATH . '/controllers/' . $class . '.php')) {
            abort(404);
        }

        $controller = new $class();
        $controller->run($a);
    }
}

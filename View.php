<?php
class View
{
    public static function render($view, array $data = array(), $layout = 'main')
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require APP_PATH . '/views/' . $view . '.php';
        $content = ob_get_clean();
        if ($layout) {
            require APP_PATH . '/views/layouts/' . $layout . '.php';
        } else {
            echo $content;
        }
    }
}

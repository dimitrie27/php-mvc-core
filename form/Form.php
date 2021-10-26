<?php

namespace dimmvc\phpmvc\form;

use dimmvc\phpmvc\Model;

class Form 
{
    public static function begin($action = '/', $method = 'post')
    {
        echo sprintf('<form action="%s" method="%s">', $action, $method );
        return new Form();
    }

    public static function end()
    {
        echo '</form>';
    }

    public static function field(Model $model, $attribute)
    {
        return new InputField($model, $attribute);
    }

}
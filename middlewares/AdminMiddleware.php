<?php

namespace dimmvc\phpmvc\middlewares;

use dimmvc\phpmvc\Application;
use dimmvc\phpmvc\exception\ForbiddenException;

class AdminMiddleware extends BaseMiddleware
{
    public function execute()
    {
        if (!Application::isAdmin()) {
            throw new ForbiddenException();
        }
    }
}
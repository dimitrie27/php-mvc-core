<?php

namespace dimmvc\phpmvc\middlewares;

abstract class BaseMiddleware
{
    abstract public function execute();
}
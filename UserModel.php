<?php

namespace dim-mvc\phpmvc;

use dim-mvc\phpmvc\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}
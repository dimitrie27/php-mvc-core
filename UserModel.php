<?php

namespace dimmvc\phpmvc;

use dimmvc\phpmvc\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;

    abstract public function getRole(): string;

}
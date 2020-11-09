<?php
namespace aj\phpmvc;

use aj\phpmvc\db\DBModel;

abstract class UserModel extends DBModel
{

    abstract public function getDisplayName(): string;

}
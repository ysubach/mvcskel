<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUser extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('User');
    $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'primary' => true, 'autoincrement' => true));
    $this->hasColumn('username', 'string', 255, array('type' => 'string', 'length' => 255, 'default' => '', 'notnull' => true));
    $this->hasColumn('password', 'string', 255, array('type' => 'string', 'length' => 255, 'default' => '', 'notnull' => true));
    $this->hasColumn('roles', 'string', 255, array('type' => 'string', 'length' => 255, 'default' => '', 'notnull' => true));
    $this->hasColumn('email', 'string', 255, array('type' => 'string', 'length' => 255, 'default' => '', 'notnull' => true));
    $this->hasColumn('fname', 'string', 255, array('type' => 'string', 'length' => 255, 'default' => '', 'notnull' => true));
  }

}
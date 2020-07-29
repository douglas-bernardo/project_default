<?php

use Library\Database\Record;

class Users extends Record
{
    const TABLENAME = 'usuario';

    // public function get_grupo()
    // {
    //     return new PermissionGroup($this->id_group);
    // }

    // public function get_nome_grupo()
    // {
    //     return (new PermissionGroup($this->id_group))->name;
    // }
}
<?php
namespace gift\appli\core\domain\entities;

class User extends \Illuminate\Database\Eloquent\Model
{

    /**
     * déclarations des attributs
     */
    protected $table = 'user';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * Association vers Box 
     */
    public function box() {
        return $this->hasMany('gift\appli\core\domain\entities\User', 'id');
    } 
}

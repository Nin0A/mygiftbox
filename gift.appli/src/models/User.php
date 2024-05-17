<?php
namespace gift\appli\models;

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
        return $this->hasMany('gift\appli\models\User', 'id');
    } 
}

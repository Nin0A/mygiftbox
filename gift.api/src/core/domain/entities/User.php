<?php
namespace gift\api\core\domain\entities;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class User extends \Illuminate\Database\Eloquent\Model
{

    use HasUuids;

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
        return $this->hasMany('gift\api\core\domain\entities\User', 'id');
    } 
}

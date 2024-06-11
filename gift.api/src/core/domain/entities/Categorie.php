<?php
namespace gift\api\core\domain\entities;

class Categorie extends \Illuminate\Database\Eloquent\Model
{

    /**
    * déclarations des attributs
    */
    protected $table = 'categorie';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * Association vers Prestation
     */
    public function prestation() {
        return $this->hasMany('gift\api\core\domain\entities\Prestation', 'id');
        } 
}
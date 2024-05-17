<?php
namespace gift\appli\models;

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
        return $this->hasMany('gift\appli\models\Prestation', 'id');
        } 
}

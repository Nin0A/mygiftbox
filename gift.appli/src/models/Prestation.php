<?php
namespace gift\appli\models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Prestation extends \Illuminate\Database\Eloquent\Model
{

    use HasUuids;

    /**
     * déclarations des attributs
     */
    protected $table = 'prestation';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * Association vers Categorie
     */
    public function categorie() {
        return $this->belongsTo('gift\appli\models\Categorie', 'cat_id');
    }

    /**
     * Association vers Box par table Pivot box2presta
     */
    public function box() {
        return $this->belongsToMany('gift\appli\models\Box',
                                    'box2presta',
                                    'presta_id',
                                    'box_id')
                    ->withPivot( ['quantite'] );
    } 
}

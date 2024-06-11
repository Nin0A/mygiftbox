<?php
namespace gift\api\core\domain\entities;
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
        return $this->belongsTo('gift\api\core\domain\entities\Categorie', 'cat_id');
    }

    /**
     * Association vers Box par table Pivot box2presta
     */
    public function box() {
        return $this->belongsToMany('gift\api\core\domain\entities\Box',
                                    'box2presta',
                                    'presta_id',
                                    'box_id')
                    ->withPivot( ['quantite'] );
    } 
}

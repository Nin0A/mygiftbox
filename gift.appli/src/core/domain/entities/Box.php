<?php
namespace gift\appli\core\domain\entities;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Box extends \Illuminate\Database\Eloquent\Model
{

    use HasUuids;

    /**
     * déclarations des attributs
     */
    protected $table = 'box';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * Association vers User
     */
    public function user() {
        return $this->belongsTo('gift\appli\core\domain\entities\User', 'createur_id');
        } 
    
    /**
     * Association vers Prestation par table Pivot box2presta
     */
    public function prestation() {
        return $this->belongsToMany('gift\appli\core\domain\entities\Prestation',
                                    'box2presta',
                                    'box_id',
                                    'presta_id')
                    ->withPivot( ['quantite'] );
    } 
}

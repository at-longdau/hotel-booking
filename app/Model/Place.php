<?php
namespace App\Model;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
{
    use Sluggable, SoftDeletes;
    /**
     * Value paginate of row
     */
    const NUM_ROW = 10;
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
    */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    /**
     * Get hotels for place
     *
     * @return array
     */
    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }
    
     /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($place) {
            $place->hotels()->delete();
        });
    }
}

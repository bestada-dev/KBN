<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryModel extends Model
{
    protected $table = 'category';

    protected $fillable = [
    	'name', 'icon'
    ];


    public function getProperty()
    {
        return $this->hasMany(PropertyModel::class, 'category_id');
    }
}

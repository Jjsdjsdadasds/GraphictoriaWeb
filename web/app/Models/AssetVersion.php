<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetVersion extends Model
{
    use HasFactory;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'parentAsset',
		'localVersion',
		'contentURL'
	];
	
	public function asset()
    {
        return $this->belongsTo(Asset::class, 'parentAsset');
    }
}

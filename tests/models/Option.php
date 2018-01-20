<?php

use LittleApps\SerializableModel\Serializable;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
	use Serializable;
	
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'value'];
	protected $guarded = [];
    /**
	* The attributes that are serializable.
	* 
	* @var array
	* 
	*/
    protected $serializable = ['value'];
    
    protected $attributes = [
		'autoload' => false
	];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = [
        'title','description','price','image', 'created_by', 'created_at', 'updated_at', 'updated_by'
    ];

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = Str::lower($value);
    }

	/**
	* Get the user's full name.
	*
	* @return string
	*/
	public function getTitleAttribute($value)
	{
		return Str::ucfirst($value);
	}
}

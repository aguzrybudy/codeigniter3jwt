<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModel extends Eloquent{

	use SoftDeletes;

    protected $table = 'user';
    protected $dates = ['deleted_at'];

    protected $hidden = array('password', 'deleted_at');

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // public function orders()
	// {
	// 	return $this->hasMany('Order', 'user_id');
    // }
    
    // public function role(){
	// 	return $this->belongsTo('UserRole', 'role_id');
    // }
    
    // public function status(){
	// 	return $this->belongsTo('Status', 'status_id');
	// }
    
}

/* End of file  */
/* Location: ./application/models/ */
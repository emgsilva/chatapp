<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'password', 'full_name', 'address', 'status', 'registration_date'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id', 'password', 'registration_date', 'created_at', 'updated_at'];

    /**
     * Delete user and all the messages associated to the user
     *
     */
    public function delete()
    {
        // delete all related messages
        Message::where("dest_user", $this->username)->delete();

        // delete the user
        return parent::delete();
    }

    /**
     * Update the Messages table in case the "username" of the user is changed
     * - this has not been implemented in the API...
     *
     * @param $newusername
     */
    public function updateMessages($newusername)
    {

        Message::where('src_user', $this->username)
            ->update(array('src_user' => $newusername));

        Message::where('dest_user', $this->username)
            ->update(array('dest_user' => $newusername));
    }

}

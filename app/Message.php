<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['src_user', 'dest_user', 'message', 'status'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id', 'dest_user', 'updated_at'];

}
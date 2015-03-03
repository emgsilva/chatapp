<?php namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Exception;
use Request;
use Response;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Users Controller
    |--------------------------------------------------------------------------
    |
    | This controller processes handles all the activities that deal with user
    | resource operations: create user, get users, update user, delete user.
    |
    */

    /**
     * Creates a new user and stores it in the db.
     *
     */
    public function createUser()
    {

        $username = Request::get('username');

        try {

            // if it fails it throws an exception that we can capture!
            $user = User::where('username', $username)->first();

            // if the user already exists, throw a 403 - "Forbidden" request
            if ($user != null)
                return Response::json(array("message" => "User with username $username already exists"), 403);

            // create new user
            $userData['username'] = Request::get('username');
            $userData['password'] = Request::get('password');
            $userData['full_name'] = Request::get('full_name');
            $userData['address'] = Request::get('address');
            $userData['status'] = "online";
            $userData['registration_date'] = Carbon::now();

            User::create($userData);

        } catch (Exception $exception) {
            return Response::json(array("message" => "Server error"), 500);
        }

        return Response::json(array("message" => "User account created successfully"), 200);
    }


    /**
     * Get all the users registered in the system.
     *
     */
    public function getUsers()
    {

        try {

            // gets all the users' from DB
            $users = User::all();

        } catch (Exception $exception) {
            return Response::json(array("message" => "Server error"), 500);
        }

        return $users;
    }


    /**
     * Gets the user properties from DB.
     *
     * @param $username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUserProps($username)
    {

        try {

            // get the user data
            $user = User::where('username', $username)->first();

            // if the user does not exist, throw a 404 - "Resource not found"
            if ($user == null)
                return Response::json(array("message" => "User with username $username does not exist"), 404);

            // username
            $userData['username'] = $user->username;
            $userData['full_name'] = $user->full_name;
            $userData['address'] = $user->address;
            $userData['status'] = $user->status;

        } catch (Exception $exception) {
            return Response::json(array("message" => "Server error"), 500);
        }

        return $userData;
    }


    /**
     * Updates user properties on DB.
     *
     * @param $username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateUser($username)
    {

        try {

            // get user with username from DB
            $user = User::where('username', $username)->first();

            // if the user does not exist, throw a 404 - "Resource not found"
            if ($user == null)
                return Response::json(array("message" => "User with username $username does not exist"), 404);

            // update User
            if (Request::get('full_name') != null)
                $user->full_name = Request::get('full_name');
            if (Request::get('address') != null)
                $user->address = Request::get('address');
            if (Request::get('status') != null)
                $user->status = Request::get('status');

            // in case we would allow update of username
            // - simplification: in real app would need to improve the
            // users-messages relationships
            /*
            if (Request::get('newusername') != null) {
                $user->username = Request::get('newusername');
                // update messages from/to user
                $user->updateMessages(Request::get('newusername'), $user);
            }
            */

            // update user
            $user->update();

        } catch (Exception $exception) {
            return Response::json(array("message" => "Server error"), 500);
        }

        return Response::json(array("message" => "User updated successfully"), 200);
    }


    /**
     * Delete a user with the username.
     *
     * @param $username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteUser($username)
    {

        try {

            // get user with username from DB
            $user = User::where('username', $username)->first();

            // if the user does not exist, throw a 404 - "Resource not found"
            if ($user == null)
                return Response::json(array("message" => "User with username $username does not exist"), 404);

            // delete user (including deletion of all its messages)
            $user->delete();

        } catch (Exception $exception) {
            return Response::json(array("message" => "Server error"), 500);
        }

        return Response::json(array("message" => "User deleted successfully"), 200);
    }

}
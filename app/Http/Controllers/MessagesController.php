<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Message;
use App\User;
use Request;
use Response;

class MessagesController extends Controller
{

    /**
     * Retrieves the all the messages ("all" or "new") for a given user.
     *
     * @param $username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUserMessages($username)
    {

        $filter = Request::get('filter');

        try {

            // if to retrieve only "new" messages
            if ($filter == "new") {

                // go to DB and retrieve new messages (status=new)
                $messages = Message::where(['dest_user' => $username, 'status' => 'new'])->get()->latest();

            } else {

                // get all the messages destined for the user
                $messages = Message::where('dest_user', $username)->get();
            }

            // mark all "new" messages retrieved as "read"
            Message::where(['dest_user' => $username, 'status' => 'new'])->update(array('status' => "read"));

        } catch (Exception $exception) {
            return Response::json(array("message" => "Server error"), 500);
        }

        return Response::json($messages);
    }


    /**
     * Stores a new message for a given user.
     *
     * @param $username
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
    public function newMessage($username)
    {

        $message = Request::get('message');

        // if no message has been sent - send error
        if ($message == null)
            return Response::json(array("message" => "Message is missing"), 400);

        try {

            // check if sender and recipient exist
            // - user user
            $user = User::where('username', $username)->first();

            if ($user == null)
                return Response::json("User with username $username does not exist", 404);

            // - user recipient
            $recipient = User::where('username', Request::get('dest_user'))->first();

            if ($recipient == null)
                return Response::json("Recipient does not exist", 404);

            // create new message
            $messageData['message'] = $message;
            $messageData['src_user'] = $username;
            $messageData['dest_user'] = Request::get('dest_user');
            $messageData['status'] = "new";

            Message::create($messageData);


        } catch (Exception $exception) {
            return Response::json(array("message" => "Server error"), 500);
        }

        return Response::json(array("message" => "Message successfully sent"), 200);
    }


    /**
     * Gets the messages (all or new) of a user from a given contact.
     *
     * @param $username
     * @param $contact
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getMessagesFromContact($username, $contact)
    {

        // check if user and contact exist
        // - user sender
        $user = User::where('username', $username)->first();

        if ($user == null)
            return Response::json("User with username $username does not exist", 404);

        // - user recipient
        $recipient = User::where('username', $contact)->first();

        if ($recipient == null)
            return Response::json("Contact does not exist", 404);

        $filter = Request::get('filter');

        // if the user wants only the new messages
        if ($filter == 'new') {

            // get all "new" messages (status=new)
            $messages = Message::where(['dest_user' => $username, 'src_user' => $contact, 'status' => 'new'])->get();

        } else {

            // get all the user messages
            $messages = Message::where(['dest_user' => $username, 'src_user' => $contact])->get();
        }

        // mark all "new" messages as "read"
        Message::where(['dest_user' => $username, 'src_user' => $contact, 'status' => 'new'])->update(array('status' => "read"));

        return $messages;
    }

}

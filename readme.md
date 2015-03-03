# chatapp - a simple Chat service RESTful API

This is a simple Chat web service. It provides a RESTful API that exposes "users" and "messages" resources and operations, allowing to build different client applications / UIs that enable users to send messages to each other.

## Application Description

chatapp exposes a RESTful API with two resources:

1. users
2. messages

### Simplifications

1. anyone can get a list of all the users registered in the system (every one can talk with everyone - "a la IRC")
2. login has not been implemented
3. users cannot update their "username"
4. no data validation has been implemented

### Users operations - `/users`

1. `POST: /users` : create new user (using user resource representation)
    - input data: `{username, password, full_name, address}`
    - response/exceptions: 200 (OK) / 403 (User already exists) / 500 (Server error) 
2. `GET: /users` : get all registered users “representation” (username, and status)
    - response data: `Array{username, full_name, address, status}`
         - `status` - status of the user: “online” or “offline”
    - response/exceptions: 200 (OK) / 500 (Server error)
3. `GET: users/{username}` : get all user details
    - response data: `{username, full_name, address, status}`
    - response/exceptions: 200 (OK) / 404 (User does not exists) / 500 (Server error)
4. `PATCH: users/{username}` : update user (using PATCH - for partial update of resource)
    - input data: `{username, password,full_name, address}`
    - response/exceptions: 200 (OK) / 404 (User does not exists) / 500 (Server error)
5. `DELETE: users/{username}` : delete a given user from the system
    - response/exceptions: 200 (OK) / 404 (User does not exists) / 500 (Server error)

### Messages operations - `/users/{username}/messages`

1. `GET users/{username}/messages` : gets all the messages for a given user with username
    - response data: `Array{message, src_user, status, created_at}`
        - `src_user`: sender of the message
        - default situation, gets all messages
        - allow filtering:
            - `?filter=new` : get all new messages (status=new)
    - response/exceptions: 200 (OK) / 500 (Server error)
2. `POST: users/{username}/messages` : creates a new message
    - input data: `{message, dest_user}`
        - `dest_user` - username of the recipient of the message
    - response/exceptions: 200 (OK) / 400 (Message is missing) / 404 (User or Recipient does not exist) / 500 (Server error)
3. `GET: users/{username}/messages/{contact}` : gets all messages from a given contact
    - response data: `Array{message, src_user, status, created_at}`
        - `src_user`: sender of the message
        - `status`: status of the message: “new” or “read”
        - `created_at`: time when the message was sent
    - default situation, gets all messages
    - allow filtering:
        - `?filter=new` : get all new messages (status=new)
    - response/exceptions: 200 (OK) / 404 (User or Recipient does not exist) / 500 (Server error)

## Software Stack

chatapp is developed using the PHP MVC framework [Lavarel](http://laravel.com). For DB management it uses several Laravel features (including "migrations") and also the [Eloquent](http://laravel.com/docs/5.0/eloquent) ORM, which provides a ActiveRecord implementation.


## Setup instruction

In order to execute the application you must have Apache and PHP 5.5+ installed in your server machine, including the "mcrypt" plugin. Furthermore, you must install [Composer](https://getcomposer.org/) in order to build the application (download and install all the dependencies of the chatapp application).

Setup and Running application:

- `git clone https://github.com/emgsilva/chatapp`
- `cd chatapp`
- `composer install --dev`
- `php -S localhost:8888 -t public`

The application will run on: [http://localhost:8888](http://localhost:8888)

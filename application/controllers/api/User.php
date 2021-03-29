<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class User extends RestController {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function all_get()
    {
        // Users from a data store e.g. database
        $users = UserModel::all();

        if ( $users )
        {
            // Set the response and exit
            $this->response( $users, 200 );
        }
        else
        {
            // Set the response and exit
            $this->response( [
                'status' => false,
                'message' => 'No users were found'
            ], 404 );
        }
    }

    public function store_post()
    {
        // Users from a data store e.g. database
        $users = UserModel::all();

        if ( $users )
        {
            // Set the response and exit
            $this->response( $users, 200 );
        }
        else
        {
            // Set the response and exit
            $this->response( [
                'status' => false,
                'message' => 'No users were found'
            ], 404 );
        }
    }

    public function show_get($id)
    {
        try
        {
            $users = UserModel::findOrFail($id);

        }
        catch(ModelNotFoundException $e)
        {
             $this->response( [
                'status' => false,
                'message' =>'No users were found'
            ], 404);
        }

        $this->response($users, 200);
    }

    public function update_put($id)
    {
        $this->response( [
            'status' => true,
            'message' =>$this->put('name')
        ], 200);
    }

    public function destroy_delete($id)
    {
        try
        {
            $users = UserModel::findOrFail($id);

        }
        catch(ModelNotFoundException $e)
        {
             $this->response( [
                'status' => false,
                'message' =>'No users were found'
            ], 404);
        }

        $users->delete();
        $this->response( [
            'status' => true,
            'message' =>'User data deleted'
        ], 200);
    }

    
}
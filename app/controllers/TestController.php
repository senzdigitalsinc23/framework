<?php

namespace App\Controllers\Api;

use App\Models\User;

class TestController
{
    public function index()
    {
        echo json_encode(User::all());
    }

}

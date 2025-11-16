<?php
namespace App\Http\Controllers;

class TaskController extends Controller
{
    public function __call($method, $args)
    {
        abort(404);
    }
}

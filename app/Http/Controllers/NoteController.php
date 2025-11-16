<?php
namespace App\Http\Controllers;

class NoteController extends Controller
{
    public function __call($method, $args)
    {
        abort(404);
    }
}

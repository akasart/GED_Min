<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Project features removed — keep a placeholder to avoid autoload errors.
    public function __call($method, $args)
    {
        abort(404);
    }
}

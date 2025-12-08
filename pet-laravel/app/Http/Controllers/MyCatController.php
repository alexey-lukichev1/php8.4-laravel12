<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyCatController extends Controller
{
    public function index() {
        return 'My cat is Musya';
    }
}

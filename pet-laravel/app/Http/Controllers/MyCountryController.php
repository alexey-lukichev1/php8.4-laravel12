<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyCountryController extends Controller
{
    public function index() {
        return 'I live in Russia';
    }
}

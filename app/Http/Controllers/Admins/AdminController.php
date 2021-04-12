<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public $viewData = [];

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];

        return view('admins.index', $data);
    }
}

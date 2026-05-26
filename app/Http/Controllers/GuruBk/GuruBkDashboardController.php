<?php

namespace App\Http\Controllers\GuruBk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuruBkDashboardController extends Controller
{
    public function index()
    {
        return view('guru_bk.dashboard');
    }
}
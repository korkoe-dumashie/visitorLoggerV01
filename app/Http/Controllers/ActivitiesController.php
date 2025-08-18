<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    public function index(){
        return view('logs.index',[
            'logs'=>Activities::with('user')
            ->orderBy('created_at','desc')
            ->get(),
        ]);
    }
}

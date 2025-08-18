<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    //


    //show all departments
    public function index(){

        $departments = Department::get();
        return view('departments.index',compact('departments'));
    }


    //create a new department

    public function create(){
        return view('departments.create');
    }


    //store a new department

    public function store(){
        request()->validate([
            'name'=>'required',
        ]);


        Department::create([
            'name'=>request('name'),
        ]);

        Activities::log(
            action: 'Created New department.',
            description: 'New department called ' . request('name')
        );

        return redirect('/departments');
    }


    //delete a department

    
}

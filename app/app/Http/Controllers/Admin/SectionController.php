<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'form_id'=>'required',
            'name'=>'required',
            'step_number'=>'required'
        ]);

        Section::create($request->all());

        return back()->with('success','Section added');
    }
}

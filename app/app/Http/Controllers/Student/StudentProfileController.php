<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Activitylog\Models\Activity;
use Auth;
use Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;

class StudentProfileController extends Controller
{

      /**
     * Show the form for editing the specified resource.
     */
    public function edit($user)
    {
        $user = User::find($user);
        $role = $user->getRoleNames()[0] ?? "";
        $roles = ["name" => 'Student'];
        return view('admin.users.stud-edit', compact('user', 'role', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user)
    {
        $user = User::find($user);
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email,' . $user->id,
            'mobile' => 'required|unique:users,mobile,' . $user->id,
            'gender' => 'required',
            'address' => 'required',
        ]);

        $data = $request->all();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if (!empty($data['profile_pic'])) {
            $filename = 'user_' . time() . '.' . $request->profile_pic->getClientOriginalExtension();
            $data['profile_pic'] = $filename;
            $request->profile_pic->storeAs('profile', $filename, 'public');
        }

        $data['updated_by'] = auth()->id();

        $user->update($data);

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties(['name' => $user->name, 'email' => $user->email])
            ->log('Student updated successfully');

        return redirect()->back()->with('warning', 'Student updated successfully.');
    }


}

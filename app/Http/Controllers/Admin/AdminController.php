<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAdminProfileRequest;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        return view('admin.home');
    }

    public function adminProfile() {
       return view('admin.profile.index');
    }

    public function editAdminProfile() {
        $user = auth()->user();
        return view('admin.profile.edit', compact('user'));
    }

    public function updateAdminProfile(UpdateAdminProfileRequest $request, $id) {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = $request->password == null ? $user->password : Hash::make($request->password);

        $user->update();

        return redirect()->route('admin-profile')->with('success', 'Your Profile is updated successfully!');
    }
}

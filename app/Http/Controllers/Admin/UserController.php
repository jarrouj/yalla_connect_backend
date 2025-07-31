<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
      public function show_user()
    {
        $user = user::latest()->paginate(10);

        return view('admin.user.show_users', compact('user'));
    }

    public function update_user($id)
    {
        $user = user::find($id);

        return view('admin.user.update_user', compact('user'));
    }

    public function update_user_confirm(Request $request, $id)
    {
        $user = user::find($id);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->usertype = $request->usertype;
        $user->balance = $request->balance;

        $user->save();

        return redirect('/admin/show_user')->with('message', 'User Updated');
    }

    public function delete_user($id)
    {
        $user = user::find($id);

        $user->delete();

        return redirect()->back()->with('message', 'User Deleted');
    }

    public function search_user(Request $request)
    {
        $query = $request->get('query');

        $user = User::where('first_name', 'like', '%' . $query . '%')
            ->orWhere('last_name', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->orWhere('phone', 'like', '%' . $query . '%') ->get();

        return response()->json($user);
    }
}

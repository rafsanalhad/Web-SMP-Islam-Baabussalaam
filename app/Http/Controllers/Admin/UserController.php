<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|max:50|unique:tb_users,username',
            'fullname' => 'required|max:100',
            'email' => 'required|email|max:100|unique:tb_users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,editor',
            'status' => 'required|in:active,inactive'
        ]);

        User::create([
            'username' => $validated['username'],
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status']
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat mengubah akun sendiri!');
        }

        $validated = $request->validate([
            'fullname' => 'required|max:100',
            'email' => 'required|email|max:100|unique:tb_users,email,' . $id,
            'role' => 'required|in:admin,editor',
            'status' => 'required|in:active,inactive'
        ]);

        $user->update([
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status']
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}

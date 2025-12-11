<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::orderByRaw("CASE category WHEN 'principal' THEN 1 WHEN 'teacher' THEN 2 WHEN 'staff' THEN 3 END, name ASC")->get();
        $count_principal = Teacher::where('category', 'principal')->count();
        $count_teacher = Teacher::where('category', 'teacher')->count();
        $count_staff = Teacher::where('category', 'staff')->count();
        $count_total = Teacher::count();
        return view('admin.guru.index', compact('teachers', 'count_principal', 'count_teacher', 'count_staff', 'count_total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'position' => 'required|max:100',
            'qualifications' => 'nullable',
            'experience' => 'nullable',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'required|in:principal,teacher,staff',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/teachers'), $filename);
            $validated['photo'] = $filename;
        }

        $teacher = Teacher::create($validated);

        ActivityLog::logActivity(
            Auth::id(),
            'create',
            'Menambahkan guru/staff: ' . $teacher->name,
            'teachers',
            $teacher->id
        );

        return redirect()->route('admin.guru.index')->with('success', 'Guru/Staff berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:100',
            'position' => 'required|max:100',
            'qualifications' => 'nullable',
            'experience' => 'nullable',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'required|in:principal,teacher,staff',
        ]);

        if ($request->hasFile('photo')) {
            if ($teacher->photo && file_exists(public_path('assets/img/teachers/' . $teacher->photo))) {
                unlink(public_path('assets/img/teachers/' . $teacher->photo));
            }

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/teachers'), $filename);
            $validated['photo'] = $filename;
        }

        $teacher->update($validated);

        ActivityLog::logActivity(
            Auth::id(),
            'update',
            'Memperbarui profil guru: ' . $teacher->name,
            'teachers',
            $teacher->id
        );

        return redirect()->route('admin.guru.index')->with('success', 'Guru/Staff berhasil diperbarui');
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);

        if ($teacher->photo && file_exists(public_path('assets/img/teachers/' . $teacher->photo))) {
            unlink(public_path('assets/img/teachers/' . $teacher->photo));
        }

        $name = $teacher->name;
        $teacher->delete();

        ActivityLog::logActivity(
            Auth::id(),
            'delete',
            'Menghapus guru/staff: ' . $name,
            'teachers',
            $id
        );

        return redirect()->route('admin.guru.index')->with('success', 'Guru/Staff berhasil dihapus');
    }
}

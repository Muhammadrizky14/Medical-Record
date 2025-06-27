<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('user')->paginate(10);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'specialization' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'doctor',
            'specialization' => $request->specialization,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Create doctor record
        Doctor::create([
            'name' => $request->name,
            'specialization' => $request->specialization,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'user_id' => $user->id,
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor created successfully');
    }

    public function show(Doctor $doctor)
    {
        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $doctor->user_id,
            'specialization' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $doctor->update($request->only(['name', 'specialization', 'phone', 'email', 'address']));
        
        if ($doctor->user) {
            $doctor->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'specialization' => $request->specialization,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        }

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully');
    }

    public function destroy(Doctor $doctor)
    {
        if ($doctor->user) {
            $doctor->user->delete();
        }
        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully');
    }
}

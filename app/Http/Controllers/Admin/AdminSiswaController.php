<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminSiswaController extends Controller
{
    public function index(Request $request)
    {
        $classes = Classes::orderBy('name')->get();
        $query = User::role('siswa')->with('studentClass');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->input('class_id'));
        }

        $siswaList = $query->orderBy('name')->get();

        return view('admin.siswa.index', compact('siswaList', 'classes'));
    }

    public function create()
    {
        $siswa = new User();
        $classes = Classes::orderBy('name')->get();
        return view('admin.siswa.form', compact('siswa', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nis' => 'required|string|max:50|unique:users,nis',
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:L,P',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'class_id.required' => 'Kelas wajib dipilih.',
            'class_id.exists' => 'Kelas tidak valid.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah digunakan.',
        ]);

        $siswa = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nis' => $request->nis,
            'class_id' => $request->class_id,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
        ]);

        $siswa->assignRole('siswa');

        return redirect()->route('admin.siswa.index')->with('success', 'Akun Siswa berhasil didaftarkan.');
    }

    public function edit($id)
    {
        $siswa = User::role('siswa')->findOrFail($id);
        $classes = Classes::orderBy('name')->get();
        return view('admin.siswa.form', compact('siswa', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $siswa = User::role('siswa')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($siswa->id)],
            'nis' => ['required', 'string', 'max:50', Rule::unique('users', 'nis')->ignore($siswa->id)],
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:L,P',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'class_id.required' => 'Kelas wajib dipilih.',
            'class_id.exists' => 'Kelas tidak valid.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah digunakan.',
        ]);

        $siswa->name = $request->name;
        $siswa->email = $request->email;
        $siswa->nis = $request->nis;
        $siswa->class_id = $request->class_id;
        $siswa->gender = $request->gender;

        if ($request->filled('password')) {
            $siswa->password = Hash::make($request->password);
        }

        $siswa->save();

        return redirect()->route('admin.siswa.index')->with('success', 'Data Siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $siswa = User::role('siswa')->findOrFail($id);
        
        // Hapus juga riwayat kuesioner terkait siswa ini secara aman
        foreach ($siswa->questionnaireResponses as $response) {
            $response->sdqAnswers()->delete();
            $response->psc17Answers()->delete();
            $response->sassvAnswers()->delete();
            $response->riskClassification()->delete();
            $response->delete();
        }

        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Akun Siswa berhasil dihapus.');
    }
}

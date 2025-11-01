<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// JANGAN LUPA IMPORT INI

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dataUser'] = User::all();        // GANTI: Pelanggan -> User
        return view('admin.user.index', $data); // GANTI: pelanggan -> user
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create'); // GANTI: pelanggan -> user
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ];

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
        // // dd($request->all());
        // $data['name'] = $request->name; // GANTI: first_name -> name
        // $data['email'] = $request->email;
        // $data['password'] = Hash::make($request->password); // TAMBAH: enkripsi password

        // // HAPUS: field yang tidak ada di User
        // // $data['first_name'] = $request->first_name;
        // // $data['last_name']  = $request->last_name;
        // // $data['birthday']   = $request->birthday;
        // // $data['gender']     = $request->gender;
        // // $data['phone']      = $request->phone;

        // User::create($data); // GANTI: Pelanggan -> User

        // return redirect()->route('users.index')->with('success', 'Penambahan Data Berhasil!'); // GANTI: pelanggan -> users
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['dataUser'] = User::findOrFail($id); // GANTI: Pelanggan -> User
        return view('admin.user.edit', $data);     // GANTI: pelanggan -> user
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $user_id = $id;                        // GANTI: pelanggan_id -> user_id
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name  = $request->name; // GANTI: first_name -> name
        $user->email = $request->email;

        // TAMBAH: update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // HAPUS: field yang tidak ada di User
        // $user->first_name = $request->first_name;
        // $user->last_name  = $request->last_name;
        // $user->birthday   = $request->birthday;
        // $user->gender     = $request->gender;
        // $user->phone      = $request->phone;

        $user->save();                                                                        // GANTI: $pelanggan -> $user
        return redirect()->route('users.index')->with('success', 'Perubahan Data Berhasil!'); // GANTI: pelanggan -> users
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // OPSIONAL: tambahkan function destroy jika perlu
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Data berhasil dihapus!');
    }
}

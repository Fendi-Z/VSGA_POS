<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;

class UserController extends Controller
{
    public function index() 
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar User yang terdaftar dalam sistem'
        ];

        $level = LevelModel::all();

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level]);
    }

    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')->with('level');

        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }
        
        return DataTables::of($users)->addIndexColumn()->addColumn('aksi', function ($user) {  
            $btn = '<a href="'.url('/user/' . $user->user_id). '" class="btn btn-info btn-sm mx-1">Detail</a>';
            $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit') . '"class="btn btn-warning btn-sm  mx-1">Edit</a>';
            $btn .= '<form class="d-inline-block" method="POST" action="' . url('/user/'.$user->user_id).'">' . csrf_field() . method_field('DELETE') . 
                        '<button type="submit" class="btn btn-danger btn-sm mx-1" onclick="return confirm(\'Apakah Anda yakin menghapus data ini ?\');">Hapus</button>
                    </form>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function create() 
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah User Baru'
        ];

        $level = LevelModel::all();

        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer'
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data User berhasil disimpan');
    }

    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail User'
        ];

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user]);
    }

    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home',  'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit User'
        ];

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama' => 'required|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'required|integer'
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password,
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('success', "Data User berhasil diubah");
    }

    public function destroy(string $id)
    {
        $check = UserModel::find($id);
        if (!$check) {
            return redirect('/user')->with('error', 'Data User tidak ditemukan');
        }
        
        try {
            UserModel::destroy($id);
            return redirect('/user')->with('success', 'Data User berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/user')->with('error', 'Data User gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
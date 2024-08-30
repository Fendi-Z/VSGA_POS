<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    
    public function index() 
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Stok',
            'list' => ['Home', 'Stok']
        ];

        $page = (object) [
            'title' => 'Daftar Stok yang terdaftar dalam sistem'
        ];

        $barang = BarangModel::all();
        $user = UserModel::all();

        return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'user' => $user]);
    }

    public function list(Request $request)
    {
        $stok = StokModel::select('stok_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')->with(['barang', 'user']);

        return DataTables::of($stok)->addIndexColumn()->addColumn('aksi', function ($stok) {
            $btn = '<a href="' . url('/stok/' . $stok->stok_id) . '" class="btn btn-info btn-sm mx-1">Detail</a>';
            $btn .= '<a href="' . url('/stok/' . $stok->stok_id . '/edit') . '" class="btn btn-warning btn-sm mx-1">Edit</a>';
            $btn .= '<form class="d-inline-block" method="POST" action="' . url('/stok/' . $stok->stok_id) . '">' . csrf_field() . method_field('DELETE') .
                        '<button type="submit" class="btn btn-danger btn-sm mx-1" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>
                    </form>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }
    
    // public function list(Request $request)
    // {
    //     $stoks = StokModel::select('stok_id', 'stok_tanggal', 'stok_jumlah', 'harga_beli', 'harga_jual', 'kategori_id')->with('kategori');

    //     if ($request->kategori_id) {
    //         $stoks->where('kategori_id', $request->kategori_id);
    //     }
        
    //     return DataTables::of($stoks)->addIndexColumn()->addColumn('aksi', function ($stok) {  
    //         $btn = '<a href="'.url('/stok/' . $stok->stok_id). '" class="btn btn-info btn-sm mx-1">Detail</a>';
    //         $btn .= '<a href="'.url('/stok/' . $stok->stok_id . '/edit') . '"class="btn btn-warning btn-sm mx-1">Edit</a>';
    //         $btn .= '<form class="d-inline-block" method="POST" action="' . url('/stok/'.$stok->stok_id).'">' . csrf_field() . method_field('DELETE') . 
    //                     '<button type="submit" class="btn btn-danger btn-sm mx-1" onclick="return confirm(\'Apakah Anda yakin menghapus data ini ?\');">Hapus</button>
    //                 </form>';
    //         return $btn;
    //     })->rawColumns(['aksi'])->make(true);
    // }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Stok',
            'list' => ['Home', 'Stok', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Stok Baru'
        ];

        $barang = BarangModel::all();
        $user = UserModel::all();

        return view('stok.create', compact('breadcrumb', 'page', 'barang', 'user'));
        // return view('stok.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'user' => $user]);
    }
    
    // public function create() 
    // {
    //     $breadcrumb = (object) [
    //         'title' => 'Daftar Stok',
    //         'list' => ['Home', 'Stok', 'Tambah']
    //     ];

    //     $page = (object) [
    //         'title' => 'Tambah Stok Baru'
    //     ];

    //     $kategori = KategoriModel::all();

    //     return view('stok.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori]);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:m_barang,barang_id',
            'user_id' => 'required|exists:m_user,user_id',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:1'
        ]);

        StokModel::create($request->all());

        return redirect('/stok')->with('success', 'Data Stok berhasil disimpan');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'stok_tanggal' => 'required|dateTime|min:3|unique:m_barang,stok_tanggal',
    //         'stok_jumlah' => 'required|integer|max:100',
    //         'harga_beli' => 'required|integer',
    //         'harga_jual' => 'required|integer',
    //         'kategori_id' => 'required|integer'
    //     ]);

    //     StokModel::create([
    //         'stok_tanggal' => $request->stok_tanggal,
    //         'stok_jumlah' => $request->stok_jumlah,
    //         'harga_beli' => $request->harga_beli,
    //         'harga_jual' => $request->harga_jual,
    //         'kategori_id' => $request->kategori_id
    //     ]);

    //     return redirect('/stok')->with('success', 'Data Stok berhasil disimpan');
    // }

    public function show($id)
    {
        $stok = StokModel::with(['barang', 'user'])->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Stok',
            'list' => ['Home', 'Stok', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Stok Barang'
        ];

        return view('stok.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stok' => $stok]);
    }
    
    // public function show(string $id)
    // {
    //     $stok = StokModel::with('kategori')->find($id);

    //     $breadcrumb = (object) [
    //         'title' => 'Daftar Stok',
    //         'list' => ['Home', 'Stok', 'Detail']
    //     ];

    //     $page = (object) [
    //         'title' => 'Detail Stok'
    //     ];

    //     return view('stok.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stok' => $stok]);
    // }

    public function edit($id)
    {
        $stok = StokModel::find($id);
        $barang = BarangModel::all();
        $user = UserModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Stok',
            'list' => ['Home', 'Stok', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Stok'
        ];

        return view('stok.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stok' => $stok, 'barang' => $barang, 'user' => $user]);
    }

    // public function edit(string $id)
    // {
    //     $stok = StokModel::find($id);
    //     $kategori = KategoriModel::all();

    //     $breadcrumb = (object) [
    //         'title' => 'Edit Stok',
    //         'list' => ['Home',  'Stok', 'Edit']
    //     ];

    //     $page = (object) [
    //         'title' => 'Edit Stok'
    //     ];

    //     return view('stok.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stok' => $stok, 'kategori' => $kategori]);
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:m_barang,barang_id',
            'user_id' => 'required|exists:m_user,user_id',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:1'
        ]);

        StokModel::find($id)->update($request->all());

        return redirect('/stok')->with('success', 'Data Stok berhasil diubah');
    }

    // public function update(Request $request, string $id)
    // {
    //     $request->validate([
    //         'stok_tanggal' => 'required|dateTime|min:3|unique:m_barang,stok_tanggal,' . $id . ',stok_id',
    //         'stok_jumlah' => 'required|integer|max:100',
    //         'harga_beli' => 'required|integer',
    //         'harga_jual' => 'required|integer',
    //         'kategori_id' => 'required|integer'
    //         'kategori_id' => 'required|integer'
    //     ]);

    //     StokModel::find($id)->update([
    //         'stok_tanggal' => $request->stok_tanggal,
    //         'stok_jumlah' => $request->stok_jumlah,
    //         'harga_beli' => $request->harga_beli,
    //         'harga_jual' => $request->harga_jual,
    //         'kategori_id' => $request->kategori_id,
    //     ]);

    //     return redirect('/stok')->with('success', "Data Stok berhasil diubah");
    // }

    public function destroy($id)
    {
        $check = StokModel::find($id);
        if (!$check) {
            return redirect('/stok')->with('error', 'Data Stok tidak ditemukan');
        }
        // $stok = StokModel::findOrFail($id);

        try {
            StokModel::destroy($id);
            // $stok->delete();
            return redirect('/stok')->with('success', 'Data Stok berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/stok')->with('error', 'Data Stok gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    // public function destroy(string $id)
    // {
    //     $check = StokModel::find($id);
    //     if (!$check) {
    //         return redirect('/stok')->with('error', 'Data Stok tidak ditemukan');
    //     }
        
    //     try {
    //         StokModel::destroy($id);
    //         return redirect('/stok')->with('success', 'Data Stok berhasil dihapus');
    //     } catch (\Illuminate\Database\QueryException $e) {
    //         return redirect('/stok')->with('error', 'Data Stok gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    //     }
    // }
}

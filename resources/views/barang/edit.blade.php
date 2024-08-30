@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($barang)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i>Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan
                </div>
                <a href="{{ url('barang') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form action="{{ url('/barang/' . $barang->barang_id) }}" method="post" class="form-horizontal">
                    @csrf
                    {!! method_field('PUT') !!}
                    <div class="form-group row">
                        <label for="kategori_id" class="col-1 control-label col-form-label">Kategori</label>
                        <div class="col-11">
                            <select name="kategori_id" id="kategori_id" class="form-control">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->kategori_id }}" @if ($item->kategori_id == $barang->kategori_id) selected @endif>
                                        {{ $item->kategori_nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="barang_kode" class="col-1 control-label col-form-label">Kode</label>
                        <div class="col-11">
                            <input type="text" name="barang_kode" id="barang_kode" class="form-control" value="{{ old('barang_kode', $barang->barang_kode) }}" required>
                            @error('barang_kode')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="barang_nama" class="col-1 control-label col-form-label">Nama</label>
                        <div class="col-11">
                            <input type="text" name="barang_nama" id="barang_nama" class="form-control" value="{{ old('barang_nama', $barang->barang_nama) }}" required>
                            @error('barang_nama')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_beli" class="col-1 control-label col-form-label">Harga Beli</label>
                        <div class="col-11">
                            <input type="text" name="harga_beli" id="harga_beli" class="form-control" value="{{ old('harga_beli', $barang->harga_beli) }}" required>
                            @error('harga_beli')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_jual" class="col-1 control-label col-form-label">Harga Jual</label>
                        <div class="col-11">
                            <input type="text" name="harga_jual" id="harga_jual" class="form-control" value="{{ old('harga_jual', $barang->harga_jual) }}" required>
                            @error('harga_jual')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-11 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <a href="{{ url('barang') }}" class="btn btn-default ml-1">Kembali</a>
                        </div>
                    </div>
                </form>
            @endempty
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush
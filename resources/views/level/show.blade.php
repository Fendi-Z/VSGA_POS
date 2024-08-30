@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($level)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i>Kesalahan!!!</h5>
                    Data yang Snda cari tidak ditemukan
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th class="col-1">ID</th>
                        <td class="col-11">{{ $level->level_id }}</td>
                    </tr>
                    <tr>
                        <th class="col-1">Kode</th>
                        <td class="col-11">{{ $level->level_kode }}</td>
                    </tr>
                    <tr>
                        <th class="col-1">Nama</th>
                        <td class="col-11">{{ $level->level_nama }}</td>
                    </tr>
                </table>
            @endempty
            <div class="d-flex justify-content-center">
                <a href="{{ url('level') }}" class="btn btn-sm btn-default mt-3">Kembali</a>
            </div>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush
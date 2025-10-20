@extends('layouts.master')
@section('title') Master Obat @endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Master Obat @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Daftar Master Obat</h4>
                <div class="float-end">
                    <a href="{{ route('master-obat.create') }}" class="btn btn-success btn-sm"><i class="ri-add-line align-bottom me-1"></i> Tambah Obat Baru</a>
                </div>
            </div><div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Kode Obat</th>
                                <th scope="col">Nama Obat</th>
                                <th scope="col">Satuan</th>
                                <th scope="col">Total Stok</th> <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($obats as $obat)
                                <tr>
                                    <td>{{ $obat->id }}</td>
                                    <td>{{ $obat->kode_obat }}</td>
                                    <td>{{ $obat->nama_obat }}</td>
                                    <td>{{ $obat->satuan_terkecil }}</td>
                                    {{-- Menampilkan nilai dari accessor getTotalStockAttribute() --}}
                                    <td class="fw-bold text-end">
                                        {{ number_format($obat->total_stock, 0) }} 
                                    </td> 
                                    <td>
                                        <span class="badge {{ $obat->is_aktif ? 'bg-success' : 'bg-danger' }}">{{ $obat->is_aktif ? 'Aktif' : 'Non-Aktif' }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('master-obat.edit', $obat->id) }}" class="btn btn-sm btn-primary"><i class="ri-pencil-fill align-bottom"></i> Edit</a>
                                        
                                        <form action="{{ route('master-obat.destroy', $obat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat ini? Menghapus obat dengan riwayat transaksi TIDAK diizinkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="ri-delete-bin-fill align-bottom"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $obats->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
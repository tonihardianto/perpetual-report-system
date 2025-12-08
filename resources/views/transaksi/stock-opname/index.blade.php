@extends('layouts.master')
@section('title') Riwayat Input Sisa Stock @endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') Transaksi @endslot
    @slot('title') Riwayat Input Sisa Stock (SO) @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <h4 class="card-title flex-grow-1">Daftar Periode Input Sisa Stock</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('transaksi.stock-opname.create') }}" class="btn btn-success"><i class="ri-add-line align-bottom me-1"></i> Lakukan SO Baru</a>
                    </div>
                </div>
                
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Periode SO</th>
                                <th>Status</th>
                                <th>Tanggal Proses</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatSO as $header)
                                <tr class="clickable-row" data-href="{{ route('transaksi.stock-opname.show', $header->id) }}" style="cursor: pointer;">
                                    <td><span class="fw-medium">{{ \Carbon\Carbon::create($header->tahun, $header->bulan)->translatedFormat('F Y') }}</span></td>
                                    <td>
                                        @if($header->dynamic_status == 'Selesai Penuh')
                                            <span class="badge bg-success">{{ $header->dynamic_status }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ $header->dynamic_status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $header->tanggal_so_dilakukan ? $header->tanggal_so_dilakukan->format('d M Y, H:i') : '-' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('transaksi.stock-opname.show', $header->id) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada riwayat Input Sisa Stock.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $riwayatSO->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rows = document.querySelectorAll(".clickable-row");
        rows.forEach(row => {
            row.addEventListener("click", () => {
                window.location.href = row.dataset.href;
            });
        });
    });
</script>
@endsection
<table class="table table-bordered table-nowrap text-center align-middle"
    style="
        font-size: 11px;
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #000;
    ">
    <thead>
        {{-- HEADER BARIS 1 --}}
        <tr>
            <th rowspan="3" style="background:#ddebf7; border:1px solid #000;">Nama Obat</th>
            <th rowspan="3" style="background:#ddebf7; border:1px solid #000;">No. Batch</th>
            <th rowspan="3" style="background:#ddebf7; border:1px solid #000;">Tgl ED</th>
            <th rowspan="3" style="background:#ddebf7; border:1px solid #000;">Tgl Masuk</th>
            <th rowspan="3" style="background:#ddebf7; border:1px solid #000;">HPP Satuan</th>
            <th colspan="2" style="background:#b4c7e7; border:1px solid #000;">Saldo Awal {{ $tahun }}</th>

            @for ($m = 1; $m <= 12; $m++)
                <th colspan="6" style="background:#9dc3e6; border:1px solid #000;">
                    {{ \Carbon\Carbon::createFromDate($tahun, $m, 1)->translatedFormat('F') }}
                </th>
            @endfor
        </tr>

        {{-- HEADER BARIS 2 --}}
        <tr>
            <th rowspan="2" style="background:#b4c7e7; border:1px solid #000;">Qty</th>
            <th rowspan="2" style="background:#b4c7e7; border:1px solid #000;">Value</th>

            @for ($m = 1; $m <= 12; $m++)
                <th colspan="2" style="background:#c6efce; border:1px solid #000;">Masuk (Beli)</th>
                <th colspan="2" style="background:#fce4d6; border:1px solid #000;">Keluar (Pakai)</th>
                <th colspan="2" style="background:#fff2cc; border:1px solid #000;">Sisa Stok</th>
            @endfor
        </tr>

        {{-- HEADER BARIS 3 --}}
        <tr>
            @for ($m = 1; $m <= 12; $m++)
                <th style="background:#e2f0d9; border:1px solid #000;">Qty</th>
                <th style="background:#e2f0d9; border:1px solid #000;">Value</th>

                <th style="background:#f8cbad; border:1px solid #000;">Qty</th>
                <th style="background:#f8cbad; border:1px solid #000;">Value</th>

                <th style="background:#fff2cc; border:1px solid #000;">Qty</th>
                <th style="background:#fff2cc; border:1px solid #000;">Value</th>
            @endfor
        </tr>
    </thead>

    <tbody>
        @foreach ($reportData as $data)
            <tr>
                <td style="border:1px solid #000;">{{ $data['obat_nama'] }}</td>
                <td style="border:1px solid #000;">{{ $data['batch_no'] }}</td>
                <td style="border:1px solid #000;">{{ \Carbon\Carbon::parse($data['ed'])->format('d-m-Y') }}</td>
                <td style="border:1px solid #000;">{{ \Carbon\Carbon::parse($data['tanggal_masuk'])->format('d-m-Y') }}</td>
                <td style="border:1px solid #000;" class="text-end">{{ number_format($data['hpp_unit'], 2) }}</td>

                {{-- Saldo Awal --}}
                <td style="border:1px solid #000;" class="text-end">{{ $data['saldo_awal_qty'] }}</td>
                <td style="border:1px solid #000;" class="text-end">{{ $data['saldo_awal_value'] }}</td>

                {{-- Mutasi Bulanan --}}
                @for ($m = 1; $m <= 12; $m++)
                    @php 
                        $saldo_akhir_bulan_sebelumnya = $data['months'][$m-1]['saldo_akhir_qty'] ?? $data['saldo_awal_qty']; 
                        $mutasi = $data['months'][$m] ?? null; 
                    @endphp
                    @if ($mutasi)
                        {{-- Masuk --}}
                        <td style="border:1px solid #000;" class="text-end">{{ number_format($mutasi['masuk_qty'], 0) }}</td>
                        <td style="border:1px solid #000;" class="text-end">{{ number_format($mutasi['masuk_value'], 2) }}</td>

                        {{-- Keluar --}}
                        <td style="border:1px solid #000;" class="text-end">{{ number_format($mutasi['keluar_qty'], 0) }}</td>
                        <td style="border:1px solid #000;" class="text-end">{{ number_format($mutasi['keluar_value'], 2) }}</td>

                        {{-- Sisa Stok --}}
                        <td style="border:1px solid #000;" class="text-end">{{ number_format($mutasi['penyesuaian_qty'], 0) }}</td>
                        <td style="border:1px solid #000;" class="text-end">{{ number_format($mutasi['penyesuaian_value'], 2) }}</td>
                    @else
                        <td style="border:1px solid #000;" class="text-end">0</td>
                        <td style="border:1px solid #000;" class="text-end">0.00</td>
                        <td style="border:1px solid #000;" class="text-end">0</td>
                        <td style="border:1px solid #000;" class="text-end">0.00</td>
                        <td style="border:1px solid #000;" class="text-end">0</td>
                        <td style="border:1px solid #000;" class="text-end">0.00</td>
                    @endif
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>

@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <!-- Navigasi Tab -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="transaksi-tab" data-bs-toggle="tab" data-bs-target="#transaksi"
                    type="button" role="tab" aria-controls="transaksi" aria-selected="true">
                    Laporan Transaksi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="data-obat-tab" data-bs-toggle="tab" data-bs-target="#data-obat" type="button"
                    role="tab" aria-controls="data-obat" aria-selected="false">
                    Laporan Data Obat
                </button>
            </li>
        </ul>

        <!-- Isi Tab -->
        <div class="tab-content" id="myTabContent">
            <!-- Tab Laporan Transaksi -->
            <div class="tab-pane fade show active" id="transaksi" role="tabpanel" aria-labelledby="transaksi-tab">
                <div class="row py-3">
                    <!-- Laporan Obat Masuk -->
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header text-center">
                                <h5>Laporan Transaksi Obat Masuk</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('laporan.obatMasuk') }}" method="GET">
                                    <div class="form-group">
                                        <label for="bulan">Perbulan</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select name="bulan" id="bulan" class="form-control">
                                                    <option value="">- Pilih Bulan -</option>
                                                    @for ($i = 1; $i <= 12; $i++)
                                                        <option value="{{ $i }}">
                                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="tahun" id="tahun" class="form-control">
                                                    <option value="">- Pilih Tahun -</option>
                                                    @for ($i = 2020; $i <= date('Y'); $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Cetak</button>

                                    <div class="form-group mt-3">
                                        <label for="tahun_per">Pertahun</label>
                                        <select name="tahun_per" id="tahun_per" class="form-control">
                                            <option value="">- Pilih Tahun -</option>
                                            @for ($i = 2020; $i <= date('Y'); $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Cetak</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Laporan Obat Keluar -->
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header text-center">
                                <h5>Laporan Transaksi Obat Keluar</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('laporan.obatKeluar') }}" method="GET">
                                    <div class="form-group">
                                        <label for="bulan_keluar">Perbulan</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select name="bulan_keluar" id="bulan_keluar" class="form-control">
                                                    <option value="">- Pilih Bulan -</option>
                                                    @for ($i = 1; $i <= 12; $i++)
                                                        <option value="{{ $i }}">
                                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="tahun_keluar" id="tahun_keluar" class="form-control">
                                                    <option value="">- Pilih Tahun -</option>
                                                    @for ($i = 2020; $i <= date('Y'); $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Cetak</button>

                                    <div class="form-group mt-3">
                                        <label for="tahun_keluar_per">Pertahun</label>
                                        <select name="tahun_keluar_per" id="tahun_keluar_per" class="form-control">
                                            <option value="">- Pilih Tahun -</option>
                                            @for ($i = 2020; $i <= date('Y'); $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Cetak</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Laporan Data Obat -->
            <div class="tab-pane fade" id="data-obat" role="tabpanel" aria-labelledby="data-obat-tab">
                <div class="row py-3">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header text-center">
                                <h5>Laporan Semua Data Obat</h5>
                            </div>
                            <div class="card-body text-center">
                                <a href="{{ route('laporan.index') }}" class="btn btn-primary">Cetak</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Efek Hover pada Kartu -->
    <style>
        .card:hover {
            transform: scale(1.02);
            transition: all 0.3s ease-in-out;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection

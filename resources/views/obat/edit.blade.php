@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card">
            <div class="card-header">
                <h4>Edit Obat</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('obat.update', $obat->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nama_obat">Nama Obat</label>
                        <input type="text" name="nama_obat" class="form-control"
                            value="{{ old('nama_obat', $obat->nama_obat) }}" placeholder="Masukkan nama obat" required>
                    </div>

                    <div class="form-group">
                        <label for="dosis">Dosis</label>
                        <input type="text" name="dosis" class="form-control" value="{{ old('dosis', $obat->dosis) }}"
                            placeholder="Masukkan dosis obat (contoh: 100 Mg)" required>
                    </div>

                    <div class="form-group">
                        <label for="jenis_obat_id">Jenis Obat</label>
                        <select name="jenis_obat_id" id="jenis_obat_id" class="form-control" required>
                            <option value="">Pilih Jenis Obat</option>
                            @foreach ($jenisObat as $jenis)
                                <option value="{{ $jenis->id }}"
                                    {{ old('jenis_obat_id', $obat->jenis_obat_id) == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama_jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="harga">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" value="{{ old('harga', $obat->harga) }}"
                            placeholder="Masukkan harga obat (contoh: 10000)" required>
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" name="stok" class="form-control" value="{{ old('stok', $obat->stok) }}"
                            placeholder="Masukkan jumlah stok obat ini (contoh: 1000)" required>
                    </div>

                    <div class="form-group">
                        <label for="exp">Tanggal Kadaluwarsa</label>
                        <input type="date" name="exp" class="form-control" value="{{ old('exp', $obat->exp) }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan Obat</label>
                        <textarea name="keterangan" class="form-control" id="keterangan" rows="3" placeholder="Masukkan keterangan obat">{{ old('keterangan', $obat->keterangan) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="foto">Foto Obat</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('obat.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        CKEDITOR.replace('keterangan', {
            height: 200, // Tinggi CKEditor, bisa disesuaikan
        });
    </script>
@endsection

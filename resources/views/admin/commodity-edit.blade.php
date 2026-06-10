@extends('layouts.app', ['title' => 'Edit Komoditas', 'pageTitle' => $pageTitle])

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('admin.commodities') }}" class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-[#196b2c] hover:underline">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Kembali ke daftar komoditas
        </a>

        <div class="surface-panel p-6 md:p-8">
            <div class="mb-6 space-y-2">
                <h2 class="font-heading text-2xl font-extrabold text-[#172018]">Edit komoditas</h2>
                <p class="text-sm text-[#5b6658]">Perbarui detail komoditas {{ $commodity->nama_komoditas }}.</p>
            </div>

            <form method="POST" action="{{ route('admin.commodities.update', $commodity) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <label for="nama_komoditas" class="text-sm font-semibold text-[#5b6658]">Nama komoditas</label>
                    <input id="nama_komoditas" name="nama_komoditas" type="text" value="{{ old('nama_komoditas', $commodity->nama_komoditas) }}" required class="field-input">
                    @error('nama_komoditas') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="kategori_id" class="text-sm font-semibold text-[#5b6658]">Kategori</label>
                        <select id="kategori_id" name="kategori_id" required class="field-input">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('kategori_id', $commodity->kategori_id) == $category->id ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="satuan" class="text-sm font-semibold text-[#5b6658]">Satuan</label>
                        <select id="satuan" name="satuan" required class="field-input">
                            @foreach (['kg', 'kuintal', 'ton', 'ikat', 'buah'] as $unit)
                                <option value="{{ $unit }}" {{ old('satuan', $commodity->satuan) === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                            @endforeach
                        </select>
                        @error('satuan') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="harga_acuan" class="text-sm font-semibold text-[#5b6658]">Harga acuan (Rp)</label>
                        <input id="harga_acuan" name="harga_acuan" type="number" step="100" value="{{ old('harga_acuan', $commodity->harga_acuan) }}" class="field-input">
                        @error('harga_acuan') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="icon" class="text-sm font-semibold text-[#5b6658]">Ikon emoji</label>
                        <input id="icon" name="icon" type="text" value="{{ old('icon', $commodity->icon) }}" class="field-input">
                        @error('icon') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <label class="flex items-center gap-3 text-sm text-[#5b6658]">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $commodity->is_active) ? 'checked' : '' }} class="rounded border-[#cad4c4] text-[#196b2c] focus:ring-[#196b2c]/20">
                    Komoditas aktif
                </label>

                <button type="submit" class="btn-primary w-full py-4 text-base">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Simpan perubahan
                </button>
            </form>
        </div>
    </div>
@endsection

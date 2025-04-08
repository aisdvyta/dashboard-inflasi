@extends('layouts.dashboard')

@section('body')
    <h2 class="text-4xl font-bold text-biru1 p-10 pl-24">Silahkan
        <span class="text-kuning1">edit form</span>
        untuk mengubah Akun!
    </h2>

    <div class="max-w-lg bg-white shadow-md rounded-lg p-6 ml-24">
        <form action="{{ route('manajemen-akun.update', $user->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-4">
            @csrf
            @method('PUT')

            <div class="mb-4 text-left">
                <label for="nama" class="block text-biru1 font-semibold">Username</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5 focus:ring-biru5">
                @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 text-left">
                <label for="email" class="block text-biru1 font-semibold">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5 focus:ring-biru5"
                    pattern="[a-zA-Z0-9._!?%+-]+@bps\.go\.id$" title="Email harus menggunakan domain @bps.go.id">
                <p class="text-biru1 text-sm font-thin">Contoh: xxxx@bps.go.id</p>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="id_satker" class="block text-sm font-medium text-biru1">Pilih Satker</label>
                <select id="id_satker" name="id_satker"
                    class="w-full mt-1 p-2 border border-biru5 rounded-lg focus:ring-biru1 focus:border-biru1" required>
                    <option value="" disabled>Pilih satker disini</option>
                    @foreach ($satkers as $satker)
                        <option value="{{ $satker->kode_satker }}"
                            {{ $user->id_satker == $satker->kode_satker ? 'selected' : '' }}>
                            {{ $satker->kode_satker }} - {{ $satker->nama_satker }}
                        </option>
                    @endforeach
                </select>
                @error('id_satker')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 text-left relative">
                <label for="password" class="block text-biru1 font-semibold">Password</label>
                <input type="password" id="password" name="password" minlength="6"
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5 focus:ring-biru5 pr-10">
                <p class="text-biru1 text-sm font-thin">Kosongkan jika tidak ingin mengubah password</p>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <button type="button" onclick="togglePassword()" class="absolute right-3 top-9">
                    <img src="{{ asset('images/login/eyeOffIcon.svg') }}" id="eyeToggle" class="w-5 h-5">
                </button>
            </div>

            <button type="submit"
                class="w-full bg-biru1 hover:bg-biru4 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                Update Akun
            </button>
        </form>
    </div>
@endsection

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('eyeToggle'); // Pastikan ID sesuai dengan elemen gambar
        if (passwordInput.type === 'text') {
            passwordInput.type = 'password';
            toggleIcon.src = "{{ asset('images/login/eyeOffIcon.svg') }}"; // Ganti ikon menjadi "eyeOffIcon"
        } else {
            passwordInput.type = 'text';
            toggleIcon.src = "{{ asset('images/login/eyeIcon.svg') }}"; // Ganti ikon menjadi "eyeIcon"
        }
    }
</script>

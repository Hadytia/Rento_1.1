@extends('layouts.app')

@section('content')

<div class="p-6 w-full">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Kelola Akun Admin</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola akun administrator, atur hak akses, dan lacak aktivitas audit.</p>
        </div>
        <button onclick="openAddModal()"
            class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 h-9 rounded-lg flex items-center gap-2 whitespace-nowrap">
            + Tambah Data Admin
        </button>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-2.5 text-sm text-green-700 mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white rounded-xl shadow-sm p-5">

        {{-- Toolbar --}}
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-semibold text-gray-800">Tabel Admin</span>
            <div class="flex items-center border border-gray-200 rounded-lg px-3 gap-2 h-9 w-52">
                <span class="text-gray-400 text-sm">🔍</span>
                <input type="text" placeholder="Cari admin..." id="searchInput" onkeyup="filterTable()"
                    class="border-none outline-none text-sm text-gray-800 w-full placeholder-gray-400">
            </div>
        </div>

        {{-- Table --}}
        <table class="w-full text-sm" id="adminTable">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Aksi</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Identitas Admin</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Akun & Autentikasi</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Role & Hak Akses</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php
                    $avColors = ['bg-blue-700', 'bg-purple-600', 'bg-emerald-600', 'bg-amber-600', 'bg-red-600'];
                @endphp
                @forelse ($admins as $admin)
                @php
                    $avColor = $avColors[$admin->id % 5];
                    $roleLabel = match($admin->role) {
                        'superadmin' => 'Super Admin',
                        'admin'      => 'Admin',
                        'staff'      => 'Staff',
                        default      => ucfirst($admin->role),
                    };
                    $accessLabel = match($admin->role) {
                        'superadmin' => 'Semua Akses',
                        'admin'      => 'Kelola Produk',
                        'staff'      => 'Terbatas',
                        default      => 'Terbatas',
                    };
                    $accessClass = match($admin->role) {
                        'superadmin' => 'bg-purple-100 text-purple-700',
                        'admin'      => 'bg-blue-100 text-blue-700',
                        default      => 'bg-gray-100 text-gray-500',
                    };
                @endphp
                <tr class="hover:bg-gray-50">
                    {{-- Aksi --}}
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-1">
                            <button onclick="openEditModal(
                                {{ $admin->id }},
                                '{{ addslashes($admin->name) }}',
                                '{{ addslashes($admin->email) }}',
                                '{{ $admin->role }}',
                                '{{ $admin->status }}'
                            )" class="w-7 h-7 rounded-md bg-indigo-100 text-indigo-700 hover:bg-indigo-200 flex items-center justify-center text-xs">✏️</button>
                            <form method="POST" action="{{ route('admins.destroy', $admin->id) }}" class="inline"
                                onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-7 h-7 rounded-md bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center text-xs">🗑️</button>
                            </form>
                        </div>
                    </td>

                    {{-- Identitas --}}
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full {{ $avColor }} text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($admin->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">{{ $admin->name }}</div>
                                <div class="text-xs text-gray-500">{{ $admin->email }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Autentikasi --}}
                    <td class="px-3 py-3">
                        <div class="text-xs text-gray-500">Username</div>
                        <div class="text-sm text-gray-800">{{ strtolower(explode('@', $admin->email)[0]) }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            Dibuat: {{ \Carbon\Carbon::parse($admin->created_date)->format('d M Y') }}
                        </div>
                    </td>

                    {{-- Role --}}
                    <td class="px-3 py-3">
                        <div class="font-semibold text-gray-900 mb-1">{{ $roleLabel }}</div>
                        <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded {{ $accessClass }}">{{ $accessLabel }}</span>
                    </td>

                    {{-- Status --}}
                    <td class="px-3 py-3">
                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full
                            {{ $admin->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $admin->status ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-8">Belum ada data admin.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL ADD --}}
<div class="hidden fixed inset-0 bg-black/40 z-50 items-center justify-center" id="modalAdd">
    <div class="bg-white rounded-xl p-8 w-[480px] max-h-[90vh] overflow-y-auto shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-900">Tambah Data Admin</h2>
            <button onclick="closeModal('modalAdd')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">✕</button>
        </div>
        <form method="POST" action="{{ route('admins.store') }}">
            @csrf

            <p class="text-xs font-semibold text-gray-800 mb-3">Identitas Admin</p>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">👤</span>
                <input type="text" name="name" placeholder="Masukkan nama lengkap..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>

            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">✉️</span>
                <input type="email" name="email" placeholder="Masukkan email..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>

            <p class="text-xs font-semibold text-gray-800 mb-3 mt-4">Akun & Autentikasi</p>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                    <input type="password" name="password" placeholder="Password..." required
                        class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
                </div>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi..."
                        class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
                </div>
            </div>

            <p class="text-xs font-semibold text-gray-800 mb-3 mt-4">Role & Hak Akses</p>
            <select name="role" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500 mb-3">
                <option value="" disabled selected>— Pilih Role —</option>
                <option value="superadmin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
            </select>

            <div class="flex items-center justify-between mb-3 mt-4">
                <span class="text-sm font-semibold text-gray-800">Status Akun</span>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="status" value="1" checked id="toggleStatus" class="hidden">
                    <div class="w-10 h-5 bg-blue-600 rounded-full relative transition-colors" id="toggleSlider"
                        onclick="toggleCheck('toggleStatus','toggleLabel','toggleSlider')">
                        <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 transition-all" id="toggleThumb" style="left:22px"></div>
                    </div>
                    <span class="text-sm text-gray-800" id="toggleLabel">Aktif</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="closeModal('modalAdd')"
                    class="h-9 px-4 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg text-sm text-gray-800">Batal</button>
                <button type="submit"
                    class="h-9 px-4 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="hidden fixed inset-0 bg-black/40 z-50 items-center justify-center" id="modalEdit">
    <div class="bg-white rounded-xl p-8 w-[480px] max-h-[90vh] overflow-y-auto shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-900">Edit Data Admin</h2>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">✕</button>
        </div>
        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')

            <p class="text-xs font-semibold text-gray-800 mb-3">Identitas Admin</p>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">👤</span>
                <input type="text" name="name" id="editName" placeholder="Masukkan nama lengkap..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>

            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">✉️</span>
                <input type="email" name="email" id="editEmail" placeholder="Masukkan email..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>

            <p class="text-xs font-semibold text-gray-800 mb-3 mt-4">Akun & Autentikasi</p>
            <div class="relative mb-3">
                <span onclick="togglePasswordFields()" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-blue-700 font-semibold cursor-pointer">Ubah password</span>
                <div class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-400">••••••••</div>
            </div>
            <div id="passwordFields" class="hidden">
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                        <input type="password" name="password" placeholder="Password baru..."
                            class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi..."
                            class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <p class="text-xs font-semibold text-gray-800 mb-3 mt-4">Role & Hak Akses</p>
            <select name="role" id="editRole" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500 mb-3">
                <option value="" disabled>— Pilih Role —</option>
                <option value="superadmin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
            </select>

            <div class="flex items-center justify-between mb-3 mt-4">
                <span class="text-sm font-semibold text-gray-800">Status Akun</span>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="status" value="1" id="editToggleStatus" class="hidden">
                    <div class="w-10 h-5 bg-gray-200 rounded-full relative transition-colors" id="editToggleSlider"
                        onclick="toggleCheck('editToggleStatus','editToggleLabel','editToggleSlider')">
                        <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 left-0.5 transition-all" id="editToggleThumb"></div>
                    </div>
                    <span class="text-sm text-gray-800" id="editToggleLabel">Nonaktif</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="closeModal('modalEdit')"
                    class="h-9 px-4 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg text-sm text-gray-800">Batal</button>
                <button type="submit"
                    class="h-9 px-4 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleCheck(inputId, labelId, sliderId) {
        const input  = document.getElementById(inputId);
        const label  = document.getElementById(labelId);
        const slider = document.getElementById(sliderId);
        const thumb  = slider.querySelector('div');
        input.checked = !input.checked;
        if (input.checked) {
            slider.classList.replace('bg-gray-200', 'bg-blue-600');
            thumb.style.left = '22px';
            label.textContent = 'Aktif';
        } else {
            slider.classList.replace('bg-blue-600', 'bg-gray-200');
            thumb.style.left = '2px';
            label.textContent = 'Nonaktif';
        }
    }

    function togglePasswordFields() {
        document.getElementById('passwordFields').classList.toggle('hidden');
    }

    function openAddModal() {
        document.getElementById('modalAdd').classList.replace('hidden', 'flex');
    }

    function openEditModal(id, name, email, role, status) {
        document.getElementById('editForm').action = '/admins/' + id;
        document.getElementById('editName').value  = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editRole').value  = role;

        const editToggle = document.getElementById('editToggleStatus');
        const editSlider = document.getElementById('editToggleSlider');
        const editLabel  = document.getElementById('editToggleLabel');
        const editThumb  = editSlider.querySelector('div');

        editToggle.checked = status == 1;
        if (status == 1) {
            editSlider.classList.replace('bg-gray-200', 'bg-blue-600');
            editThumb.style.left = '22px';
            editLabel.textContent = 'Aktif';
        } else {
            editSlider.classList.replace('bg-blue-600', 'bg-gray-200');
            editThumb.style.left = '2px';
            editLabel.textContent = 'Nonaktif';
        }

        document.getElementById('modalEdit').classList.replace('hidden', 'flex');
    }

    function closeModal(id) {
        document.getElementById(id).classList.replace('flex', 'hidden');
    }

    document.querySelectorAll('#modalAdd, #modalEdit').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    function filterTable() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#adminTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
        });
    }
</script>

@endsection
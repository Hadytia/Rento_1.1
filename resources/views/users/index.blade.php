@extends('layouts.app')

@section('content')

<div class="p-6 w-full">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Kelola Data User</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data pelanggan, informasi kontak, dan status akun.</p>
        </div>
        <button onclick="openAddModal()"
            class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 h-9 rounded-lg flex items-center gap-2 whitespace-nowrap">
            + Tambah User
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
            <span class="text-sm font-semibold text-gray-800">Daftar User ({{ $users->count() }})</span>
            <div class="flex items-center border border-gray-200 rounded-lg px-3 gap-2 h-9 w-52">
                <span class="text-gray-400 text-sm">🔍</span>
                <input type="text" placeholder="Cari user..." id="searchInput" onkeyup="filterTable()"
                    class="border-none outline-none text-sm text-gray-800 w-full placeholder-gray-400">
            </div>
        </div>

        {{-- Table --}}
        <table class="w-full text-sm" id="userTable">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Aksi</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Identitas User</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Kontak</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">No. KTP</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Kontak Darurat</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Kode Perusahaan</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php
                    $avColors = ['bg-blue-700', 'bg-purple-600', 'bg-emerald-600', 'bg-amber-600', 'bg-red-600'];
                @endphp
                @forelse ($users as $user)
                @php
                    $avColor = $avColors[$user->id % 5];
                @endphp
                <tr class="hover:bg-gray-50">
                    {{-- Aksi --}}
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-1">
                            <button onclick="openEditModal(
                                {{ $user->id }},
                                '{{ addslashes($user->name) }}',
                                '{{ addslashes($user->email) }}',
                                '{{ addslashes($user->phone) }}',
                                '{{ addslashes($user->address) }}',
                                '{{ addslashes($user->id_card_number) }}',
                                '{{ addslashes($user->emergency_contact) }}',
                                '{{ addslashes($user->company_code) }}',
                                '{{ $user->status }}'
                            )" class="w-7 h-7 rounded-md bg-indigo-100 text-indigo-700 hover:bg-indigo-200 flex items-center justify-center text-xs">✏️</button>
                            <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="inline"
                                onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-7 h-7 rounded-md bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center text-xs">🗑️</button>
                            </form>
                        </div>
                    </td>

                    {{-- Identitas --}}
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full {{ $avColor }} text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Kontak --}}
                    <td class="px-3 py-3">
                        <div class="text-sm text-gray-800">{{ $user->phone }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $user->address }}</div>
                    </td>

                    {{-- No KTP --}}
                    <td class="px-3 py-3">
                        <span class="text-sm text-gray-700 font-mono">{{ $user->id_card_number }}</span>
                    </td>

                    {{-- Kontak Darurat --}}
                    <td class="px-3 py-3">
                        <span class="text-sm text-gray-700">{{ $user->emergency_contact }}</span>
                    </td>

                    {{-- Company Code --}}
                    <td class="px-3 py-3">
                        <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                            {{ $user->company_code }}
                        </span>
                    </td>

                    {{-- Status --}}
                    <td class="px-3 py-3">
                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full
                            {{ $user->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->status ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-500 py-8">Belum ada data user.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL ADD --}}
<div class="hidden fixed inset-0 bg-black/40 z-50 items-center justify-center" id="modalAdd">
    <div class="bg-white rounded-xl p-8 w-[540px] max-h-[90vh] overflow-y-auto shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-900">Tambah User</h2>
            <button onclick="closeModal('modalAdd')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">✕</button>
        </div>
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <p class="text-xs font-semibold text-gray-800 mb-3">Identitas User</p>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">👤</span>
                <input type="text" name="name" placeholder="Nama lengkap..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">✉️</span>
                <input type="email" name="email" placeholder="Email..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>
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

            <p class="text-xs font-semibold text-gray-800 mb-3 mt-4">Informasi Kontak</p>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">📱</span>
                <input type="text" name="phone" placeholder="No. telepon..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">📍</span>
                <input type="text" name="address" placeholder="Alamat lengkap..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>

            <p class="text-xs font-semibold text-gray-800 mb-3 mt-4">Data Tambahan</p>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🪪</span>
                <input type="text" name="id_card_number" placeholder="No. KTP..." maxlength="16"
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🆘</span>
                <input type="text" name="emergency_contact" placeholder="Kontak darurat (Nama - No. HP)..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🏢</span>
                <input type="text" name="company_code" placeholder="Kode perusahaan..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>

            {{-- Status --}}
            <div class="flex items-center justify-between mt-4 mb-3">
                <span class="text-sm font-semibold text-gray-800">Status Akun</span>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="status" value="1" checked id="toggleStatus" class="hidden">
                    <div class="w-10 h-5 bg-blue-600 rounded-full relative transition-colors" id="toggleSlider"
                        onclick="toggleCheck('toggleStatus','toggleLabel','toggleSlider')">
                        <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 transition-all" style="left:22px"></div>
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
    <div class="bg-white rounded-xl p-8 w-[540px] max-h-[90vh] overflow-y-auto shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-900">Edit User</h2>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">✕</button>
        </div>
        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')

            <p class="text-xs font-semibold text-gray-800 mb-3">Identitas User</p>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">👤</span>
                <input type="text" name="name" id="editName" placeholder="Nama lengkap..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">✉️</span>
                <input type="email" name="email" id="editEmail" placeholder="Email..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>

            <p class="text-xs font-semibold text-gray-800 mb-3 mt-4">Informasi Kontak</p>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">📱</span>
                <input type="text" name="phone" id="editPhone" placeholder="No. telepon..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">📍</span>
                <input type="text" name="address" id="editAddress" placeholder="Alamat lengkap..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>

            <p class="text-xs font-semibold text-gray-800 mb-3 mt-4">Data Tambahan</p>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🪪</span>
                <input type="text" name="id_card_number" id="editIdCard" placeholder="No. KTP..." maxlength="16"
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🆘</span>
                <input type="text" name="emergency_contact" id="editEmergencyContact" placeholder="Kontak darurat (Nama - No. HP)..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🏢</span>
                <input type="text" name="company_code" id="editCompanyCode" placeholder="Kode perusahaan..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>

            {{-- Status --}}
            <div class="flex items-center justify-between mt-4 mb-3">
                <span class="text-sm font-semibold text-gray-800">Status Akun</span>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="status" value="1" id="editToggleStatus" class="hidden">
                    <div class="w-10 h-5 bg-gray-200 rounded-full relative transition-colors" id="editToggleSlider"
                        onclick="toggleCheck('editToggleStatus','editToggleLabel','editToggleSlider')">
                        <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 left-0.5 transition-all"></div>
                    </div>
                    <span class="text-sm text-gray-800" id="editToggleLabel">Nonaktif</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="closeModal('modalEdit')"
                    class="h-9 px-4 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg text-sm text-gray-800">Batal</button>
                <button type="submit"
                    class="h-9 px-4 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-sm">Update</button>
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

    function openAddModal() {
        document.getElementById('modalAdd').classList.replace('hidden', 'flex');
    }

    function openEditModal(id, name, email, phone, address, idCard, emergencyContact, companyCode, status) {
        document.getElementById('editForm').action          = '/users/' + id;
        document.getElementById('editName').value           = name;
        document.getElementById('editEmail').value          = email;
        document.getElementById('editPhone').value          = phone;
        document.getElementById('editAddress').value        = address;
        document.getElementById('editIdCard').value         = idCard;
        document.getElementById('editEmergencyContact').value = emergencyContact;
        document.getElementById('editCompanyCode').value    = companyCode;

        const toggle = document.getElementById('editToggleStatus');
        const slider = document.getElementById('editToggleSlider');
        const label  = document.getElementById('editToggleLabel');
        const thumb  = slider.querySelector('div');

        toggle.checked = status == 1;
        if (status == 1) {
            slider.classList.replace('bg-gray-200', 'bg-blue-600');
            thumb.style.left = '22px';
            label.textContent = 'Aktif';
        } else {
            slider.classList.replace('bg-blue-600', 'bg-gray-200');
            thumb.style.left = '2px';
            label.textContent = 'Nonaktif';
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
        document.querySelectorAll('#userTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
        });
    }
</script>

@endsection
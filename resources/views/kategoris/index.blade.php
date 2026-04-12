@extends('layouts.app')

@section('content')

<style>
    /* ── Page Header ── */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .page-title h1 {
        font-family: Inter, sans-serif;
        font-size: 20px;
        font-weight: 600;
        color: #1E1E1E;
        margin: 0;
    }

    .page-title p {
        font-family: Inter, sans-serif;
        font-size: 13px;
        color: #6B6B6B;
        margin: 4px 0 0 0;
    }

    .btn-add {
        height: 36px;
        padding: 0 16px;
        background: #2D4DA3;
        color: #FFFFFF;
        border: none;
        border-radius: 8px;
        font-family: Inter, sans-serif;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-add:hover { background: #253f8a; color: #fff; }

    /* ── Table Container ── */
    .table-container {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0px 2px 8px rgba(0,0,0,0.06);
    }

    .table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .table-toolbar span {
        font-family: Inter, sans-serif;
        font-size: 14px;
        font-weight: 600;
        color: #1E1E1E;
    }

    .search-bar {
        height: 36px;
        width: 200px;
        border: 1px solid #E5E5E5;
        border-radius: 8px;
        padding: 0 12px;
        font-family: Inter, sans-serif;
        font-size: 13px;
        color: #1E1E1E;
        outline: none;
    }

    .search-bar::placeholder { color: #9E9E9E; }
    .search-bar:focus { border-color: #2D4DA3; }

    /* ── Table ── */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead tr { background: #F5F5F5; }

    thead th {
        font-family: Inter, sans-serif;
        font-size: 13px;
        font-weight: 500;
        color: #6B6B6B;
        padding: 12px 16px;
        text-align: left;
    }

    tbody tr {
        border-bottom: 1px solid #E5E5E5;
        height: 50px;
    }

    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #FAFAFA; }

    tbody td {
        font-family: Inter, sans-serif;
        font-size: 13px;
        color: #1E1E1E;
        padding: 0 16px;
    }

    /* ── Badge Status ── */
    .badge {
        display: inline-block;
        background: #F5F5F5;
        border-radius: 12px;
        padding: 4px 10px;
        font-family: Inter, sans-serif;
        font-size: 12px;
        font-weight: 600;
        color: #6B6B6B;
    }

    .badge.active   { background: #D1FAE5; color: #065F46; }
    .badge.inactive { background: #FEE2E2; color: #991B1B; }

    /* ── Action Buttons ── */
    .action-btn {
        width: 28px;
        height: 28px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 4px;
        font-size: 13px;
    }

    .btn-edit   { background: #E0E7FF; color: #3730A3; }
    .btn-delete { background: #FEE2E2; color: #DC2626; }
    .btn-edit:hover   { background: #C7D2FE; }
    .btn-delete:hover { background: #FECACA; }

    /* ── Alert ── */
    .alert-success {
        background: #D1FAE5;
        border: 1px solid #6EE7B7;
        border-radius: 8px;
        padding: 10px 16px;
        font-family: Inter, sans-serif;
        font-size: 13px;
        color: #065F46;
        margin-bottom: 16px;
    }

    /* ── Modal Overlay ── */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 999;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show { display: flex; }

    .modal-box {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 28px 32px;
        width: 440px;
        box-shadow: 0px 10px 30px rgba(0,0,0,0.2);
    }

    .modal-box h2 {
        font-family: Inter, sans-serif;
        font-size: 18px;
        font-weight: 600;
        color: #1E1E1E;
        margin: 0 0 20px 0;
    }

    .form-group { margin-bottom: 14px; }

    .form-group label {
        display: block;
        font-family: Inter, sans-serif;
        font-size: 13px;
        color: #1E1E1E;
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        border: 1px solid #E5E5E5;
        border-radius: 8px;
        padding: 10px 12px;
        font-family: Inter, sans-serif;
        font-size: 13px;
        color: #1E1E1E;
        outline: none;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus { border-color: #2D4DA3; }

    .form-group textarea { resize: vertical; min-height: 80px; }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .btn-cancel {
        height: 38px;
        padding: 0 18px;
        background: #F5F5F5;
        border: 1px solid #E5E5E5;
        border-radius: 8px;
        font-family: Inter, sans-serif;
        font-size: 13px;
        cursor: pointer;
        color: #1E1E1E;
    }

    .btn-save {
        height: 38px;
        padding: 0 18px;
        background: #2D4DA3;
        border: none;
        border-radius: 8px;
        font-family: Inter, sans-serif;
        font-size: 13px;
        color: #FFFFFF;
        cursor: pointer;
    }

    .btn-cancel:hover { background: #ececec; }
    .btn-save:hover   { background: #253f8a; }
</style>

<div class="page-header">
    <div class="page-title">
        <h1>Kelola Kategori</h1>
        <p>Tambah, edit, atau hapus kategori produk.</p>
    </div>
    <button class="btn-add" onclick="openAddModal()">+ Tambah Kategori</button>
</div>

{{-- Alert sukses --}}
@if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="table-container">
    <div class="table-toolbar">
        <span>Daftar Kategori</span>
        <input type="text" class="search-bar" placeholder="Cari kategori..." id="searchInput" onkeyup="filterTable()">
    </div>

    <table id="kategoriTable">
        <thead>
            <tr>
                <th>Aksi</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kategoris as $kategori)
            <tr>
                <td>
                    {{-- Edit --}}
                    <button class="action-btn btn-edit"
                        onclick="openEditModal(
                            {{ $kategori->id }},
                            '{{ addslashes($kategori->category_name) }}',
                            '{{ addslashes($kategori->description) }}',
                            '{{ $kategori->status }}'
                        )">
                        ✏️
                    </button>

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('kategoris.destroy', $kategori->id) }}" style="display:inline;"
                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete">🗑️</button>
                    </form>
                </td>
                <td>{{ $kategori->category_name }}</td>
                <td>{{ $kategori->description }}</td>
                <td>
                    <span class="badge {{ $kategori->status ? 'active' : 'inactive' }}">
                        {{ $kategori->status ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center; color:#6B6B6B; padding:30px;">
                    Belum ada kategori.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ── MODAL ADD ── --}}
<div class="modal-overlay" id="modalAdd">
    <div class="modal-box">
        <h2>Tambah Kategori</h2>
        <form method="POST" action="{{ route('kategoris.store') }}">
            @csrf
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="category_name" placeholder="Masukkan nama kategori..." required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" placeholder="Masukkan deskripsi kategori..."></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('modalAdd')">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL EDIT ── --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal-box">
        <h2>Edit Kategori</h2>
        <form method="POST" id="editForm" action="">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="category_name" id="editCategoryName" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" id="editDescription"></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="editStatus">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn-save">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('modalAdd').classList.add('show');
    }

    function openEditModal(id, categoryName, description, status) {
        document.getElementById('editForm').action         = '/kategoris/' + id;
        document.getElementById('editCategoryName').value  = categoryName;
        document.getElementById('editDescription').value   = description;
        document.getElementById('editStatus').value        = status;
        document.getElementById('modalEdit').classList.add('show');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('show');
        });
    });

    function filterTable() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#kategoriTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
        });
    }
</script>

@endsection
@extends('layouts.app')

@section('title', $title . ' - e-Rapor SD')
@section('header_title', $title)
@section('header_subtitle', 'Pilih Rombongan Belajar untuk mencetak dokumen')

@section('content')
    <div class="stat-card animate-slide-up" style="margin-bottom: 2rem; padding: 1.5rem; display: flex; gap: 1rem; align-items: flex-end;">
        @if(count($rombonganBelajar) > 1 || session('role') === 'admin')
        <div style="flex: 1;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Pilih Rombongan Belajar (Kelas)</label>
            <select id="rombelSelect" class="form-control" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 0.75rem; border-radius: 8px; width: 100%;">
                <option value="">-- Pilih Kelas --</option>
                @foreach($rombonganBelajar as $rombel)
                    <option value="{{ $rombel['rombongan_belajar_id'] ?? $rombel['id'] }}">{{ $rombel['nama'] }}</option>
                @endforeach
            </select>
        </div>
        <button onclick="loadCetakForm()" id="btnLoad" class="btn-sync" style="padding: 0.75rem 1.5rem; border-radius: 8px; background: var(--accent); color: white; border: none; cursor: pointer;">
            Tampilkan Data
        </button>
        @else
        <div style="flex: 1; display: none;">
            <select id="rombelSelect">
                @foreach($rombonganBelajar as $rombel)
                    <option value="{{ $rombel['rombongan_belajar_id'] ?? $rombel['id'] }}" selected>{{ $rombel['nama'] }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex: 1;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Rombongan Belajar</label>
            <div style="background: rgba(59, 130, 246, 0.1); color: #60a5fa; padding: 0.75rem; border-radius: 8px; font-weight: 700; border: 1px solid rgba(59, 130, 246, 0.2);">
                Kelas {{ $rombonganBelajar[0]['nama'] ?? '-' }}
            </div>
        </div>
        <button onclick="loadCetakForm()" id="btnLoad" class="btn-sync" style="padding: 0.75rem 1.5rem; border-radius: 8px; background: var(--accent); color: white; border: none; cursor: pointer;">
            Tampilkan Data
        </button>
        @endif
    </div>

    <div id="cetakContainer" class="table-container animate-slide-up delay-1" style="display: none; overflow-x: auto;">
        <div style="padding: 1rem 1.5rem; background: rgba(59, 130, 246, 0.1); border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <div style="font-weight: 600; color: #60a5fa;" id="tableTitle">Daftar Cetak</div>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                @if($type === 'leger')
                <button id="printLegerBtn" onclick="printLeger()" style="padding: 0.5rem 1rem; border-radius: 6px; background: #8b5cf6; color: white; border: none; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Cetak Leger PDF
                </button>
                <button id="exportExcelBtn" onclick="exportExcel()" style="padding: 0.5rem 1rem; border-radius: 6px; background: linear-gradient(135deg,#10b981,#059669); color: white; border: none; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export Excel (CSV)
                </button>
                @endif
                @if($type === 'nilai')
                <button id="printMassalBtn" onclick="printMassal()" style="padding: 0.5rem 1rem; border-radius: 6px; background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Unduh Semua Rapor (ZIP)
                </button>
                @endif
            </div>
        </div>
        
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Siswa</th>
                    <th>NISN</th>
                    @if($type === 'nilai')
                    <th style="text-align: center;">Aksi Cetak</th>
                    @endif
                </tr>
            </thead>
            <tbody id="studentTableBody">
                <!-- Data will be populated by JS -->
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rombelSelect = document.getElementById('rombelSelect');
            if (rombelSelect && rombelSelect.options.length === 1 && rombelSelect.selectedIndex === 0) {
                // Untuk single class (non-admin)
                loadCetakForm();
            } else if (rombelSelect && rombelSelect.options.length === 2) {
                // Untuk admin yang mungkin punya 1 class (opsional)
                rombelSelect.selectedIndex = 1;
                loadCetakForm();
            }
        });

        function loadCetakForm() {
            const rombelId = document.getElementById('rombelSelect').value;
            const btnLoad = document.getElementById('btnLoad');
            const type = '{{ $type }}';
            
            if(!rombelId) {
                alert('Pilih kelas terlebih dahulu!');
                return;
            }

            btnLoad.innerText = "Memuat...";
            btnLoad.disabled = true;

            // We can reuse the nilai.data endpoint to get the list of students easily
            fetch(`{{ route('nilai.data') }}?rombongan_belajar_id=${rombelId}&mata_pelajaran_id=matematika`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(async res => {
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw new Error(data.message || 'Server Error');
                    return data;
                })
                .then(data => {
                    btnLoad.innerText = "Tampilkan Data";
                    btnLoad.disabled = false;

                    if(data.success) {
                        renderTable(data.students, type, rombelId);
                    } else {
                        alert(data.message || 'Gagal memuat data');
                    }
                })
                .catch(err => {
                    btnLoad.innerText = "Tampilkan Data";
                    btnLoad.disabled = false;
                    alert(err.message && err.message !== 'Failed to fetch' ? err.message : 'Terjadi kesalahan jaringan.');
                });
        }

        function renderTable(students, type, rombelId) {
            const tbody = document.getElementById('studentTableBody');
            tbody.innerHTML = '';

            if (students.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align:center; padding: 2rem;">Tidak ada siswa di kelas ini.</td></tr>';
            }

            students.forEach((student, index) => {
                const pdId = student.peserta_didik_id || student.id;
                
                let actionHtml = '';
                if(type === 'nilai') {
                    const printUrl = `{{ url('/print/rapor') }}/${rombelId}/${pdId}`;
                    actionHtml = `<td style="text-align: center;">
                        <button onclick="window.open('${printUrl}', '_blank')" style="padding: 0.4rem 0.8rem; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">Cetak Rapor</button>
                    </td>`;
                }

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="text-align: center;">${index + 1}</td>
                    <td style="font-weight: 500;">${student.nama}</td>
                    <td style="color: var(--text-secondary);">${student.nisn || '-'}</td>
                    ${actionHtml}
                `;
                tbody.appendChild(tr);
            });

            document.getElementById('cetakContainer').style.display = 'block';
        }

        function printLeger() {
            const rombelId = document.getElementById('rombelSelect').value;
            if(!rombelId) return;
            const url = `{{ route('cetak.print_leger') }}?rombongan_belajar_id=${rombelId}`;
            window.open(url, '_blank');
        }

        function exportExcel() {
            const rombelId = document.getElementById('rombelSelect').value;
            if (!rombelId) { alert('Pilih kelas terlebih dahulu!'); return; }
            const url = `{{ route('cetak.leger_excel') }}?rombongan_belajar_id=${rombelId}`;
            window.location.href = url;
        }

        function printMassal() {
            const rombelId = document.getElementById('rombelSelect').value;
            if (!rombelId) { alert('Pilih kelas terlebih dahulu!'); return; }

            const btn = document.getElementById('printMassalBtn');
            const originalHtml = btn ? btn.innerHTML : '';

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0"/></svg> Membuat ZIP...`;
            }

            const url = `{{ url('/print/rapor-massal') }}/${rombelId}`;

            // Gunakan fetch untuk bisa mendeteksi error dari server
            fetch(url, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(async response => {
                    if (!response.ok) {
                        // Server mengembalikan error JSON
                        const errData = await response.json().catch(() => ({}));
                        throw new Error(errData.error || `Server error: ${response.status}`);
                    }
                    // Sukses — buat blob dan trigger download
                    return response.blob();
                })
                .then(blob => {
                    const downloadUrl = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = downloadUrl;
                    a.download = `Rapor_Massal.zip`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(downloadUrl);
                })
                .catch(err => {
                    alert('Gagal mengunduh ZIP:\n' + err.message);
                })
                .finally(() => {
                    if (btn) { btn.disabled = false; btn.innerHTML = originalHtml; }
                });
        }
    </script>
    <style>
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
@endsection

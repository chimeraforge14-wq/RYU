@extends('layouts.app')

@section('title', 'Pelengkap Rapor - e-Rapor SD')
@section('header_title', 'Data Pelengkap Rapor')
@section('header_subtitle', 'Input Absensi dan Catatan Wali Kelas')

@section('content')
    <!-- Filter Section -->
    @if(count($rombonganBelajar) > 1 || session('role') === 'admin' || session('role') === 'superadmin')
    <div class="stat-card animate-slide-up" style="margin-bottom: 2rem; padding: 1.5rem; display: flex; gap: 1rem; align-items: flex-end;">
        <div style="flex: 2;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Pilih Rombongan Belajar (Kelas)</label>
            <select id="rombelSelect" class="form-control" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 0.75rem; border-radius: 8px; width: 100%;">
                <option value="">-- Pilih Kelas --</option>
                @foreach($rombonganBelajar as $rombel)
                    <option value="{{ $rombel['rombongan_belajar_id'] ?? $rombel['id'] }}" {{ $rombelId == ($rombel['rombongan_belajar_id'] ?? $rombel['id']) ? 'selected' : '' }}>
                        {{ $rombel['nama'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <button onclick="loadForm()" id="btnLoad" class="btn-sync" style="padding: 0.75rem 1.5rem; border-radius: 8px; background: var(--accent); color: white; border: none; cursor: pointer;">
            Tampilkan Form
        </button>
    </div>
    @endif

    @if($rombelId)
    <!-- Form Section -->
    <div id="formSection" class="table-container animate-slide-up delay-1" style="overflow-x: auto;">
        <div style="padding: 1rem 1.5rem; background: rgba(59, 130, 246, 0.1); border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: 600; color: #60a5fa;">Input Absensi & Catatan Wali Kelas</div>
            <button id="saveBtn" onclick="saveData()" style="padding: 0.5rem 1rem; border-radius: 6px; background: #10b981; color: white; border: none; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Simpan Data
            </button>
        </div>
        <table style="width: 100%; min-width: 900px;">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 250px;">Nama Siswa</th>
                    <th style="width: 80px; text-align: center;">Sakit</th>
                    <th style="width: 80px; text-align: center;">Izin</th>
                    <th style="width: 80px; text-align: center;">Alpa</th>
                    <th>Catatan Wali Kelas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaData as $index => $siswa)
                    @php 
                        $pdId = $siswa['peserta_didik_id'] ?? $siswa['id'];
                        $data = $pelengkapMap[$pdId] ?? null;
                    @endphp
                    <tr data-id="{{ $pdId }}">
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <div style="font-weight: 500;">{{ $siswa['nama'] ?? 'Nama Tidak Ditemukan' }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">NISN: {{ $siswa['nisn'] ?? '-' }}</div>
                        </td>
                        <td style="text-align: center;">
                            <input type="number" min="0" class="absensi-input" data-type="sakit" value="{{ $data ? $data->sakit : 0 }}">
                        </td>
                        <td style="text-align: center;">
                            <input type="number" min="0" class="absensi-input" data-type="izin" value="{{ $data ? $data->izin : 0 }}">
                        </td>
                        <td style="text-align: center;">
                            <input type="number" min="0" class="absensi-input" data-type="alpa" value="{{ $data ? $data->tanpa_keterangan : 0 }}">
                        </td>
                        <td>
                            <div style="margin-bottom: 0.5rem;">
                                <select class="template-select" onchange="applyTemplate(this)" style="width: 100%; padding: 0.25rem; background: rgba(255,255,255,0.05); color: #94a3b8; border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; font-size: 0.75rem; outline: none; cursor: pointer;">
                                    <option value="">-- Pilih Template Cepat --</option>
                                    <option value="Pertahankan prestasimu dan tetap semangat belajar!">🏆 Pertahankan prestasimu!</option>
                                    <option value="Tingkatkan terus belajarmu agar meraih hasil yang lebih baik lagi.">📈 Tingkatkan terus belajarmu</option>
                                    <option value="Sikap dan perilakumu sangat baik, jadilah teladan bagi teman-temanmu.">🌟 Sikap sangat baik</option>
                                    <option value="Tingkatkan kedisiplinan dan kehadiranmu di sekolah.">⏰ Tingkatkan kedisiplinan</option>
                                    <option value="Teruslah berlatih dan jangan ragu bertanya jika ada materi yang belum dipahami.">💪 Terus berlatih</option>
                                </select>
                            </div>
                            <textarea class="catatan-input" placeholder="Tulis catatan perkembangan siswa di sini atau pilih template di atas...">{{ $data ? $data->catatan_wali_kelas : '' }}</textarea>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">Tidak ada data siswa ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    <!-- Notification Toast -->
    <div id="toast" style="position: fixed; bottom: 20px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); transform: translateY(100px); opacity: 0; transition: all 0.3s; z-index: 1000;">
        Data berhasil disimpan!
    </div>

    <script>
        function loadForm() {
            const rombelId = document.getElementById('rombelSelect').value;
            if(!rombelId) {
                alert('Pilih kelas terlebih dahulu!');
                return;
            }
            window.location.href = `{{ route('pelengkap_rapor') }}?rombongan_belajar_id=${rombelId}`;
        }

        function applyTemplate(selectEl) {
            if(!selectEl.value) return;
            const td = selectEl.closest('td');
            const textarea = td.querySelector('.catatan-input');
            
            // Jika sudah ada teks, tambahkan dengan spasi. Jika kosong, langsung isi.
            if(textarea.value.trim() !== '') {
                textarea.value = textarea.value.trim() + ' ' + selectEl.value;
            } else {
                textarea.value = selectEl.value;
            }
            
            // Kembalikan dropdown ke posisi default
            selectEl.value = "";
        }

        function saveData() {
            const btn = document.getElementById('saveBtn');
            const rombelId = "{{ $rombelId }}";
            
            if(!rombelId) return;

            btn.innerHTML = 'Menyimpan...';
            btn.disabled = true;
            
            let dataToSave = {};
            const rows = document.querySelectorAll('tbody tr[data-id]');
            
            rows.forEach(tr => {
                const pdId = tr.getAttribute('data-id');
                const inputs = tr.querySelectorAll('.absensi-input');
                const catatan = tr.querySelector('.catatan-input').value;
                
                dataToSave[pdId] = {
                    sakit: inputs[0].value,
                    izin: inputs[1].value,
                    tanpa_keterangan: inputs[2].value,
                    catatan_wali_kelas: catatan
                };
            });

            fetch('{{ route("pelengkap_rapor.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    rombongan_belajar_id: rombelId,
                    data: dataToSave 
                })
            })
            .then(res => res.json())
            .then(data => {
                btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Simpan Data';
                btn.disabled = false;
                if (data.success) {
                    showToast(data.message || 'Data pelengkap rapor berhasil disimpan!');
                } else {
                    showToast('⚠️ ' + (data.message || 'Gagal menyimpan data.'), true);
                }
            })
            .catch(err => {
                btn.innerHTML = 'Coba Lagi';
                btn.disabled = false;
                showToast('⚠️ Gagal menyimpan: ' + err.message, true);
            });
        }

        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            toast.innerText = message;
            toast.style.background = isError ? '#ef4444' : '#10b981';
            toast.style.transform = 'translateY(0)';
            toast.style.opacity = '1';
            
            setTimeout(() => {
                toast.style.transform = 'translateY(100px)';
                toast.style.opacity = '0';
            }, isError ? 5000 : 3000);
        }
    </script>

    <style>
        .absensi-input {
            width: 60px;
            padding: 0.5rem;
            background: rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            border-radius: 6px;
            text-align: center;
        }
        .catatan-input {
            width: 100%;
            min-height: 60px;
            background: rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            padding: 0.5rem;
            border-radius: 6px;
            font-size: 0.875rem;
            resize: vertical;
        }
        .catatan-input:focus, .absensi-input:focus {
            outline: none;
            border-color: var(--accent);
            background: rgba(59, 130, 246, 0.1);
        }
    </style>
@endsection

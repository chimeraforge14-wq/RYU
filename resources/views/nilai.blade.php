@extends('layouts.app')

@section('title', 'Input Nilai - e-Rapor SD')
@section('header_title', 'Penilaian Kurikulum Merdeka')
@section('header_subtitle', 'Sesuai Permendikbudristek No.21 Tahun 2022 (Penyatuan Nilai Akhir & Otomasi Deskripsi)')

@section('content')
    <!-- Filter Section -->
    <div class="stat-card animate-slide-up" style="margin-bottom: 2rem; padding: 1.5rem; display: flex; gap: 1rem; align-items: flex-end;">
        @if(count($rombonganBelajar) > 1 || session('role') === 'admin')
        <div style="flex: 1;">
            <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; font-weight: 600;">Rombongan Belajar</label>
            <select id="rombelSelect" class="form-control" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 0.75rem; border-radius: 8px; width: 100%; cursor: pointer;">
                <option value="">-- Pilih Kelas --</option>
                @foreach($rombonganBelajar as $rombel)
                    <option value="{{ $rombel['rombongan_belajar_id'] ?? $rombel['id'] }}">{{ $rombel['nama'] }}</option>
                @endforeach
            </select>
        </div>
        @else
        <div style="flex: 1; display: none;">
            <select id="rombelSelect">
                @foreach($rombonganBelajar as $rombel)
                    <option value="{{ $rombel['rombongan_belajar_id'] ?? $rombel['id'] }}" selected>{{ $rombel['nama'] }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex: 1;">
            <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; font-weight: 600;">Rombongan Belajar</label>
            <div style="background: rgba(59, 130, 246, 0.1); color: #60a5fa; padding: 0.75rem; border-radius: 8px; font-weight: 700; border: 1px solid rgba(59, 130, 246, 0.2);">
                Kelas {{ $rombonganBelajar[0]['nama'] ?? '-' }}
            </div>
        </div>
        @endif
        <div style="flex: 1;">
            <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; font-weight: 600;">Mata Pelajaran</label>
            <select id="mapelSelect" class="form-control" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 0.75rem; border-radius: 8px; width: 100%; cursor: pointer;">
                <option value="">-- Pilih Rombel Dahulu --</option>
            </select>
        </div>
        <button onclick="loadTable()" id="btnLoad" class="btn-sync" style="padding: 0.75rem 1.5rem; border-radius: 8px; background: var(--accent); color: white; border: none; cursor: pointer; font-weight: 600;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 5px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            Tampilkan Form
        </button>
    </div>

    <!-- Spreadsheet Section -->
    <div id="spreadsheetSection" class="table-container animate-slide-up delay-1" style="display: none; overflow-x: auto; border: 1px solid rgba(59, 130, 246, 0.2);">
        <div style="padding: 1.25rem 1.5rem; background: rgba(59, 130, 246, 0.1); border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-weight: 700; color: #60a5fa; font-size: 1.1rem;" id="formTitle">Form Input Nilai</div>
                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 2px;">Input nilai TP dan SAS untuk kalkulasi Nilai Akhir otomatis</div>
            </div>
            <button id="saveBtn" onclick="saveData()" style="padding: 0.6rem 1.25rem; border-radius: 8px; background: #10b981; color: white; border: none; cursor: pointer; font-weight: 700; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                SIMPAN DATA
            </button>
        </div>
        <table style="width: 100%; min-width: 1100px; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: rgba(0,0,0,0.2);">
                    <th rowspan="2" style="width: 50px; text-align: center; border-right: 1px solid rgba(255,255,255,0.05);">NO</th>
                    <th rowspan="2" style="width: 280px; border-right: 1px solid rgba(255,255,255,0.05);">NAMA PESERTA DIDIK</th>
                    <th colspan="2" style="text-align: center; border-bottom: 1px solid rgba(255,255,255,0.05); background: rgba(59, 130, 246, 0.05);">SUMATIF LINGKUP MATERI (TP)</th>
                    <th rowspan="2" style="width: 110px; text-align: center; background: rgba(139, 92, 246, 0.05);">AKHIR (SAS)</th>
                    <th rowspan="2" style="width: 110px; text-align: center; background: rgba(16, 185, 129, 0.1); color: #10b981;">RAPOR</th>
                    <th rowspan="2">CAPAIAN KOMPETENSI (OTOMATIS)</th>
                </tr>
                <tr style="background: rgba(0,0,0,0.1);">
                    <th style="font-size: 0.7rem; font-weight: 600; text-align:center; width: 100px; color: var(--text-secondary);">TP 1</th>
                    <th style="font-size: 0.7rem; font-weight: 600; text-align:center; width: 100px; color: var(--text-secondary);">TP 2</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                <!-- Data will be populated by JS -->
            </tbody>
        </table>
    </div>

    <!-- Notification Toast -->
    <div id="toast" style="position: fixed; bottom: 20px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); transform: translateY(100px); opacity: 0; transition: all 0.3s; z-index: 1000;">
        Data nilai berhasil disimpan ke Database!
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rombelSelect = document.getElementById('rombelSelect');
            
            // Logika Auto-select:
            // 1. Jika length === 1 (berarti non-admin yang sudah difilter di server)
            // 2. Jika length === 2 (berarti ada 1 rombel + 1 placeholder '-- Pilih Kelas --')
            if (rombelSelect && (rombelSelect.options.length === 1 || rombelSelect.options.length === 2)) {
                if (rombelSelect.options.length === 2) {
                    rombelSelect.selectedIndex = 1;
                } else {
                    rombelSelect.selectedIndex = 0;
                }
                
                // Trigger event change secara manual agar dropdown Mapel terisi
                rombelSelect.dispatchEvent(new Event('change'));
            }
        });

        document.getElementById('rombelSelect').addEventListener('change', function() {
            const rombelId = this.value;
            const mapelSelect = document.getElementById('mapelSelect');
            
            if(!rombelId) {
                mapelSelect.innerHTML = '<option value="">-- Pilih Rombel Dahulu --</option>';
                return;
            }

            mapelSelect.innerHTML = '<option value="">Memuat mata pelajaran...</option>';
            
            fetch(`{{ route('nilai.mapel') }}?rombongan_belajar_id=${rombelId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if(data.success && data.mapels.length > 0) {
                        mapelSelect.innerHTML = '';
                        data.mapels.forEach(m => {
                            mapelSelect.innerHTML += `<option value="${m.id}">${m.nama}</option>`;
                        });

                        // Logika Auto-select Mapel:
                        // Jika cuma ada 1 mapel, langsung pilih dan tampilkan tabel
                        if (data.mapels.length === 1) {
                            mapelSelect.selectedIndex = 0;
                            loadTable();
                        }
                    } else {
                        mapelSelect.innerHTML = '<option value="">-- Tidak ada mapel di kelas ini --</option>';
                    }
                })
                .catch(err => {
                    mapelSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
                });
        });

        function loadTable() {
            const rombelId = document.getElementById('rombelSelect').value;
            const mapelId = document.getElementById('mapelSelect').value;
            const btnLoad = document.getElementById('btnLoad');
            
            if(!rombelId) {
                alert('Pilih kelas terlebih dahulu!');
                return;
            }

            btnLoad.innerText = "Memuat...";
            btnLoad.disabled = true;

            fetch(`{{ route('nilai.data') }}?rombongan_belajar_id=${rombelId}&mata_pelajaran_id=${mapelId}`, {
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
                    btnLoad.innerText = "Tampilkan Form";
                    btnLoad.disabled = false;

                    if(data.success) {
                        renderTable(data.students, data.grades);
                    } else {
                        alert(data.message || 'Gagal memuat data');
                    }
                })
                .catch(err => {
                    btnLoad.innerText = "Tampilkan Form";
                    btnLoad.disabled = false;
                    alert(err.message && err.message !== 'Failed to fetch' ? err.message : 'Terjadi kesalahan jaringan.');
                });
        }

        function renderTable(students, grades) {
            const tbody = document.getElementById('studentTableBody');
            tbody.innerHTML = '';

            if (students.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding: 2rem;">Tidak ada siswa di kelas ini.</td></tr>';
            }

            students.forEach((student, index) => {
                const pdId = student.peserta_didik_id || student.id;
                const g = grades[pdId] || { nilai_tp1: '', nilai_tp2: '', nilai_sas: '', nilai_akhir: '', deskripsi_capaian: '-' };

                const tr = document.createElement('tr');
                tr.setAttribute('data-id', pdId);
                tr.innerHTML = `
                    <td style="text-align: center; border-right: 1px solid rgba(255,255,255,0.05);">${index + 1}</td>
                    <td style="border-right: 1px solid rgba(255,255,255,0.05);">
                        <div style="font-weight: 600;">${student.nama || 'Siswa Tanpa Nama'}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">NISN: ${student.nisn || '-'}</div>
                    </td>
                    <td style="text-align: center;"><input type="number" min="0" max="100" class="grade-input" data-type="tp1" aria-label="Nilai TP 1 untuk ${student.nama}" value="${g.nilai_tp1}" oninput="calculate(this)"></td>
                    <td style="text-align: center; border-right: 1px solid rgba(255,255,255,0.05);"><input type="number" min="0" max="100" class="grade-input" data-type="tp2" aria-label="Nilai TP 2 untuk ${student.nama}" value="${g.nilai_tp2}" oninput="calculate(this)"></td>
                    <td style="text-align: center; border-right: 1px solid rgba(255,255,255,0.05);"><input type="number" min="0" max="100" class="grade-input" data-type="sas" aria-label="Nilai SAS untuk ${student.nama}" value="${g.nilai_sas}" oninput="calculate(this)" style="background: rgba(139, 92, 246, 0.1);"></td>
                    <td style="text-align: center; font-size: 1.25rem; font-weight: 800; color: #10b981;" class="final-score">${g.nilai_akhir || 0}</td>
                    <td style="font-size: 0.85rem; color: var(--text-secondary); padding-left: 1rem;" class="desc-text">${g.deskripsi_capaian || '-'}</td>
                `;
                tbody.appendChild(tr);
                
                // Pastikan kalkulasi awal berjalan untuk setiap baris
                const firstInput = tr.querySelector('.grade-input');
                if(firstInput) calculate(firstInput);
            });

            document.getElementById('spreadsheetSection').style.display = 'block';
        }

        function calculate(element) {
            const tr = element.closest('tr');
            if(!tr) return;

            const inputs = tr.querySelectorAll('.grade-input');
            const finalScoreEl = tr.querySelector('.final-score');
            const descEl = tr.querySelector('.desc-text');

            const tp1_name = document.getElementById('tp1_name')?.value || "Materi 1";
            const tp2_name = document.getElementById('tp2_name')?.value || "Materi 2";

            let val1 = inputs[0].value;
            let val2 = inputs[1].value;
            let valSas = inputs[2].value;

            let tp1 = parseFloat(val1) || 0;
            let tp2 = parseFloat(val2) || 0;
            let sas = parseFloat(valSas) || 0;

            // Jika semua kosong, reset tampilan
            if(val1 === "" && val2 === "" && valSas === "") {
                finalScoreEl.innerText = "0";
                descEl.innerHTML = "-";
                return;
            }

            // Hitung Rata-rata Sumatif Materi (TP)
            let sumatifCount = 0;
            let sumatifTotal = 0;
            if(val1 !== "") { sumatifTotal += tp1; sumatifCount++; }
            if(val2 !== "") { sumatifTotal += tp2; sumatifCount++; }
            
            let avgSumatif = sumatifCount > 0 ? (sumatifTotal / sumatifCount) : 0;
            
            // Hitung Nilai Akhir (Rata-rata TP dan SAS)
            let finalScore = 0;
            if (valSas !== "") {
                finalScore = (avgSumatif + sas) / 2;
            } else {
                finalScore = avgSumatif;
            }
            
            finalScore = Math.round(finalScore);
            finalScoreEl.innerText = finalScore;

            // Generate Deskripsi Otomatis
            let desc = "";
            if (tp1 >= tp2 && val1 !== "") {
                desc = `Menunjukkan penguasaan yang <strong>Sangat Baik</strong> dalam memahami <strong>${tp1_name}</strong>.`;
                if (tp2 < 75 && val2 !== "") {
                    desc += ` <span style="color:#f87171;">Perlu bimbingan</span> dalam <strong>${tp2_name}</strong>.`;
                }
            } else if (tp2 > tp1 && val2 !== "") {
                desc = `Menunjukkan penguasaan yang <strong>Sangat Baik</strong> dalam memahami <strong>${tp2_name}</strong>.`;
                if (tp1 < 75 && val1 !== "") {
                    desc += ` <span style="color:#f87171;">Perlu bimbingan</span> dalam <strong>${tp1_name}</strong>.`;
                }
            } else if (val1 !== "" || val2 !== "") {
                desc = `Menunjukkan penguasaan dalam materi yang diajarkan.`;
            } else {
                desc = "-";
            }
            descEl.innerHTML = desc;
        }

        function saveData() {
            const btn = document.getElementById('saveBtn');
            btn.innerHTML = 'Menyimpan...';
            
            const rombelId = document.getElementById('rombelSelect').value;
            const mapelId = document.getElementById('mapelSelect').value;
            
            let dataToSave = {};
            const rows = document.querySelectorAll('#studentTableBody tr');
            
            rows.forEach(tr => {
                const pdId = tr.getAttribute('data-id');
                if(!pdId) return;

                const inputs = tr.querySelectorAll('.grade-input');
                const finalScore = tr.querySelector('.final-score').innerText;
                const descHTML = tr.querySelector('.desc-text').innerHTML;
                
                dataToSave[pdId] = {
                    tp1: inputs[0].value,
                    tp2: inputs[1].value,
                    sas: inputs[2].value,
                    akhir: finalScore,
                    deskripsi: descHTML
                };
            });

            fetch('{{ route("nilai.save") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    rombongan_belajar_id: rombelId,
                    mata_pelajaran_id: mapelId,
                    grades: dataToSave 
                })
            })
            .then(res => res.json())
            .then(data => {
                btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Simpan Otomatis';
                showToast(data.message || 'Data nilai berhasil disimpan!');
            })
            .catch(err => {
                btn.innerHTML = 'Coba Lagi';
                alert('Gagal menyimpan data ke database.');
            });
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            if(message) toast.innerText = message;
            toast.style.transform = 'translateY(0)';
            toast.style.opacity = '1';
            
            setTimeout(() => {
                toast.style.transform = 'translateY(100px)';
                toast.style.opacity = '0';
            }, 3000);
        }
    </script>

    <style>
        .grade-input {
            width: 70px;
            padding: 0.5rem;
            background: rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            border-radius: 6px;
            text-align: center;
            font-size: 1rem;
            font-weight: 500;
        }
        .grade-input:focus {
            outline: none;
            border-color: var(--accent);
            background: rgba(59, 130, 246, 0.1);
        }
        
        /* Chrome, Safari, Edge, Opera hide arrows in number input */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection

# **MASTERPLAN PENGEMBANGAN MODUL JADWAL & AGENDA MADRASAH**

Dokumen ini berfungsi sebagai peta jalan (roadmap) dan acuan arsitektur sistem untuk pengembangan Modul Jadwal Pelajaran dan Agenda Madrasah pada aplikasi SIGMA3-V4. Dokumen ini dirancang khusus untuk memastikan kemudahan bagi guru senior dan fleksibilitas penuh bagi Waka Kurikulum.

## **1\. ARSITEKTUR DATABASE & MODEL**

Pondasi database telah selesai dirancang dan dieksekusi melalui migration dengan struktur sebagai berikut:

### **A. Tabel: agenda_madrasahs**

Berfungsi sebagai pusat kendali hari libur dan acara insidental sekolah yang mengalahkan (override) jadwal reguler.

- **id**: Primary Key
- **tanggal**: Date (Indeks hari khusus)
- **tipe_agenda**: Enum ('libur_nasional', 'kegiatan_bersama', 'kondisi_kelas')
- **nama_agenda**: String (Contoh: Pekan Madaris, Takziah Bersama, Class Meeting)
- **kelas_id**: Foreign Key (Nullable, digunakan jika acara hanya berlaku untuk kelas tertentu seperti Outbound)
- **keterangan**: Text (Nullable)

### **B. Tabel: ploting_gurus**

Berfungsi sebagai "Kotak Bidak/Puzzle" yang memetakan Kode Guru dan Mata Pelajaran secara dinamis per Tahun Ajaran.

- **id**: Primary Key
- **tahun_ajaran_id**: Foreign Key ke tabel tahun_ajaran
- **pegawai_id**: Foreign Key ke tabel pegawai
- **kode_guru**: String (Dinamis per tahun, contoh: 01, 21, SI)
- **mapel**: String (Mengakomodasi guru yang mengajar lebih dari 1 mapel)

### **C. Tabel: jadwal_pelajarans**

Berfungsi sebagai "Papan Jadwal Rutin" mingguan yang merujuk pada hasil ploting guru.

- **id**: Primary Key
- **tahun_ajaran_id**: Foreign Key ke tabel tahun_ajaran
- **hari**: String (Senin \- Sabtu)
- **jam_ke**: String (Contoh: 1, 2, 1-2, 3-4)
- **kelas_id**: Foreign Key ke tabel kelas
- **ploting_guru_id**: Foreign Key ke tabel ploting_gurus

## **2\. PROGRAMMING & UI/UX PLAN (FILAMENT RESOURCE)**

### **Rencana Strategis Implementasi Antarmuka (UI):**

| Resource            | Strategi Tampilan (UI)             | Fitur Otomatisasi & UX                                                                                                                                                                                                         |
| :------------------ | :--------------------------------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **AgendaMadrasah**  | Form Standar Filament              | Input Rentang Tanggal (Date Range) untuk acara panjang. Tombol otomatis "Tarik Libur Nasional" via API Kalender Indonesia.                                                                                                     |
| **PlotingGuru**     | Hidden Sidebar & Slide-over Modal  | Menu disembunyikan dari sidebar utama agar tidak membingungkan. Diakses melalui tombol di halaman Jadwal Pelajaran dalam bentuk Slide-over/Popup. Menggunakan Editable Table (Input kilat seperti Excel) & Fitur Import Excel. |
| **JadwalPelajaran** | Custom Page Livewire (Matrix Grid) | Tampilan berbentuk grid interaktif kelas vs hari/jam pelajaran. Pengaturan jadwal menggunakan metode interaktif berbasis Kode Guru. Menyediakan Fitur Bulk Import Jadwal dari file Excel Waka Kurikulum.                       |

## **3\. INTEGRASI & ALUR SINKRONISASI JURNAL MENGAJAR (LIVE)**

Ketika Guru membuka halaman Jurnal Mengajar, sistem akan menjalankan logika pemeriksaan berlapis:

1. **Lapis 1 (Hari Minggu)**: Jika tanggal hari ini adalah hari Minggu, form otomatis terkunci dengan keterangan "Libur Akhir Pekan".
2. **Lapis 2 (Agenda Pusat/Libur Nasional)**: Sistem memeriksa tabel  
   `agenda_madrasahs`. Jika bertipe 'libur_nasional' atau 'kegiatan_bersama' (misal: Pekan Madaris), form langsung terkunci dan otomatis terisi nama agenda tersebut secara kolektif tanpa guru harus mengklik satu per satu kelasnya.
3. **Lapis 3 (Kondisi Parsial Kelas)**: Jika ada agenda khusus kelas (misal Kelas VII Outbound), maka guru yang terjadwal di kelas tersebut otomatis jurnalnya terkunci dengan keterangan acara, sementara kelas VIII & IX tetap berjalan normal.
4. **Lapis 4 (Hari Kerja Normal)**: Jika tidak ada interupsi agenda, sistem otomatis membaca tabel  
   `jadwal_pelajarans`sesuai hari aktif, lalu langsung "menyuapi" form guru dengan data Kelas, Jam Ke, dan Mapel secara otomatis.

## **4\. CATATAN PENGEMBANGAN LOKAL KE LIVE HOSTING**

- Seluruh pembuatan komponen Livewire Matrix, Logic API Libur, dan Excel Importer wajib diselesaikan dan dites secara intensif di komputer lokal terlebih dahulu.
- Pastikan file template Excel untuk Ploting Guru dan Jadwal Pelajaran sudah dikunci format kolomnya sebelum diserahkan kepada Waka Kurikulum.
- Proses deploy ke web hosting hanya dilakukan setelah simulasi pengisian jurnal dari sisi guru dan manajemen Waka berjalan 100% tanpa kendala (bug-free).

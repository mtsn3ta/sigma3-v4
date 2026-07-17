<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Hash;

class ImportLegacyStudents extends Command
{
    protected $signature = 'sigma3:import-legacy-students';

    protected $description = 'Import siswa dari SIGMA lama';

    public function handle(): int
    {
        $legacyStudents = DB::connection('legacy')

            ->table('kelas_siswa')

            ->join(
                'kelas',
                'kelas.id_kelas',
                '=',
                'kelas_siswa.id_kelas'
            )

            ->join(
                'siswa',
                'siswa.id_siswa',
                '=',
                'kelas_siswa.id_siswa'
            )

            ->select(

                'siswa.*',

                'kelas.nama_kelas',

            )

            ->orderBy('kelas.nama_kelas')

            ->orderBy('siswa.nama')

            ->get();

        $this->info('Total siswa ditemukan : ' . $legacyStudents->count());
        $progress = $this->output->createProgressBar(
            $legacyStudents->count()
        );

        $progress->start();

        $success = 0;
        $skip = 0;
        $failed = 0;
        $tahun = TahunAjaran::aktifId();

        if (!$tahun) {

            $this->error('Tahun ajaran aktif tidak ditemukan.');

            return self::FAILURE;
        }
        foreach ($legacyStudents as $legacy) {
            if (Siswa::where('nisn', $legacy->nis)->exists()) {

                $skip++;

                $progress->advance();

                continue;
            }
            if (User::where('username', $legacy->nis)->exists()) {

                $skip++;

                $progress->advance();

                continue;
            }
            $kelas = Kelas::where(
                'nama_kelas',
                $legacy->nama_kelas
            )->first();

            if (!$kelas) {

                $failed++;

                $progress->advance();

                $this->newLine();

                $this->error("Kelas {$legacy->nama_kelas} tidak ditemukan.");

                continue;
            }

            try {
                DB::transaction(function () use ($legacy, $kelas, $tahun) {
                    $user = User::create([

                        'username' => $legacy->nis,

                        'password' => Hash::make('mtsn3ta123'),

                        'must_change_password' => true,

                        'status' => 'aktif',

                    ]);

                    $user->assignRole('siswa');

                    $siswa = Siswa::create([

                        'user_id' => $user->id,

                        'nisn' => $legacy->nis,

                        'nama' => $legacy->nama,

                        'jenis_kelamin' => $legacy->jenis_kelamin,

                        'tempat_lahir' => $legacy->tempat_lahir,

                        'tanggal_lahir' => $legacy->tanggal_lahir,

                        'alamat' => $legacy->alamat,

                        'no_hp' => $legacy->no_hp,

                        'foto' => $legacy->foto,

                        'nama_ayah' => $legacy->nama_ayah,

                        'nama_ibu' => $legacy->nama_ibu,

                        'pekerjaan_ayah' => $legacy->pekerjaan_ayah,

                        'pekerjaan_ibu' => $legacy->pekerjaan_ibu,

                        'nik_ayah' => $legacy->nik_ayah,

                        'nik_ibu' => $legacy->nik_ibu,

                        'alamat_ortu' => $legacy->alamat_ortu,

                        'telpon_ortu' => $legacy->telpon_ortu,

                        'nama_wali' => $legacy->nama_wali,

                        'pekerjaan_wali' => $legacy->pekerjaan_wali,

                        'alamat_wali' => $legacy->alamat_wali,

                        'telpon_wali' => $legacy->telpon_wali,

                        'anak_ke' => (int) $legacy->anak_ke,

                        'sekolah_asal' => $legacy->sekolah_asal,

                        'rfid_uid' => $legacy->rfid_uid,

                        // sesuai keputusan kita
                        'status' => 'aktif',

                    ]);
                    KelasSiswa::create([

                        'siswa_id' => $siswa->id,

                        'kelas_id' => $kelas->id,

                        'tahun_ajaran_id' => $tahun,

                        'status' => 'aktif',

                    ]);
                });
                $success++;
            } catch (\Throwable $e) {

                $failed++;

                $this->newLine();

                $this->error(
                    "{$legacy->nis} : {$e->getMessage()}"
                );
            }

            $progress->advance();
        }
        $progress->finish();

        $this->newLine(2);

        $this->info('==============================');

        $this->info('IMPORT SELESAI');

        $this->info('==============================');

        $this->line("Total    : " . $legacyStudents->count());

        $this->line("Berhasil : " . $success);

        $this->line("Skip     : " . $skip);

        $this->line("Gagal    : " . $failed);

        return self::SUCCESS;
    }
}

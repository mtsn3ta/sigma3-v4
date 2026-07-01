<div style="display: flex; flex-direction: column; gap: 1.25rem; font-size: 0.875rem; color: #374151;">

    {{-- Keterangan Status --}}
    <div style="display: flex; flex-direction: column; gap: 1rem;">

        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
            <div style="font-weight: 600; color: #059669; display: flex; align-items: center; gap: 0.5rem;">
                <span>🟢</span> Hadir
            </div>
            <p style="margin: 0; color: #4b5563; padding-left: 1.5rem;">
                Digunakan apabila siswa mengikuti ibadah tetapi tidak berhasil melakukan scan RFID (kartu rusak, reader
                bermasalah, atau kendala teknis lainnya).
            </p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
            <div style="font-weight: 600; color: #db2777; display: flex; align-items: center; gap: 0.5rem;">
                <span>🩷</span> Menstruasi
            </div>
            <p style="margin: 0; color: #4b5563; padding-left: 1.5rem;">
                Digunakan khusus untuk siswi yang tidak mengikuti ibadah karena sedang menstruasi.
            </p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
            <div style="font-weight: 600; color: #dc2626; display: flex; align-items: center; gap: 0.5rem;">
                <span>🔴</span> Tidak Hadir
            </div>
            <p style="margin: 0; color: #4b5563; padding-left: 1.5rem;">
                Digunakan apabila siswa seharusnya mengikuti ibadah tetapi tidak hadir tanpa alasan yang dibenarkan.
            </p>
        </div>

    </div>

    {{-- Catatan Box --}}
    <div
        style="
        border-radius: 0.75rem;
        border: 1px solid #fde68a;
        background-color: #fefce8;
        padding: 1rem;
        color: #92400e;
    ">
        <div style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
            <span>💡</span> Catatan
        </div>
        <p style="margin: 0.25rem 0 0 0; line-height: 1.5;">
            Halaman ini hanya menampilkan siswa yang <strong>hadir di madrasah</strong>,
            <strong>tidak sedang sakit</strong>, <strong>tidak izin</strong>,
            <strong>tidak alfa</strong>, <strong>tidak sedang izin keluar</strong>,
            dan <strong>belum memiliki data kehadiran ibadah</strong> pada hari tersebut.
        </p>
    </div>

</div>

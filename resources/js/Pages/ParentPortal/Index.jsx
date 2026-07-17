export default function Index({ student }) {
    return (
        <div className="container mx-auto py-10">
            <h1 className="text-3xl font-bold">Portal Wali Murid</h1>

            <div className="mt-8 rounded-xl border p-6">
                <h2 className="text-xl font-semibold">{student.nama}</h2>

                <p>NISN : {student.nisn}</p>

                <p>Kelas : {student.kelas}</p>
            </div>
        </div>
    );
}

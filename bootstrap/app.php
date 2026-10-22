<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\HandleInertiaRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->redirectUsersTo('/admin');
        
        $middleware->web(
            append: [
                HandleInertiaRequests::class,
            ],
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );

        // Mencegah Livewire 500 saat file upload temporary Flysystem kadaluarsa / hilang
        $exceptions->render(function (\League\Flysystem\UnableToRetrieveMetadata $e, Request $request) {
            \Illuminate\Support\Facades\Log::warning('Temporary upload file missing/expired: ' . $e->getMessage());

            if ($request->hasHeader('X-Livewire')) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'dokumentasi_foto' => 'File foto yang dipilih sudah kadaluarsa atau tidak ditemukan di penyimpanan sementara. Silakan pilih kembali foto kegiatan.',
                ]);
            }
        });

        // Mencegah Livewire 500 saat duplikasi unik jurnal / presensi terpicu
        $exceptions->render(function (\Illuminate\Database\UniqueConstraintViolationException $e, Request $request) {
            \Illuminate\Support\Facades\Log::warning('Database duplicate entry caught: ' . $e->getMessage());

            if ($request->hasHeader('X-Livewire')) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'jam_ke' => 'Data untuk tanggal, jam pelajaran, dan kelas ini sudah pernah disimpan.',
                ]);
            }
        });
    })
    ->create();

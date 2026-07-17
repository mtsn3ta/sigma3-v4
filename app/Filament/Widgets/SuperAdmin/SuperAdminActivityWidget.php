<?php

namespace App\Filament\Widgets\SuperAdmin;

use App\Models\ActivityLog;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class SuperAdminActivityWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Activity Log Terbaru')
            ->description('Pantau aktivitas terbaru dari seluruh pengguna di dalam sistem.')
            ->query(
                ActivityLog::query()
                    ->with('user')
                    ->latest('created_at')
                    ->limit(10)
            )
            ->columns([
                // 1. Kolom Pengguna (Diberi ikon dan ditebalkan)
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Pengguna')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('primary')
                    ->weight(FontWeight::Bold)
                    ->searchable(),

                // 2. Kolom Aksi (Ditambahkan ikon pada masing-masing badge status)
                Tables\Columns\TextColumn::make('aksi')
                    ->label('Aksi')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'login'  => 'heroicon-m-arrow-right-on-rectangle',
                        'logout' => 'heroicon-m-arrow-left-on-rectangle',
                        'create' => 'heroicon-m-plus-circle',
                        'update' => 'heroicon-m-pencil-square',
                        'delete' => 'heroicon-m-trash',
                        default  => 'heroicon-m-cog-6-tooth',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'login'  => 'success',
                        'logout' => 'gray',
                        'create' => 'info',
                        'update' => 'warning',
                        'delete' => 'danger',
                        default  => 'gray',
                    }),

                // 3. Kolom Deskripsi (Dibuat wrap agar tidak terpotong kaku)
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->wrap()
                    ->color('gray')
                    ->limit(60),

                // 4. Kolom IP Address (Diberi font Monospace ala Tech/Hacker)
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('Alamat IP')
                    ->icon('heroicon-m-globe-alt')
                    ->fontFamily(FontFamily::Mono)
                    ->color('gray'),

                // 5. Kolom Waktu (Dipindah ke ujung kanan, ditambah time-ago "2 jam lalu")
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->description(fn(ActivityLog $record): string => $record->created_at->diffForHumans())
                    ->sortable()
                    ->alignEnd(),
            ])
            ->paginated(false)
            // Menambahkan aksen garis zebra agar mudah dibaca
            ->striped()
            // Mengubah tampilan saat log masih kosong (Empty State)
            ->emptyStateIcon('heroicon-o-clipboard-document-check')
            ->emptyStateHeading('Belum Ada Aktivitas')
            ->emptyStateDescription('Aktivitas pengguna akan muncul di sini secara real-time.');
    }

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('super_admin') ?? false;
    }
}

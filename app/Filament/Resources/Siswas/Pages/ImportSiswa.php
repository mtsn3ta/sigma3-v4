<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentImport;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Notifications\Notification;
use App\Services\StudentImport\ValidateImportService;

class ImportSiswa extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string $resource = SiswaResource::class;

    protected string $view = 'filament.resources.siswas.pages.import-siswa';

    public ?array $data = [];

    public array $preview = [];
    public function mount(): void
    {
        $this->form->fill();
    }
    // Menggunakan Schema (bukan Form) sesuai standar Filament v4/v5 terbaru
    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                FileUpload::make('file')
                    ->label('File Excel')
                    ->acceptedFileTypes([
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                    ])
                    ->multiple(false)
                    ->storeFiles(false)
                    ->required(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Preview Data')
                ->icon('heroicon-o-eye')
                ->color('primary')
                // PERBAIKAN: Gunakan callback closure untuk memanggil method preview()
                ->action(fn() => $this->preview()),
            Action::make('import')

                ->label('Import')

                ->icon('heroicon-o-arrow-down-on-square')

                ->color('success')

                ->requiresConfirmation()

                ->visible(
                    fn() =>
                    count($this->preview) > 0 &&
                        ! $this->hasErrors()
                )

                ->action(function () {

                    $result = app(
                        \App\Services\StudentImport\ExecuteImportService::class
                    )->execute($this->preview);

                    Notification::make()

                        ->title('Import Berhasil')

                        ->body(sprintf(
                            '%d siswa berhasil diimport. %d dilewati.',
                            $result['created'],
                            $result['skipped'],
                        ))

                        ->success()

                        ->send();

                    $this->preview = [];

                    $this->form->fill();
                }),
        ];
    }

    public function hasErrors(): bool
    {
        return collect($this->preview)
            ->contains(fn($row) => $row['status'] === 'error');
    }

    protected function uploadedFile(): ?TemporaryUploadedFile
    {
        $files = $this->data['file'] ?? [];

        if (empty($files)) {
            return null;
        }

        // Antisipasi jika Livewire mengembalikan array data file
        if (is_array($files)) {
            $file = reset($files);
        } else {
            $file = $files;
        }

        return $file instanceof TemporaryUploadedFile ? $file : null;
    }

    public function preview(): void
    {
        if (method_exists($this->form, 'validate')) {
            $this->form->validate();
        }

        $file = $this->uploadedFile();

        if (! $file) {
            return;
        }

        $import = new StudentImport();

        Excel::import(
            $import,
            $file->getRealPath(),
        );

        $this->preview = app(
            ValidateImportService::class
        )->validate($import->rows);
    }

    public function getTitle(): string
    {
        return 'Import Biodata Siswa';
    }
}

<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageCompressor
{
    /**
     * Compress and resize an image file to ~100KB target size.
     *
     * @param string|null $diskPath Path relative to disk (e.g. 'jurnal/foto/abc.jpg')
     * @param string $disk Storage disk name (default 'public')
     * @param int $maxWidth Max width in pixels (default 1024)
     * @param int $maxHeight Max height in pixels (default 768)
     * @param int $quality JPEG quality (default 72 produces ~70KB - 110KB)
     * @return bool
     */
    public static function compress(
        ?string $diskPath,
        string $disk = 'public',
        int $maxWidth = 1024,
        int $maxHeight = 768,
        int $quality = 72
    ): bool {
        if (blank($diskPath)) {
            return false;
        }

        try {
            $fullPath = Storage::disk($disk)->path($diskPath);

            if (!file_exists($fullPath) || !is_readable($fullPath)) {
                return false;
            }

            $imageInfo = @getimagesize($fullPath);
            if (!$imageInfo) {
                return false;
            }

            [$origWidth, $origHeight, $imageType] = $imageInfo;

            $sourceImage = match ($imageType) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($fullPath),
                IMAGETYPE_PNG => @imagecreatefrompng($fullPath),
                IMAGETYPE_WEBP => @imagecreatefromwebp($fullPath),
                default => null,
            };

            if (!$sourceImage) {
                return false;
            }

            // Correct EXIF orientation for smartphone photos
            if ($imageType === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
                $exif = @exif_read_data($fullPath);
                if (!empty($exif['Orientation'])) {
                    $sourceImage = match ($exif['Orientation']) {
                        3 => imagerotate($sourceImage, 180, 0),
                        6 => imagerotate($sourceImage, -90, 0),
                        8 => imagerotate($sourceImage, 90, 0),
                        default => $sourceImage,
                    };
                    $origWidth = imagesx($sourceImage);
                    $origHeight = imagesy($sourceImage);
                }
            }

            // Calculate new dimensions preserving aspect ratio
            $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight, 1.0);
            $newWidth = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);

            $targetImage = imagecreatetruecolor($newWidth, $newHeight);

            // Handle white background for transparent images
            $white = imagecolorallocate($targetImage, 255, 255, 255);
            imagefilledrectangle($targetImage, 0, 0, $newWidth, $newHeight, $white);

            imagecopyresampled(
                $targetImage,
                $sourceImage,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $origWidth, $origHeight
            );

            // Save optimized JPEG
            $saved = imagejpeg($targetImage, $fullPath, $quality);

            imagedestroy($sourceImage);
            imagedestroy($targetImage);

            return $saved;
        } catch (\Throwable $e) {
            Log::warning('ImageCompressor failed: ' . $e->getMessage(), [
                'path' => $diskPath,
            ]);
            return false;
        }
    }
}

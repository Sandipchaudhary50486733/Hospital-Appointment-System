<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Serves uploaded profile/doctor photos straight from storage/app/public.
 *
 * This removes the dependency on the public/storage symlink
 * (`php artisan storage:link`), which silently fails on many Windows setups
 * and on hosts that block symlinks. The route only allows the two upload
 * folders and plain file names, so nothing else on disk can be reached.
 */
class MediaController extends Controller
{
    public function show(string $path): StreamedResponse
    {
        $disk = Storage::disk('public');

        abort_unless($disk->exists($path), 404);

        // File names are random per upload, so browsers may cache them for long.
        return $disk->response($path, null, [
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }
}

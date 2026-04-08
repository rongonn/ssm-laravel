<?php

namespace Isotope\Metronic\Http\Traits;

use Exception;
use Carbon\Carbon;
use Isotope\Metronic\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Fileable
{
    public function deleteFile($file, $delete = true)
    {
        if (!$file) {
            $file = $this->image;
        }
        if (optional($file)->path) {
            if (Storage::exists($file->path)) {
                Storage::delete($file->path);
            }
            if ($delete) {
                return $file->delete();
            }
        }
    }

    public function saveFile($file, $type, $title = null, $create = true)
    {
        $fileName       = Carbon::now()->toDateString() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $fileSystemPath = $this->fileSystemPath($type);
        $path = $file->storeAs(
            $fileSystemPath,
            $fileName
        );
        if ($create) {
            return $this->createFile($path, $file, $type, $title);
        } else {
            return $path;
        }
    }

    private function createFile($path, $file, $type, $title = null)
    {
        return $this->file()->create([
            'path'          => $path,
            'original_name' => $file->getClientOriginalName(),
            'type'          => $type,
            'extension'     => $file->getClientOriginalExtension(),
            'size'          => $file->getSize(),
            'file_title'    => $title
        ]);
    }

    private function fileSystemPath($type)
    {
        if (config('filesystems.default') == 'local') {
            throw new Exception('Please set the filesystems.default to public or ftp or s3', 400);
        }

        $path = '';
        switch (config('filesystems.default')) {
            case 's3':
                $path = config('app.env') == 'production' ? $type : "development/$type";
                break;

            case 'ftp':
                $path = "storage/$type";
                break;

            default:
                $path = $type;
                break;
        };
        return $path;
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function medias()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function media(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function hasFiles()
    {
        return $this->files()->count() > 0;
    }

    public function avatarImage(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')->where('type', 'avatar');
    }
}

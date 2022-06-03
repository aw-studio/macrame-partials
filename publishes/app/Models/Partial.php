<?php

namespace App\Models;

use App\Casts\PartialAttributesCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Macrame\Admin\Media\Traits\HasFiles;

class Partial extends Model
{
    use HasFiles;

    protected $fillable = [
        'attributes',
        'name',
        'template',
    ];

    protected $casts = [
        'attributes' => PartialAttributesCast::class,
    ];

    protected $attributes = [
        'attributes' => '[]',
    ];

    /**
     * Get all Partials from database for the available templates.
     *
     * @return Collection Partials
     */
    public static function allFromTemplates()
    {
        // make configurable?
        $path = resource_path('admin/js/Pages/Partial/components/templates');

        return collect(File::files($path))->filter(function ($templateFile) {
            return $templateFile->getExtension() == 'vue';
        })->map(function ($templateFile) {
            $template = Str::of($templateFile->getFilename())
                ->before('.vue')
                ->slug()
                ->toString();

            return self::firstOrCreate(
                [
                    'template' => $template,
                    'name'     => ucfirst($template),
                ],
            );
        });
    }
}

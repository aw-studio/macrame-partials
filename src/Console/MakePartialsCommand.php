<?php

namespace AwStudio\Partials\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakePartialsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:partials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Partials to Macrame admin backend app.';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new controller creator command instance.
     *
     * @param  Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Handle the execution of the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->makeAppFiles();
        $this->makeResourcesFiles();
        $this->addToInertiaMiddleware();

        return 0;
    }

    protected function makeAppFiles()
    {

        // Admin Controllers
        $this->files->copyDirectory(
            $this->publishPath('admin/Controllers'),
            base_path('admin/Http/Controllers')
        );

        // Admin Resources
        $this->files->copyDirectory(
            $this->publishPath('admin/Resources'),
            base_path('admin/Http/Resources')
        );

        // Database Migrations
        $this->files->copyDirectory(
            $this->publishPath('database/migrations'),
            database_path('migrations')
        );

        // Models
        $this->files->copyDirectory(
            $this->publishPath('app/models'),
            app_path('Models')
        );

        // Casts
        $this->files->copyDirectory(
            $this->publishPath('app/Casts'),
            app_path('Casts')
        );

        $this->addRoutes();
    }

    protected function addRoutes()
    {

        //
        $insert = "
    // partials
    Route::get('/partial', [PartialController::class, 'index'])->name('partial.index');
    Route::get('/partial/{partial}', [PartialController::class, 'show'])->name('partial.show');
    Route::put('/partial/{partial}', [PartialController::class, 'update'])->name('partial.update');
";

        $routesPath = base_path('routes/admin.php');
        $this->insertBefore(
            path: $routesPath,
            insert: $insert,
            before: '});'
        );

        $insert = "use Admin\Http\Controllers\PartialController;";
        $before = "use Illuminate\Support\Facades\Route;";

        $this->insertBefore($routesPath, $insert, $before);
    }

    protected function makeResourcesFiles()
    {

        // Partials
        $this->files->copyDirectory(
            $this->publishPath('resources/Pages/Partial'),
            resource_path('admin/js/Pages/Partial')
        );

        $this->addTypes();
        $this->addSidebarLink();
    }

    protected function addTypes()
    {

        // Types
        $insert = '// Partial
export interface Partial {
    id: number;
    attributes: { [k: string]: any };
    template: string;
    name: string;
    created_at: DateTime;
    update_at: DateTime;
}
export type PartialResource = Resource<Partial>;
export type PartialCollectionResource = CollectionResource<Partial>';
        $this->insertAtEnd(
            resource_path('admin/js/types/resources.ts'),
            $insert
        );

        $insert = '// Partials

export type Partial = {
    name: string;
    attributes: { [k: string]: any };
};
export type PartialForm = Form<Partial>;
';
        $this->insertAtEnd(
            resource_path('admin/js/types/forms.ts'),
            $insert
        );
    }

    protected function addSidebarLink()
    {
        $this->insertAtStart(
            resource_path('admin/js/modules/sidebar-navigation/index.ts'),
            'import { IconPartials } from \'@macramejs/admin-vue3\';'
        );
        //
        $insert = '// Partials
sidebarLinks.push({
    title: "Bereiche",
    href: "/admin/partial",
    icon: IconPartials
}); ';

        $this->insertAtEnd(
            resource_path('admin/js/modules/sidebar-navigation/index.ts'),
            $insert
        );
    }

    protected function addToInertiaMiddleware()
    {
        $path = app_path('Http/Middleware/HandleInertiaRequests.php');
        $insert = '
        "partials" => Partial::all()->mapWithKeys(function ($item) {
            return [$item["template"] => $item->attributes->parse()];
        }),
        ';
        $after = 'parent::share($request), [';

        $this->insertAfter($path, $insert, $after);

        $insert = '
use App\Models\Partial;';
        $after = 'use Inertia\Middleware;';

        $this->insertAfter($path, $insert, $after);
    }

    protected function publishPath($path)
    {
        return __DIR__.'/../../publishes/'.$path;
    }

    /**
     * Insert code at the end of the given file.
     *
     * @param  string $path
     * @param  string $insert
     * @return void
     */
    protected function insertAtEnd(string $path, string $insert)
    {
        $content = $this->files->get($path);

        if (str_contains($content, $insert)) {
            return;
        }

        $this->files->put($path, "{$content}\n\n{$insert}");

        $this->info("{$path} changed, please check it for correction and formatting.");
    }

    /**
     * Insert code at the start of the given file.
     *
     * @param  string $path
     * @param  string $insert
     * @return void
     */
    protected function insertAtStart(string $path, string $insert)
    {
        $content = $this->files->get($path);

        if (str_contains($content, $insert)) {
            return;
        }

        $this->files->put($path, "{$insert}\n{$content}");

        $this->info("{$path} changed, please check it for correction and formatting.");
    }

    /**
     * Insert code in the given file.
     *
     * @param  string $path
     * @param  string $insert
     * @param  string $after
     * @return void
     */
    protected function insertAfter(string $path, string $insert, string $after)
    {
        $content = $this->files->get($path);

        if (str_contains($content, $insert)) {
            return;
        }
        $content = Str::replaceFirst($after, $after.PHP_EOL.$insert, $content);

        $this->files->put($path, $content);

        $this->info("{$path} changed, please check it for correction and formatting.");
    }

    public function insertBefore(string $path, string $insert, string $before)
    {
        $content = $this->files->get($path);

        if (str_contains($content, $insert)) {
            return;
        }

        $content = Str::replaceFirst($before, $insert.PHP_EOL.$before, $content);

        $this->files->put($path, $content);

        $this->info("{$path} changed, please check it for correction and formatting.");
    }
}

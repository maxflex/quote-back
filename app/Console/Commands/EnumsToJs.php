<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Enums\{EntityType};

class EnumsToJs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enums-to-js';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export enums to JS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $folder = '../front/src/enums/';
        $flags = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_FORCE_OBJECT;

        // TODO: экспортировать автоматически все файлы из папки App/Enums без явного указания
        foreach ([EntityType::class] as $enum) {
            $classBaseName = Str::upper(Str::snake(class_basename($enum)));
            $toArray = json_encode($enum::toArray(), $flags);
            $toSelectArray = json_encode($enum::toSelectArray(), $flags);
            $content = <<<END
            export const {$classBaseName} = {$toArray}

            export const {$classBaseName}_TITLE = {$toSelectArray}
            END;
            file_put_contents($folder . Str::snake(class_basename($enum), '-') . '.js', $content);
        }
    }
}

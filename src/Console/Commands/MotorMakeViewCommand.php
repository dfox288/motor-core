<?php

namespace Motor\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MotorMakeViewCommand extends MotorAbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'motor:make:view {name} {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view file';

    protected function getTargetPath()
    {
        $values = $this->getTemplateVars();
        return resource_path('views').'/backend/'.$values['pluralSnake'].'/';
    }

    protected function getTargetFile()
    {
        return $this->argument('type').'.blade.php';
    }
    
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/views/'.$this->argument('type').'.blade.stub';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Check target file
        if (file_exists($this->getTargetPath().$this->getTargetFile())) {
            $this->error('View target '.$this->argument('type').' file exists');
            return;
        }

        $filesystem = new Filesystem();
        if (!$filesystem->isDirectory($this->getTargetPath())) {
            $filesystem->makeDirectory($this->getTargetPath(), 0755, true);
        }

        $stub = file_get_contents($this->getStub());
        $stub = $this->replaceTemplateVars($stub);
        file_put_contents($this->getTargetPath().$this->getTargetFile(), $stub);

        $this->info('View file '.$this->argument('type').' generated');
    }
}
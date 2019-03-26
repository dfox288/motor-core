<?php

namespace Motor\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Motor\Core\Helpers\GeneratorHelper;

abstract class MotorAbstractCommand extends Command
{

    protected function replaceTemplateVars($stub)
    {
        foreach ($this->getTemplateVars() as $key => $value) {
            $stub = str_replace('{{' . $key . '}}', $value, $stub);
        }

        return $stub;
    }


    protected function getTemplateVars()
    {
        $singularSnake = Str::snake(Str::singular($this->argument('name')));
        $pluralSnake   = Str::snake(Str::plural($this->argument('name')));

        $namespace = (! is_null($this->option('namespace')) ? $this->option('namespace') . '\\' : $this->laravel->getNamespace());
        $namespace = str_replace('/', '\\', $namespace);

        // Guess package name to prefix the views and i18n location
        $packageName = '';
        if ( ! is_null($this->option('namespace'))) {
            $packageName = str_replace('/', '-', strtolower($this->option('namespace'))) . '::';
        }

        return [
            'singularKebabWithoutPrefix' => Str::kebab(Str::singular(str_replace('Component', '',
                $this->argument('name')))),
            'pluralKebabWithoutPrefix'   => Str::kebab(Str::plural(str_replace('Component', '',
                $this->argument('name')))),
            'singularSnakeWithoutPrefix' => Str::snake(Str::singular(str_replace('Component', '',
                $this->argument('name')))),
            'pluralSnakeWithoutPrefix'   => Str::snake(Str::plural(str_replace('Component', '',
                $this->argument('name')))),
            'singularTitleWithoutPrefix' => Str::ucfirst(str_replace('_', ' ',
                (str_replace('Component', '', Str::singular($this->argument('name')))))),

            'singularSnake'     => $singularSnake,
            'pluralSnake'       => $pluralSnake,
            'singularKebab'     => Str::kebab(Str::singular($this->argument('name'))),
            'pluralKebab'       => Str::kebab(Str::plural($this->argument('name'))),
            'singularTitle'     => Str::ucfirst(str_replace('_', ' ', $singularSnake)),
            'pluralTitle'       => Str::ucfirst(str_replace('_', ' ', $pluralSnake)),
            'singularLowercase' => Str::lower(str_replace('_', ' ', $singularSnake)),
            'pluralLowercase'   => Str::lower(str_replace('_', ' ', $pluralSnake)),
            'singularStudly'    => Str::studly($singularSnake),
            'pluralStudly'      => Str::studly($pluralSnake),
            'namespace'         => $namespace,
            'namespaceNoSlash'  => str_replace('\\', '', $namespace),
            'packageName'       => $packageName,
            'randomInteger'     => rand(101, 999)
        ];
    }


    abstract protected function getTargetPath();


    abstract protected function getTargetFile();


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/i18n.stub';
    }
}

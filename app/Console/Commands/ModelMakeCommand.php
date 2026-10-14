<?php

namespace App\Console\Commands;

//use Illuminate\Console\Command;
use Illuminate\Foundation\Console\ModelMakeCommand as Command;

class ModelMakeCommand extends Command
{
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */

    ############### Overwrite The Existing Function To Change Model Default Path
    ############### The New Path = [  App/Models  ] Alternative [  App  ]
    protected function getDefaultNamespace($rootNamespace)
    {
        return "{$rootNamespace}\Models";
    }
}

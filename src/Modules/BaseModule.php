<?php

namespace Bakhari\GenericHost\Modules;

use Bakhari\Console\Contracts\Console;
use Bakhari\GenericHost\Contracts\Module;

class BaseModule implements Module
{
    /**
     * The Console.
     *
     * @var \Bakhari\Console\Contracts\Console
     */
    protected $console;

    /**
     * Create the Filter Module
     *
     * @param   Bakhari\GenericHost\Contracts\Console
     * @param   mixed   $module_extra_config
     */
    public function __construct(Console $console, $module_extra_config = null)
    {
        $this->console = $console;
    }

    /**
     * Get console interface
     *
     * @return \Bakhari\Console\Contracts\Console
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Execute the command
     *
     * @param   \Bakhari\Console\Contracts\Command    $command
     * @param   bool    $dry_run
     * @param   int     $wait
     *
     * @return  \Bakhari\Console\Contracts\ReturnContract
     */
    public function exec(Command $command, $dry_run = false, $wait = 10000)
    {
        if($console = $this->getConsole()) {

            return $console->run($command, $dry_run, $wait);
        }

        return
            new ReturnClass([
                'status_code'   => 1,
                'errno'         => 500,
                'error'         => 'Console is not ready',
            ]);
    }

}

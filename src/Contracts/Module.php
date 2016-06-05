<?php

namespace Bakhari\GenericHost\Contracts;

use Bakhari\Console\Contracts\Console;

interface Module
{
    /**
     * Create the Module
     *
     * @param   Bakhari\GenericHost\Contracts\Console
     * @param   mixed   $module_extra_config
     */
    public function __construct(Console $console, $module_extra_config = null);
}

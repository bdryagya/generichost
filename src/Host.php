<?php

namespace Bakhari\GenericHost;

use Exception;

use Bakhari\Console\Console;
use Bakhari\Console\Streams\FileOutputStream;

use Illuminate\Config\Repository as Config;

class Host
{
    /**
     * The host configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * The host console.
     *
     * @var \Bakhari\Console\Contracts\Console
     */
    protected $console;

    /**
     * Loaded Module lists.
     *
     * @var array
     */
    protected $modules = [];

    /**
     * Module map
     *
     * @var array
     */
    protected $module_map = __DIR__ . '/Modules/map.php';

    /**
     * Create a new Host instance.
     * @param   array|string    $config
     *
     * @return  void
     */
    public function __construct($config = null)
    {
        if(isset($config)) {

            $this->config = $config;

            $load_modules = null;

            /*
             * Remove configs not related to console
             */
            unset($config['load_modules']);

            unset($config['file_output']);

            /*
             * Instantiate Console
             */
            $this
                ->attachConsole(
                    new Console(
                        new Config($this->config)
                    )
                );

            /*
             * Autoload modules
             */
            if(isset($this->config['load_modules'])) {

                foreach($load_modules as $module_name => $module_extra_config) {

                    $this->loadModule($module_name, $module_config_extra);
                }
            }

            /*
             * We'll output to file, if fileoutput is set to true
             */
            if(isset($this->config['file_output'])) {
                $this->console
                    ->getStreamManager()
                    ->pushOutputStream(
                        new FileOutputStream($this->config['file_output'])
                    );
            }
            
        }
    }

    /**
     * Set Console Interface
     *
     * @param   \Bakhari\Console\Contracts\Console
     * 
     * @return  void
     */
    public function attachConsole(Console $console)
    {
        return $this->console = $console;
    }

    /**
     * Get the console interface.
     * 
     * @return  \Bakhari\Console\Contracts\Console
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Login to device console
     *
     * @return  void
     */
    public function login()
    {
        $this->getConsole()->login();
    }

    /**
     * Set Module path
     *
     * @var string  $filepath
     *
     * @return  void
     */
    public function setModuleMap($filepath)
    {
        $this->module_map = $filepath;
    }

    /**
     * Module loader
     *
     * @var string  $module
     *
     * @return  $module
     */
    public function loadModule($module_name, $module_extra_config = null)
    {
        /*
         * Load Module only if console is ready
         */
        if(! $this->getConsole()){
            throw new Exception('Console is not attached!. Please attach a Console before loading other modules.');
        }

        /*
         * If already loaded we'll return the loaded module
         */
        if(array_key_exists($module_name, $this->modules)) {
            return $this->$module_name;
        }

        /*
         * Load module map
         */
        $module_map = require $this->module_map;

        $module = $module_map[$module_name];

        /*
         * Push to loaded modules
         */
        array_push($this->modules, $module_name);

        return $this->$module_name = new $module($this->getConsole(), $module_extra_config);
    }
}

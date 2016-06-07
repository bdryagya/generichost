<?php

namespace Bakhari\GenericHost\Builders;

use Exception;

use Bakhari\Console\Command;

class Command 
{
    /**
     * The built list.
     *
     * @var array;
     */
    protected $list = [];

    /**
     * Template Directory
     *
     * @var string
     */
    protected $template_dir = __DIR__ . '/../Templates';

    /**
     * Create a new Builder.
     *
     * @param   array   $template_dir
     */
    public function __construct($config = [])
    {

        if(isset($config['template_dir'])) {
            $this->template_dir = $config['template_dir'];
        }
    }

    /**
     * Build command
     *
     * @var array   $config
     *
     * @return  void
     */
    public function make($config)
    {
        if(is_array($config) && isset($config['config'])) {

            extract($config['config']);

            $template_file = $this->getTemplateDir() . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $config['template']). '.php';

            if(file_exists($template_file)) {

                $command = require $template_file;

                $this->list = $command;
                
            } else {

                throw new Exception('Filter template ' . $config['template'] . ' does not exists!');
            }
        } elseif(is_array($config)) {

            $this->list = $config;
        } else {

            $this->list = array($config);
        }
        
        return new Command($this->list);
    }

    /**
     * Set template directory
     *
     * @param   string  $dirpath
     * 
     * @return  void
     */
    public function setTemplateDir($dir = null)
    {
        return $this->template_dir = $dir;
    }

    /**
     * Get template directory
     *
     * @return  string $dirpath
     */
    public function getTemplateDir()
    {
        return $this->template_dir;
    }
}

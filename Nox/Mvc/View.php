<?php

namespace Nox\Mvc;

use Nox\Core\Base;
use Nox\Core\THash;
use Nox\Exceptions\ViewNotFoundException;
use Nox\Fs\FileManager;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Class View
 * @package App\Views
 */
class View extends Base
{
    use THash;

    /** @var array */
    protected $data = [];

    /** @var array|string[] */
    protected $templatesPath = [];
    
    /** @var string */
    protected $cachePath;

    /** @var string */
    protected $controllerName;

    /** @var \Nox\Core\Application */
    protected $app;
    
    const TEMPLATE_FORMATS = [
        '.html',
        '.twig',
    ];

    const CONTROLLER_SUFFIXES = [
        'Controller',
        'Ctrl'
    ];
    
    public function __construct(string $controllerName)
    {
        parent::__construct();
        
        $this->controllerName = str_replace(self::CONTROLLER_SUFFIXES, '', $controllerName);

        $config = $this->app->config;
        $this->addTemplatesPath($config->templatesPath);
        $this->addTemplatesPath($config->templatesPath . $this->controllerName . '/');

        $this->cachePath = $config->cachePath;
    }

    /**
     * @param string $path
     */
    public function addTemplatesPath(string $path)
    {
        if (is_dir($path)) {
            $this->templatesPath[] = $path;
        }
    }

    public function __get($name)
    {
        return $this->hashGet($name);
    }

    public function __set($name, $value)
    {
        $this->hashSet($name, $value);
    }

    public function __isset($name)
    {
        return $this->hashIsSet($name);
    }

    public function __unset($name)
    {
        $this->hashUnset($name);
    }

    /**
     * @param string $action
     * @return string
     */
    public function render(string $action)
    {
        foreach ($this->__data as $prop => $value) {
            $$prop = $value;
        }
        
        $template = $this->findTemplate($action);

        ob_start();
        include $this->templatesPath . $template;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * @param string $action
     * @return string
     */
    public function renderTwig(string $action)
    {
        $loader = new Twig_Loader_Filesystem($this->templatesPath);

        $twig = new Twig_Environment($loader, [
            'cache' => $this->cachePath . '/Cache/Twig/',
            'auto_reload' => true
        ]);

        $template = $this->findTemplate($action);

        ob_start();
        echo $twig->render($template, $this->__data);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * @param string $action
     */
    public function display(string $action)
    {
        echo $this->renderTwig($action);
    }

    /**
     * @param string $action
     * @return string
     * @throws ViewNotFoundException
     */
    protected function findTemplate(string $action): string
    {
        $formats = implode(',', self::TEMPLATE_FORMATS);

        foreach ($this->templatesPath as $path) {
            $templates = FileManager::getFiles(
                $path,
                strtolower($action).'*'.'{'.$formats.'}',
                PATHINFO_BASENAME
            );
            if (count($templates) > 0) {
                return $templates[0];
            }
        }

        throw new ViewNotFoundException('Appropriate template not found for an action '.$action);
    }
}

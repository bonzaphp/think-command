<?php
/**
 * Created by yang
 * User: bonzaphp@gmail.com
 * Date: 2019/6/4
 * Time: 16:58
 */

namespace bonza\think\command\command\make;

use think\console\command\Make;
use think\console\input\Option;
use think\facade\App;
use think\facade\Config;

/**
 * 生成控制器
 * Class Action
 * @author bonzaphp@gmail.com
 * @Date 2019-09-02 17:41
 * @package bonza\think\command\command\make
 */
class Action extends Make
{
    protected $type = 'Controller';

    protected function configure(): void
    {
        parent::configure();
        $this->setName('bonza:action')
            ->addOption('api', null, Option::VALUE_NONE, 'Generate an api controller class.')
            ->addOption('plain', null, Option::VALUE_NONE, 'Generate an empty controller class.')
            ->setDescription('Create a new resource controller class by self');
    }

    protected function getStub(): string
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR;
        if ($this->input->getOption('api')) {
            return $stubPath . 'controller.api.stub';
        }
        if ($this->input->getOption('plain')) {
            return $stubPath . 'controller.plain.stub';
        }
        return $stubPath . 'controller.stub';
    }

    protected function getClassName($name)
    {
        return parent::getClassName($name) . (Config::get('controller_suffix') ? ucfirst(Config::get('url_controller_layer')) : '');
    }

    /**
     * 获取时间
     * @return string
     * @author bonzaphp@gmail.com
     */
    protected function getTime(): string
    {
        return date('H:i:s');
    }

    /**
     * 获取年月日
     * @return string
     * @author bonzaphp@gmail.com
     */
    protected function getDate(): string
    {
        return date('y/m/d');
    }

    /**
     * 获取类名的单数形式
     * @param  string  $name 文件名
     * @return string
     * @author bonzaphp@gmail.com
     */
    protected function getFieldName(string $name): string
    {
        $name = $this->getClassName($name);
        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
        $class = str_replace($namespace . '\\', '', $name);
        return rtrim(strtolower(preg_replace_callback('/([a-z])([A-Z])/', static function ($matches) {
            return $matches[1] . '_' . $matches[2];
        }, $class)), 's');
    }

    protected function buildClass($name)
    {
        $stub = file_get_contents($this->getStub());
        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
        $class = str_replace($namespace . '\\', '', $name);
        $module = explode('\\', $namespace)[1];
        return str_replace(['{%className%}', '{%actionSuffix%}', '{%namespace%}', '{%app_namespace%}', '{%fieldName%}', '{%date%}', '{%time%}', '{%module%}'], [
            $class,
            Config::get('action_suffix'),
            $namespace,
            App::getNamespace(),
            $this->getFieldName($name),
            $this->getDate(),
            $this->getTime(),
            $module,
        ], $stub);
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, $module) . '\controller';
    }

}
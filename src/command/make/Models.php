<?php
/**
 * Created by yang
 * User: bonzaphp@gmail.com
 * Date: 2019/6/8
 * Time: 11:13
 */


namespace bonza\think\command\make;

use think\console\command\Make;
use think\facade\App;
use think\facade\Config;

class Models extends Make
{
    protected $type = 'Model';

    protected function configure()
    {
        parent::configure();
        $this->setName('make:models')
            ->setDescription('Create a new model class');
    }

    protected function getStub()
    {
        return __DIR__ . DIRECTORY_SEPARATOR;
    }

    /**
     * 获取时间
     * @author bonzaphp@gmail.com
     * @return string
     */
    protected function getTime():string
    {
        return date('H:i:s');
    }

    /**
     * 获取年月日
     * @author bonzaphp@gmail.com
     * @return string
     */
    protected function getDate():string
    {
        return date('y/m/d');
    }

    /**
     * 获取类名的单数形式
     * @author bonzaphp@gmail.com
     * @param string $name 参数
     * @return string
     */
    protected function getFieldName(string $name) :string
    {
        $name = $this->getClassName($name);
        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
        $class = str_replace($namespace . '\\', '', $name);
        return strtolower(rtrim(preg_replace_callback('/([a-z])([A-Z])/', static function ($matches) {
            return $matches[1] . '_' . $matches[2];
        }, $class), ''));
    }

    protected function buildClass($name)
    {
        $stub = file_get_contents($this->getStub());

        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

        $class = str_replace($namespace . '\\', '', $name);

        $module = explode('\\',$namespace)[1];

        return str_replace(['{%className%}', '{%actionSuffix%}', '{%namespace%}', '{%app_namespace%}','{%fieldName%}','{%date%}','{%time%}','{%module%}'], [
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
        return parent::getNamespace($appNamespace, $module) . '\model';
    }
}
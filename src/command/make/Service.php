<?php
/**
 * Created by yang
 * User: bonzaphp@gmail.com
 * Date: 2019/6/4
 * Time: 16:58
 */

namespace bonza\think\command\command\make;

use think\console\command\Make;
use think\facade\App;
use think\facade\Config;

class Service extends Make
{
    protected $type = 'Service';

    protected function configure(): void
    {
        parent::configure();
        $this->setName('bonza:service')
            ->setDescription('Create a service class');
    }

    protected function getStub(): string
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR;
        return $stubPath . 'service.stub';
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, $module) . '\service';
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
     * @param string $name 参数
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
        return str_replace(['{%className%}', '{%actionSuffix%}', '{%namespace%}', '{%app_namespace%}', '{%fieldName%}', '{%date%}', '{%time%}'], [
            $class,
            Config::get('action_suffix'),
            $namespace,
            App::getNamespace(),
            $this->getFieldName($name),
            $this->getDate(),
            $this->getTime(),
        ], $stub);
    }
}
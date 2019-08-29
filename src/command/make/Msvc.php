<?php
/**
 * Created by yang
 * User: bonzaphp@gmail.com
 * Date: 2019/6/8
 * Time: 10:42
 */
namespace bonza\think\command\make;

use think\Console;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

/**
 * 用于批量执行命令
 * Class Msvc
 * @author bonzaphp@gmail.com
 * @Date {DATE}
 * @package bonza\think\command
 */
class Msvc extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('msvc')
             ->addArgument('name', Argument::REQUIRED, 'The name of the class')
             ->setDescription('Batch execution of commands');
        // 设置参数
        
    }

    /**
     * 批量执行命令生成模型，控制器，service，validate
     * @author bonzaphp@gmail.com
     * @param Input $input
     * @param Output $output
     * @return int|null|void
     */
    protected function execute(Input $input, Output $output)
    {
        $commands = ['make:models','make:service','make:validate','make:action'];
        $msg = [];

        $name = trim($input->getArgument('name'));

        foreach ($commands as $command)
        {
            if ($command === 'make:models')
            {
                $m = preg_replace_callback('/^\w+/', static function (){
                    return 'common';
                },$name);
                $res = Console::call($command,[$m])->fetch();
                $msg[] = $res;
                continue;
            }
            $res = Console::call($command,[$name])->fetch();
            //本项目的命名规则
            $msg[] = $res;
        }
    	// 指令输出
    	$output->writeln(implode('',$msg));
    }


}

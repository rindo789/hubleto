<?php

namespace HubletoMain\Cli\Agent\App;

class ShowInstalled extends \HubletoMain\Cli\Agent\Command
{
  public function run()
  {
    $cli = $this->cli;
    $main = $this->main;

    $apps = $main->config['apps'];
    ksort($apps);

    $cli->cyan("You have following apps installed:\n");
    foreach ($apps as $appClass => $appConfig) $cli->cyan("  {$appClass}: " . json_encode($appConfig) . "\n");
  }
}
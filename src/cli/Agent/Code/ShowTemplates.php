<?php

namespace HubletoMain\Cli\Agent\Code;

class ShowTemplates extends \HubletoMain\Cli\Agent\Command
{
  public function run()
  {
    $this->cli->cyan("Templates for `code generate`:\n");
    $this->cli->cyan("  Model - generate model to specified app\n");
  }
}
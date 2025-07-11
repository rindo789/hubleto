<?php

namespace HubletoMain\Cli\Agent\Create;

class ApiEndpoint extends \HubletoMain\Cli\Agent\Command
{

  public function run(): void
  {
    $appNamespace = (string) ($this->arguments[3] ?? '');
    $endpoint = (string) ($this->arguments[4] ?? '');
    $force = (bool) ($this->arguments[5] ?? false);

    $endpointPascalCase = \ADIOS\Core\Helper::kebabToPascal($endpoint);

    $this->main->apps->init();

    if (empty($appNamespace)) throw new \Exception("<appNamespace> not provided.");
    if (empty($endpoint)) throw new \Exception("<endpoint> not provided.");

    $app = $this->main->apps->getAppInstance($appNamespace);

    if (!$app) throw new \Exception("App '{$appNamespace}' does not exist or is not installed.");

    $appFolder = $app->rootFolder;

    if (is_file($appFolder . '/Controllers/Api/' . $endpointPascalCase . '.php') && !$force) {
      throw new \Exception("REST API endpoint '{$endpoint}' already exists in app '{$appNamespace}'. Use 'force' to overwrite existing files.");
    }

    $tplFolder = __DIR__ . '/../../../code_templates/snippets';
    $this->main->addTwigViewNamespace($tplFolder, 'snippets');

    $tplVars = [
      'appNamespace' => $appNamespace,
      'endpoint' => $endpoint,
      'endpointPascalCase' => $endpointPascalCase,
    ];

    if (!is_dir($appFolder . '/Controllers')) mkdir($appFolder . '/Controllers');
    if (!is_dir($appFolder . '/Controllers/Api')) mkdir($appFolder . '/Controllers/Api');
    file_put_contents($appFolder . '/Controllers/Api/' . $endpointPascalCase . '.php', $this->main->twig->render('@snippets/ApiController.php.twig', $tplVars));

    $this->cli->cyan("REST API endpoint '{$endpoint}' in '{$appNamespace}' created successfully.\n");
    $this->cli->yellow("⚠ NEXT STEPS:\n");
    $this->cli->yellow("⚠  -> Add the route in the `init()` method of {$app->rootFolder}/Loader.php\n");
    $this->cli->blue  ("      \$this->main->router->httpGet([ '/^{$app->manifest['rootUrlSlug']}\/api\/{$endpoint}\/?$/' => Controllers\\Api\\{$endpointPascalCase}::class ]);\n");
    $this->cli->yellow("\n");
    
  }

}
<?php

namespace HubletoApp\Community\Help;

class Loader extends \HubletoMain\Core\App
{

  /** @var array<string, string> */
  public array $hotTips = [];

  /** @var array<string, array<string, string>> */
  public array $contextHelpUrls = [];

  public function init(): void
  {
    parent::init();

    $this->main->router->httpGet([
      '/^help\/?$/' => Controllers\Help::class,
    ]);

    // $this->main->sidebar->addLink(1, 1900, 'help', $this->translate('Help'), 'fas fa-life-ring', str_starts_with($this->main->requestedUri, 'help'));
  }

  public function installDefaultPermissions(): void
  {
    $mPermission = new \HubletoApp\Community\Settings\Models\Permission($this->main);
    $permissions = [
      "HubletoApp/Community/Help/Controllers/Help",
      "HubletoApp/Community/Help/Help",
    ];

    foreach ($permissions as $permission) {
      $mPermission->eloquent->create([
        "permission" => $permission
      ]);
    }
  }

  public function addHotTip(string $slugRegExp, string $title): void
  {
    $this->hotTips[$slugRegExp] = $title;
  }

  public function addContextHelpUrls(string $slugRegExp, array $urls): void
  {
    $this->contextHelpUrls[$slugRegExp] = $urls;
  }

  public function getCurrentContextHelpUrls(string $slugRegExp): array
  {
    foreach ($this->contextHelpUrls as $regExp => $urls) {
      if (preg_match($regExp, $slugRegExp)) {
        return $urls;
      }
    }

    return [];
  }

}
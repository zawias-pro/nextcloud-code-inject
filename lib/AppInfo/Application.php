<?php

declare(strict_types=1);

namespace OCA\Codeinjector\AppInfo;

use OCA\Codeinjector\Middleware\InjectMiddleware;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App implements IBootstrap {

	public const APP_ID = 'codeinjector';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerMiddleware(InjectMiddleware::class, true);
	}

	public function boot(IBootContext $context): void {}
}

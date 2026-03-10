<?php

declare(strict_types=1);

namespace OCA\Codeinjector\AppInfo;

use OCA\Codeinjector\Listener\TemplateListener;
use OCA\Codeinjector\Middleware\InjectMiddleware;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Http\Events\BeforeTemplateRenderedEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventDispatcher;

class Application extends App implements IBootstrap {

	public const APP_ID = 'codeinjector';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		// Register the middleware globally to intercept all AppFramework responses
		$context->registerMiddleware(InjectMiddleware::class, true);
	}

	public function boot(IBootContext $context): void {
	}
}

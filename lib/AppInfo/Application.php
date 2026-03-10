<?php

declare(strict_types=1);

namespace OCA\CodeInjector\AppInfo;

use OCA\CodeInjector\Listener\TemplateListener;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Http\TemplateResponse;

class Application extends App implements IBootstrap {

	public const APP_ID = 'codeinjector';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		// Inject HTML on every page load (both logged-in and public pages)
		$context->registerEventListener(
			TemplateResponse::EVENT_LOAD_ADDITIONAL_SCRIPTS,
			TemplateListener::class
		);
	}

	public function boot(IBootContext $context): void {
	}
}

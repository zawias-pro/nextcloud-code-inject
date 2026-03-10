<?php

declare(strict_types=1);

namespace OCA\Codeinjector\AppInfo;

use OCA\Codeinjector\Listener\TemplateListener;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Http\Events\BeforeTemplateRenderedEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;

class Application extends App implements IBootstrap {

	public const APP_ID = 'codeinjector';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		// Intentionally empty. Event listener is attached in boot() via closure.
	}

	public function boot(IBootContext $context): void {
		$context->injectFn(function (IEventDispatcher $dispatcher): void {
			$listener = new TemplateListener();
			$dispatcher->addListener(BeforeTemplateRenderedEvent::class, function (Event $event) use ($listener): void {
				$listener->handle($event);
			});
			$dispatcher->addListener(AddContentSecurityPolicyEvent::class, function (Event $event) use ($listener): void {
				$listener->handle($event);
			});
		});
	}
}

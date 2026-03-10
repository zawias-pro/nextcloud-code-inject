<?php

declare(strict_types=1);

namespace OCA\Codeinjector\Listener;

use OCA\Codeinjector\AppInfo\Application;
use OCP\AppFramework\Http\Events\BeforeTemplateRenderedEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IConfig;
use OCP\Server;
use OCP\Util;

/**
 * Runs on every page render and injects configured HTML into head/body
 * by publishing data via Nextcloud's initial-state mechanism and loading
 * the client-side injector script.
 *
 * @template-implements IEventListener<BeforeTemplateRenderedEvent>
 */
class TemplateListener implements IEventListener {

	public function handle(Event $event): void {
		if (!($event instanceof BeforeTemplateRenderedEvent)) {
			return;
		}

		$config = Server::get(IConfig::class);
		$headHtml = $config->getAppValue(Application::APP_ID, 'head_html', '');
		$bodyHtml = $config->getAppValue(Application::APP_ID, 'body_html', '');

		if ($headHtml === '' && $bodyHtml === '') {
			return;
		}

		// Publish data using Nextcloud's initial-state format so the client-side
		// injector can read it without an extra HTTP round-trip.
		// Format: <script id="initial-state-{appId}-{key}" type="application/json">
		//             base64(JSON.stringify(value))
		//         </script>
		if ($headHtml !== '') {
			Util::addHeader('script', [
				'id'   => 'initial-state-' . Application::APP_ID . '-head',
				'type' => 'application/json',
				'nonce' => '',
			], base64_encode(json_encode($headHtml, JSON_THROW_ON_ERROR)));
		}

		if ($bodyHtml !== '') {
			Util::addHeader('script', [
				'id'   => 'initial-state-' . Application::APP_ID . '-body',
				'type' => 'application/json',
				'nonce' => '',
			], base64_encode(json_encode($bodyHtml, JSON_THROW_ON_ERROR)));
		}

		Util::addScript(Application::APP_ID, 'inject');
	}
}

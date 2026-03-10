<?php

declare(strict_types=1);

namespace OCA\Codeinjector\Listener;

use OCA\Codeinjector\AppInfo\Application;
use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\AppFramework\Http\Events\BeforeTemplateRenderedEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IConfig;
use OCP\Server;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;
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
		if ($event instanceof BeforeTemplateRenderedEvent) {
			$this->handleTemplateEvent();
			return;
		}

		if ($event instanceof AddContentSecurityPolicyEvent) {
			$this->handleCspEvent($event);
			return;
		}
	}

	private function handleTemplateEvent(): void {
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

	private function handleCspEvent(AddContentSecurityPolicyEvent $event): void {
		$config = Server::get(IConfig::class);
		$cspRules = trim($config->getAppValue(Application::APP_ID, 'csp_rules', ''));
		if ($cspRules === '') {
			return;
		}

		$policy = new ContentSecurityPolicy();
		foreach (preg_split('/\r\n|\r|\n/', $cspRules) as $line) {
			$line = trim($line);
			if ($line === '' || str_starts_with($line, '#')) {
				continue;
			}
			$this->applyCspLine($policy, $line);
		}

		$event->addPolicy($policy);
	}

	private function applyCspLine(ContentSecurityPolicy $policy, string $line): void {
		$line = rtrim($line, ';');
		$parts = preg_split('/\s+/', $line);
		if ($parts === false || count($parts) === 0) {
			return;
		}

		$directive = strtolower((string)array_shift($parts));
		if (count($parts) === 0) {
			$this->applyKeywordDirective($policy, $directive);
			return;
		}

		switch ($directive) {
			case 'script-src':
				$this->applySourceList($policy, 'script', $parts);
				break;
			case 'style-src':
				$this->applySourceList($policy, 'style', $parts);
				break;
			case 'font-src':
				$this->applySourceList($policy, 'font', $parts);
				break;
			case 'img-src':
			case 'image-src':
				$this->applySourceList($policy, 'image', $parts);
				break;
			case 'connect-src':
				$this->applySourceList($policy, 'connect', $parts);
				break;
			case 'media-src':
				$this->applySourceList($policy, 'media', $parts);
				break;
			case 'object-src':
				$this->applySourceList($policy, 'object', $parts);
				break;
			case 'frame-src':
				$this->applySourceList($policy, 'frame', $parts);
				break;
			case 'child-src':
				$this->applySourceList($policy, 'child', $parts);
				break;
			case 'frame-ancestors':
				$this->applySourceList($policy, 'frame-ancestors', $parts);
				break;
			case 'worker-src':
				$this->applySourceList($policy, 'worker', $parts);
				break;
			case 'form-action':
				$this->applySourceList($policy, 'form-action', $parts);
				break;
			default:
				$this->applyKeywordDirective($policy, $directive);
				break;
		}
	}

	private function applyKeywordDirective(ContentSecurityPolicy $policy, string $directive): void {
		switch ($directive) {
			case 'strict-dynamic':
			case 'use-strict-dynamic':
				$policy->useStrictDynamic();
				break;
			case 'strict-dynamic-on-scripts':
			case 'use-strict-dynamic-on-scripts':
				$policy->useStrictDynamicOnScripts();
				break;
			case 'use-js-nonce':
				$policy->useJsNonce();
				break;
			case 'allow-eval-script':
				$policy->allowEvalScript();
				break;
			case 'allow-eval-wasm':
				$policy->allowEvalWasm();
				break;
			case 'allow-inline-style':
				$policy->allowInlineStyle();
				break;
		}
	}

	/**
	 * @param list<string> $sources
	 */
	private function applySourceList(ContentSecurityPolicy $policy, string $bucket, array $sources): void {
		foreach ($sources as $source) {
			$source = strtolower(trim($source));
			$source = trim($source, " \t\n\r\0\x0B;");
			if ($source === '' || $source === "'self'" || $source === "'none'") {
				continue;
			}

			if ($source === "'strict-dynamic'") {
				$policy->useStrictDynamic();
				continue;
			}
			if ($source === "'unsafe-eval'") {
				$policy->allowEvalScript();
				continue;
			}
			if ($bucket === 'style' && $source === "'unsafe-inline'") {
				$policy->allowInlineStyle();
				continue;
			}
			if ($source === "'nonce-'" || str_starts_with($source, "'nonce-")) {
				$policy->useJsNonce();
				continue;
			}

			switch ($bucket) {
				case 'script':
					$policy->addAllowedScriptDomain($source);
					break;
				case 'style':
					$policy->addAllowedStyleDomain($source);
					break;
				case 'font':
					$policy->addAllowedFontDomain($source);
					break;
				case 'image':
					$policy->addAllowedImageDomain($source);
					break;
				case 'connect':
					$policy->addAllowedConnectDomain($source);
					break;
				case 'media':
					$policy->addAllowedMediaDomain($source);
					break;
				case 'object':
					$policy->addAllowedObjectDomain($source);
					break;
				case 'frame':
					$policy->addAllowedFrameDomain($source);
					break;
				case 'child':
					$policy->addAllowedChildSrcDomain($source);
					break;
				case 'frame-ancestors':
					$policy->addAllowedFrameAncestorDomain($source);
					break;
				case 'worker':
					$policy->addAllowedWorkerSrcDomain($source);
					break;
				case 'form-action':
					$policy->addAllowedFormActionDomain($source);
					break;
			}
		}
	}
}

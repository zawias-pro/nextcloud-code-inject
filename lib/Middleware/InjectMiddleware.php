<?php

declare(strict_types=1);

namespace OCA\Codeinjector\Middleware;

use OCA\Codeinjector\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Middleware;
use OCP\IConfig;
use OCP\Server;

/**
 * Middleware to inject custom HTML into the response.
 */
class InjectMiddleware extends Middleware {

	private IConfig $config;

	public function __construct(IConfig $config) {
		$this->config = $config;
	}

	/**
	 * Intercept the rendered output and perform string replacements.
	 */
	public function beforeOutput(Controller $controller, string $methodName, string $output): string {
		$headHtml = trim($this->config->getAppValue(Application::APP_ID, 'head_html', ''));
		$bodyBeforeHtml = trim($this->config->getAppValue(Application::APP_ID, 'body_before_html', ''));
		$bodyAfterHtml = trim($this->config->getAppValue(Application::APP_ID, 'body_after_html', ''));

		if ($headHtml === '' && $bodyBeforeHtml === '' && $bodyAfterHtml === '') {
			return $output;
		}

		$nonce = '';
		// Try to get the nonce from the internal manager if available.
		// Use string class name to avoid direct dependency on private namespace in code.
		$nonceManagerClass = 'OC\\Security\\CSP\\ContentSecurityPolicyNonceManager';
		if (class_exists($nonceManagerClass)) {
			try {
				$nonceManager = Server::get($nonceManagerClass);
				if (method_exists($nonceManager, 'getNonce')) {
					$nonce = $nonceManager->getNonce();
				}
			} catch (\Throwable) {
				// Ignore errors if manager cannot be resolved
			}
		}

		if ($nonce !== '') {
			$headHtml = str_replace('{{csp_nonce}}', $nonce, $headHtml);
			$bodyBeforeHtml = str_replace('{{csp_nonce}}', $nonce, $bodyBeforeHtml);
			$bodyAfterHtml = str_replace('{{csp_nonce}}', $nonce, $bodyAfterHtml);
		}

		if ($headHtml !== '' && str_contains($output, '</head>')) {
			$output = str_replace('</head>', $headHtml . "\n</head>", $output);
		}

		if ($bodyBeforeHtml !== '') {
			$output = preg_replace('/(<body[^>]*>)/i', '$1' . "\n" . $bodyBeforeHtml, $output, 1);
		}

		if ($bodyAfterHtml !== '' && str_contains($output, '</body>')) {
			$output = str_replace('</body>', $bodyAfterHtml . "\n</body>", $output);
		}

		return $output;
	}
}

<?php

declare(strict_types=1);

namespace OCA\Codeinjector\Middleware;

use OCA\Codeinjector\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Middleware;
use OCP\IConfig;

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
		$bodyHtml = trim($this->config->getAppValue(Application::APP_ID, 'body_html', ''));

		if ($headHtml === '' && $bodyHtml === '') {
			return $output;
		}

		if ($headHtml !== '' && str_contains($output, '</head>')) {
			$output = str_replace('</head>', $headHtml . "\n</head>", $output);
		}

		if ($bodyHtml !== '' && str_contains($output, '</body>')) {
			$output = str_replace('</body>', $bodyHtml . "\n</body>", $output);
		}

		return $output;
	}
}

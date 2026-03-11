<?php

declare(strict_types=1);

namespace OCA\Codeinjector\Middleware;

use OCA\Codeinjector\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Middleware;
use OCP\IConfig;
use Psr\Log\LoggerInterface;

/**
 * Middleware to inject custom HTML into the response.
 */
class InjectMiddleware extends Middleware {

	public function __construct(
		private readonly IConfig $config,
		private readonly LoggerInterface $logger,
	) {
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
		if (preg_match('/<script[\s\S]*?nonce="([^"]+)"/i', $output, $matches)) {
			$nonce = $matches[1];
		}

		if ($nonce === '') {
			$this->logger->warning(
				'Could not extract CSP nonce from page output; {{csp_nonce}} placeholders will not be replaced.',
				['app' => Application::APP_ID]
			);
		}

        $headHtml = str_replace('{{csp_nonce}}', $nonce, $headHtml);
        $bodyBeforeHtml = str_replace('{{csp_nonce}}', $nonce, $bodyBeforeHtml);
        $bodyAfterHtml = str_replace('{{csp_nonce}}', $nonce, $bodyAfterHtml);

		if ($headHtml !== '') {
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

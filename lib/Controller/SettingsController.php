<?php

declare(strict_types=1);

namespace OCA\Codeinjector\Controller;

use OCA\Codeinjector\AppInfo\Application;
use OCA\Codeinjector\Settings\AdminSettings;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\AuthorizedAdminSetting;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\IConfig;
use OCP\IRequest;

class SettingsController extends Controller {

	public function __construct(
		IRequest $request,
		private readonly IConfig $config,
	) {
		parent::__construct(Application::APP_ID, $request);
	}

	/**
	 * Persist all three HTML snippets. Only accessible to Nextcloud admins.
	 */
	#[AuthorizedAdminSetting(settings: AdminSettings::class)]
	#[NoCSRFRequired]
	public function save(
		string $headHtml = '',
		string $bodyBeforeHtml = '',
		string $bodyAfterHtml = '',
	): RedirectResponse {
		$this->config->setAppValue(Application::APP_ID, 'head_html', $headHtml);
		$this->config->setAppValue(Application::APP_ID, 'body_before_html', $bodyBeforeHtml);
		$this->config->setAppValue(Application::APP_ID, 'body_after_html', $bodyAfterHtml);

		return new RedirectResponse('/settings/admin/' . Application::APP_ID . '?saved=1');
	}

	/**
	 * Return the currently stored snippets. Only accessible to Nextcloud admins.
	 */
	#[AuthorizedAdminSetting(settings: AdminSettings::class)]
	public function load(): DataResponse {
		return new DataResponse([
			'headHtml' => $this->config->getAppValue(Application::APP_ID, 'head_html', ''),
			'bodyBeforeHtml' => $this->config->getAppValue(Application::APP_ID, 'body_before_html', ''),
			'bodyAfterHtml' => $this->config->getAppValue(Application::APP_ID, 'body_after_html', ''),
		]);
	}
}

<?php

declare(strict_types=1);

namespace OCA\CodeInjector\Controller;

use OCA\CodeInjector\AppInfo\Application;
use OCA\CodeInjector\Settings\AdminSettings;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\AuthorizedAdminSetting;
use OCP\AppFramework\Http\DataResponse;
use OCP\IAppConfig;
use OCP\IRequest;

class SettingsController extends Controller {

	public function __construct(
		IRequest $request,
		private readonly IAppConfig $appConfig,
	) {
		parent::__construct(Application::APP_ID, $request);
	}

	/**
	 * Persist head and body HTML snippets. Only accessible to Nextcloud admins.
	 */
	#[AuthorizedAdminSetting(settings: AdminSettings::class)]
	public function save(string $headHtml = '', string $bodyHtml = ''): DataResponse {
		$this->appConfig->setValueString(Application::APP_ID, 'head_html', $headHtml);
		$this->appConfig->setValueString(Application::APP_ID, 'body_html', $bodyHtml);

		return new DataResponse(['status' => 'ok']);
	}

	/**
	 * Return the currently stored snippets. Only accessible to Nextcloud admins.
	 */
	#[AuthorizedAdminSetting(settings: AdminSettings::class)]
	public function load(): DataResponse {
		return new DataResponse([
			'headHtml' => $this->appConfig->getValueString(Application::APP_ID, 'head_html', ''),
			'bodyHtml' => $this->appConfig->getValueString(Application::APP_ID, 'body_html', ''),
		]);
	}
}

<?php

declare(strict_types=1);

namespace OCA\CodeInjector\Settings;

use OCA\CodeInjector\AppInfo\Application;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IAppConfig;
use OCP\Settings\ISettings;
use OCP\Util;

class AdminSettings implements ISettings {

	public function __construct(
		private readonly IAppConfig $appConfig,
	) {
	}

	public function getForm(): TemplateResponse {
		Util::addScript(Application::APP_ID, 'admin');
		Util::addStyle(Application::APP_ID, 'admin');

		return new TemplateResponse(Application::APP_ID, 'admin', [
			'head_html' => $this->appConfig->getValueString(Application::APP_ID, 'head_html', ''),
			'body_html' => $this->appConfig->getValueString(Application::APP_ID, 'body_html', ''),
		]);
	}

	public function getSection(): string {
		return Application::APP_ID;
	}

	public function getPriority(): int {
		return 10;
	}
}

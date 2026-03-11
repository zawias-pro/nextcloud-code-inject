<?php

declare(strict_types=1);

namespace OCA\Codeinjector\Settings;

use OCA\Codeinjector\AppInfo\Application;
use OCP\App\IAppManager;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IRequest;
use OCP\Settings\ISettings;
use OCP\Util;

class AdminSettings implements ISettings {

	public function __construct(
		private readonly IConfig $config,
		private readonly IAppManager $appManager,
		private readonly IRequest $request,
	) {
	}

	public function getForm(): TemplateResponse {
		Util::addStyle(Application::APP_ID, 'admin');
		Util::addScript(Application::APP_ID, 'admin');
		$cspEditorAppId = $this->detectCspEditorAppId();

		return new TemplateResponse(Application::APP_ID, 'admin', [
			'head_html' => $this->config->getAppValue(Application::APP_ID, 'head_html', ''),
			'body_before_html' => $this->config->getAppValue(Application::APP_ID, 'body_before_html', ''),
			'body_after_html' => $this->config->getAppValue(Application::APP_ID, 'body_after_html', ''),
			'saved' => $this->request->getParam('saved') === '1',
			'csp_editor_detected' => $cspEditorAppId !== null,
			'csp_editor_app_id' => $cspEditorAppId ?? '',
			'csp_editor_url' => '/settings/admin/additional',
		]);
	}

	private function detectCspEditorAppId(): ?string {
		foreach (['cspeditor', 'csp_editor', 'contentsecuritypolicyeditor'] as $appId) {
			if ($this->appManager->isInstalled($appId) && $this->appManager->isEnabledForAnyone($appId)) {
				return $appId;
			}
		}
		return null;
	}

	public function getSection(): string {
		return Application::APP_ID;
	}

	public function getPriority(): int {
		return 10;
	}
}

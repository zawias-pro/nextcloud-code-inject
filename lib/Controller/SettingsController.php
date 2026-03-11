<?php

declare(strict_types=1);

namespace OCA\Codeinjector\Controller;

use OCA\Codeinjector\AppInfo\Application;
use OCA\Codeinjector\Settings\AdminSettings;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\AuthorizedAdminSetting;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\IConfig;
use OCP\IRequest;
use OCP\ISession;

class SettingsController extends Controller {
	public function __construct(
		IRequest $request,
		private readonly IConfig $config,
		private readonly ISession $session,
	) {
		parent::__construct(Application::APP_ID, $request);
	}

	#[AuthorizedAdminSetting(settings: AdminSettings::class)]
	public function save(
		string $headHtml,
		string $bodyBeforeHtml,
		string $bodyAfterHtml,
	): RedirectResponse {
		$this->config->setAppValue(Application::APP_ID, 'head_html', $headHtml);
		$this->config->setAppValue(Application::APP_ID, 'body_before_html', $bodyBeforeHtml);
		$this->config->setAppValue(Application::APP_ID, 'body_after_html', $bodyAfterHtml);

		$this->session->set(Application::APP_ID.'_saved', true);

		return new RedirectResponse('/settings/admin/'.Application::APP_ID);
	}
}

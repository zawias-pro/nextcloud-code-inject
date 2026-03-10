<?php
/** @var \OCP\IL10N $l */
/** @var array $_ */
?>

<div id="codeinjector-settings">

	<div class="codeinjector-header">
		<h2><?php p($l->t('Code Injector')); ?></h2>
		<p class="settings-hint">
			<?php p($l->t('Inject custom HTML into every Nextcloud page. Changes take effect immediately after saving.')); ?>
		</p>
	</div>

	<div class="codeinjector-notice">
		<span class="icon icon-info"></span>
		<?php p($l->t('External scripts and stylesheets work without extra configuration. Inline <script> content may be blocked by the default Content Security Policy.')); ?>
	</div>

	<?php if ($_['csp_editor_detected']): ?>
		<div class="codeinjector-notice codeinjector-notice--success">
			<span class="icon icon-checkmark"></span>
			<?php p($l->t('CSP Editor app detected and enabled (%s). You can manage advanced CSP rules there: ', [$_['csp_editor_app_id']])); ?>
			<a href="<?php p($_['csp_editor_url']); ?>"><?php p($l->t('Open CSP Editor')); ?></a>
		</div>
	<?php else: ?>
		<div class="codeinjector-notice codeinjector-notice--warning">
			<span class="icon icon-info"></span>
			<?php p($l->t('CSP Editor app is not detected. Install/enable it if you prefer dedicated CSP management. If installed, open: ')); ?>
			<a href="<?php p($_['csp_editor_url']); ?>"><?php p($l->t('CSP settings page')); ?></a>
		</div>
	<?php endif; ?>

	<div class="codeinjector-field">
		<label for="codeinjector-head-html">
			<strong><?php p($l->t('Head HTML')); ?></strong>
			<span class="codeinjector-sublabel">
				<?php p($l->t('Injected before </head> — use for <meta>, <link>, <script src>, analytics snippets, etc.')); ?>
			</span>
		</label>
		<textarea
			id="codeinjector-head-html"
			class="codeinjector-textarea"
			rows="10"
			spellcheck="false"
			placeholder="<?php p($l->t('<!-- Example: Google Tag Manager snippet -->')); ?>"
		><?php p($_['head_html']); ?></textarea>
	</div>

	<div class="codeinjector-field">
		<label for="codeinjector-body-html">
			<strong><?php p($l->t('Body HTML')); ?></strong>
			<span class="codeinjector-sublabel">
				<?php p($l->t('Injected before </body> — use for chat widgets, cookie banners, GTM noscript tags, etc.')); ?>
			</span>
		</label>
		<textarea
			id="codeinjector-body-html"
			class="codeinjector-textarea"
			rows="10"
			spellcheck="false"
			placeholder="<?php p($l->t('<!-- Example: noscript fallback for GTM -->')); ?>"
		><?php p($_['body_html']); ?></textarea>
	</div>

	<div class="codeinjector-field">
		<label for="codeinjector-csp-rules">
			<strong><?php p($l->t('Additional CSP rules')); ?></strong>
			<span class="codeinjector-sublabel">
				<?php p($l->t('One directive per line, e.g. script-src https://www.googletagmanager.com or connect-src https://api.example.com')); ?>
			</span>
			<span class="codeinjector-sublabel">
				<?php p($l->t('Supported directives: script-src, style-src, font-src, img-src, connect-src, media-src, object-src, frame-src, child-src, frame-ancestors, worker-src, form-action, and flags like use-js-nonce.')); ?>
			</span>
		</label>
		<textarea
			id="codeinjector-csp-rules"
			class="codeinjector-textarea"
			rows="8"
			spellcheck="false"
			placeholder="<?php p($l->t("script-src https://www.googletagmanager.com\nconnect-src https://www.google-analytics.com")); ?>"
		><?php p($_['csp_rules']); ?></textarea>
	</div>

	<div class="codeinjector-actions">
		<button id="codeinjector-save" class="button primary">
			<?php p($l->t('Save')); ?>
		</button>
		<span id="codeinjector-msg" class="codeinjector-msg" aria-live="polite"></span>
	</div>

</div>

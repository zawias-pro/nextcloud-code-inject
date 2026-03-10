<?php
/** @var \OCP\IL10N $l */
/** @var array $_ */
?>

<div class="section">

	<div class="codeinjector-header">
		<h2><?php p($l->t('Code Injector')); ?></h2>
		<p class="settings-hint">
			<?php p($l->t('Inject custom HTML into every Nextcloud page. Changes take effect immediately after saving.')); ?>
		</p>
	</div>

	<?php if (!empty($_['saved'])): ?>
		<div class="codeinjector-notice codeinjector-notice--success">
			<span class="icon icon-checkmark"></span>
			<?php p($l->t('Saved')); ?>
		</div>
	<?php endif; ?>

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

	<form id="codeinjector-form" method="post" action="/apps/codeinjector/settings">
		<div class="codeinjector-field">
			<label for="codeinjector-head-html">
				<strong><?php p($l->t('Head HTML')); ?></strong>
				<span class="codeinjector-sublabel">
					<?php p($l->t('Injected before </head> — use for <meta>, <link>, <script src>, analytics snippets, etc.')); ?>
				</span>
			</label>
		<textarea
			id="codeinjector-head-html"
			name="headHtml"
			class="codeinjector-textarea"
			rows="10"
			spellcheck="false"
			placeholder="<?php p($l->t('<script nonce="{{csp_nonce}}">/* Example inline script */</script>')); ?>"
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
				name="bodyHtml"
				class="codeinjector-textarea"
				rows="10"
				spellcheck="false"
				placeholder="<?php p($l->t('<!-- Example: noscript fallback for GTM -->')); ?>"
			><?php p($_['body_html']); ?></textarea>
		</div>

		<div class="codeinjector-actions">
			<button id="codeinjector-save" class="button primary" type="submit">
				<?php p($l->t('Save')); ?>
			</button>
		</div>
	</form>

</div>

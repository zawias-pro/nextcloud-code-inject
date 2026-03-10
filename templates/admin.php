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

	<div class="codeinjector-actions">
		<button id="codeinjector-save" class="button primary">
			<?php p($l->t('Save')); ?>
		</button>
		<span id="codeinjector-msg" class="codeinjector-msg" aria-live="polite"></span>
	</div>

</div>

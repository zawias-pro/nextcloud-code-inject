<?php
/** @var \OCP\IL10N $l */
/** @var array $_ */
?>

<div class="section">
    <h2>
        <?php p($l->t('Code Injector')); ?>
    </h2>
    <p>
        <?php p($l->t('Inject custom HTML into every Nextcloud page. Changes take effect immediately after saving.')); ?>
    </p>
	<?php if (!empty($_['saved'])): ?>
		<div class="codeinjector__notice codeinjector__notice--success">
			<span class="icon icon-checkmark"></span>
			<?php p($l->t('Saved')); ?>
		</div>
	<?php endif; ?>
	<?php if ($_['csp_editor_detected']): ?>
		<div class="codeinjector__notice codeinjector__notice--success">
			<span class="icon icon-checkmark"></span>
            <div>
                <?php p($l->t('CSP Editor app detected and enabled (csp_editor). You can manage advanced CSP rules there: ')); ?>
                <a href="/settings/admin/additional"><?php p($l->t('Open CSP Editor')); ?></a>
            </div>
		</div>
	<?php else: ?>
		<div class="codeinjector__notice codeinjector__notice--warning">
			<span class="icon icon-alert-outline"></span>
            <?php p($l->t('CSP Editor app is not detected. Install/enable it if you prefer dedicated CSP management. If installed, open: ')); ?>
		</div>
	<?php endif; ?>
	<form method="post" action="/apps/codeinjector/settings">
		<input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']); ?>">
		<div class="codeinjector__field">
			<label for="codeinjector-head-html">
				<?php p($l->t('Head HTML')); ?>
			</label>
			<textarea
				id="codeinjector-head-html"
				name="headHtml"
				rows="8"
				spellcheck="false"
			><?php p($_['head_html']); ?></textarea>
		</div>
		<div class="codeinjector__field">
			<label for="codeinjector-body-before-html">
				<?php p($l->t('Top of Body HTML')); ?>
			</label>
			<textarea
				id="codeinjector-body-before-html"
				name="bodyBeforeHtml"
				rows="8"
				spellcheck="false"
			><?php p($_['body_before_html']); ?></textarea>
		</div>
		<div class="codeinjector__field">
			<label for="codeinjector-body-after-html">
				<?php p($l->t('Bottom of Body HTML')); ?>
			</label>
			<textarea
				id="codeinjector-body-after-html"
				name="bodyAfterHtml"
				rows="8"
				spellcheck="false"
			><?php p($_['body_after_html']); ?></textarea>
		</div>
        <button class="button primary" type="submit">
            <?php p($l->t('Save')); ?>
        </button>
	</form>
</div>

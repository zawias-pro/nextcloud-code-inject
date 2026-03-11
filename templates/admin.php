<?php
/** @var \OCP\IL10N $l */
/** @var array $_ */
?>

<div class="section">
    <div class="codeinjector__wrapper">
        <h2>
            <?php p($l->t('Custom HTML')); ?>
        </h2>
        <p>
            <?php p($l->t('Adds custom HTML to every page (including login page), for all users.')); ?>
            <?php p($l->t('Changes take effect immediately after saving.')); ?>
            <strong>
                <?php p($l->t('Make sure you know what you do. Saving wrong code can cause the app to crash for everyone.')); ?>
            </strong>
        </p>
        <p>
            <?php p($l->t('You can use <code>{{csp_nonce}}</code> placeholder where you need CSP nonce token.')); ?>
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
                    <?php p($l->t('CSP Editor app detected and enabled. You can manage CSP rules there: ')); ?>
                    <a href="/settings/admin/additional"><?php p($l->t('Open CSP Editor')); ?></a>
                </div>
            </div>
        <?php else: ?>
            <div class="codeinjector__notice codeinjector__notice--warning">
                <span class="icon icon-alert-outline"></span>
                <div>
                    <?php p($l->t('CSP Editor app is not detected. You want to use it if your scripts need CSP rules:')); ?>
                    <a href="/settings/apps/tools/csp_editor" target="_blank">install</a>
                </div>
            </div>
        <?php endif; ?>
        <form method="post" action="/apps/codeinjector/settings">
            <input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']); ?>">
            <div class="codeinjector__field">
                <label for="codeinjector-head-html">
                    <?php p($l->t('HTML in <head>')); ?>
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
                    <?php p($l->t('HTML after <body> (opening tag)')); ?>
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
                    <?php p($l->t('HTML before </body> (closing tag)')); ?>
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
</div>

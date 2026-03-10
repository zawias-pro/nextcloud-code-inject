/**
 * Code Injector — admin settings page script.
 *
 * Handles the Save button in the admin settings form: POSTs the two HTML
 * snippets to the backend controller and shows a success/error message.
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var saveBtn  = document.getElementById('codeinjector-save');
		var headArea = document.getElementById('codeinjector-head-html');
		var bodyArea = document.getElementById('codeinjector-body-html');
		var cspArea  = document.getElementById('codeinjector-csp-rules');
		var msgEl    = document.getElementById('codeinjector-msg');

		if (!saveBtn || !headArea || !bodyArea || !cspArea || !msgEl) {
			return;
		}

		function showMsg(text, type) {
			msgEl.textContent = text;
			msgEl.className   = 'codeinjector-msg ' + (type || '');
			// Auto-hide success messages after 4 seconds
			if (type === 'success') {
				setTimeout(function () {
					msgEl.textContent = '';
					msgEl.className   = 'codeinjector-msg';
				}, 4000);
			}
		}

		saveBtn.addEventListener('click', function () {
			saveBtn.disabled = true;
			showMsg('', '');

			var url = OC.generateUrl('/apps/codeinjector/settings');

			fetch(url, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'requesttoken': OC.requestToken,
				},
				body: JSON.stringify({
					headHtml: headArea.value,
					bodyHtml: bodyArea.value,
					cspRules: cspArea.value,
				}),
			})
				.then(function (response) {
					if (!response.ok) {
						return response.json().then(function (data) {
							throw new Error(data.message || t('codeinjector', 'Server error'));
						});
					}
					return response.json();
				})
				.then(function () {
					showMsg(t('codeinjector', 'Settings saved'), 'success');
				})
				.catch(function (err) {
					console.error('[codeinjector] Save failed:', err);
					showMsg(t('codeinjector', 'Error saving settings: ') + err.message, 'error');
				})
				.finally(function () {
					saveBtn.disabled = false;
				});
		});
	});
}());

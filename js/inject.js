/**
 * Code Injector — client-side injection script.
 *
 * Reads HTML snippets published via Nextcloud's initial-state mechanism and
 * injects them into <head> and <body>.  Script elements require special
 * treatment: they must be created via document.createElement so the browser
 * actually executes them (innerHTML-inserted <script> tags are inert).
 *
 * Note on Content-Security-Policy:
 *   - External scripts (<script src="…">) work without CSP changes.
 *   - Inline <script> content will be blocked by Nextcloud's default CSP
 *     (no 'unsafe-inline').  Either relax the CSP or use a nonce-based
 *     approach for inline scripts.
 */

(function () {
	'use strict';

	/**
	 * Read a value from Nextcloud's initial-state store.
	 * The data is stored in <script id="initial-state-{appId}-{key}" type="application/json">
	 * as base64-encoded JSON.
	 *
	 * @param {string} appId
	 * @param {string} key
	 * @returns {string|null}
	 */
	function loadState(appId, key) {
		var el = document.getElementById('initial-state-' + appId + '-' + key);
		if (!el) {
			return null;
		}
		try {
			return JSON.parse(atob(el.textContent.trim()));
		} catch (e) {
			console.error('[codeinjector] Failed to parse initial state "' + key + '":', e);
			return null;
		}
	}

	/**
	 * Clone a <script> element so the browser executes it.
	 * Browsers do not execute scripts inserted via innerHTML.
	 *
	 * @param {HTMLScriptElement} original
	 * @returns {HTMLScriptElement}
	 */
	function cloneScript(original) {
		var script = document.createElement('script');
		for (var i = 0; i < original.attributes.length; i++) {
			var attr = original.attributes[i];
			script.setAttribute(attr.name, attr.value);
		}
		script.textContent = original.textContent;
		return script;
	}

	/**
	 * Parse an HTML string and append all top-level nodes to the given
	 * target element.  <script> nodes are handled via cloneScript so that
	 * they are actually executed by the browser.
	 *
	 * @param {string}      html
	 * @param {HTMLElement} target
	 */
	function injectHtml(html, target) {
		if (!html || !html.trim()) {
			return;
		}

		var wrapper = document.createElement('div');
		wrapper.innerHTML = html;

		var nodes = Array.from(wrapper.childNodes);
		nodes.forEach(function (node) {
			if (node.nodeName === 'SCRIPT') {
				target.appendChild(cloneScript(/** @type {HTMLScriptElement} */ (node)));
			} else {
				target.appendChild(node.cloneNode(true));
			}
		});
	}

	function run() {
		var headHtml = loadState('codeinjector', 'head');
		var bodyHtml = loadState('codeinjector', 'body');

		if (headHtml) {
			injectHtml(headHtml, document.head);
		}
		if (bodyHtml) {
			injectHtml(bodyHtml, document.body);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', run);
	} else {
		run();
	}
}());

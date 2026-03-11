(function () {
	var dirty = false;

	document.querySelectorAll('#codeinjector-form textarea').forEach(function (el) {
		el.addEventListener('input', function () { dirty = true; });
	});

	document.getElementById('codeinjector-form').addEventListener('submit', function () {
		dirty = false;
	});

	window.addEventListener('beforeunload', function (e) {
		if (dirty) { e.preventDefault(); }
	});
})();

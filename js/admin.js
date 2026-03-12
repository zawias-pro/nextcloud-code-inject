(function () {
	var dirty = false;

	document.querySelectorAll('.codeinjector__wrapper textarea').forEach(function (el) {
		el.addEventListener('input', function () { dirty = true; });
	});

	document.querySelector('.codeinjector__wrapper form').addEventListener('submit', function () {
		dirty = false;
	});

	window.addEventListener('beforeunload', function (e) {
		if (dirty) { e.preventDefault(); }
	});
})();

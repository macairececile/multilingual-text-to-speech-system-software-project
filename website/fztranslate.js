	// start translate 
	var dictionary = {};
	$.getJSON('dictionary.json', function(data) {
		dictionary = data;
		checkLanguage();
	});

	// translate function
	function translate(code) {
		var trans = dictionary[code];
		var content = $(".fz");
		for (var i = content.length - 1; i >= 0; i--) {
			var text = content[i].textContent.trim();
			var key = text.toLowerCase();
			if (typeof trans[key] !== 'undefined') {
				content[i].innerHTML = trans[key];
			}
		}
	}

	// select language 
	function selectLanguage(me) {
		var lan = $(me).val();
		localStorage.setItem("languageCode", lan);;
		location.reload();
	}

	// check language when start loading window
	function checkLanguage() {
		var code = localStorage.getItem("languageCode");
		if (code == null) {
			code = "en";
		}
		$(".language-code").val(code);

		translate(code);
	}
function apicall(path, data, callback) {
		if (data !== null) var senddata = data;
		else var senddata = {};
		senddata.value = "Test";
		$.ajax({
			url: 'http://www.example.com/api/' + path,
			type : "POST",
			data : senddata,
			cache : false,
			success: function (result) {
				if (result == 'AUTHFAIL') {
					window.localStorage.clear();
					window.location.href = "login.html";
				} else {
					var result = $.parseJSON(result);
					var resultreturn = result;
					if (typeof callback === "function") {
						callback(resultreturn);
					}
				}
				return false;
			}, error: function(jqXHR, textStatus, errorThrown) {
				window.location.href = "offline.html";
			}
		});
	}

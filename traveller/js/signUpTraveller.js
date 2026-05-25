const signupForm = document.querySelector("#signupTraveller");

function registerTraveller(e) {
	e.preventDefault();

	const formData = new FormData(signupForm);

	fetch('../api.php', {
		method: 'POST',
		body: formData
	})
		.then(async response => {
			const rawText = await response.text();
			try {
				return JSON.parse(rawText);
			} catch (err) {
				console.error("Server output raw non-JSON text:", rawText);
				throw new Error("Server returned a malformed response.");
			}
		})
		.then(data => {
			if (data.status === 'success') {
				alert("Registration successful! Please login.");
				window.location.href = "loginTraveller.php";
			} else {
				alert("Registration failed: " + (data.data?.reason || "Unknown error"));
				console.log(data.data);
			}
		})
		.catch(error => {
			console.error('Registration system error:', error);
		});
}

signupForm.addEventListener("submit", registerTraveller);
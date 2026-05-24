const logoutForm = document.querySelector("#logoutForm");

function executeLogout(e) {
	e.preventDefault();

	const formData = new FormData(logoutForm);

	fetch('../api.php', {
		method: 'POST',
		body: formData
	})
		.then(response => {
			if (!response.ok) {
				throw new Error(`HTTP error! Status: ${response.status}`);
			}
			return response.json();
		})
		.then(data => {
			if (data.status === 'success') {
				window.location.href = "loginTraveller.php";
			} else {
				alert("Logout routine rejected by server.");
			}
		})
		.catch(error => {
			console.error('Logout script execution broken:', error);
		});
}

logoutForm.addEventListener("submit", executeLogout);
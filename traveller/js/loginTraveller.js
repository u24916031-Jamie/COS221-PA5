
const loginForm = document.querySelector("#loginForm");

function loginTraveller(e) {

	e.preventDefault();

	const formData = new FormData(loginForm);

	fetch('../../api.php', {
		method: 'POST',
		body: formData
	})
		.then(response => {

			return response.json();
		})
		.then(data => {
			if (data.status === 'success') {
				window.location.href = "/traveller/browsePackage.php"
			} else {
				//error login failed
				alert("Login failed.");
				console.log(data.data);
				throw new Error(`Server returned code: ${data.status}`);
			}
		})
		.catch(error => {
			console.error('Login failed:', error);
		});




}


loginForm.addEventListener("submit", loginTraveller);
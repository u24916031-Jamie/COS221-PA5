
const loginForm = document.querySelector("#loginForm");

function loginTraveller(e) {

	e.preventDefault();

	const formData = new FormData(loginForm);

	fetch('/COS221-PA5/api.php', {
		method: 'POST',
		body: formData
	})
		.then(async response => {
      const rawTextResponse = await response.text();

      try{
        return JSON.parse(rawTextResponse);
      }catch(err){
        console.error("Incorrect response format", rawTextResponse);
      }	
		})
		.then(data => {
			if (data.status === 'success') {
				window.location.href = "browsePackage.php";
			} else {
				//error login failed
				alert("Login failed.");
				console.log(data.data);
				throw new Error(`Server returned code`);
			}
		})
		.catch(error => {
			console.error('Login failed:');
		});




}


loginForm.addEventListener("submit", loginTraveller);
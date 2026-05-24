const loginForm = document.querySelector("#loginForm");

function loginAgency(event) {
  event.preventDefault();
  const formData = new FormData(loginForm);

  fetch('../api.php', {
    method: 'POST',
    body: formData
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        window.location.href = "../client/agentView.html";
      } else {
        alert('Login failed: ' + (data.data?.reason || 'Invalid credentials'));
      }
    })
    .catch(error => {
      console.error('Login failed:', error);
      alert('Login failed. Please try again.');
    });
}

loginForm.addEventListener('submit', loginAgency);

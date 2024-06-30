document.addEventListener('DOMContentLoaded', function () {
    const hamBurger = document.querySelector(".toggle-btn");

    hamBurger.addEventListener("click", function () {
        document.querySelector("#sidebar").classList.toggle("expand");
    });

    function showContent(sectionId) {
        // Hide all content sections except the one with sectionId
        const sections = document.querySelectorAll('.content-section');
        sections.forEach(section => {
            if (section.id === sectionId) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    }

    // Add event listener to each sidebar link
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            const sectionId = this.getAttribute('data-section-id');
            showContent(sectionId);
        });
    });

    // Initial display of "countRequestPending" section on page load
    showContent('countRequestPending');

    // Handle form submission if the form exists
    const bloodRequestForm = document.getElementById('bloodRequestForm');
    if (bloodRequestForm) {
        bloodRequestForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(bloodRequestForm);

            fetch('submit_blood_request.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Display response from server
                bloodRequestForm.reset();
                showContent('countRequestPending'); // Refresh section after submission
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error submitting your request. Please try again later.');
            });
        });
    } else {
        console.error('Form element bloodRequestForm not found.');
    }
});

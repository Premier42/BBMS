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

    const bloodRequestForm = document.getElementById('bloodRequestForm');
    let isSubmitting = false; // Submission flag

    if (bloodRequestForm) {
        bloodRequestForm.addEventListener('submit', function (event) {
            event.preventDefault();

            if (isSubmitting) {
                return; // Prevent further submissions if already submitting
            }

            isSubmitting = true; // Set the flag to true
            const formData = new FormData(bloodRequestForm);
            const submitButton = bloodRequestForm.querySelector('button[type="submit"]');
            submitButton.disabled = true; // Disable the submit button

            fetch('request_for_blood.php', {
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
                })
                .finally(() => {
                    submitButton.disabled = false; // Re-enable the submit button
                    isSubmitting = false; // Reset the flag
                });
        });
    } else {
        console.error('Form element bloodRequestForm not found.');
    }
});

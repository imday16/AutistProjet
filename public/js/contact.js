        // Form submission handling
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const responseMessage = document.getElementById('responseMessage');
            const formData = new FormData(this);
            
            // Simulate form submission
            responseMessage.textContent = 'Merci ! Votre message a été envoyé avec succès. Nous vous répondrons bientôt.';
            responseMessage.classList.add('success');
            responseMessage.style.display = 'block';
            
            // Reset form
            this.reset();
            
            // Hide message after 5 seconds
            setTimeout(() => {
                responseMessage.style.display = 'none';
                responseMessage.classList.remove('success');
            }, 5000);
        });
        
        // FAQ accordion functionality
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', function() {
                const faqItem = this.parentElement;
                faqItem.classList.toggle('active');
            });
        });
    
<?php
/**
 * Contact Page
 */
$pageTitle = 'Contact';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <div class="breadcrumb">
            <a href="<?= baseUrl() ?>">Home</a>
            <span>/</span>
            <span>Contact</span>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="contact-layout">
            <!-- Contact Info -->
            <div class="contact-info-card">
                <h2 style="font-size: 1.3rem; margin-bottom: 24px;">Get in Touch</h2>
                <p style="color: var(--text-secondary); margin-bottom: 28px;">
                    Have a question, feedback, or need help? We'd love to hear from you. Fill out the form or reach us directly.
                </p>
                <div class="contact-info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Address</h4>
                        <p>Saraet Himamaylan City</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Phone</h4>
                        <p>+1 (555) 123-4567</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>support@shopvibe.com</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h4>Business Hours</h4>
                        <p>Mon–Fri: 9 AM – 6 PM<br>Sat–Sun: 10 AM – 4 PM</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-card">
                <h2>Send Us a Message</h2>
                <form action="<?= baseUrl('includes/contact_handler.php') ?>" method="POST" data-validate>
                    <div class="form-group">
                        <label for="name">Your Name *</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="john@example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" class="form-control" placeholder="How can we help?" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" class="form-control" placeholder="Type your message here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-full">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

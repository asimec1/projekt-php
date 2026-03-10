<?php
print '
<section class="contact-section">
    <div class="auth-container">
        <div id="contact" class="contact-card">
            <div class="auth-header contact-header">
                <span class="auth-label">Kontakt i upit</span>
                <h1>Contact Form</h1>
                <p>Pošaljite upit putem obrasca ili nas pronađite na lokaciji prikazanoj na karti.</p>
            </div>

            <div class="contact-layout">
                <div class="contact-map-wrap">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2781.7890741539636!2d15.966758816056517!3d45.795453279106205!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4765d68b5d094979%3A0xda8bfa8459b67560!2sUl.+Vrbik+VIII%2C+10000%2C+Zagreb!5e0!3m2!1shr!2shr!4v1509296660756"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <div class="contact-form-wrap">
                    <form action="send-contact.php" id="contact_form" name="contact_form" method="POST" class="auth-form" autocomplete="on">
                        <div class="auth-grid">
                            <div class="auth-form-group">
                                <label for="fname">First Name <span>*</span></label>
                                <input type="text" id="fname" name="firstname" placeholder="Your name..." required>
                            </div>

                            <div class="auth-form-group">
                                <label for="lname">Last Name <span>*</span></label>
                                <input type="text" id="lname" name="lastname" placeholder="Your last name..." required>
                            </div>
                        </div>

                        <div class="auth-form-group">
                            <label for="email">Your E-mail <span>*</span></label>
                            <input type="email" id="email" name="email" placeholder="Your e-mail..." required>
                        </div>

                        <div class="auth-form-group">
                            <label for="country">Country</label>
                            <select id="country" name="country">
                                <option value="">Please select</option>
                                <option value="BE">Belgium</option>
                                <option value="HR" selected>Croatia</option>
                                <option value="LU">Luxembourg</option>
                                <option value="HU">Hungary</option>
                            </select>
                        </div>

                        <div class="auth-form-group">
                            <label for="subject">Subject</label>
                            <textarea id="subject" name="subject" placeholder="Write something..."></textarea>
                        </div>

                        <div class="auth-actions">
                            <input type="submit" value="Send message" class="auth-btn">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>';
?>
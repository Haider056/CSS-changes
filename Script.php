function handle_send_email() {
// Check that all necessary fields are received
if (!isset($_POST['userEmail'], $_POST['selectedFeatures'], $_POST['totalPrice'], $_POST['savings'])) {
wp_send_json_error('Missing required fields.');
return;
}

// Sanitize received data
$userEmail = sanitize_email($_POST['userEmail']);
$selectedFeatures = sanitize_text_field($_POST['selectedFeatures']);
$totalPrice = sanitize_text_field($_POST['totalPrice']);
$savings = sanitize_text_field($_POST['savings']);

// Prepare email content
$subject = 'Your Selected Bundle Details';
$message = "<h2>Here is your selected bundle's details</h2>";
$message .= "<p><strong>Selected Features:</strong> $selectedFeatures</p>";
$message .= "<p><strong>Total Price:</strong> $$totalPrice</p>";
$message .= "<p><strong>Savings:</strong> $$savings</p>";
$message .= "<p>We will contact you shortly.</p>";
$headers = array('Content-Type: text/html; charset=UTF-8');

// Send email
$mail_sent = wp_mail($userEmail, $subject, $message, $headers);

// Respond with success or error message
if ($mail_sent) {
wp_send_json_success('Email sent successfully.');
} else {
wp_send_json_error('Failed to send email.');
}

wp_die(); // Always end AJAX functions with wp_die()
}
add_action('wp_ajax_send_email', 'handle_send_email');
add_action('wp_ajax_nopriv_send_email', 'handle_send_email');

function enqueue_custom_js() {
wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/custom.js', array('jquery'), null, true);
wp_localize_script('custom-js', 'my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_js');
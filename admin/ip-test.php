<?php
// Get the current user's IP details by default
$current_ip_address = $_SERVER['REMOTE_ADDR'];
$current_details = json_decode(file_get_contents("http://ipinfo.io/{$current_ip_address}/json"));

// If the user submits the form to test a specific IP
if (isset($_POST['test_ip_submit']) && !empty($_POST['ip_to_test'])) {
    $ip_address = sanitize_text_field($_POST['ip_to_test']);
    $details = json_decode(file_get_contents("http://ipinfo.io/{$ip_address}/json"));
}
?>

<div class="wrap">
    <h1>Test IP Address</h1>
    
    <h2>Your IP Details:</h2>
    <p>
        <strong>IP:</strong> <?php echo $current_details->ip; ?><br>
        <strong>City:</strong> <?php echo $current_details->city; ?><br>
        <strong>Region:</strong> <?php echo $current_details->region; ?><br>
        <strong>Country:</strong> <?php echo $current_details->country; ?><br>
        <strong>Location:</strong> <?php echo $current_details->loc; ?><br>
        <strong>Organization:</strong> <?php echo $current_details->org; ?><br>
    </p>

    <h2>Test Another IP:</h2>
    <form method="post">
        <label for="ip_to_test">Enter IP Address:</label>
        <input type="text" name="ip_to_test" id="ip_to_test" value="">
        <input type="submit" name="test_ip_submit" value="Test IP">
    </form>

    <?php
    if (isset($details)) {
    ?>
        <h2>Tested IP Details:</h2>
        <p>
            <strong>IP:</strong> <?php echo $details->ip; ?><br>
            <strong>City:</strong> <?php echo $details->city; ?><br>
            <strong>Region:</strong> <?php echo $details->region; ?><br>
            <strong>Country:</strong> <?php echo $details->country; ?><br>
            <strong>Location:</strong> <?php echo $details->loc; ?><br>
            <strong>Organization:</strong> <?php echo $details->org; ?><br>
        </p>
    <?php
    }
    ?>

</div>

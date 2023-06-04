<?php
// Check if the user is authorized to access this file
if (!current_user_can('manage_options')) {
	wp_die('Access denied');
}

$blocked_countries = (array) get_option('country_blocker_blocked_countries', array());
$block_page_id = get_option('country_blocker_block_page_id', 0);
$page_options = get_pages();

// Country list array
$country_list = array(
	'US' => 'United States',
	'GB' => 'United Kingdom',
	'CA' => 'Canada',
	'BD' => 'Bangladesh',
	// Add more countries here
);
?>

<div class="wrap">
    <h1>Country Blocker Settings</h1>

    <form method="post" action="options.php">
		<?php settings_fields('country_blocker_settings_group'); ?>
		<?php do_settings_sections('country_blocker_settings_group'); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">Blocked Countries</th>
                <td>
                    <select name="country_blocker_blocked_countries[]" multiple>
						<?php
						// Output the country options
						foreach ($country_list as $code => $name) {
							$selected = in_array($code, $blocked_countries) ? 'selected' : '';
							echo "<option value='$code' $selected>$name</option>";
						}
						?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Access Restricted Page</th>
                <td>
                    <select name="country_blocker_block_page_id">
                        <option value="0">None</option>
						<?php
						// Output the page options
						foreach ($page_options as $page) {
							$selected = $block_page_id == $page->ID ? 'selected' : '';
							echo "<option value='$page->ID' $selected>$page->post_title</option>";
						}
						?>
                    </select>
                </td>
            </tr>
        </table>

		<?php submit_button(); ?>
    </form>
</div>

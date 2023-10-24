<?php
// Check if the user is authorized to access this file
if ( !current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied' );
}

$blocked_countries = (array) get_option( 'simple_country_blocker_blocked_countries', array() );
$page_options = get_pages();

// Fetch country list from REST Countries API
$response = wp_remote_get( 'https://restcountries.com/v3.1/all' );
if ( is_wp_error( $response ) ) {
    // Handle error
    $country_list = array();
} else {
    $countries_data = json_decode( wp_remote_retrieve_body( $response ), true );
    $country_list = array();
    foreach ( $countries_data as $country ) {
        $code = $country['cca2']; // Country code
        $name = $country['name']['common']; // Country name
        $country_list[$code] = $name;
    }
}

// Sort countries alphabetically by name
asort( $country_list );

?>

<div class="wrap">
    <h1>Country Blocker Settings</h1>

    <form method="post" action="options.php">
        <?php settings_fields( 'simple_country_blocker_settings_group' );?>
        <?php do_settings_sections( 'simple_country_blocker_settings_group' );?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">Blocked Countries</th>
                <td>
                    <?php
// Output the country options in checkbox format
$column_count = 3;
$countries_per_column = ceil( count( $country_list ) / $column_count );
$countries_counter = 0;

foreach ( $country_list as $code => $name ) {
    $selected = in_array( $code, $blocked_countries ) ? 'checked' : '';
    echo "<label><input type='checkbox' name='simple_country_blocker_blocked_countries[]' value='$code' $selected> $name</label><br>";

    $countries_counter++;
    if ( $countries_counter % $countries_per_column == 0 ) {
        echo "</td><td>";
    }
}
?>
                </td>
            </tr>
        </table>

        <?php submit_button();?>
    </form>
    <span style="position: absolute; bottom: 10px; right: 10px;">Total Countries: <?=count( $country_list );?></span>
</div>

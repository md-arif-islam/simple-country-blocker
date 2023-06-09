<?php

header('Content-Type: text/html; charset=utf-8');
header('Content-Encoding: none');
session_start();
ob_end_flush();
ob_start();
set_time_limit(0);
error_reporting(0);

for ($i = 0; $i < 20; $i++) {
    echo "<br>>>>" . $i . "<<<br>";
    ob_flush();
    flush();

    usleep(300000);
}
















exit();
ini_set('output_buffering', 'off');
ob_start();


// Prompts
$output_introduction_prompt = 'You are an expert in product research. You are going to write a short introduction about the selling potential of {product_name}. Write two paragraphs.\n\n No headings, output in HTML.';

$output_introduction_prompt = 'Write 3 paragraphs about cars.';


function get_ai_response($prompt)
{
    $endpoint = 'https://api.openai.com/v1/chat/completions';
    $api_key = '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';

    $data = array(
        'model' => 'gpt-3.5-turbo',
        'stream' => true,
        'messages' => array(
            array(
                'role' => 'user',
                'content' => $prompt,
            ),
        ),
    );

    $json = json_encode($data);

    $headers = array(
        "Content-Type: application/json",
        "Authorization: Bearer $api_key"
    );

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // Set to false to enable streaming
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $chunk) {

    	
    	$json_string = preg_replace('/data: /', '', $chunk);

    	$json = json_decode($json_string);
    	$choices = $json->choices;
		$delta = $choices[0]->delta;

    	if (isset($delta->content)) {
    		echo $delta->content;
		}

		ob_flush();
        flush();


        return strlen($chunk); // Return the length of the chunk to indicate successful write
    });

    curl_exec($ch);

    curl_close($ch);
}

if (isset($_GET['function']) && isset($_GET['product'])) {
    $product = $_GET['product'];
    $function = $_GET['function'];

    if ($function == 'get_output_intro') {
        $prompt = str_replace('{product_name}', $_POST['product'], $output_introduction_prompt);
        get_ai_response($prompt);
    }
}

ob_end_clean();
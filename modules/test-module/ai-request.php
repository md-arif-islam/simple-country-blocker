<?php
// Prompts
$output_introduction_prompt = 'You are an expert in product research. You are going to write an short introduction about their selling potential: {product_name}. Write two parahraphs.\n\n No headings, output in html.';



$output_table_prompt = '
[make a table, column 1 - title, column 2- score, column 3-detailed explanation]\n
You are a highly critical **e-commerce and drop-ship** webshop expert. You are known for you low scores. You will assess a product on how great the success rate is for webshop sales based on the criteria. The main question is, is this product going to be a winning product. You will give each criteria a rating from 1-100, where < 55 is bad, 55-75 is average, > 75 is good. **Be precisely, meaning you need to use numbers like 66, 67, 77, etc**. **Add explanations to each criteria**. Include the average score at the end of the table.
\n
Product: {product_name}\n
\n
Criteria:\n
- Problem Solving: Does the product solve a problem for customers? Problem-solving products are more likely to succeed.\n
- Wow Factor: Does the product have a unique selling point? Products that stand out have a better chance of success.\n
- Affordable for Customer: Is the product priced appropriately for the target audience? Affordable products are more likely to sell.\n
- Unique: Is the product unique and not too generic? Unique products are more likely to stand out in a crowded market.\n
- Specific Passionate Target Group: Does the product appeal to a specific niche market? Products that have a passionate target audience are more likely to succeed.\n
- Good Profit Margins: Is the product profitable? Products with good profit margins are more likely to be worth pursuing.\n
- Evergreen Product: Can the product be sold throughout the year? Non-seasonal products have a better chance of success.\n
- Big Enough Audience & Market: Is there a large enough audience and market for the product? Products with a larger target audience are more likely to sell.\n
- Winning Audience Proven to Work/Validation: Is there evidence that the product is already selling? Products that have already been validated by a winning audience are more likely to succeed.\n
- Good Creatives To Use/Steal: Are there good creatives already available for advertising the product? Products with good creatives available are more likely to be worth pursuing.\n
- Opportunity To Improve: Is there room for improvement in the product or marketing strategy? Products with opportunities for improvement are more likely to succeed.\n
- Upsell/Cross-sell Possibilities: Does the product offer opportunities for upselling or cross-selling? Products that can increase the average order value are more likely to be profitable.\n
\n\n
Be a critical thinker, that tends to give lower scores.'; 



$ouput_advice_prompt = '[Only output bullet list] You are an expert in product research. You are going to write an advice towards people who want to sell {product_name}. You are going to do an in-depth analysis of the product. Based on detailed information of the product you are going to give the advantages of disadvantages of selling this product. Ouput a list of bullet points';


$item_description_prompt = 'You are an expert in product research. You have done research into the product: {product_name}. In the message before you told us that the score for the criteria "{item}" for this product is: {score}. Explain in two paragraphs why the score is {score} for {product_name} on the criteria {item}.';


// Functions
require_once '../../libs/parsedown/Parsedown.php';

function get_ai_response( $prompt, $response_is_markdown = false ) {

    $parsedown = new Parsedown();

    $endpoint = 'https://api.openai.com/v1/chat/completions';
    $api_key = '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';

    $data = array(
        'model' => 'gpt-3.5-turbo',
        'messages' => array(
            array(
                'role' => 'user',
                'content' => $prompt,
            ),
        ),
    );

    $json = json_encode( $data );

    $options = array(
        'http' => array(
            'header' => "Content-Type: application/json\r\n" .
            "Authorization: Bearer $api_key\r\n",
            'method' => 'POST',
            'content' => $json,
        ),
    );

    $context = stream_context_create( $options );
    $response = file_get_contents( $endpoint, false, $context );

    $json = json_decode( $response );

    $content = $json->choices[0]->message->content;

    if ( $response_is_markdown ) {
        return $parsedown->text( $content );
    } else {
        return $content;
    }

}

if ( isset( $_POST['function'] ) && isset( $_POST['product'] ) ) {

    $product = $_POST['product'];
    $function = $_POST['function'];

    if ( $function == 'get_output_intro' ) {
        $prompt = str_replace( '{product_name}', $_POST['product'], $output_introduction_prompt );
        echo get_ai_response( $prompt );
    }

    if ( $function == 'get_output_table' ) {
        $prompt = str_replace( '{product_name}', $_POST['product'], $output_table_prompt );
        echo get_ai_response( $prompt, true );

    }

    if ( $function == 'get_output_advice' ) {
        $prompt = str_replace( '{product_name}', $_POST['product'], $output_advice_prompt );
        echo get_ai_response( $prompt, true );
    }

    if (strpos($function, 'get_description_') !== false) {

        $score = preg_replace('/\D/', '', $function);

        $item = str_replace('get_description_', '', $function);
        $item = preg_replace('/\d/', '', $item);
        $item = str_replace('_', ' ', $item);

        $prompt = str_replace( '{product_name}', $_POST['product'], $item_description_prompt );
        $prompt = str_replace('{item}', $item, $prompt);
        $prompt = str_replace('{score}', $score, $prompt);

        echo get_ai_response( $prompt, true );
    }

}

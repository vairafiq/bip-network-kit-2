<?php
/**
 * Helpers
 *
 * @author   Bipper Media
 * @category API
 * @since    0.1
 */

add_action( 'init', function(){
    if( ! isset( $_GET['bip_dev'] ) ) {
        return;
    }

    echo '<pre>';
    var_dump( bip_get_listing_content( 'The Big Texan Steak Ranch & Brewery', 'New York', 'Steak house' ) );
    echo '<pre>';
});


function bip_ai_server_response( $command ) {

    $key = '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';

    $url = 'https://api.groq.com/openai/v1/chat/completions';

    $headers = array(
        'user-agent' => md5( esc_url( home_url() ) ),
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $key,
        'Content-Type' => 'application/json'
    );

    $body = [
        'model' => 'llama3-70b-8192', //llama3-70b-8192, llama-3.1-70b-versatile, meta-llama/llama-4-maverick-17b-128e-instruct, meta-llama/llama-4-scout-17b-16e-instruct
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a helpful assistant.'
            ],
            [
                'role' => 'user',
                'content' => $command,
            ]
        ],
        'temperature' => 1,
        'max_tokens' => 1024,
        'top_p' => 1,
        'stream' => false,
        'stop' => null,
    ];

    $config = array(
        'method' => 'POST',
        'timeout' => 30,
        'redirection' => 5,
        'httpversion' => '1.0',
        'headers' => $headers,
        'body' => json_encode($body),
    );

    $response_body = array();

    try {
        $response = wp_remote_post( $url, $config );
        
        // return $response;
        if ( is_wp_error( $response ) ) {
            return false;
        } else {
            $response_body = json_decode( $response['body'], true );
            if ( isset( $response_body['choices'][0]['message']['content'] ) ) {
                return $response_body['choices'][0]['message']['content'];
            } else {
                return false;
            }
        }
    } catch ( Exception $e ) {
        return false;
    }
}

function bip_listing_description_update_prompt( $name, $city, $category ) {

return "Generate structured content for {$name} located at {$city} in {$category}. Follow the instructions below and return output in four distinct sections. Wrap each section using custom tags for easy parsing: [s_description]...[/s_description], [s_review]...[/s_review], [s_faqs]...[/s_faqs], and [s_menu]...[/s_menu]. Do not include any introductory or extra text.\n\n"

. "1. **100-Word Business Summary**:\n"
. "- Wrap this section in [s_description]...[/s_description].\n"
. "- Write a concise, SEO-friendly description of the business in 100 words.\n"
. "- Focus on key services, location, customer appeal, and unique selling points.\n"
. "- Avoid repetition, keyword stuffing, or excessive location mentions.\n\n"

. "2. **Review Summary (from Internet Data)**:\n"
. "- Wrap this section in [s_review]...[/s_review].\n"
. "- First, write a short paragraph summarizing public reviews and sentiment based on internet data (tone, praise, concerns).\n"
. "- Then provide a Review Summary block showing:\n"
. "    - Each platform (e.g., Google, TripAdvisor, Facebook) on its own line.\n"
. "    - Format: **[Platform Name]**: **[X.X stars]** from **[Y reviews]**.\n"
. "    - Do NOT use any outbound links or HTML anchor tags.\n"
. "    - Maintain vertical spacing between review lines for readability.\n\n"

. "3. **Frequently Asked Questions**:\n"
. "- Wrap this section in [s_faqs]...[/s_faqs].\n"
. "- Return a plain PHP array using **double quotes** for all strings (e.g., \"question\" => \"...\", not '...').\n"
. "- Each FAQ must contain a \"question\" and \"answer\" key.\n"
. "- Each answer should be 2–3 sentences max, clear and helpful.\n"
. "- Use <strong> HTML tags only where helpful, maximum 3–4 across all answers.\n\n"

. "**Example for FAQ Format (inside [s_faqs]):**\n"
. "[s_faqs]\n"
. "return [\n"
. "    [\n"
. "        \"question\" => \"What services does {$name} offer?\",\n"
. "        \"answer\" => \"We provide <strong>SEO</strong>, social media marketing, and website design tailored to your business.\"\n"
. "    ],\n"
. "    [\n"
. "        \"question\" => \"Where is {$name} located?\",\n"
. "        \"answer\" => \"{$name} is located in {$city}, serving local and regional clients.\"\n"
. "    ],\n"
. "    [\n"
. "        \"question\" => \"Do you offer consultations?\",\n"
. "        \"answer\" => \"Yes, we offer <strong>free initial consultations</strong> to understand your needs and goals.\"\n"
. "    ]\n"
. "];\n"
. "[/s_faqs]\n\n"

. "4. **Menu Items (Food or Services Offered)**:\n"
. "- Wrap this section in [s_menu]...[/s_menu].\n"
. "- Return a PHP array of 4 menu items.\n"
. "- Each menu item should include:\n"
. "    - \"name\" (string): The item name.\n"
. "    - \"description\" (string): A short, tasty description (max 20 words).\n"
. "    - \"price\" (string): Price in USD (e.g., \"12.99\").\n"
. "    - \"diet\" (optional string): Add only if relevant (e.g., \"Vegetarian\", \"Gluten-Free\").\n"
. "- Use clean formatting — no HTML tags or markdown.\n\n"
. "- Only generate the [s_menu] section if the business is in a food-related category such as a restaurant, cafe, bakery, food truck, diner, or similar. If it is not, skip this section entirely.\n"

. "**Example Format (inside [s_menu]):**\n"
. "[s_menu]\n"
. "return [\n"
. "    [\n"
. "        \"name\" => \"Grilled Chicken Sandwich\",\n"
. "        \"description\" => \"Juicy grilled chicken breast with lettuce, tomato, and garlic aioli on brioche.\",\n"
. "        \"price\" => \"11.50\"\n"
. "    ],\n"
. "    [\n"
. "        \"name\" => \"Vegan Buddha Bowl\",\n"
. "        \"description\" => \"Quinoa, chickpeas, avocado, and greens with lemon tahini dressing.\",\n"
. "        \"price\" => \"13.00\",\n"
. "        \"diet\" => \"Vegan\"\n"
. "    ]\n"
. "];\n"
. "[/s_menu]\n\n"

. "**General Guidelines**:\n"
. "1. Use natural, human-friendly tone — not robotic.\n"
. "2. Use 'city name, state abbreviation' only once per section.\n"
. "3. Do NOT repeat keywords or locations unnecessarily.\n"
. "4. Do NOT include any external links.\n"
. "5. Use only basic formatting (bold via **, and <strong> where allowed).\n"
. "6. Return only the requested content inside their respective tags, nothing else.";



}

function bip_get_listing_content( $name, $address, $category ) {

    if( ! $name || ! $address ) {
        return;
    }

    $command = bip_listing_description_update_prompt( $name, $address, $category );

    $response = bip_ai_server_response( $command );

    // Extract 3 parts using regex
    $parts = bip_parse_ai_response_parts( $response );

    return [
        'summary'       => $parts['summary'] ?? '',
        'review'        => $parts['review'] ?? '',
        'faqs'          => $parts['faqs'] ?? [],
        'menu'          => $parts['menu'] ?? [],
        // 'response' => $response,
    ];
}



function bip_parse_ai_response_parts( $response ) {
    $parts = [
        'summary' => '',
        'review'  => '',
        'faqs'    => [],
        'menu'    => [],
    ];

    // Extract content between [s_description]...[/s_description]
    if ( preg_match( '/\[s_description\](.*?)\[\/s_description\]/is', $response, $matches ) ) {
        $parts['summary'] = trim($matches[1]);
    }

    // Extract content between [s_review]...[/s_review]
    if ( preg_match( '/\[s_review\](.*?)\[\/s_review\]/is', $response, $matches ) ) {
        $parts['review'] = trim($matches[1]);
    }

    // Extract and eval content between [s_faqs]...[/s_faqs]
    if ( preg_match( '/\[s_faqs\](.*?)\[\/s_faqs\]/is', $response, $matches ) ) {
        $faq_code = trim($matches[1]);
        try {
            // Remove return statement from the beginning and evaluate
            $faq_code = preg_replace('/^return\s+/i', '', $faq_code);
            
            // Debug: Log the FAQ code before eval
            error_log("FAQ code: " . $faq_code);

            $faqs = eval('return ' . $faq_code . ';');
            
            // Debug: Log the result of eval to check if it's an array
            error_log("Parsed FAQ result: " . print_r($faqs, true));
            
            if ( is_array($faqs) ) {
                $parts['faqs'] = $faqs;
            } else {
                error_log("FAQ parsing failed. No valid array returned.");
            }
        } catch (Throwable $e) {
            error_log("FAQ parsing failed: " . $e->getMessage());
        }
    }

    // Extract and eval content between [s_menu]...[/s_menu]
    if ( preg_match( '/\[s_menu\](.*?)\[\/s_menu\]/is', $response, $matches ) ) {
        $menu_code = trim($matches[1]);
        try {
            // Remove return statement from the beginning and evaluate
            $menu_code = preg_replace('/^return\s+/i', '', $menu_code);
            $menu = eval('return ' . $menu_code . ';');
            
            // Debug: Log the result of eval to check if it's an array
            error_log("Parsed Menu result: " . print_r($menu, true));
            
            if ( is_array($menu) ) {
                $parts['menu'] = $menu;
            } else {
                error_log("Menu parsing failed. No valid array returned.");
            }
        } catch (Throwable $e) {
            error_log("Menu parsing failed: " . $e->getMessage());
        }
    }

    return $parts;
}
<?php
/**
 * Helpers
 *
 * @author   Bipper Media
 * @category API
 * @since    0.1
 */


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
        'model' => 'meta-llama/llama-4-maverick-17b-128e-instruct', //llama3-70b-8192, llama-3.1-70b-versatile, meta-llama/llama-4-maverick-17b-128e-instruct, meta-llama/llama-4-scout-17b-16e-instruct
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

return "Generate structured content for {$name} located at {$city} in {$category}. Follow the instructions below and return output in three distinct sections. Wrap each section using custom tags for easy parsing: [s_description]...[/s_description], [s_review]...[/s_review], and [s_faqs]...[/s_faqs]. Do not include any introductory or extra text.\n\n"

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
. "- Return a plain PHP array with 3 FAQs, each containing a 'question' and 'answer'.\n"
. "- Each answer should be 2–3 sentences max, clear and helpful.\n"
. "- Use <strong> HTML tags only where helpful, maximum 3–4 across all answers.\n\n"

. "**Example for FAQ Format (inside [s_faqs]):**\n"
. "[s_faqs]\n"
. "return [\n"
. "    [\n"
. "        'question' => 'What services does {$name} offer?',\n"
. "        'answer' => 'We provide <strong>SEO</strong>, social media marketing, and website design tailored to your business.'\n"
. "    ],\n"
. "    [\n"
. "        'question' => 'Where is {$name} located?',\n"
. "        'answer' => '{$name} is located in {$city}, serving local and regional clients.'\n"
. "    ],\n"
. "    [\n"
. "        'question' => 'Do you offer consultations?',\n"
. "        'answer' => 'Yes, we offer <strong>free initial consultations</strong> to understand your needs and goals.'\n"
. "    ]\n"
. "];\n"
. "[/s_faqs]\n\n"

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
    ];
}

function bip_parse_ai_response_parts( $response ) {
    $parts = [
        'summary' => '',
        'review'  => '',
        'faqs'    => [],
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
            $faqs = eval($faq_code);
            if ( is_array($faqs) ) {
                $parts['faqs'] = $faqs;
            }
        } catch (Throwable $e) {
            error_log("FAQ parsing failed: " . $e->getMessage());
        }
    }

    return $parts;
}


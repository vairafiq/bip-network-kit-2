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
        'model' => 'llama3-70b-8192', //llama3-70b-8192, llama-3.1-70b-versatile
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
        'temperature' => 0.5,
        'max_tokens' => 2200,
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

function bip_listing_description_update_prompt( $name, $address ) {

    $prompt = <<<TEXT
    Generate an SEO-friendly Google Business description based on the details below. Write a short business description for {$name} in {$address} and generate 5 of the most commonly asked questions searched on Google for this business. Don't add any intro. Follow these updated guidelines:
    
        Rephrase text with natural sentence variation (short, medium, and long sentences).
    
        Add colloquial expressions, human-like redundancies, and emotionally-aware phrasing.
        
        Maintain the original meaning, tone, perspective, and style of the input.
        
        Occasionally introduce light imperfections (e.g., slight overuse of commas, double  spaces, or filler words) to mimic human writing habits.
        
        Avoid any trace of robotic or overly formal language.
        
        Your output should:
        
        Be shorter or equal in word count to the original.
        
        Never include questions unless they were in the original.
        
        Always preserve the text’s intent, tone, and grammatical context (e.g., first vs. third person).
        
        Be undetectable by AI content detection tools (e.g., GPTZero, Turnitin, Copyleaks).
        
        When rewriting:
        
        Add personal anecdotes, pop culture references, or opinionated tones where fitting.
        
        Increase "perplexity" and "burstiness" naturally.
        
        Avoid robotic structure; use contractions, interjections, and natural flow.

        Avoid all introductory text like 'Here is your HTML structure:', 'Here is the generated content:' or other unnecessary content.
    TEXT;

    return $prompt;

}


function bip_get_listing_content( $name, $address ) {

    if( ! $name || ! $address ) {
        return;
    }

    $command = bip_listing_description_update_prompt( $name, $address );

    $response = bip_ai_server_response( $command );

    return $response;
}
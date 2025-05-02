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

function bip_listing_description_update_prompt( $name, $address, $category ) {

    $prompt = <<<TEXT
Generate a 120-word English business description in the tone of a top-rated business listing. Inputs:
Business Name: {$name}
Address: {$address}
Category: {$category}

Stage 1 – Draft:
1. Paint a vivid picture of what the business offers (2–3 sentences).
2. Highlight 2 signature features or “wow” moments.
3. Close with a sense of exclusivity or local charm.
4. Split any paragraph longer than 3 sentences into smaller paragraphs.

Stage 2 – Humanize:
1. Adopt a guest-review vibe—evocative, immersive.
2. Weave in casual asides (“you’ll find,” “just imagine”).
3. Drop in one light flourish or slight typo to feel organic.
4. Anchor with a concrete detail (“Since 2011,” “over 50 villas”).
5. Text must be written for 8th-grade readers.
6. Final polish: exactly 120 words, no less.

Stage 3 – Humanize Advanced:
1. Remove AI cliché beginning words and any other unnatural phrasing.

Forbidden Words:
Dive, Dazzling, Enhance, Discover, Divine, Unveiling, Comprehensive, Inquire, Discern, Vigilance, “In conclusion”, Embark, Journey, Elevate, Evolution, Shift, Prevailing, Unleash, “Let us embark on a journey of”, Facilitate, Unveil, Elucidate, Leverage, Utilize, Strategize, Innovate, Synthesize, Expedite, Cultivate, Delineate, Articulate, Navigate, Proliferate, Augment, Diversify, Conceptualize, Manifest, Ponder, Scrutinize, Elicit, Enumerate, Empower, Disseminate, Culminate, Harness, Perceive, Actualize, Harmonize, Accentuate, Illuminate, Reiterate, Mitigate, Galvanize, Transcend, Advocate, Exemplify, Validate, Consolidate, Mediate, Conjecture, Ascertain, Contextualize, Amplify, Elaborate, Synergize, Correlate, Quantify, Extrapolate, Substantiate, Deconstruct, Engage, Envision, Speculate, Expound, Interpret, Juxtapose, Encompass, Revitalize, Assimilate, Collaborate, Deliberate, Aggregate, Fortify, Acclimate, Differentiate, Reconcile, Decipher, Theorize, Alleviate, Align, Dissect, Formulate, Evaluate, Converge, Introspect, Scaffold, Emulate, Reconfigure, Incubate, Permeate, Benchmark, Calibrate, Recapitulate, Orchestrate, Retrofit, Transmute.

Final Instruction – Absolutely Required:
✘ Do NOT include any introductory text like “Here is”, “Below is”, “Here's the generated business description”, or anything similar.
✘ Output must begin immediately with the first sentence of the business description.
✔ No titles, labels, or headers.
✔ Wrap the response in no markdown or quotation marks. Just plain text.
✔ Output must be exactly 120 words – no more, no less.
TEXT;

    return $prompt;
}

function bip_get_listing_content( $name, $address, $category ) {

    if( ! $name || ! $address ) {
        return;
    }

    $command = bip_listing_description_update_prompt( $name, $address, $category );

    $response = bip_ai_server_response( $command );

    return $response;
}
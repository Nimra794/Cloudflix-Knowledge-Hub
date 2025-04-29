<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chatbot Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the chatbot settings.
    |
    */

    // API Configuration
    'api' => [
        'enabled' => false,
        'provider' => 'openai', // or 'dialogflow', 'wit', etc.
        'key' => env('CHATBOT_API_KEY', ''),
        'model' => env('CHATBOT_MODEL', 'gpt-3.5-turbo'),
        'temperature' => 0.7,
        'max_tokens' => 150,
    ],

    // The default response when no matching pattern is found
    'default_response' => 'I am sorry, I do not understand. Could you please rephrase your question?',

    // The patterns and their corresponding responses
    'patterns' => [
        // Greetings
        'hello' => 'Hello! How can I help you today?',
        'hi' => 'Hi there! How can I assist you?',
        'hey' => 'Hey! What can I do for you?',

        // Help
        'help' => 'I can help you with:\n- Finding movies\n- Information about actors\n- User account management\n- General questions about the platform',
        'what can you do' => 'I can help you with:\n- Finding movies\n- Information about actors\n- User account management\n- General questions about the platform',

        // Movies
        'movie' => 'I can help you find movies. What kind of movies are you interested in?',
        'films' => 'I can help you find films. What kind of films are you interested in?',
        'new movies' => 'You can check out our latest movies in the Movies section.',

        // Actors
        'actor' => 'I can help you find information about actors. Which actor are you interested in?',
        'actors' => 'You can find information about actors in the Actors section.',

        // Account
        'account' => 'You can manage your account settings in your profile page.',
        'profile' => 'You can update your profile information in your profile page.',
        'password' => 'You can change your password in the Change Password section.',

        // Favorites
        'favorite' => 'You can view and manage your favorite movies in the Favorites section.',
        'favorites' => 'You can view and manage your favorite movies in the Favorites section.',

        // Ratings
        'rate' => 'You can rate movies by visiting their detail pages.',
        'rating' => 'You can view your movie ratings in the Ratings section.',

        // Reviews
        'review' => 'You can write reviews for movies by visiting their detail pages.',
        'reviews' => 'You can view your movie reviews in the Reviews section.',

        // Goodbye
        'bye' => 'Goodbye! Have a great day!',
        'goodbye' => 'Goodbye! Have a great day!',
        'see you' => 'See you later! Have a great day!',
    ],

    // The minimum similarity score required for a pattern to match (0-1)
    'similarity_threshold' => 0.7,

    // Whether to use fuzzy matching for patterns
    'use_fuzzy_matching' => true,

    // The maximum number of responses to return
    'max_responses' => 1,

    // Whether to log conversations
    'log_conversations' => true,

    // The table name for storing conversations
    'conversations_table' => 'chatbot_messages',
]; 
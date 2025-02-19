# Gemini Lite for Laravel - Documentation

> **🦸🏽‍♀️🛠️🪚⚙️🦸🏽 YOU CAN CONTRIBUTE TO THIS PROJECT, CONTACT ME TO: [jose.lopez.lara.cto@gmail.com](mailto:jose.lopez.lara.cto@gmail.com) 🦸🏽‍♀️🛠️🪚⚙️🦸🏽**

[![Latest Stable Version](https://img.shields.io/packagist/v/liteopensource/gemini-lite-laravel)](https://packagist.org/packages/liteopensource/gemini-lite-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/liteopensource/gemini-lite-laravel)](https://packagist.org/packages/liteopensource/gemini-lite-laravel)
[![Stars](https://img.shields.io/github/stars/LiteOpenSource/GeminiLite-Laravel)](https://github.com/LiteOpenSource/GeminiLite-Laravel)
[![License](https://img.shields.io/packagist/l/liteopensource/gemini-lite-laravel)](https://packagist.org/packages/liteopensource/gemini-lite-laravel)

## Why use Gemini lite instead other open source Gemini Sdk?

Thanks to its minimalist **syntax based on Facades**, you can integrate AI functionalities effortlessly. Soon, it will include a **✨token and request limit management system inspired by Laravel Permission✨**, perfect for streamlining monetizable projects. Moreover, **its support is guaranteed until 2026**, as it will be used in two projects set to be launched into production this year.Support the project and help extend its lifespan. If you'd like to contribute, feel free to contact me.

## Features

Feature status:

- 🟢 Feature added
- 🟡 Feature in progress
- 🔴 Feature doesn't started
- 🟣 Feature doesn't planet **(If you want contribute with this, contact me)**

|               In progress              | Progres Status |
|---------------------------------------------------|-----|
| Text prompt support                               | 🟢 |
| Text prompt with file(Iamge, pdf, etc) support    | 🟢 |
| Chat history support                              | 🟢 |
| Change model config in runtime                    | 🟢 |
| Change between Gemini models                      | 🟢 |
| JSON mode support                                 | 🟢 |
| Easy upload file to get uri and mime type         | 🟢 |
| Easy get current gemini config function           | 🟢 |
| Easy get chat history of current chat instance    | 🔴 |
| ✨ Limit tokens and request support ✨           | 🟡 |
| Tokens counter before to do prompt                | 🟡 |
| Gemini flash 2.0 beta                             | 🟡 |
| Automatic unite testing                           | 🟡 |
| Embedding support                                 | 🔴 |
| Image generator support                           | 🟣 |

## Table of Contents

- [Gemini Lite for Laravel - Documentation](#gemini-lite-for-laravel---documentation)
  - [Why use Gemini lite instead other open source Gemini Sdk?](#why-use-gemini-lite-instead-other-open-source-gemini-sdk)
  - [Features](#features)
  - [Table of Contents](#table-of-contents)
  - [Get Started](#get-started)
    - [Requeriments](#requeriments)
    - [Installation](#installation)
  - [Configuration](#configuration)
  - [GeminiService](#geminiservice)
    - [Creating a New Chat](#creating-a-new-chat)
    - [Sending Prompts](#sending-prompts)
    - [Changing Model Configuration](#changing-model-configuration)
    - [Using JSON Mode](#using-json-mode)
    - [Changing Gemini Model](#changing-gemini-model)
    - [Getting Current Model Configuration](#getting-current-model-configuration)
  - [UploadFileToGeminiService](#uploadfiletogeminiservice)
    - [Processing Files from a Path](#processing-files-from-a-path)
    - [Processing Uploaded Files](#processing-uploaded-files)
  - [GeminiTokenCountService](#geminitokencountservice)
  - [Token Limit](#token-limit)
    - [AssignRole](#assignrole)
    - [Can make request?](#can-make-request)
    - [Is active](#is-active)
    - [Update usage](#update-usage)
    - [Store Gemini Request](#store-gemini-request)
  - [Examples](#examples)
    - [Text-Based Chat](#text-based-chat)
    - [Image-Based Chat](#image-based-chat)
    - [Changing Configuration at Runtime](#changing-configuration-at-runtime)
    - [JSON Mode Chat](#json-mode-chat)
    - [Changing Gemini Model Example](#changing-gemini-model-example)
    - [Getting Current Model Configuration Example](#getting-current-model-configuration-example)
    - [Count the number of tokens in a given text.](#count-the-number-of-tokens-in-a-given-text)
    - [Check if the user can make a request to Gemini.](#check-if-the-user-can-make-a-request-to-gemini)
    - [Check if the user is active in Gemini.](#check-if-the-user-is-active-in-gemini)
    - [Update the usage tracking for the user.](#update-the-usage-tracking-for-the-user)
    - [Assign roles to test users.](#assign-roles-to-test-users)
    - [Log the requests made by the user.](#log-the-requests-made-by-the-user)
  - [License](#license)

## Get Started

### Requeriments

You have to verify have added in your project:

- php: Minimum version ^8.0
- PostgreSQL
- guzzlehttp/guzzle: Minimum version ^7.0
- illuminate/console: Minimum version ^9.0
- illuminate/database: Minimum version ^9.0
- illuminate/http: Minimum version ^9.0
- illuminate/support: Minimum version ^9.0

### Installation

To install the Gemini Lite for Laravel package, use ```composer require liteopensource/gemini-lite-laravel``` or add the following line to your `composer.json`

```json
"require": {
    "liteopensource/gemini-lite-laravel": "^0.0.3"
}
```

Then run:
```composer update```

## Configuration

Publish your config file ```geminilite.php``` using:

```php artisan vendor:publish --tag="geminilite-config"```

Add your Gemini API key to your ```.env``` file:

```GEMINILITE_SECRET_API_KEY=your_api_key_here```

## GeminiService

GeminiService provides methods to interact with the Gemini AI model.

### Creating a New Chat

Example of starting a new chat session:

```php
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;

$chat = Gemini::newChat();
```

### Sending Prompts

To send a prompt to the Gemini model:

```php
$response = $chat->newPrompt("Your prompt here");
```

### Changing Model Configuration

You can modify the model configuration at runtime:

```php
$chat->setGeminiModelConfig($temperature, $topK, $topP, $maxOutputTokens, $returnMimeType);
```

### Using JSON Mode

```php
$responseSchema = [
    // Your JSON schema here
];

$chat->setGeminiModelConfig(
    temperature: 1,
    topK: 40,
    topP: 0.95,
    maxOutputTokens: 8192,
    responseMimeType: 'application/json',
    responseSchema: $responseSchema
);
```

### Changing Gemini Model

You can switch between different Gemini models. The currently available models are:

Stable Models:
- gemini-1.5-flash
- gemini-1.5-pro
- gemini-1.5-flash-8b
- gemini-2.0-flash
- gemini-2.0-flash-lite-preview-02-05
- gemini-2.0-pro-exp-02-05
- gemini-2.0-flash-thinking-exp-01-21



Experimental Models:
- gemini-2.0-flash-exp
- learnlm-1.5-pro-experimental

```php
$chat->changeGeminiModel("gemini-2.0-flash"); // Example using new model
```

### Getting Current Model Configuration

You can retrieve the current configuration of the Gemini model:

```php
$currentConfig = $chat->getGeminiModelConfig();
```

## UploadFileToGeminiService

This service allows you to upload files for processing by Gemini.

### Processing Files from a Path

To process a file from a local path:

```php
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\UploadFileToGemini;

$filePath = storage_path('your/file/path.file');
$fileProcessed = UploadFileToGemini::processFileFromPath($filePath);

$uri = $fileProcessed->getUri();
$mimeType = $fileProcessed->getMimeType();

```

### Processing Uploaded Files

> This is really useful when you work with Livewire and using ```use WithFileUploads;```

To process an uploaded file:

```php
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\UploadFileToGemini;

$file = $request->file($yourFile);
$fileProcessed = UploadFileToGemini::processFileFromUpload($file);

$uri = $fileProcessed->getUri();
$mimeType = $fileProcessed->getMimeType();
```
## GeminiTokenCountService
This service is responsible for counting the tokens consumed by a text prompt. It returns an integer indicating the number of tokens consumed by the text prompt.

```php
$tokens = GeminiTokenCount::coutTextTokens("Input text");
```
## Token Limit 
**Token limit is independent of GeminiChat so the use of this feature is not integrated into the use of Gemini**

This feature is intended for controlling user token consumption. It uses tables in the database to control usage. Therefore, it is important to publish the respective seeders for its operation.



Command to publish seeder and tables:
```bash
php artisan vendor:publish --tag="geminilite-limit-tokes"
```
To use this feature you need to **add** the **HasGeminiRoles** trait to the model user  
```php
use LiteOpenSource\GeminiLiteLaravel\Src\Traits\HasGeminiRoles;

class User extends Authenticatable
{
    use  HasGeminiRoles;

}
```

### AssignRole
```php
$testUser = User::create([
    'name' => 'John',
    'email' => 'test@example.com',
    'password'=> Hash::make("password"),
]);
/*
You can assign the role by the 
index or name of the role in the database 

$testUser2->assignGeminiRole('limited_user');

*/
$testUser->assignGeminiRole(1);

```
### Can make request?
This function is for check if the user can make request to Gemini according to the established limits. The function returs a boolean value 
```php
// $user MUST BE an instance of model User
$user = User::find(1); 
$canMakeRequest = $user->canMakeRequestToGemini();
```
### Is active 
This function is for check if the user is active. The function returs a boolean value 
```php
// $user MUST BE an instance of model User
$user = User::find(1); 
$canMakeRequest = $user->isActiveInGemini();
```
The difference between isActive and canMakeRequest is that canMakeRequest works based on the limit of tokens and daily and monthly requests and isActive is a more manual and controlled way of controlling the use.

### Update usage
This method updates gemini usage, increments the request counter, and increments the token counter. As a parameter it receives the number of tokens used by a prompt as an integer.
```php
// $user MUST BE an instance of model User
$user = User::find(1); 
$user->updateUsageTracking($tokens);
```
### Store Gemini Request 
This function is intended to store data from the request to gemini, this function does not affect the tracking of user usage, it only stores data.

```php
$testUser->storeGeminiRequest(requestType: "Test",
 consumedTokens: $tokens, 
 requestSuccessful: true, 
 requestData: ["request"=> $prompt],
 responseData: ["response"=> $response]); 
```

## Examples

### Text-Based Chat

You can **keep chat history** easily:

```php
$gemini = Gemini::newChat();
$response = $gemini->newPrompt('How much is 1 + 1?');
$followUp = $gemini->newPrompt('Add 8 to the previous result');
```

### Image-Based Chat

You can combine image prompt and text prompt in the only one prompt

```php
$testImagePath = storage_path('app/public/test_image.jpeg');
$uploadedFile = UploadFileToGemini::processFileFromPath($testImagePath);

$gemini = Gemini::newChat();
$response = $gemini->newPrompt(
    "What do you see in this image?",
    $uploadedFile->getUri(),
    $uploadedFile->getMimeType()
);

```

### Changing Configuration at Runtime

```php
$gemini = Gemini::newChat();
$gemini->setGeminiModelConfig(1, 40, 0.95, 8192, 'text/plain');
$response = $gemini->newPrompt('Generate a creative story');
```

### JSON Mode Chat

Here's an example of using JSON mode to generate cookie recipes:

```php
$responseSchema = [
    "responseSchema" => [
        "type" => "object",
        "description" => "Return some of the most popular cookie recipes",
        "properties" => [
            "recipes" => [
                "type" => "array",
                "items" => [
                    "type" => "object",
                    "properties" => [
                        "recipe_name" => [
                            "type" => "string",
                            "description" => "name of recipe using upper case"
                        ],
                        "ingredients_number" => [
                            "type" => "number"
                        ]
                    ],
                    "required" => [
                        "recipe_name",
                        "ingredients_number"
                    ]
                ]
            ],
            "status_response" => [
                "type" => "array",
                "items" => [
                    "type" => "object",
                    "properties" => [
                        "sucess" => [
                            "type" => "string",
                            "description" => "Short message in uppercase about request"
                        ],
                        "code" => [
                            "type" => "string",
                            "description" => "Status code",
                            "enum" => [
                                "200",
                                "400"
                            ]
                        ]
                    ],
                    "required" => [
                        "sucess",
                        "code"
                    ]
                ]
            ]
        ],
        "required" => [
            "recipes",
            "status_response"
        ]
    ]
];

$geminiChat = Gemini::newChat();
$geminiChat->setGeminiModelConfig(
    temperature: 1,
    topK: 40,
    topP: 0.95,
    maxOutputTokens: 8192,
    responseMimeType: 'application/json',
    responseSchema: $responseSchema
);

$response = $geminiChat->newPrompt("Generate a list of cookie recipes. Make the outputs in JSON format.");

$responseObject = json_decode($response);
$recipes = $responseObject->recipes;
$firstRecipeName = $recipes[0]->recipe_name;

// You can now work with the structured JSON response
```

### Changing Gemini Model Example

You can compare responses from different Gemini models:

```php
$geminiChat1 = Gemini::newChat();
$geminiChat1->changeGeminiModel("gemini-1.5-flash-8b");
$response1 = $geminiChat1->newPrompt("Your prompt here");

$geminiChat2 = Gemini::newChat();
$geminiChat2->changeGeminiModel("gemini-1.5-pro-002");
$response2 = $geminiChat2->newPrompt("Your prompt here");

// Compare $response1 and $response2
```

### Getting Current Model Configuration Example

You can compare responses from different Gemini models:

```php
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;

$geminiChat = Gemini::newChat();

// Get initial configuration
$initialModelConfig = $geminiChat->getGeminiModelConfig();

// Change configuration
$geminiChat->setGeminiModelConfig(2, 64, 1, 8192, 'text/plain');

// Get updated configuration
$updatedModelConfig = $geminiChat->getGeminiModelConfig();

// Now you can compare or use these configurations
```
### Count the number of tokens in a given text.
```php
$tokens = GeminiTokenCount::coutTextTokens("Hello Gemini can you write a funny story");
```
### Check if the user can make a request to Gemini.
```php
$user = User::find(1);
$canMakeRequest = $user->canMakeRequestToGemini();

return response()->json([
    'success'=> true,
    'message'=> 'Everything okay',
    'User can make request ' => $canMakeRequest
],200);
```
###  Check if the user is active in Gemini.
```php
$user = User::find(1);
$isActive = $user->isActiveInGemini();

return response()->json([
    'success'=> true,
    'message'=> 'Everything okay',
    'User is active' => $isActive
],200);
```
### Update the usage tracking for the user.
```php
$tokens = GeminiTokenCount::coutTextTokens("Write a teror story for my class");
$user = User::find(1);

if(!$user->canMakeRequestToGemini()){
    return response()->json([
        "success"=> false,
        "message"=> "User unauthorized",
    ],403);
}

$user->updateUsageTracking($tokens);
```
### Assign roles to test users.
```php
$faker = Faker::create();
$testUser = User::create([
    'name' => $faker->name,
    'email' => $faker->unique()->safeEmail,
    'password'=> Hash::make("1234"),
]);

$role = $testUser->assignGeminiRole(1);

$testUser2 = User::create([
    'name' => $faker->name,
    'email' => $faker->unique()->safeEmail,
    'password'=> Hash::make("1234"),
]);

$role = $testUser2->assignGeminiRole('limited_user');

```
### Log the requests made by the user.
```php
$prompt = "Write my tesis please";
$gemini = Gemini::newChat();

if(!$testUser->canMakeRequestToGemini()){
    return response()->json([
        'code' => 403,
        "success"=> true,
        "message"=> "The user cannot make requests to gemini",
    ],403);
}

$response = $gemini->newPrompt($prompt);
$tokens = GeminiTokenCount::coutTextTokens($prompt);
$testUser->updateUsageTracking($tokens);
$testUser->storeGeminiRequest(requestType: "Test", consumedTokens: $tokens, requestSuccessful: true, requestData: ["request"=> $prompt],responseData: ["response"=> $response]); 
```

## License

*MIT License*

*Copyright (c) 2025 José López Lara*

*Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the “liteopensource/gemini-lite-laravel”), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:*

*THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.*
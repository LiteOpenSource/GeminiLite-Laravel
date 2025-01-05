# Gemini Lite for Laravel - Documentation

> **🦸🏽‍♀️🛠️🪚⚙️🦸🏽 YOU CAN CONTRIBUTE TO THIS PROJECT, CONTACT ME TO: [jose.lopez.lara.cto@gmail.com](mailto:jose.lopez.lara.cto@gmail.com) 🦸🏽‍♀️🛠️🪚⚙️🦸🏽**

[![Latest Stable Version](https://img.shields.io/packagist/v/liteopensource/gemini-lite-laravel)](https://packagist.org/packages/liteopensource/gemini-lite-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/liteopensource/gemini-lite-laravel)](https://packagist.org/packages/liteopensource/gemini-lite-laravel)
[![Stars](https://img.shields.io/github/stars/LiteOpenSource/GeminiLite-Laravel)](https://github.com/LiteOpenSource/GeminiLite-Laravel)
[![License](https://img.shields.io/packagist/l/liteopensource/gemini-lite-laravel)](https://packagist.org/packages/liteopensource/gemini-lite-laravel)

## Why use Gemini lite instead other open source Gemini Sdk?

Thanks to its minimalist **syntax based on Facades**, you can integrate AI functionalities effortlessly. Soon, it will include a **token and request limit management system inspired by Laravel Permission**, perfect for streamlining monetizable projects. Moreover, **its support is guaranteed until 2026**, as it will be used in two projects set to be launched into production this year.Support the project and help extend its lifespan. If you'd like to contribute, feel free to contact me.

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
| Change Gemini model                               | 🟢 |
| Easy upload file to get uri and mime type         | 🟢 |
| Easy get current gemini config function           | 🟢 |
| Easy get current history function                 | 🟢 |
| Limit tokens and request support                  | 🟡 |
| Tokens counter before to do prompt                | 🟡 |
| Gemini flash 2.0 beta                             | 🟡 |
| Image generator support                           | 🟣 |
| Embedding support                                 | 🟣 |

## Table of Contents

1. [Installation](#installation)

2. [Configuration](#configuration)

3. [GeminiService](#geminiservice)

   - [Creating a New Chat](#creating-a-new-chat)  
   - [Sending Prompts](#sending-prompts)  
   - [Changing Model Configuration](#changing-model-configuration)

4. [UploadFileToGeminiService](#uploadfiletogeminiservice)

   - [Processing Files from a Path](#processing-files-from-a-path)  
   - [Processing Uploaded Files](#processing-uploaded-files)  

5. [Examples](#examples)  
   - [Text-Based Chat](#text-based-chat)  
   - [Image-Based Chat](#image-based-chat)  
   - [Changing Configuration at Runtime](#changing-configuration-at-runtime)

6. [License](#license)

## Installation

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

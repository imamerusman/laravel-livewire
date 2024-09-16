<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ValidateFCMServiceAccountRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $schema = (object)[
            "type" => "object",
            "properties" => (object)[
                "type" => (object)[
                    "type" => "string",
                    "enum" => ["service_account"]
                ],
                "project_id" => (object)[
                    "type" => "string"
                ],
                "private_key_id" => (object)[
                    "type" => "string"
                ],
                "private_key" => (object)[
                    "type" => "string"
                ],
                "client_email" => (object)[
                    "type" => "string"
                ],
                "client_id" => (object)[
                    "type" => "string"
                ],
                "auth_uri" => (object)[
                    "type" => "string"
                ],
                "token_uri" => (object)[
                    "type" => "string"
                ],
                "auth_provider_x509_cert_url" => (object)[
                    "type" => "string"
                ],
                "client_x509_cert_url" => (object)[
                    "type" => "string"
                ],
                "universe_domain" => (object)[
                    "type" => "string"
                ]
            ],
            "required" => [
                "type",
                "project_id",
                "private_key_id",
                "private_key",
                "client_email",
                "client_id",
                "auth_uri",
                "token_uri",
                "auth_provider_x509_cert_url",
                "client_x509_cert_url",
            ]
        ];
        $validator = new Validator(new Factory(new SchemaStorage()));

        $data = $this->getServiceAccountData((array)$value);

        $validator->validate($data, $schema, Constraint::CHECK_MODE_NORMAL);

        if (!$validator->isValid()) {
            dump($validator->getErrors());
            $fail("The $attribute must be a valid Firebase service account file. here are the errors: " . implode(",", array_map(fn($error) => $error['message'], $validator->getErrors())));
        }
    }

    public function getServiceAccountData(array $files, $associative = false): array|object
    {
        /** @var TemporaryUploadedFile $file */
        $file = collect($files)->first();
        return json_decode(file_get_contents($file->getRealPath()), $associative);
    }
}

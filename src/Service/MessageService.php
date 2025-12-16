<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Contact;
use App\Domain\UserSettings;
use App\Http\ApiClientInterface;
use App\Http\ApiResponse;

final class MessageService implements MessageServiceInterface
{
    public function __construct(private ApiClientInterface $client) {}

    public function sendWelcome(Contact $contact, UserSettings $settings): ApiResponse
    {
        $contactJson = json_encode([
            'firstname' => $contact->firstname,
            'lastname'  => $contact->lastname,
            'email'     => $contact->email,
            'country'   => $contact->country,
            'groups'    => $contact->groups,
        ], JSON_UNESCAPED_SLASHES);

        $fields = [
            
    // CONTACT NESTED – diambil dari form untuk firstname/lastname/email
    'contact[firstname]' => $contact->firstname,
    'contact[lastname]'  => $contact->lastname,
    'contact[email]'     => $contact->email,

    // COUNTRY & GROUP — FIX STATIC SESUAI PERMINTAAN
    'contact[country]'   => 'Indonesia',
    'contact[groups]'    => 'hfm',
            'button_0'              => '',
            'button_1'              => '',
            'field_10'              => '',
            'button_2'              => '',
            'from_phone_number_id'  => $settings->fromPhoneNumberId,
            'field_1'               => $contact->firstname,
            'field_2'               => $contact->lastname,
            'field_3'               => $contact->email,
            'header_document_file'  => '',
            'field_4'               => '',
            'field_5'               => '',
            'template_name'         => $settings->templateName,
            'field_6'               => '',
            'field_7'               => '',
            'header_image_file'     => '',
            'header_video_file'     => '',
            'field_8'               => '',
            'field_9'               => '',
            'copy_code'             => '',
            'header_video_url'      => '',
            'header_image_url'      => '',
            'header_field_1'        => '',
            'phone_number'          => $contact->whatsapp,
            'header_document_url'   => '',
            'header_document_name'  => '',
            'template_language'     => $settings->language,
        ];

        $headers = [
            'Accept: application/json',
            'Authorization: Bearer ' . $settings->apiToken,
        ];

        return $this->client->postMultipart($settings->apiUrl, $headers, $fields);
    }
}

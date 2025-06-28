<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppService
{
    private $apiUrl;
    private $accessToken;
    private $phoneNumberId;
    private $businessAccountId;

    public function __construct()
    {
        $this->apiUrl = config('whatsapp.api_url', 'https://graph.facebook.com/v18.0');
        $this->accessToken = config('whatsapp.access_token');
        $this->phoneNumberId = config('whatsapp.phone_number_id');
        $this->businessAccountId = config('whatsapp.business_account_id');
    }

    /**
     * إرسال رسالة نصية
     */
    public function sendTextMessage($to, $message)
    {
        try {
            $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";
            
            $data = [
                'messaging_product' => 'whatsapp',
                'to' => $this->formatPhoneNumber($to),
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $data);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                return [
                    'success' => true,
                    'message_id' => $response->json()['messages'][0]['id'] ?? null,
                    'response' => $response->json()
                ];
            } else {
                Log::error('WhatsApp message failed', [
                    'to' => $to,
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                return [
                    'success' => false,
                    'error' => $response->json()['error']['message'] ?? 'فشل في إرسال الرسالة'
                ];
            }

        } catch (Exception $e) {
            Log::error('WhatsApp service error', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'error' => 'حدث خطأ في الخدمة: ' . $e->getMessage()
            ];
        }
    }

    /**
     * إرسال مستند
     */
    public function sendDocument($to, $documentUrl, $filename, $caption = null)
    {
        try {
            $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";
            
            $data = [
                'messaging_product' => 'whatsapp',
                'to' => $this->formatPhoneNumber($to),
                'type' => 'document',
                'document' => [
                    'link' => $documentUrl,
                    'filename' => $filename
                ]
            ];

            if ($caption) {
                $data['document']['caption'] = $caption;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $data);

            if ($response->successful()) {
                Log::info('WhatsApp document sent successfully', [
                    'to' => $to,
                    'filename' => $filename,
                    'response' => $response->json()
                ]);
                return [
                    'success' => true,
                    'message_id' => $response->json()['messages'][0]['id'] ?? null,
                    'response' => $response->json()
                ];
            } else {
                Log::error('WhatsApp document failed', [
                    'to' => $to,
                    'filename' => $filename,
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                return [
                    'success' => false,
                    'error' => $response->json()['error']['message'] ?? 'فشل في إرسال المستند'
                ];
            }

        } catch (Exception $e) {
            Log::error('WhatsApp document service error', [
                'to' => $to,
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'error' => 'حدث خطأ في إرسال المستند: ' . $e->getMessage()
            ];
        }
    }

    /**
     * إرسال قالب رسالة
     */
    public function sendTemplate($to, $templateName, $languageCode = 'ar', $parameters = [])
    {
        try {
            $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";
            
            $data = [
                'messaging_product' => 'whatsapp',
                'to' => $this->formatPhoneNumber($to),
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => $languageCode
                    ]
                ]
            ];

            if (!empty($parameters)) {
                $data['template']['components'] = [
                    [
                        'type' => 'body',
                        'parameters' => $parameters
                    ]
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $data);

            if ($response->successful()) {
                Log::info('WhatsApp template sent successfully', [
                    'to' => $to,
                    'template' => $templateName,
                    'response' => $response->json()
                ]);
                return [
                    'success' => true,
                    'message_id' => $response->json()['messages'][0]['id'] ?? null,
                    'response' => $response->json()
                ];
            } else {
                Log::error('WhatsApp template failed', [
                    'to' => $to,
                    'template' => $templateName,
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                return [
                    'success' => false,
                    'error' => $response->json()['error']['message'] ?? 'فشل في إرسال القالب'
                ];
            }

        } catch (Exception $e) {
            Log::error('WhatsApp template service error', [
                'to' => $to,
                'template' => $templateName,
                'error' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'error' => 'حدث خطأ في إرسال القالب: ' . $e->getMessage()
            ];
        }
    }

    /**
     * تنسيق رقم الهاتف
     */
    private function formatPhoneNumber($phoneNumber)
    {
        // إزالة المسافات والرموز الخاصة
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // إضافة رمز الدولة إذا لم يكن موجوداً
        if (substr($phone, 0, 3) !== '964') {
            // إزالة الصفر الأول إذا كان موجوداً
            if (substr($phone, 0, 1) === '0') {
                $phone = substr($phone, 1);
            }
            $phone = '964' . $phone;
        }
        
        return $phone;
    }

    /**
     * التحقق من صحة رقم الهاتف
     */
    public function validatePhoneNumber($phoneNumber)
    {
        $phone = $this->formatPhoneNumber($phoneNumber);
        
        // التحقق من طول الرقم العراقي
        if (strlen($phone) !== 13) {
            return false;
        }
        
        // التحقق من بداية الرقم
        if (substr($phone, 0, 3) !== '964') {
            return false;
        }
        
        // التحقق من أرقام الشبكات العراقية
        $validPrefixes = ['964770', '964771', '964772', '964773', '964774', '964775', '964776', '964777', '964778', '964779', '964750', '964751', '964752', '964753', '964754', '964755', '964756', '964757', '964758', '964759'];
        
        $prefix = substr($phone, 0, 6);
        return in_array($prefix, $validPrefixes);
    }

    /**
     * الحصول على حالة الرسالة
     */
    public function getMessageStatus($messageId)
    {
        try {
            $url = "{$this->apiUrl}/{$messageId}";
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->get($url);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'status' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'فشل في الحصول على حالة الرسالة'
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'حدث خطأ: ' . $e->getMessage()
            ];
        }
    }

    /**
     * التحقق من إعدادات الواتساب
     */
    public function checkConfiguration()
    {
        $errors = [];
        
        if (empty($this->accessToken)) {
            $errors[] = 'Access Token غير محدد';
        }
        
        if (empty($this->phoneNumberId)) {
            $errors[] = 'Phone Number ID غير محدد';
        }
        
        if (empty($this->businessAccountId)) {
            $errors[] = 'Business Account ID غير محدد';
        }
        
        return [
            'configured' => empty($errors),
            'errors' => $errors
        ];
    }
}

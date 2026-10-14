<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProformaTemplate extends Model
{
    protected $fillable = [
        'name',
        'is_active',
        'sections',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sections' => 'array',
    ];

    public static function defaultSections(): array
    {
        return [
            [
                'key' => 'header_branding',
                'title' => 'Header Logo & Proforma Title',
                'description' => 'Coopera Trading brand logo, Proforma Invoice title, document number, and issue date',
                'visible' => true,
                'align' => 'between',
            ],
            [
                'key' => 'party_details',
                'title' => 'Supplier & Customer Bill-To Details',
                'description' => 'Coopera office address, phone, email, and Customer Bill-To address block',
                'visible' => true,
                'layout' => 'split',
            ],
            [
                'key' => 'items_table',
                'title' => 'Products Items Table',
                'description' => 'Line items with Description, Unit of Measure, Quantity, Unit Price, and Line Total',
                'visible' => true,
            ],
            [
                'key' => 'financial_summary',
                'title' => 'Financial Subtotal & 15% VAT Summary',
                'description' => 'Subtotal ETB, 15% VAT, and Grand Total ETB calculation',
                'visible' => true,
            ],
            [
                'key' => 'amount_in_words',
                'title' => 'Total Price in Words Line',
                'description' => 'Official line displaying the total invoice amount spelled out in words',
                'visible' => true,
                'label' => 'Total Amount in Words:',
                'content' => 'Two Hundred Fifteen Thousand Six Hundred Twenty-Five ETB Only',
            ],
            [
                'key' => 'payment_bank_info',
                'title' => 'Bank & Payment Details',
                'description' => 'CBE bank name, account number, branch, and payment instructions',
                'visible' => true,
                'content' => "Commercial Bank of Ethiopia (CBE)\nAccount Name: Coopera Trading\nAccount Number: 1000123456789\nBranch: Bole Branch, Addis Ababa",
            ],
            [
                'key' => 'terms_notes',
                'title' => 'Terms of Delivery & Tax Info',
                'description' => 'Validity period, delivery schedule, TIN number, and notes',
                'visible' => true,
                'content' => "Price validity: 30 Days from date of issue.\nDelivery: 3-5 Business days after payment confirmation.\nVAT Certificate #: 1234567890 | TIN #: 00987654321",
            ],
            [
                'key' => 'qr_code_stamp',
                'title' => 'QR Code Verification & Stamp',
                'description' => 'Verification QR code (adjustable size) and Authorized Stamp/Signature block',
                'visible' => true,
                'qr_size' => 'medium', // small (60px), medium (90px), large (120px)
            ],
        ];
    }

    public static function getActive(): self
    {
        try {
            $template = self::where('is_active', true)->first();

            if (! $template) {
                $template = self::create([
                    'name' => 'Default VAT Proforma Template',
                    'is_active' => true,
                    'sections' => self::defaultSections(),
                ]);
            }

            return $template;
        } catch (\Throwable $e) {
            $template = new self([
                'name' => 'Default VAT Proforma Template',
                'is_active' => true,
                'sections' => self::defaultSections(),
            ]);
            return $template;
        }
    }

    public static function numberToWords(float $number): string
    {
        $intPart = (int) floor($number);
        $fraction = (int) round(($number - $intPart) * 100);

        $words = self::convertIntegerToWords($intPart) . ' ETB';
        if ($fraction > 0) {
            $words .= ' and ' . self::convertIntegerToWords($fraction) . ' Cents';
        } else {
            $words .= ' Only';
        }

        return $words;
    }

    protected static function convertIntegerToWords(int $number): string
    {
        if ($number === 0) {
            return 'Zero';
        }

        $units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        $scales = ['', 'Thousand', 'Million', 'Billion'];

        $parts = [];
        $scaleIndex = 0;

        while ($number > 0) {
            $chunk = $number % 1000;
            if ($chunk > 0) {
                $chunkWords = [];
                $hundreds = (int) ($chunk / 100);
                $remainder = $chunk % 100;

                if ($hundreds > 0) {
                    $chunkWords[] = $units[$hundreds] . ' Hundred';
                }

                if ($remainder > 0) {
                    if ($remainder < 20) {
                        $chunkWords[] = $units[$remainder];
                    } else {
                        $ten = (int) ($remainder / 10);
                        $unit = $remainder % 10;
                        $chunkWords[] = $tens[$ten] . ($unit > 0 ? '-' . $units[$unit] : '');
                    }
                }

                $scaleName = $scales[$scaleIndex] ?? '';
                $chunkString = implode(' ', $chunkWords) . ($scaleName ? ' ' . $scaleName : '');
                array_unshift($parts, $chunkString);
            }
            $number = (int) ($number / 1000);
            $scaleIndex++;
        }

        return implode(' ', $parts);
    }
}

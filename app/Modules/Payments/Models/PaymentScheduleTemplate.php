<?php

namespace App\Modules\Payments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentScheduleTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'is_default',
        'is_active',
        'installment_config',
        'grace_period_days',
        'late_fees_enabled',
        'late_fee_percentage',
        'reminder_days_before',
    ];

    protected $casts = [
        'installment_config' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'late_fees_enabled' => 'boolean',
        'late_fee_percentage' => 'decimal:4',
        'grace_period_days' => 'integer',
        'reminder_days_before' => 'integer',
    ];

    // Template type constants
    const TYPE_STANDARD_3_TIER = 'standard_3_tier';
    const TYPE_EQUAL_SPLIT = 'equal_split';
    const TYPE_FULL_UPFRONT = 'full_upfront';
    const TYPE_CORPORATE_60_DAY = 'corporate_60_day';
    const TYPE_MONTHLY_6 = 'monthly_6';
    const TYPE_CUSTOM = 'custom';

    /**
     * Scope for active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default template.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get template by type.
     */
    public static function getByType(string $type): ?self
    {
        return self::where('type', $type)->first();
    }

    /**
     * Get the default template.
     */
    public static function getDefault(): ?self
    {
        return self::where('is_default', true)->first();
    }

    /**
     * Get all predefined templates data.
     */
    public static function getPredefinedTemplates(): array
    {
        return [
            self::TYPE_STANDARD_3_TIER => [
                'name' => 'Standard 3-Tier (30/40/30)',
                'description' => 'Standard payment schedule with deposit, interim, and final payments',
                'type' => self::TYPE_STANDARD_3_TIER,
                'installment_config' => [
                    [
                        'type' => Installment::TYPE_DEPOSIT,
                        'name' => 'Deposit',
                        'percentage' => 30,
                        'days_from_event' => -60, // 60 days before event
                    ],
                    [
                        'type' => Installment::TYPE_INTERIM,
                        'name' => 'Interim Payment',
                        'percentage' => 40,
                        'days_from_event' => -30, // 30 days before event
                    ],
                    [
                        'type' => Installment::TYPE_FINAL,
                        'name' => 'Final Payment',
                        'percentage' => 30,
                        'days_from_event' => -7, // 7 days before event
                    ],
                ],
                'grace_period_days' => 3,
                'late_fees_enabled' => true,
                'late_fee_percentage' => 0.02,
                'reminder_days_before' => 7,
            ],
            self::TYPE_EQUAL_SPLIT => [
                'name' => '50/50 Split',
                'description' => 'Two equal payments - deposit and final',
                'type' => self::TYPE_EQUAL_SPLIT,
                'installment_config' => [
                    [
                        'type' => Installment::TYPE_DEPOSIT,
                        'name' => 'Deposit',
                        'percentage' => 50,
                        'days_from_event' => -30,
                    ],
                    [
                        'type' => Installment::TYPE_FINAL,
                        'name' => 'Final Payment',
                        'percentage' => 50,
                        'days_from_event' => -7,
                    ],
                ],
                'grace_period_days' => 5,
                'late_fees_enabled' => true,
                'late_fee_percentage' => 0.015,
                'reminder_days_before' => 7,
            ],
            self::TYPE_FULL_UPFRONT => [
                'name' => 'Full Payment Upfront',
                'description' => '100% payment required at booking',
                'type' => self::TYPE_FULL_UPFRONT,
                'installment_config' => [
                    [
                        'type' => Installment::TYPE_DEPOSIT,
                        'name' => 'Full Payment',
                        'percentage' => 100,
                        'days_from_booking' => 7, // 7 days from booking
                    ],
                ],
                'grace_period_days' => 0,
                'late_fees_enabled' => true,
                'late_fee_percentage' => 0.025,
                'reminder_days_before' => 3,
            ],
            self::TYPE_CORPORATE_60_DAY => [
                'name' => 'Corporate 60-Day Terms',
                'description' => 'Net 60 payment terms for corporate clients',
                'type' => self::TYPE_CORPORATE_60_DAY,
                'installment_config' => [
                    [
                        'type' => Installment::TYPE_FINAL,
                        'name' => 'Payment Due',
                        'percentage' => 100,
                        'days_from_event' => 60, // 60 days after event
                    ],
                ],
                'grace_period_days' => 15,
                'late_fees_enabled' => true,
                'late_fee_percentage' => 0.015,
                'reminder_days_before' => 14,
            ],
            self::TYPE_MONTHLY_6 => [
                'name' => '6-Month Monthly',
                'description' => 'Spread payment over 6 months',
                'type' => self::TYPE_MONTHLY_6,
                'installment_config' => array_map(function ($i) {
                    return [
                        'type' => $i === 5 ? Installment::TYPE_FINAL : Installment::TYPE_MILESTONE,
                        'name' => 'Month ' . ($i + 1) . ' Payment',
                        'percentage' => 16.67,
                        'days_from_event' => -180 + ($i * 30), // Starting 6 months before
                    ];
                }, range(0, 5)),
                'grace_period_days' => 5,
                'late_fees_enabled' => true,
                'late_fee_percentage' => 0.02,
                'reminder_days_before' => 5,
            ],
        ];
    }

    /**
     * Seed the default templates.
     */
    public static function seedDefaultTemplates(): void
    {
        $templates = self::getPredefinedTemplates();

        foreach ($templates as $type => $template) {
            self::firstOrCreate(
                ['type' => $type],
                array_merge($template, [
                    'is_active' => true,
                    'is_default' => $type === self::TYPE_STANDARD_3_TIER,
                ])
            );
        }
    }

    /**
     * Generate installments from this template.
     */
    public function generateInstallments(float $totalAmount, \DateTimeInterface $eventDate, ?\DateTimeInterface $bookingDate = null): array
    {
        $installments = [];
        $bookingDate = $bookingDate ?? now();

        foreach ($this->installment_config as $index => $config) {
            $dueDate = null;

            if (isset($config['days_from_event'])) {
                $dueDate = (clone $eventDate)->modify($config['days_from_event'] . ' days');
            } elseif (isset($config['days_from_booking'])) {
                $dueDate = (clone $bookingDate)->modify($config['days_from_booking'] . ' days');
            }

            if ($dueDate) {
                $amount = round(($totalAmount * $config['percentage']) / 100, 2);

                $installments[] = [
                    'type' => $config['type'],
                    'sequence' => $index + 1,
                    'name' => $config['name'],
                    'amount' => $amount,
                    'percentage_of_total' => $config['percentage'],
                    'due_date' => $dueDate->format('Y-m-d'),
                    'status' => Installment::STATUS_PENDING,
                    'paid_amount' => 0,
                    'amount_outstanding' => $amount,
                ];
            }
        }

        // Adjust last installment to account for rounding differences
        if (!empty($installments)) {
            $totalCalculated = array_sum(array_column($installments, 'amount'));
            $difference = round($totalAmount - $totalCalculated, 2);
            
            if ($difference != 0) {
                $lastIndex = count($installments) - 1;
                $installments[$lastIndex]['amount'] += $difference;
                $installments[$lastIndex]['amount_outstanding'] += $difference;
            }
        }

        return $installments;
    }
}

<?php

namespace Database\Seeders;

use App\Modules\Payments\Models\PaymentScheduleTemplate;
use Illuminate\Database\Seeder;

class PaymentScheduleTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = PaymentScheduleTemplate::getPredefinedTemplates();

        foreach ($templates as $type => $template) {
            PaymentScheduleTemplate::firstOrCreate(
                ['type' => $type],
                array_merge($template, [
                    'is_active' => true,
                    'is_default' => $type === PaymentScheduleTemplate::TYPE_STANDARD_3_TIER,
                ])
            );
        }

        $this->command->info('Payment schedule templates seeded successfully.');
    }
}

<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the hierarchical statuses array
        $statuses = [
            [
                'name_en' => 'Pending',
                'name_ar' => 'معلقة',
                'next_statuses' => [
                    [
                        'name_en' => 'Deleted',
                        'name_ar' => 'محذوفة'
                    ],
                    [
                        'name_en' => 'Rejected',
                        'name_ar' => 'مرفوضة'
                    ],
                    [
                        'name_en' => 'Under Preparing',
                        'name_ar' => 'قيد التحضير',
                        'next_statuses' => [
                            [
                                'name_en' => 'Cancelled',
                                'name_ar' => 'ملغاة'
                            ],
                            [
                                'name_en' => 'Under Shipping',
                                'name_ar' => 'قيد الشحن',
                                'next_statuses' => [
                                    [
                                        'name_en' => 'Delivered',
                                        'name_ar' => 'مستلمة'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Seed the statuses
        $this->createStatuses(null, $statuses);
    }

    private function createStatuses($previous_status_id, array $statuses): void
    {
        foreach ($statuses as $status) {
            $orderStatus = OrderStatus::query()->create([
                'name_en' => $status['name_en'],
                'name_ar' => $status['name_ar'],
                'previous_status_id' => $previous_status_id,
            ]);
            if (isset($status['next_statuses'])) {
                $this->createStatuses($orderStatus->id, $status['next_statuses']);
            }
        }
    }
}

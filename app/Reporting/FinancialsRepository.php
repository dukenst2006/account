<?php

namespace App\Reporting;

use Carbon\Carbon;
use DB;

class FinancialsRepository
{
    public function invoiceItemSummary()
    {
        $monthItems = DB::table('receipt_items')
            ->select(DB::raw('DATE_FORMAT(created_at, \'%c\') AS month,
                description,
                SUM(price * quantity) AS total'))
            ->whereRaw('created_at >= NOW() - INTERVAL 3 MONTH')
            ->groupBy('sku', DB::raw('MONTH(created_at)'))
            ->get()->toArray();

        $items = [];
        $thisMonthNumber = Carbon::now()->format('m');
        $lastMonthNumber = Carbon::now()->subMonth()->format('m');
        $monthBeforeLastNumber = Carbon::now()->subMonths(2)->format('m');

        foreach ($monthItems as $monthItemSummary) {
            if (!array_key_exists($monthItemSummary->description, $items)) {
                $items[$monthItemSummary->description] = [
                    'thisMonth'       => 0,
                    'lastMonth'       => 0,
                    'monthBeforeLast' => 0,
                ];
            }

            if ($thisMonthNumber == $monthItemSummary->month) {
                $items[$monthItemSummary->description]['thisMonth'] += $monthItemSummary->total;
            } elseif ($lastMonthNumber == $monthItemSummary->month) {
                $items[$monthItemSummary->description]['lastMonth'] += $monthItemSummary->total;
            } elseif ($monthBeforeLastNumber == $monthItemSummary->month) {
                $items[$monthItemSummary->description]['monthBeforeLast'] += $monthItemSummary->total;
            }
        }

        return $items;
    }
}

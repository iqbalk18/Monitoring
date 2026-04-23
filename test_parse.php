<?php

$json = <<<'JSON'
{
    "success": true,
    "process_time": "0.037318 second(s)",
    "message": "Operation Success.",
    "data": [
        {
            "id": 23899,
            "recapCode": "BIH-20251231-C-RJ-1126-C",
            "items": [
                {
                    "itemNumber": 100,
                    "belongsToRefs": [
                        {
                            "refId": "EBI00002344",
                            "encounterId": "EBI00002344"
                        }
                    ]
                }
            ]
        }
    ]
}
JSON;

$data = json_decode($json, true);
$recaps = $data['data'] ?? [];

foreach ($recaps as $recap) {
    echo "Recap: " . $recap['recapCode'] . "\n";
    $items = $recap['items'] ?? [];
    foreach ($items as $item) {
        $refs = collect($item['belongsToRefs'] ?? []);
        if ($refs->isEmpty()) {
            $refs = collect($recap['refs'] ?? []);
        }
        if ($refs->isEmpty()) {
            $refs = collect([['refId' => '-', 'encounterId' => '-']]);
        }
        $refIds = collect($refs)->pluck('refId')->filter(function($val) { return $val && $val !== '-'; })->unique()->implode(', ');
        if (empty($refIds)) $refIds = '-';
        
        echo "Item " . $item['itemNumber'] . " Ref IDs: " . $refIds . "\n";
    }
}

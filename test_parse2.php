<?php
$json = file_get_contents(__DIR__ . '/test_json.json');
$data = json_decode($json, true);
$recaps = $data['data'] ?? [];

$output = [];
foreach ($recaps as $recap) {
    $items = $recap['items'] ?? [];
    foreach ($items as $item) {
        $refs = $item['belongsToRefs'] ?? [];
        if (empty($refs)) {
            $refs = $recap['refs'] ?? [];
        }
        if (empty($refs)) {
            $refs = [['refId' => '-', 'encounterId' => '-']];
        }
        
        $refIds = [];
        foreach ($refs as $r) {
            if (!empty($r['refId']) && $r['refId'] !== '-') {
                $refIds[] = $r['refId'];
            }
        }
        $refIds = array_unique($refIds);
        $finalRefId = empty($refIds) ? '-' : implode(', ', $refIds);
        
        $output[] = "Item " . $item['itemNumber'] . " Ref IDs: " . $finalRefId;
    }
}
echo implode("\n", $output);

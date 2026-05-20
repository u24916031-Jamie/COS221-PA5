<?php

function searchPackages($data) 
{
    $db = Database::instance();
    $search = is_array($data) ? ($data['search'] ?? '') : ($data->search ?? '');
    $sort = is_array($data) ? ($data['sort'] ?? 'price') : ($data->sort ?? 'price');
    $order = is_array($data) ? ($data['order'] ?? 'ASC') : ($data->order ?? 'ASC');

    $allowed_order = ["ASC", "DESC"];
    $order = strtoupper($order);
    if (!in_array($order, $allowed_order)) 
	{
        $order = "ASC";
    }

    $allowed_sort = ["cost", "price", "rating"];
    $sort = strtolower($sort);
    if (!in_array($sort, $allowed_sort)) {
        $sort = "price";
    }

    $params = 
	[
        "search" => $search,
        "sort" => $sort,
        "order" => $order
    ];

    $ret = $db->searchPackages($params);
    
    header('HTTP/1.1 200 Ok');
    header('Content-Type: application/json');
    $retdata = [
        'status' => 'success',
        'data' => $ret
    ];
            
    echo json_encode($retdata);
    exit();
}

?>

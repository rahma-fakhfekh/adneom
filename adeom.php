<?php
$result = getProductOfUser(90, '2018-10-1');
$result2 = getUsersOfSpecificProduct(10, '2018-10-1');
echo "Liste des produits de l'utilisater 90 sont". "\n";
echo (implode($result,'//')). "\n";
echo "Liste des utilisateurs du produit 10 sont" ."\n";
echo (implode($result2,'//')). "\n";
function getProductOfUser($userId,$date) {

    $url = 'http://conception.website/adneom/api/users/'.$userId;
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    curl_close($curl);
    $result_array = json_decode($result);
    $products = array();
    if(!empty($result_array) && isset($result_array->products)) {
        $products = array();

        foreach ($result_array->products as $product) {
            foreach ($product->subscriptionPeriods as $period) {
                if ($date >= $period->start && $date <= $period->end)
                    $products[]= $product->id;
            }
        }

    }
    return $products;


}
function getUsersOfSpecificProduct($productId,$date) {

    $url = 'http://conception.website/adneom/api/users';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    curl_close($curl);
    $result_array = json_decode($result);
    $users = array();
    if(!empty($result_array)) {
        foreach ($result_array as $user) {
            foreach ($user->products as $product) {
                foreach ($product->subscriptionPeriods as $period) {
                    if ($date >= $period->start && $date <= $period->end && $product->id == $productId)
                        $users[] = $user->id;
                }
            }
        }

    }
    return $users;


}

<?php 
    $merchant_txn_id = uniqid();

    $request_body = array(
        "id" => $merchant_txn_id,
        "amount" => (int)$_POST["amount"],
        "user" => array(
            "name" => $_POST["name"],
            "email" => $_POST["email"],
            "phoneNumber" => "+91" . $_POST["phone"]
        )
    );

    $request_json = json_encode($request_body);

    $partner_id = "testPartnerId";
    $private_key = "testPrivateKey";

    $path = "/api/transaction";

    $signature_input = $request_json . $path . $private_key;
    $signature = base64_encode(hash("sha256", $signature_input, true));

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://web.bharatx.tech" . $path);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'x-partner-id: ' . $partner_id,
        'x-signature: ' . $signature,
    ));
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    $result = curl_exec($ch);
    $http_status_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);    

    $response = array(
        "api_result" => $result,
        "status_code" => $http_status_code,
        "merchant_txn_id" => $merchant_txn_id
    );

    header("content-type: application/json", false, 200);
    echo json_encode($response);
?>

<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
// middleware untuk validasi api key



$keyRequest = function ($request, $response, $next)
{

    $key = $request->getQueryParam("key");

    if (!isset($key)) {
        return $response->withJson(["status" => "API Key required"], 401);
    }

    $sql = "SELECT * FROM tr_pengguna WHERE api_key=:api_key";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([":api_key" => $key]);

    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch();
        if ($key == $result["api_key"]) {

            // update hit
            $sql = "UPDATE tr_pengguna SET hit=hit+1 WHERE api_key=:api_key";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([":api_key" => $key]);

            return $response = $next($request, $response);
        }
    }

    return $response->withJson(["status" => "Unauthorized"], 401);

};

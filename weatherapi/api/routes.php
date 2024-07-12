<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }
    exit(0);
}

require_once("modules/weather.php");

$weather = new Weather();

if (isset($_REQUEST['request'])) {
    $request = explode('/', urldecode($_REQUEST['request']));
} else {
    http_response_code(404);
    echo json_encode(["error" => "Not Found"]);
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        switch ($request[0]) {
            case 'getweather':
                if (count($request) > 1) {
                    $city = urldecode($request[1]);
                    echo json_encode($weather->getWeatherCity($city));
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Enter a valid city name"]);
                }
                break;
            case 'getweatherbycoordinates':
                if (isset($_GET['lat']) && isset($_GET['lon'])) {
                        $lat = $_GET['lat'];
                        $lon = $_GET['lon'];
                        echo json_encode($weather->getWeatherByCoordinates($lat, $lon));
                    } else {
                        http_response_code(400);
                        echo json_encode(["error" => "Enter valid coordinates"]);
                    }
                    break;
            case 'fivedaysweather':
                if (count($request) > 1) {
                    $city = urldecode($request[1]);
                    echo json_encode($weather->get5daysforecast($city));
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Enter a valid city name"]);
                }
                break;
            case 'fivedaysweatherbycoordinates':
                if (isset($_GET['lat']) && isset($_GET['lon'])) {
                        $lat = $_GET['lat'];
                        $lon = $_GET['lon'];
                        echo json_encode($weather->get5daysForecastByCoordinates($lat, $lon));
                    } else {
                        http_response_code(400);
                        echo json_encode(["error" => "Enter valid coordinates"]);
                    }
                    break;
            default:
                http_response_code(403);
                echo json_encode(["error" => "Forbidden"]);
                break;
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["error" => "Method Not Allowed"]);
        break;
}
?>

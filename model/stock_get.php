<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // The request is using the POST method
    header("HTTP/1.1 200 OK");
    return;

}
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
} else {
    if((($_SERVER['PHP_AUTH_USER'] == 'ACJadmin') && ($_SERVER['PHP_AUTH_PW'] == 'Acb!2#45')))
    {
    }else{
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }
}



// get database connection
include_once '../../class/connection.php';
include_once '../../class/stock_class.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();  
// initialize object
$stock = new Stock($db);
  
// get get data
$func = isset($_GET['func']) ? $_GET['func'] : die();
if(!empty($func)){
// make sure data is not empty
    //insert user
    if(($func) == "GetAll")
    {
        $zone = isset($_GET['zone']) ? $_GET['zone'] : die();
        $stock_type = isset($_GET['stock']) ? $_GET['stock'] : die();
        $products = isset($_GET['products']) ? $_GET['products'] : die();


        $stock->zone = $zone;
        $stock->stock = $stock_type;
        $stock->products = $products;
        

        $stmt = $stock->read();
        $num = $stmt->rowCount();
        // check if more than 0 record found
        if($num>0){
        
            // products array
            $stock_arr=array();
            $stock_arr["data"]=array();
        
            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
        
                
                $stock_item=array(
                    "zone" => $zone,
                    "id" => $id,
                    "products" => $products,
                    "stock_in" => $stock_in,
                    "description" => $description,
                    "stock_out" => $stock_out,
                    "pdate" => $pdate
                );
        
                array_push($stock_arr["data"], $stock_item);
            }
            
            // set response code - 200 OK
            http_response_code(200);
        
            // show products data in json format
            echo json_encode($stock_arr);
        }
        // no products found will be here
        else{
          
            // set response code - 404 Not found
            http_response_code(200);
            $stock_arr=array();
            $stock_arr["data"]=array();
            echo json_encode($stock_arr);
        
        }
    //login function
    }else if(($func) == "GetAllByDate")
    {
        $zone = isset($_GET['zone']) ? $_GET['zone'] : die();
        $stock_type = isset($_GET['stock']) ? $_GET['stock'] : die();
        $products = isset($_GET['products']) ? $_GET['products'] : die();
        $date_frm = isset($_GET['date_frm']) ? $_GET['date_frm'] : die();
        $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : die();


        $stock->zone = $zone;
        $stock->stock = $stock_type;
        $stock->products = $products;
        $stock->date_frm = $date_frm;
        $stock->date_to = $date_to;
        

        $stmt = $stock->readByDate();
        $num = $stmt->rowCount();
        // check if more than 0 record found
        if($num>0){
        
            // products array
            $stock_arr=array();
            $stock_arr["data"]=array();
        
            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
        
                
                $stock_item=array(
                    "zone" => $zone,
                    "id" => $id,
                    "products" => $products,
                    "stock_in" => $stock_in,
                    "description" => $description,
                    "stock_out" => $stock_out,
                    "pdate" => $pdate
                );
        
                array_push($stock_arr["data"], $stock_item);
            }
            
            // set response code - 200 OK
            http_response_code(200);
        
            // show products data in json format
            echo json_encode($stock_arr);
        }
        // no products found will be here
        else{
          
            // set response code - 404 Not found
            http_response_code(200);
            $stock_arr=array();
            $stock_arr["data"]=array();
            echo json_encode($stock_arr);
        }
    //login function
    }
}    
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Function is empty."));
}
?>

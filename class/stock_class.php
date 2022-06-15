<?php
class Stock{
  
    // database connection and table name
    private $conn;
    private $table_name = "stock";
  
    // object properties

    //stock
    public $stock_id;
    public $zone;
    public $description;
    public $products;//0-getah,1-sawit,2-durian
    public $in;
    public $out;
    public $issueby;
    public $pdate;
    public $isdelete;
    public $zone_frm;
    public $zone_to;
    public $date_frm;
    public $date_to;
    public $stock;

  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    
    // read products
    function read(){

        if($this->stock == "0"){
            // select all query stock IN
            $query = "select Date(pdate) as pdate, `in` as stock_in, `out` as stock_out, zone, description, 
            case when products = 0 then 'Getah' when products = 1 then 'Sawit' else 'Durian' end as products, 
            stock_id as id from stock where `zone` like :zone and products like :products
            and `in` > 0 and isdelete = 0 order by zone, pdate asc;";
  
        }else if($this->stock == "1"){
            // select all query stock OUT
            $query = "select Date(pdate) as pdate, `in` as stock_in, `out` as stock_out, zone, description, 
            case when products = 0 then 'Getah' when products = 1 then 'Sawit' else 'Durian' end as products, 
            stock_id as id from stock where `zone` like :zone and products like :products
            and `out` >0 and isdelete = 0 order by zone, pdate asc;";
  
        }else{
            // select all query
            $query = "select Date(pdate) as pdate, `in` as stock_in, `out` as stock_out, zone, description, case when products = 0 then 'Getah' when products = 1 then 'Sawit' else 'Durian' end as products, stock_id as id from stock where `zone` like :zone and products like :products
            and isdelete = 0 order by zone, pdate asc;";
            
        }
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // // sanitize
        $this->zone=htmlspecialchars(strip_tags($this->zone));
        $this->products=htmlspecialchars(strip_tags($this->products));

        // bind values
        $stmt->bindParam(":zone",$this->zone);
        $stmt->bindParam(":products",$this->products);
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readByDate(){
        if($this->stock == "0"){
            // select all query stock IN
            $query = "select Date(pdate) as pdate, `in` as stock_in, `out` as stock_out, zone, description, 
            case when products = 0 then 'Getah' when products = 1 then 'Sawit' else 'Durian' end as products, 
            stock_id as id from stock where `zone` like :zone and products like :products
            and `in` > 0 and pdate >= :date_frm and pdate <= :date_to and isdelete = 0 order by zone, pdate asc;";
  
        }else if($this->stock == "1"){
            // select all query stock OUT
            $query = "select Date(pdate) as pdate, `in` as stock_in, `out` as stock_out, zone, description, 
            case when products = 0 then 'Getah' when products = 1 then 'Sawit' else 'Durian' end as products, 
            stock_id as id from stock where `zone` like :zone and products like :products
            and `out` >0 and pdate >= :date_frm and pdate <= :date_to and isdelete = 0 order by zone, pdate asc;";
  
        }else{
            // select all query
            $query = "select Date(pdate) as pdate, `in` as stock_in, `out` as stock_out, zone, description, 
            case when products = 0 then 'Getah' when products = 1 then 'Sawit' else 'Durian' end as products, 
            stock_id as id from stock where `zone` like :zone and products like :products
           and pdate >= :date_frm and pdate <= :date_to and isdelete = 0 order by zone, pdate asc;";
            
        }
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // // sanitize
        $this->zone=htmlspecialchars(strip_tags($this->zone));
        $this->products=htmlspecialchars(strip_tags($this->products));
        $this->date_frm=htmlspecialchars(strip_tags($this->date_frm));
        $this->date_to=htmlspecialchars(strip_tags($this->date_to));

        $date_f = $this->date_frm . " 00:00:00";
        $date_t = $this->date_to . " 23:59:59";
        // bind values
        $stmt->bindParam(":zone",$this->zone);
        $stmt->bindParam(":products",$this->products);
        $stmt->bindParam(":date_frm", $date_f);
        $stmt->bindParam(":date_to", $date_t);
       
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

}


?>

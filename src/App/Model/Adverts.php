<?php
namespace App\Model;
class Adverts extends PdoConfig {
    
    /**  Variable pour les données surchargées.  */
    private $data = array();
    public $resultDb = null;

    // Initialize the channel/feed data array
    private $channel = array(
        'title'       => null,
        'link'        => null,
        'description' => null,
        'items'       => array()
    );
    
    public function __construct($config){ 
        parent::__construct( $config['pdo']['dsnAdverts'], $config['pdo']['user'], $config['pdo']['pass'] );
		$this->data['domain'] = $config['domain'];
		
    }
    
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
      
    
    private function doSql(){
        $formule="(6366*acos(cos(radians(".$this->data['lat']."))*cos(radians(`lat`))*cos(radians(`lng`)-radians(".$this->data['lng']."))+sin(radians(".$this->data['lat']."))*sin(radians(`lat`))))";
        /* $sql = "\n"
            . "SELECT * \n"
                . "FROM `Advert`\n"
                    . "INNER JOIN `user`\n"
                        . "ON `Advert`.`user_id` = `user`.`id`\n"
                            . "INNER JOIN `Image`\n"
                                . "ON `Advert`.`image_id` = `Image`.`id`\n"
                                    . "INNER JOIN `Geolocate` \n"
                                        . "ON `Advert`.`geolocate_id` = `Geolocate`.`id`\n"
                                            . "INNER JOIN `advert_category`\n"
                                                ."ON `Advert`.`id` = `advert_category`.`advert_id`\n"
                                                    . "INNER JOIN `Category`\n"
                                                        ."ON `Category`.`id` = `advert_category`.`category_id`\n";
														*/
        $sql = "SELECT advert.id as id, advert.author as author, advert.content as content, advert.title as title, advert.price as price, advert.date as date, user.email as email, category.name as name, image.url as url, geolocate.lat as lat, geolocate.lng as lng, itineraire.departure as departure, itineraire.arrival as arrival, itineraire.date as date_itineraire, itineraire.time as time_itineraire  FROM advert INNER JOIN user ON advert.user_id = user.id INNER JOIN image ON advert.image_id = image.id INNER JOIN geolocate ON advert.geolocate_id = geolocate.id INNER JOIN advert_category ON advert.id = advert_category.advert_id LEFT JOIN itineraire ON advert.itineraire_id = itineraire.id INNER JOIN category ON Category.id = advert_category.category_id";
        if (!in_array($this->data['category'], array('annonces')))
            $sql  .=" AND `category`.`name` = :category\n";
        $sql  .=" WHERE $formule <= ". $this->data['distance']  ."\n";
        if (isset($this->data['q']))
            $sql .=" AND (`Advert`.`title` LIKE '%".$this->data['q']."%' OR `Advert`.`content` LIKE '%".$this->data['q']."%') \n";
        $sql .= " ORDER BY `Advert`.`date` ASC";
        
        $sth = $this->prepare($sql);
        //$sth->bindParam(':distance', $distance, PDO::PARAM_INT);
        if (!in_array($this->data['category'], array('Annonces')))
            $sth->bindParam(':category', $this->data['category'], \PDO::PARAM_STR);
        
        return $sth;
    }
    
    public function getAround()  {
        try {
            $sth = $this->doSql();
            $sth->execute();
            $this->resultDb = $sth->fetchAll();
        } catch (\PDOException $e) {
            $message = 'Erreur : ' . $e->getMessage();
            die($message);
        }
    }
    
    
    
    public function toArray()  {
        // Loop over each channel item/entry and store relevant data for each
        foreach ($this->resultDb as $item) {
            $this->channel['items'][] = array(
                'id'                  => $item['id'],
                'title'               => $item['title'],
                'link'                => $item['email'],
                'price'               => $item['price'],
                'date'                => $item['date'],
                'category'            => $item['name'],
                'region'              => "",
                'city'                => "",
                'thumbnail_link'      => $this->data['domain']."/uploads/img/".$item['id'].".".$item['url'],
                'professionnal'       => "",
                'urgent'              => "",
                'lat'                 => $item['lat'],
                'lng'                 => $item['lng'],
                'location'            => null,
                'describe'            => $item['content']." << ".$item['author'].", ".$item['email']." >> ",
            );
        }
		
		return $this->channel;
    
    }
    

}

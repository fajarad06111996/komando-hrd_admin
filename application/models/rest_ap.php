<?php
	//$sytem_re='AIzaSyD5KX6VRon6yQ0vu4s6GSnAVzazRWg8wrc';
    $rest =$this->config->item('rest_ap');
    $data1 = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false&key=$rest");

        $data2 = json_decode($data1);
        
        $time = 0;
        $distance = 0;
        
        foreach($data2->rows[0]->elements as $road) {
            
            if(empty($road->duration->value)){
                $km=0;
            }else{
                 $time += $road->duration->value;
                 $distance += $road->distance->value;
                 $km = $distance / 1000;
            }
          
        
        }
?>
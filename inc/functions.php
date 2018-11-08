<?php
    function getNeighboringIndexes($index, $width, $height){
        $validindexes = array();
    	$validindexes[] = $index-$width-1;
    	$validindexes[] = $index-$width+1;
    	$validindexes[] = $index+$width-1;
    	$validindexes[] = $index+$width+1;
	    
    	if (($index/$width)%2 == 0){ //horizontal
    		if ($index%$width - 2 > 0){
    			$validindexes[] = $index - 2;
    		}
    		if ($index%$width + 2 < $width){
    			$validindexes[] = $index + 2;
    		}
    	}
    	else{ //vertical
    		if ($index/$width - 2 > 0){
    			$validindexes[] = $index - 2*$width;
    		}
    		if ($index/$width + 2 < $height){
    			$validindexes[] = $index + 2*$width;
    		}
    	}
	    $validindexes[] = count($validindexes); //append maxindex to the end, so it can be retrieved and used.
        return $validindexes;
    }
    
    function GenerateMaze($width, $height){
        $maxindex = $width * $height;
        $mazearray = array_fill(0, $width * $height, 0);
        $validstarts = array_fill(0, $width * $height, 0);
        $validstartsindex = 0;
        $newstart = true;
        $currentindex = 0;
        $prevoptionsindex = 0;
        $prevoptions = array_fill(0, 5, 0);
        $debugcounter = 0;
        
        //Init starting walls on all sides
        for($i = 0; $i < $width; $i++){
            $mazearray[$i] = 1;
            $mazearray[($width*($height-1))+$i] = 1;
        }
        
        for($i = 0; $i < $height; $i++){
            $mazearray[$i*$width] = 1;
            $mazearray[$i*$width + $width-1] = 1;
        }
        
        //Init valid start points
        
        for ($i = 1; $i < ($width)/2; $i++){
    		$mazearray[$i*2+1*$width] = 3;
    		$validstarts[$validstartsindex++] = $i*2+1*$width;
    		
    		$mazearray[$i*2+($height-3)*$width + $width*($height%2)] = 3;
    		$validstarts[$validstartsindex++] = $i*2+($height-3)*$width + $width*($height%2);
    	}
    	
    	for ($i = 1; $i < ($height-1)/2; $i++){
    		$mazearray[$i*2*$width + 1] = 3;
    		$validstarts[$validstartsindex++] = $i*2*$width + 1;
    		
    		$mazearray[($i*2)*$width + $width - 3 +$width%2]= 3;
    		$validstarts[$validstartsindex++] = ($i*2)*$width + $width - 3 +$width%2;
    	}
    	
    	//Main loop
    	while ($validstartsindex > 0 and $debugcounter < $maxindex and true){
    	    //echo $debugcounter + " ";
    	    $debugcounter++;
    	    //echo $validstartsindex . ' ' ;
    	    
            if ($newstart == true){
				$r = rand(0,$validstartsindex-1);
				$currentindex = $validstarts[$r];
				$validstarts[$r] = $validstarts[--$validstartsindex];
				$newstart = false;
			}
			//Set chosen index (either from new start, or previous iteration's selection) to a wall.
			$mazearray[$currentindex] = 1;
			if (($currentindex/$width)%2 == 0){
				$mazearray[$currentindex+1] = 1;
				$mazearray[$currentindex-1] = 1;
			}
			else{
				$mazearray[$currentindex+$width] = 1;
				$mazearray[$currentindex-$width] = 1;
			}
				
			$validoptions = getNeighboringIndexes($currentindex,$width,$height);
            $validoptionsindex = array_pop($validoptions);
			
			for ($i = 0; $i < $validoptionsindex; ){
				switch ($mazearray[$validoptions[$i]]){
					//Set all white options to gold, and track
					case 0:
						$mazearray[$validoptions[$i]] = 4;
					case 4:
						$i++;
						break;
					
					//Blue options, remove and set to red. Also find the matching validstarts[] index, and remove that entry
					case 3:
						$mazearray[$validoptions[$i]] = 2;
						for ($j = 0;  $j < $validstartsindex; $j++){
							if ($validoptions[$i] == $validstarts[$j]){
								$validstarts[$j] = $validstarts[--$validstartsindex];
								break;
							}
						}
					//Wall, Red, and Blue options - remove.
					case 1:
					case 2:
						$validoptions[$i] = $validoptions[--$validoptionsindex];
						break;
				}
			}

			
			//select an option
			if ($validoptionsindex > 0){
				$r = rand(0,$validoptionsindex-1);
				$currentindex = $validoptions[$r];
			}
			else{
				$newstart = true;
			}
			

			
			$found;
			for ($i = 0; $i < $prevoptionsindex;){
				$found = 0;
				for ($j = 0; $j < $validoptionsindex; $j++){
					if ($prevoptions[$i] == $validoptions[$j]){
						$found = 1;
						break;
					}
				}
				if ($found == 1){
					$prevoptions[$i] = $prevoptions[--$prevoptionsindex];
				}
				else{
					$i++;
				}
			}
			
			
			//Does the exact same thing as the above commented out code
			//$prevoptions = array_diff($prevoptions, $validoptions);
			//$prevoptionsindex = count($prevoptions);
			
			for ($i = 0; $i < $prevoptionsindex; $i++){
				//convert gold to blue, add as valid starts
				if ($mazearray[$prevoptions[$i]] == 4){
					$mazearray[$prevoptions[$i]] = 3;
					$validstarts[$validstartsindex++] = $prevoptions[$i];
				}
			}
			
			$prevoptionsindex = 0;
			for ($i = 0; $i < $validoptionsindex; $i++){
				$prevoptions[$i] = $validoptions[$i];
				$prevoptionsindex++;
			}
    	}
    	
    	//Convert values to 0-1
    	for ($i = 0; $i < $maxindex; $i++){
    		if ($mazearray[$i] > 1){
    			$mazearray[$i] = 0;
    		}
    	}
	    echo '<br/>';
	    echo "<div id ='maze'>";
    	for ($i = 0; $i < $height; $i++){
    	    for ($j = 0; $j < $width; $j++){
    	        if ($mazearray[$i*$width + $j] == 0){
    	            echo "<img class='w' src='img/W.png'/>";
    	        }
    	        else{
    	            echo "<img src='img/B.png'/>";
    	        }
    	    }
    	    echo '<br/>';
    	}
        echo "</div>";
    }
    
?>

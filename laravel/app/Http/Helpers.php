<?php
	function dotp($arr1, $arr2){
		//return (float)array_sum(array_map( function($a, $b) {return  $a * $b;} , $arr1, $arr2));

		$product = 0;
		for($i = 0; $i < count($arr1); $i++){
			$product += $arr1[$i] * $arr2[$i];
		}

		return $product;
	}
	
	function cosSimilarity($arr1, $arr2)
	{
		return (float) abs (dotp($arr1,$arr2) / sqrt(dotp($arr1,$arr1)*dotp($arr2,$arr2)) );
	}
	
	function stringToEmbedding($arr1, $arr2)
	{
		$arr1 = preg_split("/\r\n|\r|\n/", $arr1);
		$arr2 = preg_split("/\r\n|\r|\n/", $arr2);
		
		$i = 0;
		foreach($arr1 as $num){
			$arr1[$i] = floatval($num); 
			$i++;
		}

		$i = 0;
		foreach($arr2 as $num){
			$arr2[$i] = floatval($num); 
			$i++;
		}

		if(isset($arr1[1024])){
			unset($arr1[1024]);
		}
		
		if(isset($arr2[1024])){
			unset($arr2[1024]);
		}
		
		return(cosSimilarity($arr1, $arr2));
	}
?>
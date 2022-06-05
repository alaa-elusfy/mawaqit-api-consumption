
//Search Engine
add_action('wp_ajax_search_engine', 'search_callback');
add_action('wp_ajax_nopriv_search_engine', 'search_callback');
function search_callback(){
//echo $_POST['name'];
	 $header=array();
     $header[]='Api-Access-Token:4b0eab2d-79ff-48de-a552-b9ee2c827221';
     $curl=curl_init();
     curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
     if(isset($_POST['name'])){
         $search=$_POST['name'];
     }
     if(strlen($search)<2){
         echo "<li style='display:none;'> </li>";
     }
     curl_setopt($curl, CURLOPT_URL,"https://mawaqit.net/api/2.0/mosque/search?word=$search");
     curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
     $result = curl_exec($curl);
     curl_close($curl);
     $mosques=json_decode($result, true);
     foreach ($mosques as $mosque){
        echo "        <a class='text-dark' style='text-decoration:none; display:block;' href='https://mawaqit.net/fr/".$mosque['slug']."' target='_blank' rel='noopener noreferrer'>
        <li class='' style='color:black; list-style-type: none; width:100%; padding:0; '>".$mosque['name']."</li></a>";
     }
	wp_die();
}



//latestMosques
add_action('wp_ajax_load_Mosques', 'load_Mosques_callback');
add_action('wp_ajax_nopriv_load_Mosques', 'load_Mosques_callback');
function load_Mosques_callback(){
	if(isset($_POST['page'])){
         $npage=$_POST['page'];
     }
	else{ $npage=1;}
           $header=array();
           $header[]='Api-Access-Token:928af318-dacf-4348-8453-01de334f7754';
           $curl=curl_init();
           curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

           curl_setopt($curl, CURLOPT_URL,"https://pp.mawaqit.net/api/2.0/mosque/?page=$npage&order=desc");
           curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
           $result = curl_exec($curl);
           curl_close($curl);

           $mosques=json_decode($result, true);
	$localdb = array();
	foreach($mosques as $mosque){
		$mos=array();
		$mos[1]=substr($mosque['created'],0,10);
		$mos[2]="https://mawaqit.net/fr/".$mosque['slug'];
		$mos[3]=$mosque['imageUrl1'];
		$mos[4]=$mosque['slug'];
		$mos[5]=$mosque['name'];
		$mos[6]=$mosque['city'];
		$mos[7]=$mosque['countryFullName'];
		$mos[8]=$mosque['imageUrl2'];
		$mos[9]=$mosque['imageUrl3'];
		$localdb[$i]=$mos;
		$i++;
		if ($nbmsq==8){echo"<div class='text-center'> ";}
		echo"
			<div class=' col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3' >
                    <div class='card mb-4 shadow-lg ' style='width:100%;'>
                        <img onclick='hideNavBar()'src='". $mos[3]."' class='' type='button' data-toggle='modal' data-backdrop='false' data-target='#myModal". $mos[4]."' style='height:210px;'>
                        <div class='card-body'>

                                <div class='text-center'>".$mos[5]."<br>
                                    <span class='text-center'>".$mos[6].", ".$mos[7]."</span> <br>
                                    <a class='text-success' style='text-decoration:none' href= '".$mos[2]."'> Consultez les horaires </a>
									</p>
                                </div>

                        </div>
                    </div>

					<div id='myModal".$mos[4]."' class='modal' tabindex='-1' role='dialog' >
                        <div class='modal-dialog' role='document' style='width: 100%; max-width: none; height: 100%;margin: 0;'>
                            <div class='modal-content' style='  height: 100%; border: 0;border-radius: 0; '>
                                <div class='modal-body' style='overflow-y: auto;'>
                                    <div class='text-center'>
                                    <h1 class='text-center text-uppercase modal-title mt-5 mb-4'>
                                        ". $mos[5]."-".$mos[6]." ".$mos[5]."
                                    </h1>
                                    <button type='button' class='btn-close position-absolute top-0 end-0' data-dismiss='modal' aria-label='Close' style='font-size: 40px; margin-top: 20px; margin-right: 20px;' onclick='showNavBar()'>
                                    </button>
                                    <img src='".$mos[3]."' class='img-fluid mb-4 rounded' style='width: 900px;'><br>
                                    <img src='".$mos[8]."' class='img-fluid mb-4 rounded' style='width: 900px;'><br>
                                    <figure>
                                    <img src=".$mos[9]." class='border img-fluid mb-4 rounded' style='width: 900px;'>
                                    </figure>
                                        <p>date d'ajout:  ".$mos[1]." </p>
                  	<p>Consultez les horaires:
<a class='text-success' style='text-decoration:none' href= ". $mos[2]." target='_blank' > https://mawaqit.net/fr/". $mos[4]." </a>
                                    </p>
                                    <button type='button' class='btn position-absolute start-50 translate-middle mt-2 ' data-dismiss='modal' style='background-color:#4B0082;color:white;' onclick='showNavBar()' >Fermer</button>

                                </div>
                            </div>
			            </div>
	            	</div>
                 </div>
                </div>
					";if ($i==10){ echo"<div style='display:none;'";}
	}
}


// Mosques
add_action('wp_ajax_insert_mosques', 'insert_mosques_callback');
add_action('wp_ajax_nopriv_insert_mosques', 'insert_mosques_callback');
function insert_mosques_callback(){
		global $wpdb;
	$std=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}mosques ORDER BY id DESC;  ");

		$results= (array)$std;
		if(isset($_POST['kali'])){
         $nb=$_POST['kali'];
global $wpdb;
	 for ($i=$nb;$i<$nb+10;$i++) {
				 $mos=(array)$results[$i];

		$dateajout=substr($mos['created'],0,10);
	$consulth="https://mawaqit.net/fr/".$mos['slug'];
	//	$mos[3]=$results[$i]['imageUrl1'];
	//	$mos[4]=$results[$i]['slug'];
	//	$mos[5]=$results[$i]['name'];
	//	$mos[6]=$results[$i]['city'];
	//	$mos[7]=$results[$i]['countryFullName'];
	//	$mos[8]=$results[$i]['imageUrl2'];
	if (!$mos['imageUrl3']){$mos['imageUrl3']="https://dummyimage.com/640x360/fff/aaa" ;}
		 else {$id3=substr($mos['imageUrl3'],0,2)."/".substr($mos['imageUrl3'],2,2)."/".substr($mos['imageUrl3'],4,2)."/";
			  	$image3=$mos['imageUrl3'];
			   $mos['imageUrl3']="https://mawaqit.net/upload/mosque/".$id3.$image3;
			  }
	if (!$mos['imageUrl2']){$mos['imageUrl2']="https://mawaqit.net/bundles/app/prayer-times/img/default.jpg" ;}
		 else {$id2=substr($mos['imageUrl2'],0,2)."/".substr($mos['imageUrl2'],2,2)."/".substr($mos['imageUrl2'],4,2)."/";
			  	$image2=$mos['imageUrl2'];
			   $mos['imageUrl2']="https://mawaqit.net/upload/mosque/".$id2.$image2;
			  }

	if (!$mos['imageUrl1']){$mos['imageUrl1']="https://mawaqit.net/bundles/app/prayer-times/img/default.jpg" ;}
		 else {$id1=substr($mos['imageUrl1'],0,2)."/".substr($mos['imageUrl1'],2,2)."/".substr($mos['imageUrl1'],4,2)."/";
			  	$image1=$mos['imageUrl1'];
			   $mos['imageUrl1']="https://mawaqit.net/upload/mosque/".$id1.$image1;
			  }


			 echo"
			<div class=' col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3' >
                    <div class='card mb-4 shadow-lg ' style='width:100%; height:320px;'>
                        <img onclick='hideNavBar()'src='". $mos['imageUrl1']."' class='card-img-top' type='' data-toggle='modal' data-backdrop='false' data-target='#myModal". $mos['slug']."' style='height:12em;'>
                        <div class='card-body'>

                                <div class='text-center'>".$mos['name']."<br>
                                    <span class='text-center'>".$mos['city'].", ".$mos['countryFullName']."</span> <br>
                                    <a class='text-success' style='text-decoration:none' href= '".$consulth."'> Consultez les horaires </a>
									</p>
                                </div>

                        </div>
                    </div>

					<div id='myModal".$mos['slug']."' class='modal' tabindex='-1' role='dialog' >
                        <div class='modal-dialog' role='document' style='width: 100%; max-width: none; height: 100%;margin: 0;'>
                            <div class='modal-content' style='  height: 100%; border: 0;border-radius: 0; '>
                                <div class='modal-body' style='overflow-y: auto;'>
                                    <div class='text-center'>
                                    <h1 class='text-center text-uppercase modal-title mt-5 mb-4'>
                                        ". $mos['name']."-".$mos['city']." ".$mos['name']."
                                    </h1>
                                    <button type='button' class='btn-close position-absolute top-0 end-0' data-dismiss='modal' aria-label='Close' style='font-size: 40px; margin-top: 20px; margin-right: 20px;' onclick='showNavBar()'>
                                    </button>
                                    <img src='".$mos['imageUrl1']."' class='img-fluid mb-4 rounded' style='width: 900px;'><br>
                                    <img src='".$mos['imageUrl2']."' class='img-fluid mb-4 rounded' style='width: 900px;'><br>
                                    <figure>
                                    <img src=".$mos['imageUrl3']." class='border img-fluid mb-4 rounded' style='width: 900px;'>
                                    </figure>
                                        <p>Date d'ajout:  ".$dateajout." </p>
                  	<p>Consultez les horaires:
<a class='text-success' style='text-decoration:none' href= ". $consulth." target='_blank' > https://mawaqit.net/fr/". $mos['slug']." </a>
                                    </p>
                                    <button type='button' class='btn position-absolute start-50 translate-middle mt-2 ' data-dismiss='modal' style='background-color:#4B0082;color:white;' onclick='showNavBar()' >Fermer</button>

                                </div>
                            </div>
			            </div>
	            	</div>
                 </div>
                </div>
					";$c++;
			 if ($c==10){ echo"<div style='display:none;'";}
	 }
        //            $ok=(array)$results[1];
//print_r($ok);
		//	echo $ok['name'];

     }
	else{ $nb=0; echo"failed";}

}



// Mosques
add_action('wp_ajax_testingmap', 'testingmap_callback');
add_action('wp_ajax_nopriv_testingmap', 'testingmap_callback');
function testingmap_callback(){
	if(isset($_POST['kali'])){
		global $wpdb;
	$std=$wpdb->get_results("SELECT location_extrafields FROM {$wpdb->prefix}map_locations ORDER BY location_id DESC LIMIT 25;  ");

		$new_mosques=array();
$new_mosques= (array)$std;
		$ok=(array)$new_mosques;
		//$ch=explode('"',$ok['location_extrafields']);
		//$ck=explode(' ',$ch[7]);
print_r( $ok);
			  }



	else { echo "failed";}
}



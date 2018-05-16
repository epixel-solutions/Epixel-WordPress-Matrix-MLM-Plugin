<?php get_header(); ?>
<?php  

$prefixes = array('.coma','.caa','.zaa','.coa','.neta','.casha','.uka','.naa');
$users = get_users();
foreach($users as $user){
	$email_check = 0;
	foreach($prefixes as $prefix){
		if(stripos($user->user_email,$prefix)){
			$email_check = 1;
			if($prefix == '.coma'){
				//echo $user->user_email . ' -- ' . str_replace($prefix,'.com',$user->user_email);
				$wpdb->update($wpdb->users, array('user_email' => str_replace($prefix,'.com',$user->user_email)), array('ID' => $user->ID));
			}
			if($prefix == '.caa'){
				//echo $user->user_email . ' -- ' . str_replace($prefix,'.ca',$user->user_email);
				$wpdb->update($wpdb->users, array('user_email' => str_replace($prefix,'.ca',$user->user_email)), array('ID' => $user->ID));
			}
			if($prefix == '.zaa'){
				//echo $user->user_email . ' -- ' . str_replace($prefix,'.za',$user->user_email);
				$wpdb->update($wpdb->users, array('user_email' => str_replace($prefix,'.za',$user->user_email)), array('ID' => $user->ID));
			}
			if($prefix == '.coa'){
				//echo $user->user_email . ' -- ' . str_replace($prefix,'.co',$user->user_email);
				$wpdb->update($wpdb->users, array('user_email' => str_replace($prefix,'.co',$user->user_email)), array('ID' => $user->ID));
			}
			if($prefix == '.neta'){
				//echo $user->user_email . ' -- ' . str_replace($prefix,'.net',$user->user_email);
				$wpdb->update($wpdb->users, array('user_email' => str_replace($prefix,'.net',$user->user_email)), array('ID' => $user->ID));
			}
			if($prefix == '.casha'){
				//echo $user->user_email . ' -- ' . str_replace($prefix,'.cash',$user->user_email);
				$wpdb->update($wpdb->users, array('user_email' => str_replace($prefix,'.cash',$user->user_email)), array('ID' => $user->ID));
			}
			if($prefix == '.uka'){
				//echo $user->user_email . ' -- ' . str_replace($prefix,'.uk',$user->user_email);
				$wpdb->update($wpdb->users, array('user_email' => str_replace($prefix,'.uk',$user->user_email)), array('ID' => $user->ID));
			}
			if($prefix == '.naa'){
				//echo $user->user_email . ' -- ' . str_replace($prefix,'.na',$user->user_email);
				$wpdb->update($wpdb->users, array('user_email' => str_replace($prefix,'.na',$user->user_email)), array('ID' => $user->ID));
			}
			//echo '<br>';
			break;
		}
	}
}



?>

<?php get_footer(); ?>

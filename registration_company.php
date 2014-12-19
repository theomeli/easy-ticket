<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Φόρμα εγγραφής εταιρείας</title>
   
</head>
<body>


	<?php 	// Θεοδόσης Μελίδης
			    // melidisteo@gmail.com
			    // Σχολείο Ανάπτυξης Ανοικτού Κώδικα ΑΠΘ
			    // 18/12/2014
			

	echo "<h1>Φόρμα Εγγραφής Εταιρείας</h1>";
	
	if (isset($_POST['submitted'])) {
	$dbc = mysql_connect('localhost', 'lol', '12345') or die(mysql_error());
	mysql_select_db('easy_ticket');
		
	$trimmed = array_map('trim', $_POST);
	$errors = array(); 
	
	
	// Αρχικοποίηση μεταβλητών με false
	$fn = $ln = $e = $u = $p = FALSE;
	
	// Έλεγχος για το όνομα
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['company_name'])) {
		$fn = mysql_real_escape_string ($trimmed['company_name'], $dbc);
	} else {
		$errors[] = 'Παρακαλώ εισάγετε το όνομα της εταιρείας';
	}
	
	// Έλεγχος για το ΑΦΜ
	if (preg_match ('/^[0-9]{9}$/', $trimmed['afm'])) {
		$ln = mysql_real_escape_string ($trimmed['afm'], $dbc);
	} else {
		$errors[] = 'Παρακαλώ εισάγετε το ΑΦΜ της εταιρείας';
	}
	
	// Έλεγχος για το τηλέφωνο
	if (preg_match ('/^[0-9]{10}$/', $trimmed['phone'])) {
		$ln = mysql_real_escape_string ($trimmed['phone'], $dbc);
	} else {
		$errors[] = 'Παρακαλώ εισάγετε το τηλέφωνο της εταιρείας';
	}

	// Έλεγχος για τη διεύθυνση ηλεκτρονικού ταχυδρομείου
	if (preg_match ('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $trimmed['email'])) {
		$e = mysql_real_escape_string ($trimmed['email'], $dbc);
	} else {
		$errors[] = 'Παρακαλώ εισάγετε σωστή διεύθυνση email';
	}
	
	// Έλεγχος για την περιοχή της εταιρείας
	if (preg_match ('/^[A-Za-z]{2,20}$/', $trimmed['location']) ) {
		$u = mysql_real_escape_string ($trimmed['location'], $dbc);
	} else {
		$errors[] = 'Παρακαλώ εισάγετε την περιοχή της εταιρείας';
	}
	
	// Έλεγχος για το όνομα χρήστη
	if (preg_match ('/^\w{4,20}$/', $trimmed['user_name']) ) {
		$u = mysql_real_escape_string ($trimmed['user_name'], $dbc);
	} else {
		$errors[] = 'Παρακαλώ εισάγετε σωστό όνομα χρήστη';
	}
	
	// Έλεγχος για το συνθηματικό
	if (preg_match ('/^\w{4,20}$/', $trimmed['pass']) ) {
		if ($trimmed['pass'] == $trimmed['conf_pass']) {
			$p = mysql_real_escape_string ($trimmed['pass'], $dbc);
		} else {
			$errors[] = 'Το συνθηματικό που πληκτρολογήσατε δεν είναι ίδιο με το παραπάνω συνθηματικό';
		}
	} else {
		$errors[] = 'Παρακαλώ εισάγετε έγκυρο συνθηματικό';
	}
	

	
	if (empty($errors)) { // Αν δεν υπάρχουν σφάλματα
	
		// Καταχώριση του νέου χρήστη στη βάση δεδομένων
		
		
		$q = "INSERT INTO users (company_name, afm, email, location, pass) VALUES ('$fn', '$ln', '$e', '$u', MD5('$p'))"; // χρήση της συνάρτησης MD5 για το συνθηματικό
		$r = @mysql_query ($q,$dbc); // Εκτέλεση του αιτήματος
		if ($r) { // Αν πήγαν όλα καλά
		
			
			echo '<h1>Ευχαριστούμε!</h1>
		<p>Η εγγραφή σας στο σύστημα ολοκληρώθηκε επιτυχώς. </p><p><br /></p>';	
		  echo "<a href=\"index.php\">Αρχική σελίδα</a>";

		} else { // Αν υπάρχουν σφάλματα
			
			
			echo '<h1>Σφάλμα</h1>
			<p class="error">Η εγγραφή σας δεν πέτυχε λόγω κάποιου σφάλματος</p>'; 
			
			// Μήνυμα για Αποσφαλμάτωση:
			echo '<p>' . mysql_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
						
		} // Τέλος του if ($r) IF.
		
		mysql_close($dbc); // Κλείσιμο της σύνδεσης με τη Βάση Δεδομένων

		exit();
		
	} else { 
	
		echo '<h1>Σφάλμα!</h1>
		<p class="error"><font color="red">Τα παρακάτω σφάλματα υπάρχουν:<br />';
		foreach ($errors as $msg) { 
			echo " - $msg<br />\n";
		}
		echo '</p><p>Παρακαλώ προσπαθήστε ξανά.</p><p><br /></font></p>';
		
	} // Τέλος του if (empty($errors))
	
	mysql_close($dbc); // Κλείσιμο της σύνδεσης με τη Βάση

} // Τέλος της κύριας συνθήκης submit
?>

<form action="registration_company.php" method="post">

<table>
	<tr>
	<td>Όνομα:</td>
	<td><input type="text" name="company_name" size="20" maxlength="20" value="<?php if (isset($trimmed['company_name'])) echo $trimmed['company_name']; ?>" /> 
	<small>Επιτρέπονται μόνο λατινικοί χαρακτήρες.</small></td>
	</tr>
	
	<tr>
	<td>ΑΦΜ εταιρείας: </td>
	<td><input type="text" name="afm" size="20" maxlength="40" value="<?php if (isset($trimmed['afm'])) echo $trimmed['afm']; ?>" />
	<small>Επιτρέπονται μόνο 9 αριθμοί.</small></td>
	</tr>
	
	<tr>
	<td>Τηλέφωνο εταιρείας: </td>
	<td><input type="text" name="phone" size="20" maxlength="40" value="<?php if (isset($trimmed['phone'])) echo $trimmed['phone']; ?>" />
	<small>Επιτρέπονται μόνο 10 αριθμοί.</small></td>
	</tr>
	
	<tr>
	<td>Διεύθυνση Email: </td> 
	<td><input type="text" name="email" size="20" maxlength="50" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" /> </td>
	</tr>
	
	<tr>
	<td>Περιοχή εταιρείας: </td>
	<td><input type="text" name="location" size="20" maxlength="20" value="<?php if (isset($trimmed['location'])) echo $trimmed['location']; ?>" /> 
	<small>Επιτρέπονται μόνο γράμματα (2-20 χαρακτήρες).</small></td>
	</tr>
	
	<tr>
	<td>Όνομα χρήστη: </td>
	<td><input type="text" name="user_name" size="20" maxlength="20" value="<?php if (isset($trimmed['user_name'])) echo $trimmed['user_name']; ?>" /> 
	<small>Επιτρέπονται μόνο γράμματα, αριθμοί και η κάτω παύλα (4-20 χαρακτήρες).</small></td>
	</tr>
	
	<tr>
	<td>Συνθηματικό: </td>
	<td><input type="password" name="pass" size="20" maxlength="20" value="<?php if (isset($trimmed['pass'])) echo $trimmed['pass']; ?>" /> 
	<small>Επιτρέπονται μόνο γράμματα, αριθμοί και η κάτω παύλα (4-20 χαρακτήρες).</small></td>
	</tr>
	
	<tr>
	<td>Επιβεβαίωση Συνθηματικού: </td> 
	<td><input type="password" name="conf_pass" size="20" maxlength="20" value="<?php if (isset($trimmed['conf_pass'])) echo $trimmed['conf_pass']; ?>" /></td>
	</tr>
</table>

Εισάγετε τον κωδικό:
<input name="verif_box" type="text" id="verif_box" style="padding:2px; border:1px solid #CCCCCC; width:60px; height:14px;font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px;"/>
<img src="verificationimage.php?<?php echo rand(0,9999);?>" alt="Εικόνα επικύρωσης, σημειώστε στο κουτί" width="50" height="24" align="absbottom" /><br />
<br />

<!-- if the variable "wrong_code" is sent from previous page then display the error field -->
<?php if(isset($_GET['wrong_code'])){?>
<div style="border:1px solid #990000; background-color:#D70000; color:#FFFFFF; padding:4px; padding-left:6px;width:295px;">Wrong verification code</div><br /> 
<?php ;}?>
 <input type="submit" name="submit" class="button" value="Εγγραφή" />
 <input type="hidden" name="submitted" value="true" />


</form>
</body>
</html>

<?php 	// Θεοδόσης Μελίδης
		    // melidisteo@gmail.com
		    // Σχολείο Ανάπτυξης Ανοικτού Κώδικα ΑΠΘ
		    // 18/12/2014
?>

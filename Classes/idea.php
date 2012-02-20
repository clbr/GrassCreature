<?php
	require_once('../DatabaseOperation/idea.php');
	
	class Idea {
	
		/*function add($_POST['IdeaName'], $_POST['IdeaDesc'], $_POST['IdeaCost'],
			$_POST['IdeaAdditionalInfo'], $_POST['IdeaBasedOn'], $_POST['IdeaRequestDate']) {*/
		function add() {
			addIdea();
			/*setName($_POST['IdeaName']);
			setDescription($_POST['IdeaDesc']);
			setStatus("Awaiting approval");
			setCost($_POST['IdeaCost']);
			setAdditionalInfo($_POST['IdeaAdditionalInfo']);
			setBasedOn($_POST['IdeaBasedOn']);
			setRequestDate($_POST['IdeaRequestDate']); // Format to MySQL !
			setInventor(sess.getUserID());*/
		}
		
		function removePermanently() {
		
		}
		
		function setStatus() {
		
		}
		
		function setInfo() {
		
		}
		
		function search() {
		
		}
		
		function comment() {
		
		}
		
		function vote() {
		
		}
	
	}
?>
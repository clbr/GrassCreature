<?php
	require_once('../DatabaseOperation/idea.php');
	
	class Idea {
	
		function add($_POST['IdeaName'], $_POST['IdeaDesc'], $_POST['IdeaCost'],
			$_POST['IdeaAdditionalInfo'], $_POST['IdeaBasedOn'], $_POST['IdeaRequestDate']) {
		//function add() {
		
			// adding propably going to change
			
			addIdea();
			setName($_POST['IdeaName']);
			setDescription($_POST['IdeaDesc']);
			setStatus("Awaiting approval");
			setCost($_POST['IdeaCost']);
			setAdditionalInfo($_POST['IdeaAdditionalInfo']);
			setBasedOn($_POST['IdeaBasedOn']);
			setRequestDate($_POST['IdeaRequestDate']); // Format to MySQL !
			setInventor(sess.getUserID());
		}
		
		function removePermanently($_POST['IdeaID']) {
			removeIdeaPermanently($_POST['IdeaID']);
		}
		
		function setStatus($_POST['IdeaID'], $_POST['IdeaStatus') {
			// miten kohdistetaan tiettyyn ideaan?			
			setStatus($_POST['IdeaID'], $_POST['IdeaStatus'); 
		}
		
		function setInfo() {
			// Check if there is a diff to previous entry, if yes then change.
		
			if (getName($_POST['IdeaID']) != $_POST['IdeaName'])
				setName($_POST['IdeaName']);
			
			if (getDescription($_POST['IdeaDesc']) != $_POST['IdeaDesc'])
				setDescription($_POST['IdeaDesc']);
				
			if (getStatus($_POST['IdeaStatus']) != $_POST['IdeaStatus'])
				setStatus($_POST['IdeaStatus']);
				
			if (getCost($_POST['IdeaCost']) != $_POST['IdeaCost'])
				setCost($_POST['IdeaCost']);
				
			if (getAdditionalInfo($_POST['IdeaAdditionalInfo']) != $_POST['IdeaAdditionalInfo'])
				setAdditionalInfo($_POST['IdeaAdditionalInfo']);
				
			if (getBasedOn($_POST['IdeaBasedOn']) != $_POST['IdeaBasedOn'])
				setBasedOn($_POST['IdeaBasedOn']);
			
			if (getRequestDate($_POST['IdeaRequestDate']) != $_POST['IdeaRequestDate'])
				setRequestDate($_POST['IdeaRequestDate']); // Format to MySQL !
		}
		
		function search($_POST['search']) {			
			$tags = explode(", ", $_POST['search']);
			$schaisse = searchIdeas($tags); // Palataan tähän palvelinohjelmoinnin mysql-tunnin jälkeen...
		}
		
		function addComment($_POST['IdeaID'], $_POST['Comment']) {
			commentIdea($_POST['IdeaID'], $_POST['Comment']);
		}
		
		function vote() {
		
		}
	
	}
?>
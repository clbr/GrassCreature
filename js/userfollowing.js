// Reasoning for being in a separate file: needed in different places (showIdea.php and showUser.php)

function userFollowUser(stalkedid, userid, ideaid) {
		var call = 'userFollowUser';

		$.ajax( {
			url: 'ajaxCalls.php',
			type: 'POST',
			data: 'call=' + call + '&stalkedid=' + stalkedid + '&userid=' + userid,

			success: function(response) {
				$('#followUserButton').empty().fadeOut(500, function() {
					$('#followUserButton').append("You are now following this user.").css('background-color', '#66FF66').fadeIn(1000);

					// This is necessary to get interactivity immediately after starting to follow the user.
					$('#followUserButton').hover(function() {
						stopFollowingUserEff(stalkedid, userid, ideaid);
					});
				});
			}
		});
	}

	// This function has highlights and whatnot, button effects and interactivity for stopping user following.
	function stopFollowingUserEff(stalkedid, userid, ideaid) {
		$('#followUserButton').text("Stop following this user?"+ideaid).css('background-color', '#FF4D4D').fadeIn(1000);

		$('#followUserButton').mouseout(function() {
			$('#followUserButton').text("You are following this user.").css('background-color', '#66FF66');
		});

		$('#followUserButton').click(function() {
			// This might prevent flickering of colors on clicking.
			$('#followUserButton').text("Stop following this user?"+ideaid).css('background-color', '#FF4D4D').fadeIn(1000);
			 stopFollowingUser(stalkedid, userid, ideaid);
		});
	}

	// This function does the actual removal for following user.
	function stopFollowingUser(stalkedid, userid, ideaid) {
		var call = 'stopFollowingUser';

		$.ajax({
			url: 'ajaxCalls.php',
			type: 'POST',
			data: 'call=' + call + '&stalkedid=' + stalkedid + '&userid=' + userid,

			// Reload page.
			success: function(response) {
				// Only place here where ideaid is needed: redirect to show the same page that was viewed.
				window.location = 'showIdea.php?id='+ideaid;
			}
		});
	}
	
	function escapeHTML(string) {
		return String(string).replace(/</g, '&lt;').replace(/>/g, '&gt;');
	}
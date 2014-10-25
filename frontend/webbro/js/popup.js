// Adapt as you please! But do not remove this header
// Creative-Commons Attribution License (http://creativecommons.org/licenses/by/3.0/)
$(function(){
    //wait till DOM loads before referencing any elements
    var comments = null;
    chrome.tabs.getSelected(null, function(tab) {
		get_url_comments(tab.url);
	});
});

function get_url_comments(tab_url){
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "http://localhost/web/TAMUHack2014/web/index.php/welcome/get_url_comment",
      data: {}
    })
    .done(function(msg) {
        //create_cards(msg);
    });
}




function setEventListeners(){
	$('#search_button').click(function(){
		var bookmarks = new Bookmarks();
		var query = $('#search').val();
		if(!query){
			getRecent();
		}else{
			bookmarks.getAllBmarks(query);
		}
		chrome.storage.sync.set({'search': query}, function() {});
	});
	$('#add_button').click(function(){
		$('#result').empty();
		var bookmarks = new Bookmarks();
		
		var title = $('#addName').val();
		var url = $('#currenturl').val();
		var tags = $('#addTags').val();
		
		bookmarks.addBmark(title, url, tags);
		//update the current dialog
		showResults();
	});
	//focus on search box
	$('#search').focus();
	
	//map enter key press
	$('#search').keyup(function(event) {
		$('#search_button').click();
    });
	
	$('#addTags').keypress(function(event) {
		if (event.keyCode == 13) {
			$('#add_button').click();
        }
    });
}

function writeToDom(title, urlString, id){
	//create anchor
	var anchor = $('<div>');
	
	//set anchor attributes and click event handler
	anchor.attr('class', 'metro-tile resultlist truncate');
	anchor.append("<span>" + title + "</span>");
	
	//create image tag
	var img = $('<img>');
	img.attr('src', 'chrome://favicon/' + urlString);
	img.attr('class', 'favicon');
	anchor.prepend(img);
	
	//attach handler to anchor
	anchor.click(function() {
		chrome.tabs.create({url: urlString});
    });
	
	var deleteLink = $('<a id="deletelink" href="#" class="hoverlink">Delete</a>');
	anchor.hover(function(){
		anchor.prepend(deleteLink);
		//take care of click on this delete
		$('#deletelink').click(function(){
			getRecent(); //## hack, to prevent the background link working
			chrome.bookmarks.remove(String(id));
			showResults();
		});
	},
	function() { //unhover
		deleteLink.remove();
	}).append(anchor);
	
	$('#result').append(anchor);
}

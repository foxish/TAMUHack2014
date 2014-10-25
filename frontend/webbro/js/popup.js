var resultElement = '#content';
var loaderElement = '#loader';
var getCommentsURI = 'http://localhost/web/TAMUHack2014/web/index.php/welcome/get_url_comment';
var postCommentsURI = 'http://localhost/web/TAMUHack2014/web/index.php/welcome/new_comment';
var pageUrl = "";

$(function(){
    $('#comment').on('submit', function(e){
        e.preventDefault(); 
        submitComment();
    });
    chrome.tabs.getSelected(null, function(tab) {
        pageUrl = tab.url;
        createUrlCard(pageUrl);
        getUrlComments(pageUrl);
	});
});

function submitComment() {
    var pData = {
        "url"  : pageUrl,
        "type" : 1,
        "title" : $('#title').val(), 
        "username" : $('#username').val(), 
        "description" : $('#description').val()
    }
    
    showLoader();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: postCommentsURI,
        data: pData
    })
    .done(function(msg) {
        hideLoader();
        getUrlComments(pageUrl);
    })
    .fail(function(msg) {
        alert(msg.response);
    });
}

function clearForm(){
    $('#title').val("");
    $('#username').val("");
    $('#description').val("");
    
}

function createCard(card){
    console.log(card);
    var section = $('<section></section>')
                    .addClass('card')
                    .append('<h1 class="singleline">' + card.username + '</h1>')
                    .append('<h2>' + card.description + '</h2>');
    $(resultElement).append(section);
}

function createUrlCard(url){
    var section = $('<section></section>')
                    .addClass('card gray')
                    .append('<h2 class="singleline">' + url + '</h2>');
    $(resultElement).prepend(section);
}

function getUrlComments(tabUrl){
    $.ajax({
        type: "POST",
        dataType: "json",
        url: getCommentsURI,
        data: {'url': tabUrl}
    })
    .done(function(msg) {
        hideLoader();
        var length = msg.length;
        for (var i = 0; i < length; i++) {
            createCard(msg[i]);
        }
    }).fail(function(msg) {
        alert(msg);
    });
}

function hideLoader(){
    $(loaderElement).hide();
}

function showLoader(){
    $(loaderElement).show();
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

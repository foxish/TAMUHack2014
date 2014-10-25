var resultElement = '#content';
var loaderElement = '#loader';
var baseUrl = "http://webbro2.azurewebsites.net/web/index.php";

//API endpoints
var getComments = '/comment/get';
var postComments = '/comment/new';
var upvoteComment = "/comment/upvote";
var downvoteComment = "/comment/downvote";
var flagComment = "/comment/spam";
var pageUrl;


$(function(){
    $('#comment').on('submit', function(e){
        e.preventDefault(); 
        submitComment();
    });
    chrome.tabs.getSelected(null, function(tab) {
        pageUrl = tab.url;
        getUrlComments(pageUrl);
	});
});

function getUrl(endPoint){
    return baseUrl + endPoint;
}

function submitComment() {
    var pData = {
        "url"  : pageUrl,
        "type" : 1,
        "title" : $('#title').val(), 
        "username" : $('#username').val(), 
        "description" : $('#description').val()
    }
    
    showLoader();
    $(resultElement).html('');
    $.ajax({
        type: "POST",
        dataType: "json",
        url: getUrl(postComments),
        data: pData
    })
    .done(function(msg) {
        hideLoader();
        clearForm();
        getUrlComments(pageUrl);
    })
    .fail(function(msg) {
        hideLoader();
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
                    .append('<h1 class="singleline">' + card.title + '</h1>')
                    .append('<h5 class="floatleft">' + card.timestamp + '</h5>')
                    .append('<h5 class="floatright">' + card.username + '</h5>')
                    .append('<br/>')
                    .append('<h2>' + card.description + '</h2>')
                    .append('<br/>');
                    
    var imgUp = $('<img></img>')
            .attr('src', 'images/thumbs_up.png')
            .attr('id', card.id)
            .on('click', function(e){
                e.preventDefault();
                doPostAction(upvoteUrl, this.id)
            });
            
    var imgDn = $('<img></img>')
            .attr('src', 'images/thumbs_down.png')
            .attr('id', card.id)
            .on('click', function(e){
                e.preventDefault();
                doPostAction(downvoteUrl, this.id)
            });
            
    var imgFlag = $('<img></img>')
            .attr('src', 'images/flag.png')
            .attr('id', card.id)
            .on('click', function(e){
                e.preventDefault();
                doPostAction(flagUrl, this.id)
            });
    section.append(imgUp)
           .append('<span class="floatup">&nbsp;' + '(' + card.upvotes + ')&nbsp;&nbsp;&nbsp;</span>')
           .append(imgDn)
           .append('<span class="floatup">&nbsp;' + '(' + card.downvotes + ')&nbsp;&nbsp;&nbsp;</span>')
           .append(imgFlag);
    $(resultElement).append(section);
}

function doPostAction(endPoint, id){
    var url = getUrl(endPoint)
    showLoader();
    $(resultElement).html('');
    $.ajax({
        type: "POST",
        url: url,
        data: {"id": id}
    })
    .done(function(msg) {
        hideLoader();
        clearForm();
        getUrlComments(pageUrl);
    })
    .fail(function(msg) {
        hideLoader();
        alert(msg.response);
    });
}

function createUrlCard(url){
    var section = $('<section></section>')
                    .addClass('card gray')
                    .append('<h2 class="singleline">' + url + '</h2>');
    $(resultElement).prepend(section);
}

function getUrlComments(tabUrl){
    showLoader();
    $(resultElement).html('');
    
    createUrlCard(pageUrl);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: getUrl(getComments),
        data: {'url': tabUrl}
    })
    .done(function(msg) {
        hideLoader();
        var length = msg.length;
        for (var i = 0; i < length; i++) {
            createCard(msg[i]);
        }
    }).fail(function(msg) {
        hideLoader();
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

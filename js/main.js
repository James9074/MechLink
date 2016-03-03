DEBUG = true;

function _(x){
	return document.getElementById(x);
}
function toggleElement(x){
	var x = _(x);
	if(x.style.display == 'block'){
		x.style.display = 'none';
	}else{
		x.style.display = 'block';
	}
}

function ShowLoading(aObj){
	aObj.append('<img class="Loader" src="http://www.mechlink.org/gifs/blueloader.gif" alt="Loading..." />');
}

function HideLoading(aObj){
	aObj.find(".Loader").remove();
}
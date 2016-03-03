/**
 * Applies an error class to some text-holding ID
 * @param aID - The ID to toggle and error on.
 * @param aAdd - True for error, false to remove error
 */
function ApplyError(aObj, aAdd){
    if(aAdd)
        aObj.addClass("status_error");
    else
        aObj.removeClass("status_error");
}

/**
 * Hides all buttons and displays a loading gif
 * @param aObj -> The jQuery Object to work with
 */
function ShowLoading(aObj){
    aObj.append('<img class="Loader" src="http://www.mechlink.org/gifs/blueloader.gif" alt="Loading..." />');
    aObj.find("button").hide();
}

/**
 * Shows all buttons and removes any loading gifs
 * @param aObj -> The jQuery Object to work with
 */
function HideLoading(aObj){
    aObj.find(".Loader").remove();
    aObj.find("button").show();
}
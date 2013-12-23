// ==UserScript==
// @name        EZTC
// @namespace   com.soterous.eztc
// @description Pushes each DELTEK timecard to the EZTC server
// @include     https://*/DeltekTC/TimeCollection.msv
// @version     1.0
// @downloadURL https://raw.github.com/soterous/EZTC/master/Greasemonkey/eztc.user.js
// @updateURL   https://raw.github.com/soterous/EZTC/master/Greasemonkey/eztc.meta.js
// @require  http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js
// @grant    GM_getValue
// @grant    GM_setValue
// @grant    GM_listValues
// @grant    GM_deleteValue
// ==/UserScript==

// Config
var eztcServerUrl = 'http://localhost/eztc/update';

//////////////////// DO NOT EDIT PAST THIS LINE ////////////////////
/* CHANGELOG
 * v1    : Initial release
*/
//End Log

// Wait for load
$(window).load(function () {

  // Verify we're on the correct screen
  if($('#appOptionsDivopenTS').length < 1) 
    return;
    
  // Add Click event to the save button to trigger the data push
  $('#appOptionsImgsaveTS').click(function(){
    pushTimesheet();
  });

  // Add the Manual Timesheet Push button
  $('\
  <div title="Manual Timesheet Push" class="navFunctionDisabled" id="PushTimesheet">\
    <li class="appOptionsImgLi">\
      <img src="/DeltekTC/com/deltek/tc/framework/images/push-def-left.png" id="appOptionsLeftnotes" class="appOptionsLeft">\
      <img src="data:image/png;base64,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" id="appOptionsImgno\
      tes" height="24" class="appOptionsImg">\
    </li>\
    <li class="appOptionsLi"><div id="appOptionsDivnotes" class="appOption">Manual Timesheet Push</div></li>\
    <li><img class="appOptionsRight"></li>\
  </div>').click(function(){pushTimesheet();}).appendTo('#applicationOptionsUl');
 
});

/***********************************
 * FUNCTIONS
 ***********************************/

// this function pushes the timesheet to our local server for project update (basically the most important function)
function pushTimesheet() {
  
  // Parse the project strings //
  // this is the var that will store each project and each timestamp loggged
  var projects = {};
  // this is a lookup to get the project string for the calendar parsing
  var projectsLookup = {};
  
  // Grab the project string col and initialize the projects obj
  $('#udtColumn0').children().each(function(index){
    // Snag the project string
    projString = $(this).text();
    // Verify it's legit (lazy verify)
    if(projString.length < 1)
      return;
    // Initialize the project
    projects[projString] = {};
    // Save the string to the lookup via index
    projectsLookup[index] = projString;
  });

  
  // Parse the calendar part //
  // This is a lookup, specify the col index and it returns the date
  var datesLookup = {};  
  var pulledDate = $('#endingDateSpan').text().trim();
  var year = '/'+ pulledDate.substring(pulledDate.length - 4);
  
  // Loop thru each date (top row) in the calendar
  $('#hrsHeader').children().each(function(index){
    // Add the date to the lookup table if it's legit or else stop looking
    if($(this).html().length > 0)
      datesLookup[index] = $(this).html().match('<br>[ ]*([^ ]+)[ ]*$')[1] + year;
    else
      return false;
  });
  
  // Parse the time cells //
  // Now loop thru each actual time entry, and add it to the appropriate projects table
  $('#hrsBody').children().each(function(colIndex){
    // $this is the col
    $(this).children().each(function(rowIndex){
      // $this is a cell
      
      // Lookup the project string and verify we have a valid string
      var projectString = projectsLookup[rowIndex] || '';
      if(projectString.length < 1)
        return;
      
      // Get the date and verify
      var date = datesLookup[colIndex] || '';
      if(date.length < 1)
        return;      

      // Get the stored time for the project string and given day     
      if(projects[projectString][date] === undefined)
        projects[projectString][date] = 0;
      
      // get the time from the cell and verify it's not blank
      var cellTime = parseInt($(this).text()) || 0;
        
      // Save the time
      projects[projectString][date] += cellTime;
    });
  });
  
  // Finalize the post values
  var postVals = {};
  postVals['employee'] = $('#emplName').text().trim();
  postVals['projects'] = projects;
  
  // post it!
  $.post(eztcServerUrl, postVals).fail(function(){
    console.error("Couldn't post the values");
  });
  
  
}
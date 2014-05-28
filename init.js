
  var change_edu_subform = function(id){
    var itemValue  = dojo.byId(id+'_country').options[dojo.byId(id+'_country').selectedIndex].text;
    if (itemValue=='Ελλάδα') {
		dojo.byId(id+'_name').disabled=false;
		dojo.byId(id+'_name_other').value='';
		dojo.byId(id+'_name_other').disabled=true;
		
	} else
	{
	    dojo.byId(id+'_name').selectedIndex=0;
	    dojo.byId(id+'_name').disabled=true;
	    dojo.byId(id+'_name_other').disabled=false;
		
	}
	
	
  }  

  var change_region_subform = function(){
    var itemValue  = dojo.byId('cand_address_region').options[dojo.byId('cand_address_region').selectedIndex].value;
	        var xhrArgs = {
            url: "get_municipalities.php?regionid="+itemValue,
            handleAs: "text",
            preventCache: true,
            load: function(response, ioArgs) {
            dojo.byId("cand_address_municipality").innerHTML = response;
             return response;
            },
            error: function(error) {
                
            }
        }
        var deferred = dojo.xhrGet(xhrArgs);
	
  }  
  
  
function on_add_cb( type, id, txt, ioArgs ) {
	var name;
	if ( txt == '' ) return;
	if ( type == 'edu' ) {
		name = 'cand_edu_container';
		li_id = 'li_cand_edu_' + id;
	}
	else if ( type == 'prof' ) {
		name = 'cand_prof_container';
		li_id = 'li_cand_prof_' + id;
	}
	else if ( type == 'reco' ) {
		name = 'cand_reco_container';
		li_id = 'li_cand_reco_' + id;
	}
	else return;
	var el = dojo.byId( name );
	var li = document.createElement( "li" );
	dojo.attr( li, 'id', li_id );
	li.innerHTML = txt;
	el.appendChild( li );
}

function on_btn_add_click( type, evt ) {
	if ( evt ) evt.preventDefault();
	var name;
	if ( type == 'edu' ) name = 'li_cand_edu';
	else if ( type == 'prof' ) name = 'li_cand_prof';
	else if ( type == 'reco' ) name = 'li_cand_reco';
	else return;
	var i = -1;
	do {
		i++;
		var el = dojo.byId( name + '_' + i );
	} while ( el );
	dojo.xhrGet( {
		url: '/form.php',
		content: {
			'action': 'add',
			'type': type,
			'id': i
		},
		handleAs: 'text',
		load: dojo.hitch( null, 'on_add_cb', type, i ) } );
}

function on_btn_del_click( id, evt ) {
	if ( evt ) evt.preventDefault();
	var el = dojo.byId( id );
	if ( el ) el.parentNode.removeChild( el );
}

function on_btn_navigate( dir, evt ) {
	if ( evt ) evt.preventDefault();
	var tabs = dijit.byId( 'mainTabContainer' );
	var current_tab = tabs.selectedChildWidget;
	var first = "tab_first";
	var last = "tab_last";
	if ( dir == 'previous' ) {
		if ( current_tab.id == first ) return;
		tabs.back();
		location.hash = 'anchor_form';
	}
	else if ( dir == 'next' ) {
		if ( current_tab.id == last ) return;
		tabs.forward();
		location.hash = 'anchor_form';
	}
}

function init( ) {
	dojo.connect( dojo.byId( 'btn_previous' ), 'onclick', dojo.hitch( null, 'on_btn_navigate', 'previous' ) );
	dojo.connect( dojo.byId( 'btn_next' ), 'onclick', dojo.hitch( null, 'on_btn_navigate', 'next' ) );
}

dojo.addOnLoad( init );

window.setInterval("renewSession();", 120000);
function renewSession() {

	var xhrArgs = {
		url: "/renewSes.php",
		handleAs: "text",
		preventCache: true,
		load: function(data) {
		  
		},
		error: function(error) {
			
		}
	}
	var deferred = dojo.xhrGet(xhrArgs);
}
var kcGetSNS = function( id, sources ) {
	if ( !sources.hasOwnProperty(id) )
		return false;

	var printVars = function( item ) {
		if ( !item.hasOwnProperty('data') )
			return;

		var data = item.data.split(' = ', 2);
		window[ data[0].substr(4) ] = JSON.parse(data[1].replace(/;$/, ''));
	};

	var out = [];
	if ( !sources[id].hasOwnProperty('deps') ) {
		out.push(sources[id].src);
		printVars( sources[id] );
	}
	else {
		for ( var i = 0; i < sources[id].deps.length; i++ ) {
			if ( !sources.hasOwnProperty(sources[id].deps[i]) )
				continue;

			var depItem = sources[sources[id].deps[i]];
			// Only add js/css that hasn't been queued by WP
			if ( depItem.hasOwnProperty('queue') && !depItem.queue && depItem.hasOwnProperty('src') ) {
				out.push( depItem.src );
				printVars( depItem );
			}
		}
	}

	return out;
};


(function($) {
})(jQuery);

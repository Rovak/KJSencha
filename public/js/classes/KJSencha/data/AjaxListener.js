/**
 * Ajax Listener
 * 
 * Listens to all AJAX requests and execute functions depending
 * on specific returned variables like _exec and _add
 */
Ext.define('KJSencha.data.AjaxListener', {

	singleton: true,

	/**
	 * Enable the service by activating the listener
	 */
	enable: function()
	{
		Ext.Ajax.on('requestcomplete', this.onRequestComplete, this);
	},

	/**
	 * Disable this service
	 */
	disable: function()
	{
		Ext.Ajax.un('requestcomplete', this.onRequestComplete);
	},

	/**
	 * Process AJAX response
	 * 
	 * @param  {Ext.data.Connection} conn
	 * @param  {XMLHttpRequest} xhr
	 */
	onRequestComplete: function(conn, xhr)
	{
		var json = Ext.decode(xhr.responseText),
			evil = eval;

		if (json._exec) {
			evil(json._exec);
		}

		if (json._add) {
			this.processComponentAdds(json._add);
		}
	},

	/**
	 * Process components that are added through a AJAX request
     * 
	 * @param  {Array} components
	 */
	processComponentAdds: function(components)
	{
		Ext.Array.each(components, function(comp){
			var target = Ext.ComponentQuery.query(comp.selector)[0];
			if (target) {
				target.add(comp.config)
			}
		});
	}
});
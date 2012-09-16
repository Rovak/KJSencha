/**
 * Data Factory
 * 
 * Builds configuration that connects with the KJSencha module
 */
Ext.define('KJSencha.data.Factory', {

	restPath: App.basePath + '/kjsencha/data/rest',
	restParameters: {},
	servicePath: App.basePath + '/kjsencha/data/service',
	serviceParameters: {},
	pollingPath: App.basePath + '/kjsencha/data/polling',
	
    /**
     * Create a REST configuration that can be used in models
     * 
     * @param {Object} options
     * @param {String} options.module Name of the module to use
     * @param {String} options.model Model name, relative to your PHP namespace
     * @return {Object} configuration which can be used as model config
     */
	createRestConfig: function(options)
	{
		var extraParams = Ext.clone(this.restParameters);

        options = options || {};

        if ( ! options.module) {
            Ext.Error.raise('Module is required');
        }

        if ( ! options.model) {
           Ext.Error.raise('Model is required');
        }

        extraParams.module = options.module;
        extraParams.model = options.model;

        if (Ext.isSimpleObject(options.params)) {
			extraParams = Ext.Object.merge(extraParams, options.params);
            delete options.params;
		}

		var obj = {
			type: 'rest',
			appendId: false,
			batchActions: false,
			url: this.restPath,
			reader: {
				type: 'json',
				root: 'data',
				successProperty: 'success',
				messageProperty: 'message',
				totalProperty  : 'total'
			},
			writer: {
				type: 'json',
				root: 'data'
			},
			extraParams: extraParams,
			listeners: {
				'metachange': {
					fn: function(proxy, metaData) {
						// Optional metadata change later
					}, 
					scope: this
				}
			}
		};

		return Ext.merge(obj, options);
	},
    
    /**
     * Create a REST configuration that can be used in models
     * 
     * @param {Object/String} options
     * @param {String} options.module Name of the module to use
     * @param {String} options.model Model name, relative to your PHP namespace
     * @return {Ext.data.proxy.Rest} Proxy which can be used in a model
     */
	createRestProxy: function(config, className)
	{
		config = this.createRestConfig(config);
		className = className || 'Ext.data.proxy.Rest';

		return Ext.create('Ext.data.proxy.Rest', config);
	},

	/**
     * Store config factory
     * 
     * @param {Object/String} options
     * @param {String} options.module Name of the module to use
     * @param {String} options.action Action which will be executed
     * @return {Object}
     */
	createStoreConfig: function(options)
	{
		var extraParams = Ext.clone(this.restParameters);

        options = options || {};

        if ( ! options.module) {
            Ext.Error.raise('Module is required');
        }

        if ( ! options.action) {
           Ext.Error.raise('Action is required');
        }

        extraParams.module = options.module;
        extraParams.action = options.action;

        if (Ext.isSimpleObject(options.params)) {
			extraParams = Ext.Object.merge(extraParams, options.params);
            delete options.params;
		}

		var obj = {
			proxy: {
				type: 'ajax',
				api: {
					create: 	this.servicePath + '?xaction=create',
					read: 		this.servicePath + '?xaction=read',
					update: 	this.servicePath + '?xaction=update',
					destroy: 	this.servicePath + '?xaction=delete'
				},
				reader: {
					type: 'json',
					root: 'rows'
				},
				extraParams: extraParams,
				writer: {
					root: 'rows',
					writeAllFields: false
				}
			}
		};

		return Ext.merge(obj, options);
	},
    
	/**
     * Store factory
     * 
     * @param {Object/String} options
     * @param {String} options.module Name of the module to use
     * @param {String} options.action Action which will be executed
     * @return {Ext.data.Store}
     */
	createServiceStore: function(config, className)
	{
		config = this.createServiceConfig(config);
		className = className || 'Ext.data.Store';

		return Ext.create(className, config);
	},
	
    /**
     * Direct Proxy Factory
     */
	createDirectProxy: function(config)
	{
        Ext.Error.raise('Not implemented, yet!');
	},

	/**
	 * Create a polling provider
	 * 
	 * @param  {Object} config
	 * @return {Object}
	 */
	createPollingProvider: function(config)
	{	
		config = config || {};
		var interval = config.interval || 3000;

		var data =  {
			type:'polling',
	        url: this.pollingPath,
	        interval: interval,
            runTasks: {},
	        baseParams: {
	        	interval: interval,
	        	start:  Math.round(Ext.Date.now() / 1000)
	        },
	        updateLatency: function() {
	        	if (this.latency) {
	        		this.baseParams.latency = ( Ext.Date.now() - this.latency );
	        		this.latency = null;
	        	}
	        },
	        listeners: {
	            data: function(provider, event){
                   this.runTasks[event.id] = true;
	               this.updateLatency();
	            },
	            beforepoll: function() {
                    this.baseParams.runTasks = Ext.Object.getKeys(this.runTasks).join(',');
					this.latency = Ext.Date.now();
	            }
        	}
	    };

	    return Ext.merge(data, config);
	}
});
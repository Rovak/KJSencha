/**
 * KJ Sencha Direct Provider
 */
Ext.define('KJSencha.direct.ModuleRemotingProvider', {

	extend: 'Ext.direct.RemotingProvider',

	alias: 'direct.kjsenchamoduleremotingprovider',
    

	// Overwrite constructor so we can take the namespace
	constructor : function(config) {
		this.namespaceName = config.namespace;
		this.callParent(arguments);
	},

    initAPI : function(){
        var actions = this.actions,
            namespace = this.namespace,
            action,
            cls,
            methods,
            i,
            len,
            method;

        for (action in actions) {
            methods = actions[action];
            var className	= action.replace(/\\/g, '.'),
                objectPath	= this.namespaceName,
                pos			= className.lastIndexOf('.');
				
            if ( -1 != pos) {
                className = className.substr(0, pos).toLowerCase() + '.' + className.substr(pos+1);
            }
            cls = Ext.ns(objectPath + '.' + className);
 
            for (i = 0, len = methods.length; i < len; ++i) {
                method = new Ext.direct.RemotingMethod(methods[i]);
                method.module = methods[i].module; // toegevoegd
                cls[method.name] = this.createHandler(action, method);
            }
        }
    },

    configureRequest: function(action, method, args) {
        var me = this,
            callData = method.getCallData(args),
            data = callData.data, 
            callback = callData.callback, 
            scope = callData.scope,
            transaction;

        transaction = new Ext.direct.Transaction({
            provider: me,
            args: args,
            action: action,
            method: method.name,
            module: method.module, // toegevoegd
            data: data,
            callback: scope && Ext.isFunction(callback) ? Ext.Function.bind(callback, scope) : callback
        });

        if (me.fireEvent('beforecall', me, transaction, method) !== false) {
            Ext.direct.Manager.addTransaction(transaction);
            me.queueTransaction(transaction);
            me.fireEvent('call', me, transaction, method);
        }
    },
    
    /**
     * Configure a form submission request
     * @private
     * @param {String} action The action being executed
     * @param {Object} method The method being executed
     * @param {HTMLElement} form The form being submitted
     * @param {Function} callback (optional) A callback to run after the form submits
     * @param {Object} scope (optional) A scope to execute the callback in
     */
    configureFormRequest : function(action, method, form, callback, scope){
        
        var me = this,
            transaction = new Ext.direct.Transaction({
                provider: me,
                action: action,
                method: method.name,
                args: [form, callback, scope],
                callback: scope && Ext.isFunction(callback) ? Ext.Function.bind(callback, scope) : callback,
                isForm: true
            }),
            isUpload,
            params;
            
        if (me.fireEvent('beforecall', me, transaction, method) !== false) {
            Ext.direct.Manager.addTransaction(transaction);
            isUpload = String(form.getAttribute("enctype")).toLowerCase() == 'multipart/form-data';
            
            params = {
                extTID: transaction.id,
                extAction: action,
                extMethod: method.name,
                extType: 'rpc',
                extModule: method.module,
                extUpload: String(isUpload)
            };
            
            // change made from typeof callback check to callback.params
            // to support addl param passing in DirectSubmit EAC 6/2
            Ext.apply(transaction, {
                form: Ext.getDom(form),
                isUpload: isUpload,
                params: callback && Ext.isObject(callback.params) ? Ext.apply(params, callback.params) : params
            });
            me.fireEvent('call', me, transaction, method);
            me.sendFormRequest(transaction);
        }
    },

    getCallData: function(transaction){
        return {
            action: transaction.action,
            method: transaction.method,
            data: transaction.data,
            type: 'rpc',
            tid: transaction.id,
            module: transaction.module // toegevoegd
        };
    }
});

Ext.define('Ext.cmp.cmp_hbksplit.Viewport', {
  extend: 'Ext.tualo.ApplicationPanel',
  requires: [ 
  ],
  layout: 'fit',
  listeners: {
    boxReady: 'onBoxReady',
    resize: 'onResize'
  },
   
  items: [
    {
      xtype: 'grid',
      dockedItems: [{
        xtype: 'toolbar',
        dock: 'top',
        items: [
            { xtype: 'button', text: 'Dateiupload', handler: function(){
              var dialog = Ext.create('Ext.tualo.Window', {
                title: 'Belege hochladen',
                layout: 'fit',
                items: [
                  {
                    xtype: 'tualocontextdduploadlist',
                    uploadUrl:  "./hbksplit/upload",
                    listeners: {
                      done: function(){
                        }
                    }
                  }
                ],
                modal: true
              });
          
              dialog.show();
              dialog.resizeMe();
            } 
          }
        ]
    }],

    columns: [{
      header: 'ID',
      dataIndex: 'id',
      flex: 2
      },{
          header: 'Datei',
          dataIndex: 'file',
          flex: 2
      }, {
        header: 'Seitengesamt',
        dataIndex: 'pagecount',
        flex: 1,
        //renderer: Ext.util.Format.fileSize
      }, {
        header: 'Verarbeitet',
        dataIndex: 'currentpage',
        flex: 1,
        //renderer: Ext.util.Format.fileSize
      }, {
        header: 'in den HBK Verschieben',
        dataIndex: 'move',
        flex: 1,
        //renderer: Ext.util.Format.fileSize
      }],

      viewConfig: {
          emptyText: 'Keine Dokumente vorhanden',
          deferEmptyText: false
      },
      store: {
        type: 'json',
        autoLoad: true,
        proxy: {
          type: 'ajax',
          url: './hbksplit/status',
          reader: {
              type: 'json',
              rootProperty: 'data'
          }
        },
        listeners: {
          load: function(store){
              console.log('1321');
              Ext.defer(store.load(), 5000, this, [ ]);
            
          }
        },
      },

      

  }
  ],
  routeTo: function(val){

  },
  statics: {
    canRouteTo: function(val) {
      var r = Ext.cmp.cmp_template_default.controller.Viewport.requestParams(val);
      if (typeof r=='object'){
        if (typeof r.t=='string'){
            if (typeof Ext.ClassManager.get('Tualo.DataSets.views.'+r.t)=='function'){
                return true; 
            }
        }
      }
      console.warn('DS not accessible',val);
      return false;
    }
  }
});

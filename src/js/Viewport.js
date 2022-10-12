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
            { xtype: 'button', text: 'Button 1', handler: function(){
              var dialog = Ext.create('Ext.tualo.Window', {
                title: 'Belege hochladen',
                layout: 'fit',
                items: [
                  {
                    xtype: 'tualocontextdduploadlist',
                    uploadUrl:  "/hbksplit/upload",
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
          header: 'Name',
          dataIndex: 'name',
          flex: 2
      }, {
          header: 'Size',
          dataIndex: 'size',
          flex: 1,
          renderer: Ext.util.Format.fileSize
      }],

      viewConfig: {
          emptyText: 'Drop Files Here',
          deferEmptyText: false
      },
      store: {
        type: 'store'
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

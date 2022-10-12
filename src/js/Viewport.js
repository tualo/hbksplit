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
        type: 'memory'
      },

      listeners: {

          drop: {
              element: 'el',
              fn: 'drop'
          },

          dragstart: {
              element: 'el',
              fn: 'addDropZone'
          },

          dragenter: {
              element: 'el',
              fn: 'addDropZone'
          },

          dragover: {
              element: 'el',
              fn: 'addDropZone'
          },

          dragleave: {
              element: 'el',
              fn: 'removeDropZone'
          },

          dragexit: {
              element: 'el',
              fn: 'removeDropZone'
          },

      },

      noop: function(e) {
          e.stopEvent();
      },

      addDropZone: function(e) {
          if (!e.browserEvent.dataTransfer || Ext.Array.from(e.browserEvent.dataTransfer.types).indexOf('Files') === -1) {
              return;
          }

          e.stopEvent();

          this.addCls('drag-over');
      },

      removeDropZone: function(e) {
          var el = e.getTarget(),
              thisEl = this.getEl();

          e.stopEvent();


          if (el === thisEl.dom) {
              this.removeCls('drag-over');
              return;
          }

          while (el !== thisEl.dom && el && el.parentNode) {
              el = el.parentNode;
          }

          if (el !== thisEl.dom) {
              this.removeCls('drag-over');
          }

      },

      drop: function(e) {
          e.stopEvent();

          Ext.Array.forEach(Ext.Array.from(e.browserEvent.dataTransfer.files), function(file) {
              store.add({
                  file: file,
                  name: file.name,
                  size: file.size
              });
              console.log(file);
          });

          this.removeCls('drag-over');
      }


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

Ext.define('Ext.cmp.cmp_hbksplit.StatusModel', {
    extend: 'Ext.data.Model',
    alias: 'viewmodel.hbksplit_statusmodel',
    
    fields: [
        {name: 'id',  type: 'string'},
        {name: 'currentpage',   type: 'int' },
        {name: 'pagecount',   type: 'int' },
        {name: 'file', type: 'string'},
        {name: 'uploadname', type: 'string'},
        {name: 'documents', type: 'array'},
        {name: 'documents_quantity',calculate: function (data) {
            return data.documents.length
        }},
        {name: 'progress',calculate: function (data) {
            try{
                return  data.currentpage  / data.pagecount;
            }catch(e){
                
            }
            return 0;
        }},
        {name: 'done',calculate: function (data) {
            try{
                return  data.currentpage === data.pagecount;
            }catch(e){
                
            }
            return false;
        }}
    ]
    
});
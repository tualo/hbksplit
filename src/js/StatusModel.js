Ext.define('Ext.cmp.cmp_hbksplit.StatusModel', {
    extend: 'Ext.data.Model',
    alias: 'viewmodel.hbksplit_statusmodel',
    
    fields: [
        {name: 'id',  type: 'string'},
        {name: 'currentpage',   type: 'int' },
        {name: 'pagecount',   type: 'int' },
        {name: 'file', type: 'string'},
        {name: 'progress',calculate: function (data) {
            try{
                return  data.currentpage  / data.pagecount;
            }catch(e){
                
            }
            return 0;
        }}
    ],

    changeName: function() {
        var oldName = this.get('name'),
            newName = oldName + " The Barbarian";

        this.set('name', newName);
    }
});
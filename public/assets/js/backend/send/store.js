define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'send/store/index',
                    add_url: 'send/store/add',
                    edit_url: 'send/store/edit',
                    del_url: 'send/store/del',
                    multi_url: 'send/store/multi',
                    table: 'send_store',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'express_id', title: __('Express_id'), searchList: {"1":__('Express_id 1'),"2":__('Express_id 2'),"3":__('Express_id 3'),"4":__('Express_id 4'),"5":__('Express_id 5')}, formatter: Table.api.formatter.normal},
                        {field: 'send_code', title: __('Send_code')},
                        {field: 'send_address', title: __('Send_address')},
                        {field: 'store_name', title: __('Store_name')},
                        {field: 'is_multiple', title: __('Is_multiple'), searchList: {"1":__('Is_multiple 1'),"0":__('Is_multiple 0')}, formatter: Table.api.formatter.normal},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            $(document).on('click','.data-sync',function(){
                $.ajax({
                    dataType:'json',
                    url:'send/store/data_sync',
                    type:'post',
                    success:function(res){
                        layer.msg(res.msg);
                    }
                })
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'express/index',
                    add_url: 'express/add',
                    edit_url: 'express/edit',
                    del_url: 'express/del',
                    multi_url: 'express/multi',
                    table: 'express',
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
						{field: 'user.username', title: __('User.username'),formatter: Table.api.formatter.search},
                        {field: 'express_no', title: __('Express_no')},
						{field: 'taskid', title: '主站ID'},
                        {field: 'out_order_no', title: __('Out_order_no'),visible: false},
                        {field: 'expressid', title: __('Expressid'), searchList: {"1":'圆通',"2":'中通',"3":'申通','4':'韵达','5':'汇通'}, formatter: Table.api.formatter.normal},
                        {field: 'price', title: __('Price'), operate:'BETWEEN'},
                        {field: 'num', title: '数量'},
                        {field: 'sender', title: '发货仓',formatter: Table.api.formatter.search},
                        {field: 'goods.name', title: __('Goods'),formatter: Table.api.formatter.search},
                        {field: 'addressee', title: __('Addressee')},
                        {field: 'a_mphone', title: __('A_mphone'),formatter: Table.api.formatter.search},
                        {field: 'all_address', title: __('All_address')},
						{field: 'from', title: __('From'), searchList: {"0":__('From 0'),"1":__('From 1')}, formatter: Table.api.formatter.normal},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            

            // 为表格绑定事件
            Table.api.bindevent(table);
			table.off('dbl-click-row.bs.table');
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
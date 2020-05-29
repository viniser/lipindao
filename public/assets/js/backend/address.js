define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'address/index',
                    add_url: 'address/add',
                    edit_url: 'address/edit',
                    del_url: 'address/del',
                    multi_url: 'address/multi',
                    table: 'address',
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
                        {field: 'user.username', title: __('User.username'), formatter: Table.api.formatter.search},
                        {field: 'ismr', title: __('Ismr'), searchList: {"0":__('Ismr 0'),"1":__('Ismr 1')}, formatter: Table.api.formatter.normal},
                        {field: 'dianpu', title: __('Dianpu')},
                        {field: 'fajianren', title: __('Fajianren')},
                        {field: 'shouji', title: __('Shouji')},
                        {field: 'a_province', title: __('A_province')},
                        {field: 'city', title: __('City')},
                        {field: 'area', title: __('Area')},
                        {field: 'address', title: __('Address')},
                        {field: 'statusswitch', title: __('Statusswitch'), formatter: Table.api.formatter.toggle},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
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
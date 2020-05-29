define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'goods/index',
                    add_url: 'goods/add',
                    edit_url: 'goods/edit',
                    del_url: 'goods/del',
                    multi_url: 'goods/multi',
                    table: 'goods',
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
                        {field: 'id', title: 'Id'},
                        {field: 'fid', title: '主站编号'},
                        {field: 'name', title: __('Name')},
                        {field: 'is_out', title: '商品状态',formatter:function(value,row,index){
                                if(value==1){
                                    return '<a style="text-decoration:none;color:#18bc9c" data-id='+row.id+' class="sold_out" href="javascript:void(0);">已上架</a> '
                                }else{
                                    return '<a style="text-decoration:none;color:gray" data-id='+row.id+' class= "sold_up" href="javascript:void(0);">已下架</a>'
                                }
                            }},
                        {field: 'price', title: __('Price'), operate:'BETWEEN'},
                        {field: 'apiprice', title: '代理价',visible: false, operate:false},
                        {field: 'cprice', title: '成本',visible: false, operate:false},
                        {field: 'weight', title: __('Weight'), operate:'BETWEEN'},
                        {field: 'goods_image', title: __('Goods_image'), formatter: Table.api.formatter.image},
                        {field: 'express_codes', title: '仓库配置'},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            //数据同步
            $('.data-sync').on('click',function(){
                $.ajax({
                    url:'Goods/data_sync',
                    dataType:'json',
                    type:'post',
                    success:function(res){
                        layer.msg(res.msg);
                    }
                })
            });
            //商品下架
            $(document).on('click','.sold_out',function(){
                var id = $(this).attr('data-id');
                $.ajax({
                    data:{gid:id},
                    type:'post',
                    dataType:'json',
                    url:'goods/sold_out',
                    success:function(res){
                        if(res.code==1){
                            window.location.reload()
                        }
                    }
                })
            })
            // console.log(window)
            //商品上架
            $(document).on('click','.sold_up',function(){
                var id = $(this).attr('data-id');
                $.ajax({
                    data:{gid:id},
                    type:'post',
                    dataType:'json',
                    url:'goods/sold_up',
                    success:function(res){
                        if(res.code==1){
                            window.location.reload()
                        }
                    }
                })
            })
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
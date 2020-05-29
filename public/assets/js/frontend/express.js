define(['jquery', 'bootstrap', 'frontend', 'form', 'table'], function ($, undefined, Frontend, Form, Table) {
 
    var Controller = {
     //定义的方法
		 exlist: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'express/exlist',
                    add_url: 'express/add',
                    table: 'express',
                }
            });
		  var table = $("#table");
			 // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
				showToggle: false,
				exportDataType: 'selected',
                columns: [
                    [
                        {field: 'id', title: '编号'},
                        {field: 'express_no', title: __('Express_no'),formatter:function(value,row,index){
                                if(value=='0'){
                                    return '<a href="javascript:void(0)" id="getdan" class="searchList" data-toggle="tooltip" title="" data-field="expressid" data-value="5" data-original-title="下单后10分钟之内会有单号,刷新查看">查看</a>'
                                }else{
                                    return value;
                                }
                            }},
                        {field: 'out_order_no', title: __('Out_order_no'),visible: false},
                        {field: 'expressid', title: '快递', searchList: {"1":'圆通',"2":'中通',"3":'申通','4':'韵达','5':'汇通'}, formatter: Table.api.formatter.normal},
                        {field: 'sender', title: '发货仓', operate:false},
                        {field: 'price', title: '总价', operate:false},
                        {field: 'num', title:'数量', operate:false},

                        {field: 'goods.name', title:'礼品名称', operate:false,formatter:function(value,row){
                                return "<a href="+'/Goods/detail?gid='+row.goods.id+">"+value+"</a>"
                            }},
                        {field: 'addressee', title: __('Addressee')},
                        {field: 'a_mphone', title: __('A_mphone'),formatter: Table.api.formatter.search},
                        {field: 'all_address', title: __('All_address'), operate:false},
                        {field: 'from', title: __('From'), searchList: {"0":'普通',"1":'批量'}, formatter: Table.api.formatter.normal,visible: false},
                        {field: 'tableid', title: '导入编号',visible: false},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field:'operate','title':__('Operate'),formatter:function(){
                                return '<a href="javascript:void(0);" class="order_detail">订单明细</a>'
                            }}
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
			table.off('dbl-click-row.bs.table');
             //订单明细
             $(document).on("click", ".order_detail",function () {
                 var eid = $(this).parent().parent().children().first().html();//获取订单主键
                 Fast.api.open("/Express/order_detail?eid="+eid, "订单详情", {area:['580px','260px']});
             });
		 },
		 adds: function () {
			 $(".btn-editone").data("area", ['800px','470px']);
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'express/adds',
                    add_url: '/express/newadds',
					edit_url: '/express/editdds',
                    table: 'address',
                }
            });
		  var table = $("#table");
			 // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
				showToggle: false,
				exportDataType: 'selected',
				search:false,
				showToggle: false,
				showColumns: false,
				showExport: false,
				commonSearch: false,
                columns: [
                    [
                        {field: 'dianpu', title: '旺旺'},
                        {field: 'fajianren', title: '发件人'},
                        {field: 'shouji', title: '手机'},
                        {field: 'a_province', title: '省'},
                        {field: 'city', title: '市'},
                        {field: 'area', title: '区'},
                        {field: 'address', title: '地址'},
						{field: 'ismr', title: '是否默认', searchList: {"0":'否',"1":'是'}, formatter: Table.api.formatter.normal},
                        {field: 'statusswitch', title: '状态', searchList: {"0":'冻结',"1":'正常'}, formatter: Table.api.formatter.status},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                         {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,formatter: Table.api.formatter.operate}
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
			table.off('dbl-click-row.bs.table');
		 },
		ulist: function () {
			 $(".btn-editone").data("area", ['800px','470px']);
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'express/ulist',
                    table: 'upinfo',
                }
            });
		  var table = $("#table");
			 // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
				showToggle: false,
				exportDataType: 'selected',
				search:false,
				showToggle: false,
				showColumns: false,
				showExport: false,
				commonSearch: false,
                columns: [
                    [
                        {field: 'id', title: __('Id')},
                         {field: 'expressid', title: __('Expressid'), searchList: {"1":'圆通',"2":'中通',"3":'申通','4':'韵达','5':'汇通'}, formatter: Table.api.formatter.normal},
                        {field: 'exnum', title: '导入总数'},
                        {field: 'oknum', title: '导入成功数'},
                        {field: 'vars', title: '说明'},
                        {field: 'tableid', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
						{field: 'operate', title: __('Operate'), table: table,events: Table.api.events.operate,width:'160px',
                        buttons: [
                                {name: 'buttons', text: '明细', title: '明细', icon: '', classname: 'btn btn-xs btn-dialog',url:'/express/exlist?tableid={tableid}'},
                                {name: 'buttons', text: '导出', title: '导出', icon: '', classname: 'btn btn-xs btn-derive',url:'/express/export?tableid={tableid}'},
                                {name: 'buttons', text: '一键发货', title: '一键发货', icon: '', classname: 'btn btn-xs btn-derive',url:'/express/autosend?tableid={tableid}'}
                            ],formatter: Table.api.formatter.operate}   
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
			table.off('dbl-click-row.bs.table');

		 },
		buy: function () {
			$("body").bind("click",function(){
				var expressid=$('input:radio[name="expressid"]:checked').val();//停留时间
				$.ajax({
                          data:{expressid:expressid},
                          dataType:'json',
                          url:'/Express/get_kuaidi',
                          type:'post',
                          success:function(res){
                              $("#lp").text(res);
                             }
                        })
			});	
			
			// Fast.api.open('/express/uploads', 'title', '')
			$(document).on("click", ".btn-dialog", function () {
			  var adds =$("#addstext").val();
			  var addts1 =adds.replace(/，/g,','); 
			  var addtext =addts1.replace(/^\s+|\s+$/g,''); 
              var addtextarr= new Array(); 
			  var adddan=new Array();
			      addtextarr=addtext.split("\n");
				for(i=0;i<addtextarr.length;i++){
					if(addtextarr[i]!=''){
						adddan=addtextarr[i].split(",");
						if(adddan.length!=3){alert("第"+(i+1)+"个收货地址格式有错误，请仔细检查！"); return; }
						if(adddan[1].length!=11){alert("第"+(i+1)+"个地址的手机号码格式不对，请仔细检查！"); return;}
					}else{
					alert("第"+(i+1)+"个地址不能为空,请删除空数据");
					return;
					}
				}
                 alert('验证通过'+addtextarr.length+'个地址，请提交');
				
                $("#addstext").val(addtext); 
				$('.btn-embossed').attr('disabled',false);
            });
			Controller.api.bindevent();
		},
		uploads: function () {
			
			$("body").bind("click",function(){
				var expressid=$('input:radio[name="expressid"]:checked').val();//停留时间
				$.ajax({
                          data:{expressid:expressid},
                          dataType:'json',
                          url:'/Express/get_kuaidi',
                          type:'post',
                          success:function(res){
                              $("#lp").text(res);
                             }
                        })
			});	
			
			// 给上传按钮添加上传成功事件
            $("#plupload-xls").data("upload-success", function (data) {
                var url = Fast.api.cdnurl(data.url);
			//	console.log('数组：', data);
				$(".plupload").text('已选择：'+url);
               // Toastr.success(__('Upload successful'));
            });
			 //为表单绑定事件
            Form.api.bindevent($("#upload-form"), function (data, ret) {
                setTimeout(function () {
                    location.href = ret.url ? ret.url : "/";
                }, 1000);
            },function(data,ret){
                setTimeout(function () {
                    location.href = ret.url ? ret.url : "/";
                }, 1000);
            });
          //  Form.api.bindevent($("#upload-form"));
			Controller.api.bindevent();
        },
        autosend: function () {
            Form.api.bindevent($("#sendinfo-form"), function (data, ret) {
                $('#info').hide(0);
                var datas = ret.data;
                var output = "<table class='table table-striped table-bordered table-hover'><thead><tr><th>编号</th><th>订单号</th><th>快递单号</th><th>状态</th></tr></thead><tbody>";
                var k = 1;
                for (var i in datas) {
                    output += "<tr><td>"+k+"</td><td>" + datas[i].order_no + "</td><td>" + datas[i].ex_no + "</td><td>" + datas[i].ErrMsg + "</td></tr>";
                    k++;
                }
                $("#info").html(output + "</tbody></table>").fadeIn(300);
            },function(data,ret){
                $('#info').hide(0);
                var output = "<h1 style='text-align:center;font-size:20px;color:red'>系统异常，请联系客服处理!</h1>";
                $("#info").html(output).fadeIn(300);
            })
        },
		newadds: function () {
             Controller.api.bindevent();
        },
		editdds: function () {
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
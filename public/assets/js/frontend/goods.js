define(['jquery', 'bootstrap', 'frontend', 'form', 'table'], function ($, undefined, Frontend, Form, Table) {
    var Controller = {
        detail:function() {
        	var jian = $('#jian');
            var jia = $('#jia'); 
            jian.click(function(){
                var num = $('.count').val();
                if(num>1){
                    $('.count').val(num-1);
                }
            })
            jia.click(function(){
                var num = $('.count').val();
                if(num<=100){
                    $('.count').val(Number(num)+Number(1));
                }
            })
        },
        uploads: function () {
            //动态改变价格
            $(function(){
                //默认价格
                var price = $('.price').html();//商品单价
                var express_price = $('.express_price').html();//快递默认价格
                var total_price = $('.total-price');
                var goods_price = $('.goods-price');
                $('#num').on('input',function(){
                    var num = $(this).val();//获取商品数量
                    if(num>1){
                        layer.confirm('确定每个收货地址要购买'+num+'件礼品吗');
                    }
                    total_price.html((Number(price*num)+Number(express_price)).toFixed(2));
                    goods_price.html((price*num).toFixed(2));
                })
                $("input[name='store_id']").click(function(event){
                    event.stopPropagation();
                    var store_id = $(this).val();
                    $.ajax({
                        data:{store_id:store_id},
                        dataType:'json',
                        type:'post',
                        url:"/Goods/express_price",
                        success:function(res){
                            if(res.is_multiple==0){
                                $('input[name="num"]').attr('readonly','readonly');
                                $('.express_price').html(res.express_price);
                                var num = $('input[name="num"]').val(1);
                                total_price.html((Number(price*1)+Number(res.express_price)).toFixed(2));
                                goods_price.html((price*1).toFixed(2));
                            }
                            if(res.is_multiple==1){
                                $('input[name="num"]').removeAttr('readonly');
                                $('.express_price').html(res.express_price);
                                var num = $('input[name="num"]').val();
                                total_price.html((Number(price*num)+Number(res.express_price)).toFixed(2));
                                $('#num').on('input',function(){
                                    var num = $(this).val();//获取商品数量
                                    total_price.html((Number(price*num)+Number(res.express_price)).toFixed(2));
                                    goods_price.html((price*num).toFixed(2));
                                })
                            }
                        }
                    })
                })
            })
            // 给上传按钮添加上传成功事件
            $("#plupload-xls").data("upload-success", function (data) {
                var url = Fast.api.cdnurl(data.url);
            //  console.log('数组：', data);
                $(".plupload").text('已选择：'+url);
               // Toastr.success(__('Upload successful'));
            });
             //为表单绑定事件
            Controller.api.bindevent();
        },
        order:function() {
            //动态改变快递价格
            $(function(){
                //默认价格 不点击其他快递
                var price = $('.price').html();//商品单价
                var express_price = $('.express_price').html();//快递默认价格
                var total_price = $('.total-price');//总价 包含快递费
                var goods_price = $('.goods-price');//商品总价 不包含快递费
                if($('#num').val()>1){
                    layer.confirm('确定每个收货地址要购买'+$('#num').val()+'件礼品吗');
                }
                //默认价格 不点击其他快递 改变商品数量
                $('#num').on('input',function(){
                    var num = $(this).val();//获取商品数量
                    total_price.html((Number(price*num)+Number(express_price)).toFixed(2));
                    goods_price.html((price*num).toFixed(2));
                    if(num>1){
                        layer.confirm('确定每个收货地址要购买'+num+'件礼品吗');
                    }
                })
                //选择不同快递时的价格
                $("input[name='store_id']").click(function(event){
                    event.stopPropagation();
                    var store_id = $(this).val();
                    $.ajax({
                        data:{store_id:store_id},
                        dataType:'json',
                        type:'post',
                        url:"/Goods/express_price",
                        success:function(res){
                            if(res.is_multiple==0){
                                $('input[name="num"]').attr('readonly','readonly');
                                $('.express_price').html(res.express_price);
                                var num = $('input[name="num"]').val(1);
                                total_price.html((Number(price*1)+Number(res.express_price)).toFixed(2));
                                goods_price.html((price*1).toFixed(2));
                            }
                            if(res.is_multiple ==1){
                                $('input[name="num"]').removeAttr('readonly');
                                $('.express_price').html(res.express_price);
                                var num = $('input[name="num"]').val();
                                total_price.html(Number(price*num)+Number(res.express_price));
                                $('#num').on('input',function(){
                                    var num = $(this).val();//获取商品数量
                                    total_price.html((Number(price*num)+Number(res.express_price)).toFixed(2));
                                    goods_price.html((price*num).toFixed(2));
                                })
                            }
                        }
                    })
                })
            })
        	//验证手动提交地址
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
						if(adddan.length!=3 && adddan.length!=4){alert("第"+(i+1)+"个收货地址格式有错误，请仔细检查！"); return; }
						if(adddan[1].length!=11){alert("第"+(i+1)+"个地址的手机号码格式不对，请仔细检查！"); return;}
						var addr_char = adddan[2];
                        var address_arr = addr_char.split(' ');
                        if(address_arr.length<4){
                            alert("第"+(Number(i)+Number(1))+" 个收货地址格式中省、市、区或县之间应该用空格隔开，请仔细检查！");
                            return;
                        }
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
        favorite: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'goods/favorite',
                    table: 'favorite',
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
                        {field: 'goods_image', title: '图片', formatter: Table.api.formatter.image},
                        {field: 'goods_name', title: '礼品名'},
                        {field: 'goods_num', title: '数量'},
                        {field: 'store_name', title: '快递仓'},
                        {field: 'create_time', title: '收藏时间', operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'buttons', title:'操作',formatter:function(value,row,index){
                                return "<a href='/goods/order/gid/"+row.goods_id+"/sid/"+row.store_id+"/num/"+row.goods_num+".html' class='btn btn-danger'><i class='fa fa-plus'></i>立即下单</a>"
                            }}
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            table.off('dbl-click-row.bs.table');
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"), function (data, ret) {
                setTimeout(function () {
                location.href = ret.url;
                    }, 1000);
            },function(data,ret){
                    setTimeout(function () {
                        location.href = ret.url;
                    }, 2000);
                });
              }
            }
    };
    return Controller;
});
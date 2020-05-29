define(['jquery', 'bootstrap', 'backend', 'addtabs', 'table', 'echarts', 'echarts-theme', 'template','form','bootstrap-datetimepicker'], function ($, undefined, Backend, Datatable, Table, Echarts, undefined, Template,Form,datetimepicker) {

    var Controller = {
        statistics_user: function () {
            Form.events.datetimepicker($("form"));  
            // $('.datetimepicker').datetimepicker();
            $('#end_time').on('blur',function(){
                var end_time = $(this).val();
                var start_time = $('#start_time').val();
                if(end_time<start_time){
                    layer.msg('截止日期不能比开始日期小')
                    $('#search').attr('disabled','disabled');
                }else{
                    $('#search').removeAttr('disabled')
                }
            })
        },
        goods_count:function(){
            //绑定日期插件
            $('.datetimepicker').datetimepicker();
            //判断日期选择的起始和结束
            $('#end_time').on('blur',function(){
                var end_time = $(this).val();
                var start_time = $('#start_time').val();
                if(end_time<start_time){
                    layer.msg('截止日期不能比开始日期小')
                    $('#search').attr('disabled','disabled');
                }else{
                    $('#search').removeAttr('disabled')
                }
            })
        }
    };
    return Controller;
});
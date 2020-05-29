define(['jquery', 'bootstrap', 'backend', 'addtabs', 'table', 'template'], function ($, undefined, Backend, Datatable, Table, Template) {

    var Controller = {
        index: function () {
            // 基于准备好的dom，初始化echarts实例
            

            $(document).on("click", ".btn-checkversion", function () {
                top.window.$("[data-toggle=checkupdate]").trigger("click");
            });
 
          
        }
    };

    return Controller;
});
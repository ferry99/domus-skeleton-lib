var adminListFeedBack = {

    init : function(){
        console.log('init');
        this.search();
        this.ajax.ajaxStart();
        this.ajax.ajaxEnd();
    },
    
    search: function(){
        console.log('we searhing');
    },

    ajax : {
        ajaxStart : function(){
            $(document).ajaxStart(function(){
                $('#spinner').show();
            })
        },
        ajaxEnd : function(){
            $(document).ajaxStart(function(){
                $('#spinner').show();
            })
        }
    },

    action : {
        add : function(){
            $.ajax({
                url: '/path/to/file',
                type: 'default GET (Other values: POST)',
                dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                data: {param1: 'value1'},
            })
            .done(function() {
                console.log("success");
            })            
        },
        edit: function(id){

        },
        delete: function(id){

        }
    },

    config : function($elm){
        submitBtn: $elm.btn.submitBtn,
        editBtn: $elm.btn.editBtn,
        deleteBtn: $elm.btn.deleteBtn
    }
}

$(function(){
    
    adminListFeedBack.config({
        {btn : $('#submit') , url : 'localhost:8080'},
    });



})
filterOptions = {
    init : function () {
        $('.filter_options_btn').on('click', function () {
            var _self = $(this);
            var filterOptionsMain = _self.parent().closest('.filter_wrapper').find('.filter_options');
            var filterOptionsWrapper = _self.parent().closest('.filter_wrapper').find('.filter_options_wrapper');
            if(_self.hasClass('active')){
                _self.removeClass('active');
                filterOptionsWrapper.css('max-height','');
            }else{
                _self.addClass('active');
                filterOptionsWrapper.css('max-height',(filterOptionsMain.outerHeight()+ 15)+'px');            }
        });
    },
    openOptions : function(filterBtn,filterOptions,filterOptionsWrapper){

    },
    closeOptions : function(filterBtn,filterOptions,filterOptionsWrapper){

    }
}



jQuery(document).ready(function ($) {
    console.log('test');
});


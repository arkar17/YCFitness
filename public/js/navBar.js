$( document ).ready(function() {
    $(".burger-icon").click(function(){
        // $(".customer-navlinks-container").css("display", "inline-flex");
        // $(".customer-navlinks-container").addClass("customer-nav-show-flex")
        $(".customer-navlinks-container").addClass("fade-in")
        $(".customer-navlinks-container").removeClass("fade-out")
        $(".customer-nav-btns-container").addClass("fade-in")
        $(".customer-nav-btns-container").removeClass("fade-out")
        $(".nav-overlay").addClass("fade-in")
        $(".nav-overlay").removeClass("fade-out")
        $(".burger-icon").css("display", "none");
        $(".close-nav-icon").css("display", "block");
    })

    $(".close-nav-icon").click(function(){
        // $(".customer-navlinks-container").css("display", "inline-flex");
        $(".customer-navlinks-container").addClass("fade-out")
        $(".customer-navlinks-container").removeClass("fade-in")
        $(".customer-nav-btns-container").removeClass("fade-in")
        $(".customer-nav-btns-container").addClass("fade-out")
        $(".nav-overlay").removeClass("fade-in")
        $(".nav-overlay").addClass("fade-out")
        $(".burger-icon").css("display", "block");
        $(".close-nav-icon").css("display", "none");
    })

    $( window ).resize(function() {
        // console.log($(this).width())
        if($(this).width() > 1000){
            $(".burger-icon").css("display", "none");
            $(".close-nav-icon").css("display", "none");
        }else if($(this).width() <= 1000){
            if( $(".close-nav-icon").css("display") == "none" ){
                $(".burger-icon").css("display", "block");
            }

        }
      });

});

// Dropdown Menu
var dropdown = document.querySelectorAll('.customer-dropdown');
var dropdownArray = Array.prototype.slice.call(dropdown,0);
dropdownArray.forEach(function(el){
	var button = el.querySelector('a[data-toggle="dropdown"]'),
			menu = el.querySelector('.customer-dropdown-menu'),
			arrow = button.querySelector('i.icon-arrow');

	button.onclick = function(event) {
		if(!menu.hasClass('show')) {
			menu.classList.add('show');
			menu.classList.remove('hide');
			arrow.classList.add('open');
			arrow.classList.remove('close');
			event.preventDefault();
		}
		else {
			menu.classList.remove('show');
			menu.classList.add('hide');
			arrow.classList.remove('open');
			arrow.classList.add('close');
			event.preventDefault();
		}
	};
})

Element.prototype.hasClass = function(className) {
    return this.className && new RegExp("(^|\\s)" + className + "(\\s|$)").test(this.className);
};

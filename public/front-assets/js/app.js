

$(document).ready(() => {
  // Sidebar Menu
    jQuery('body').on('blur keydown keyup', '.alphaonly', function () {
            var node = $(this);
            node.val(node.val().replace(/[^a-zA-Z ]/g, ''));
        }
    );

    jQuery('body').on('blur keydown keyup', '.alpha-num', function () {
            var node = $(this);
            node.val(node.val().replace(/[^a-zA-Z0-9 ]/g, ''));
        }
    );

    jQuery('body').on('keydown blur', ".input_int_number", function (e) {

        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            ((e.keyCode === 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) ||

            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }

        var minValue = parseFloat($(this).attr('data-min'));
        var maxValue = parseFloat($(this).attr('data-max'));
        var valueCurrent = parseFloat($(this).val());
        var name = $(this).attr('name');
        if (valueCurrent < minValue) {
            $(this).val(''); //$(this).data('oldvalue')
        } else if (valueCurrent > maxValue) {
            $(this).val(''); //$(this).data('oldvalue')
        } else {
            var checkRelatedTo = $(this).attr('data-toinput');
            if (typeof checkRelatedTo != 'undefined' && checkRelatedTo != '') {
                jQuery('#' + checkRelatedTo).attr('data-min', $(this).val());
            }

            var checkRelatedfrom = $(this).attr('data-frominput');
            if (typeof checkRelatedfrom != 'undefined' && checkRelatedfrom != '') {
                jQuery('#' + checkRelatedfrom).attr('data-max', $(this).val());
            }
            $(this).attr('data-oldvalue', $(this).val());
        }
    });

    jQuery('body').on('blur', ".input-number, .number-input", function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105) && e.keyCode != 110) {
            e.preventDefault();
        }
        var val = jQuery(this).val();
        if (isNaN(val)) {
            val = val.replace(/[^0-9\.]/g, '');
            if (val.split('.').length > 2)
                val = val.replace(/\.+$/, "");
        }
        jQuery(this).val(val);
        var minValue = parseFloat($(this).attr('data-min'));
        var maxValue = parseFloat($(this).attr('data-max'));
        var valueCurrent = parseFloat(val);
        var name = $(this).attr('name');
        if (valueCurrent > maxValue) {
            var min_message = $(this).attr('data-max-message');
            if (min_message != '' && typeof min_message != 'undefined') {
                notifyError(min_message);
            }
            $(this).val('');
        } else if (valueCurrent < minValue) {
            var min_message = $(this).attr('data-min-message');
            if (min_message != '' && typeof min_message != 'undefined') {
                notifyError(min_message);
            }

            $(this).val('');
        } else {
            var checkRelatedTo = $(this).attr('data-toinput');
            if (typeof checkRelatedTo != 'undefined' && checkRelatedTo != '') {
                jQuery('#' + checkRelatedTo).attr('data-min', val);
            }

            var checkRelatedfrom = $(this).attr('data-frominput');
            if (typeof checkRelatedfrom != 'undefined' && checkRelatedfrom != '') {
                jQuery('#' + checkRelatedfrom).attr('data-max', val);
            }
            $(this).attr('data-oldvalue', val);
        }
    });
  setTimeout(function () {
    $(".vertical-nav-menu").metisMenu();
  }, 100);

  // Search wrapper trigger

  $(".search-icon").click(function () {
    $(this).parent().parent().addClass("active");
  });

  $(".search-wrapper .close").click(function () {
    $(this).parent().removeClass("active");
  });

  // BS4 Popover

  $('[data-toggle="popover-custom-content"]').each(function (i, obj) {
    $(this).popover({
      html: true,
      placement: "auto",
      template:
        '<div class="popover popover-custom" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
      content: function () {
        var id = $(this).attr("popover-id");
        return $("#popover-content-" + id).html();
      },
    });
  });

  // Stop Bootstrap 4 Dropdown for closing on click inside

  $(".dropdown-menu").on("click", function (event) {
    var events = $._data(document, "events") || {};
    events = events.click || [];
    for (var i = 0; i < events.length; i++) {
      if (events[i].selector) {
        if ($(event.target).is(events[i].selector)) {
          events[i].handler.call(event.target, event);
        }

        $(event.target)
          .parents(events[i].selector)
          .each(function () {
            events[i].handler.call(this, event);
          });
      }
    }
    event.stopPropagation(); //Always stop propagation
  });

  $('[data-toggle="popover-custom-bg"]').each(function (i, obj) {
    var popClass = $(this).attr("data-bg-class");

    $(this).popover({
      trigger: "focus",
      placement: "top",
      template:
        '<div class="popover popover-bg ' +
        popClass +
        '" role="tooltip"><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
    });
  });

  $(function () {
    $('[data-toggle="popover"]').popover();
  });

  $('[data-toggle="popover-custom"]').each(function (i, obj) {
    $(this).popover({
      html: true,
      container: $(this).parent().find(".rm-max-width"),
      content: function () {
        return $(this)
          .next(".rm-max-width")
          .find(".popover-custom-content")
          .html();
      },
    });
  });

  $("body").on("click", function (e) {
    $('[rel="popover-focus"]').each(function () {
      if (
        !$(this).is(e.target) &&
        $(this).has(e.target).length === 0 &&
        $(".popover").has(e.target).length === 0
      ) {
        $(this).popover("hide");
      }
    });
  });

  $(".header-megamenu.nav > li > .nav-link").on("click", function (e) {
    $('[data-toggle="popover-custom"]').each(function () {
      if (
        !$(this).is(e.target) &&
        $(this).has(e.target).length === 0 &&
        $(".popover").has(e.target).length === 0
      ) {
        $(this).popover("hide");
      }
    });
  });

  // BS4 Tooltips

  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  $(function () {
    $('[data-toggle="tooltip-light"]').tooltip({
      template:
        '<div class="tooltip tooltip-light"><div class="tooltip-inner"></div></div>',
    });
  });

  // Drawer

  $(".open-right-drawer").click(function () {
    $(this).addClass("is-active");
    $(".app-drawer-wrapper").addClass("drawer-open");
    $(".app-drawer-overlay").removeClass("d-none");
  });

  $(".drawer-nav-btn").click(function () {
    $(".app-drawer-wrapper").removeClass("drawer-open");
    $(".app-drawer-overlay").addClass("d-none");
    $(".open-right-drawer").removeClass("is-active");
  });

  $(".app-drawer-overlay").click(function () {
    $(this).addClass("d-none");
    $(".app-drawer-wrapper").removeClass("drawer-open");
    $(".open-right-drawer").removeClass("is-active");
  });

  $(".mobile-toggle-nav").click(function () {
    $(this).toggleClass("is-active");
    $(".app-container").toggleClass("sidebar-mobile-open");
  });

  $(".mobile-toggle-header-nav").click(function () {
    $(this).toggleClass("active");
    $(".app-header__content").toggleClass("header-mobile-open");
  });

  $(".mobile-app-menu-btn").click(function () {
    $(".hamburger", this).toggleClass("is-active");
    $(".app-inner-layout").toggleClass("open-mobile-menu");
  });

  // Responsive

  var resizeClass = function () {
    var win = document.body.clientWidth;
      if (win < 1250) {
          $(".app-container").addClass("closed-sidebar-mobile closed-sidebar");
      } else {
          $(".app-container").removeClass("closed-sidebar-mobile closed-sidebar");
      }
  };

  $(window).on("resize", function () {
    resizeClass();
  });

  resizeClass();
});


function printErrorMsg (msg) {
    if(typeof msg == "string" && msg != undefined )
    {
        errorMessage(msg)
    }else if(Object.keys(msg).length > 0){
        var htmlMs = '';
        $.each( msg, function( key, value ) {
            htmlMs += value+'<br/>';
        });
        errorMessage(htmlMs)
    }

}

function successMessage(msg)
{
    //toastr.success(msg, 'Success!')
    swal({
        title: "Success!",
        text: msg,
        icon: "success",
    });
}

function errorMessage(msg)
{

    //toastr.error(msg, 'Error!')
    swal({
        title: "Error!",
        text: msg,
        icon: "error",
    });

}

function blockContainer(containerId)
{
    $(containerId).block({
        message: $(
            '<div class="loader mx-auto">\n' +
            '                            <div class="ball-grid-pulse">\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            "                            </div>\n" +
            "                        </div>"
        ),
    });
}

function unBlockContainer()
{
    $.unblockUI();
}

$.blockUI.defaults = {
    fadeIn: 200,
    fadeOut: 400,
    overlayCSS:  {
        backgroundColor: '#000',
        opacity:         0.2,
        cursor:          'wait'
    },
};

function showLoader()
{
    var htmlCon = '<div class="loaderEffect d-none" >\n' +
        '    <div class="loader bg-transparent no-shadow p-0">\n' +
        '        <div class="ball-grid-pulse">\n' +
        '            <div class="bg-white"></div>\n' +
        '            <div class="bg-white"></div>\n' +
        '            <div class="bg-white"></div>\n' +
        '            <div class="bg-white"></div>\n' +
        '            <div class="bg-white"></div>\n' +
        '            <div class="bg-white"></div>\n' +
        '            <div class="bg-white"></div>\n' +
        '            <div class="bg-white"></div>\n' +
        '            <div class="bg-white"></div>\n' +
        '        </div>\n' +
        '    </div>\n' +
        '</div>';
    $.blockUI({ message: htmlCon });
}

function hideLoader()
{
   $.unblockUI();
}



function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

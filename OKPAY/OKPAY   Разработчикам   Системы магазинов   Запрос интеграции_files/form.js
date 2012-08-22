$(document).ready(function() {
prepareInput();
$("input[type=text], input[type=password]").blur(forcedvalidate);
});
function prepareInput() {
    $('input[type=text], input[type=password],textarea.text, select').focus(function () {       
        $(this).parent().children("div.hint").children("span.error-validator").remove();
        $(this).parent().children("div.hint").children("span.valid-email").remove();
        $(this).parent().children("div.hint").children("span.invalid-email").remove();
        where = $(this).parent().children("div.hint");
        if ($(this).parent().children("span.error-validator") && where.length > 0) {
            $(this).parent().children("span.error-validator").clone().prependTo(where);
            $(this).parent().children("span.error-validator").fadeOut();
        }
        if ($(this).parent().children("span.valid-email")) {
            $(this).parent().children("span.valid-email").clone().prependTo(where);
        }

        if ($(this).parent().children("span.invalid-email")) {
            $(this).parent().children("span.invalid-email").clone().prependTo(where);
        }
        where.fadeIn();
        active_hint = $(this).parent().children("div.hint");
    });

    $('input[type=text], input[type=password],textarea.text, select').blur(function() {
        $(this).parent().children("div.hint").fadeOut();
        $(this).parent().children("div.hint").children("span.error-validator").remove();
        $(this).parent().children("div.hint").children("span.valid-email").remove();
        $(this).parent().children("div.hint").children("span.invalid-email").remove();
        active_hint = false;
    });
}

function forcedvalidate() {
    var txt = this;
    var id = $(this).attr("id");    
    $.each(Page_Validators, function() {
        if (this.controltovalidate == id) {
            ValidatorValidate(this);
        }
    });
    validateColor(this);
}

function validateColor(txt) {
    var id = $(txt).attr("id");
    var find = false;
    $.each(Page_Validators, function() {
        if (this.controltovalidate == id) {
            if (!this.isvalid) {
                $(txt).parent().addClass("error-input");
                find = true;
            }
        }
        if (!find) { $(txt).parent().removeClass("error-input"); }
    });
}

function checkValidators() {    
    $.each(Page_Validators, function () {
        if (!this.isvalid) { $('#' + this.controltovalidate).parent().addClass("error-input"); }
    });   
}


function HasPageValidators() { var hasValidators = false; try { if (Page_Validators.length > 0) { hasValidators = true; } } catch (error) { } return hasValidators; }


$.fn.passwordStrength = function (options) {
    return this.each(function () {
        var that = this; that.opts = {};
        that.opts = $.extend({}, $.fn.passwordStrength.defaults, options);
        that.div = $(that.opts.targetDiv);
        that.defaultClass = that.div.attr('class');
        that.percents = (that.opts.classes.length) ? 100 / that.opts.classes.length : 100;

        this.div.append("<div class='pswstr smsgns passive'></div>");
        this.div.append("<div class='pswstr lgsgns passive'></div>");
        this.div.append("<div class='pswstr numb passive'></div>");
        this.div.append("<div class='pswstr char passive'></div>");
        this.div.append("<div class='pswstr symb passive'></div>");


        var specialsymbolRegex = false;
        var smallsymbolPassed = false;
        var largesymbolPassed = false;
        var lengthPassed = false;

        var numberRegex = new RegExp("([0-9]+)");
        var smallsymbolRegex = new RegExp("([a-z]+)");
        var largesymbolRegex = new RegExp("([A-Z]+)");
        var specialsymbolRegex = new RegExp("([^A-Za-z0-9]+)");

        v = $(this).keyup(function () {
            if (typeof el == "undefined")
            { this.el = $(this); }

            var dv = $(that.opts.targetDiv);

            if (smallsymbolRegex.test(this.value)) { toogleDv($(dv).find("div.smsgns"), true); smallsymbolPassed = true; }
            else { toogleDv($(dv).find("div.smsgns"), false); smallsymbolPassed = false ; }

            if (largesymbolRegex.test(this.value)) { toogleDv($(dv).find("div.lgsgns"), true); largesymbolPassed = true; }
            else { toogleDv($(dv).find("div.lgsgns"), false); largesymbolPassed = false; }

            if (this.value.length >= 8) { toogleDv($(dv).find("div.symb"), true); lengthPassed = true; }
            else { toogleDv($(dv).find("div.symb"), false); lengthPassed = false; }

            if (specialsymbolRegex.test(this.value) || numberRegex.test(this.value)) {
                specialSymbPassed = true;
                toogleDv($(dv).find("div.char"), true);
                toogleDv($(dv).find("div.numb"), true);
                }
            else {
                toogleDv($(dv).find("div.char"), false);
                toogleDv($(dv).find("div.numb"), false); 
                specialSymbPassed = false; 
             }

//            if (numberRegex.test(this.value)) { toogleDv($(dv).find("div.numb"), true); numberPassed = true; }
//            else { toogleDv($(dv).find("div.numb"), false); numberPassed = false; }


            if (smallsymbolPassed && largesymbolPassed && lengthPassed && specialSymbPassed) {
                $(dv).parent().addClass("pass");
                $(dv).addClass("pass");
            }
            else {
                $(dv).parent().removeClass("pass");
                $(dv).removeClass("pass");
            }

            //            var s = getPasswordStrength2(this.value);
            //            var p = this.percents;
            //            var t = Math.floor(s / p);
            //            if (t > 10) { t = 10; }
            //            //if (s >= 110)
            //            //t = this.opts.classes.length - 1;
            //            this.div.removeAttr('class').addClass(this.defaultClass);
            //            if (s > 0) { this.div.addClass(this.opts.classes[t - 1]); }
        })
    });

    function toogleDv($dv, Enable) {
        if (Enable) { $dv.removeClass("passive").addClass("active"); } else { $dv.removeClass("active").addClass("passive"); }
    }

    function getPasswordStrength2(H) {
        var numberRegex = new RegExp("([0-9]+)");
        var smallsymbolRegex = new RegExp("([a-z]+)");
        var largesymbolRegex = new RegExp("([A-Z]+)");
        var specialsymbolRegex = new RegExp("([^A-Za-z0-9]+)");
        var power = 0;
        if (numberRegex.test(H)) { power += 20; }
        if (smallsymbolRegex.test(H)) { power += 20; }
        if (largesymbolRegex.test(H)) { power += 20; }
        if (specialsymbolRegex.test(H)) { power += 20; }
        var l = Math.floor(H.length / 2);
        if (l > 6) { l = 6; }
        power += l * 5;
        if (H.length < 8) { if (power > 85) { power = 85; } }
        return power;
    }
};
$.fn.passwordStrength.defaults = {
    classes: Array('is10', 'is20', 'is30', 'is40', 'is50', 'is60', 'is70', 'is80', 'is90', 'is100'),
    targetDiv: '#dvPasswordStrength',
    cache: {}
}
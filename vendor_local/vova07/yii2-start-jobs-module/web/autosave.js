var jobAutoSave = jobAutoSave || {};


// @see http://stackoverflow.com/questions/10896749/what-does-function-function-window-jquery-do
!(function ($) {
    // @see http://ejohn.org/blog/ecmascript-5-strict-mode-json-and-more/
    "use strict";

    $(document).ready(function () {
        /***********************************************************************
         *                              METHODS
         **********************************************************************/
        /**
         * @param element (object)
         */
        jobAutoSave.save = function ($element) {
            var $row        = $element.closest('tr'),
                url         = $element.attr('href'),
                $icon       = $element.find('i'),
                iconClass   = $icon.attr('class'),
                timeout     = 1200,
                delay       = 2500
            ;
            $form.ajaxSubmit({
                delegation: true,
                url: url,
                type: 'POST',
                dataType: 'json',
                beforeSubmit: function (data, form, options) {
                    $element.data('locked', true);
                    if ( iconClass ) {
                        $icon.attr('class', 'fa fa-spinner fa-pulse');
                    }
                    if ( $element.attr('before-send-message') ) {
                        // show iGrowl popup message
                        // @see http://catc.github.io/iGrowl/
                        $.iGrowl.prototype.dismissAll('all');
                        $.iGrowl({
                            placement:  {
                                x: $element.attr('placement-x') || 'center',
                                y: $element.attr('placement-y') || 'top'
                            },
                            type:       'notice',
                            delay:      delay * 60,
                            animation:  true,
                            animShow:   'fadeIn',
                            animHide:   'fadeOut',
                            title:      ':: ' + ($element.attr('before-send-title') || 'REQUEST SENT') + ' .:',
                            message:    $element.attr('before-send-message') || 'Please wait...'
                        });
                    }
                },
                success: function (response, status, xhr, form) {
                    window.setTimeout(function () {
                        $.iGrowl.prototype.dismissAll('all');
                        window.setTimeout(function () {
                            $.iGrowl({
                                placement:  {
                                    x: $element.attr('placement-x') || 'center',
                                    y: $element.attr('placement-y') || 'top'
                                },
                                type:       response.status || 'error',
                                delay:      (response.status !== 'success') ? delay * 60 : delay,
                                animation:  true,
                                animShow:   'fadeIn',
                                animHide:   'fadeOut',
                                title:      ':: ' + ($element.attr('success-title') || 'SERVER RESPONSE') + ' .:',
                                message:    response.message || $element.attr('success-message') || '...'
                            });
                            if ( iconClass ) {
                                $icon.attr('class', iconClass);
                            }
                            window.setTimeout(function () {$element.data('locked', false);}, delay * 2);

                            if ( response.status === 'success' ) {
                                $row.addClass('success');
                                $form.addClass('has-success');
                            } else {
                                $row.addClass('danger');
                                $form.addClass('has-error');
                            }
                        }, timeout);
                    }, timeout);
                },
                error: function (response) {
                    window.setTimeout(function () {
                        $.iGrowl.prototype.dismissAll('all');
                        window.setTimeout(function () {
                            $.iGrowl({
                                placement:  {
                                    x: $element.attr('placement-x') || 'center',
                                    y: $element.attr('placement-y') || 'top'
                                },
                                type:       'error',
                                delay:      delay * 60,
                                animation:  true,
                                animShow:   'fadeIn',
                                animHide:   'fadeOut',
                                title:      ':: SERVER ERROR .:',
                                message:    response.responseText || 'Error'
                            });
                            if ( iconClass ) {
                                $icon.attr('class', iconClass);
                            }
                            window.setTimeout(function () {$element.data('locked', false);}, delay * 2);

                            $row.addClass('danger');
                            $form.addClass('has-error');
                        }, timeout);
                    }, timeout);
                }
            });
        };

    });
})(window.jQuery);

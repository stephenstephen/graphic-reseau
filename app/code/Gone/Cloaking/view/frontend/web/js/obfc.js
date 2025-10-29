/*
 *
 * @copyright Copyright © 2020 410-Gone. All rights reserved.
 * @author    contact@410-gone.fr
 *
 */
require(['jquery', 'domReady!'],
    function ($) {

        $(".quatrecentdix").on("click", function (event) {

            var attribute = $(this).data("atc");

            if (window.innerWidth < 768) {
                var options = $(this).data("options");
                if (undefined !== options) {
                    if (!options.follow) {
                        return;
                    }
                }
            }

            if (event.ctrlKey) {
                var newWindow = window.open(decodeURIComponent(window.atob(attribute)), '_blank');
                newWindow.focus();
            } else {
                document.location.href = decodeURIComponent(window.atob(attribute));
            }
        });
    });

/*gtl function for obfuscated links*/
function gtl(str) {
    document.location.href = decodeURIComponent(escape(window.atob(str)))
}

/*global define*/
define([
    'jquery', './netreviewsHelpful', 'mage/translate', 'fingerprint2', './carousel'
], function ($, nrHelpful) {
    'use strict';
    var isEnableHelpfulReviews = false;

    /**
     * show Netreviews Tab
     */
    function showNetreviewsTab() {
        var tabs = document.getElementsByClassName("product data items")[0].childNodes; // Tab to show
        for (var i = 0; i < tabs.length; i++) {
            if (tabs[i].nodeType != Node.TEXT_NODE) { // Esquiva los elementos text_node.
                // Desactivar/ocultar las otras pestañas.
                if (tabs[i].hasAttribute("class") && tabs[i].hasAttribute("aria-selected") && tabs[i].hasAttribute("aria-expanded")) {
                    tabs[i].classList.remove("active");
                    tabs[i].setAttribute("aria-selected", "false");
                    tabs[i].setAttribute("aria-expanded", "false");
                }

                if (tabs[i].hasAttribute("aria-hidden") && tabs[i].hasAttribute("style")) {
                    tabs[i].setAttribute("aria-hidden", "true");
                    tabs[i].style.display = "none";
                }

            }

            // Mostrar el título de la pestaña VR.
            if (tabs[i].id == "tab-label-verified.reviews.tab") {
                tabs[i].className += tabs[i].className ? ' active' : 'active';
                tabs[i].setAttribute("aria-selected", "true");
                tabs[i].setAttribute("aria-expanded", "true");
            }

            // Mostrar el contenido de la pestaña VR.
            if (tabs[i].id == "verified.reviews.tab") {
                tabs[i].setAttribute("aria-hidden", "false");
                tabs[i].style.display = "block";
            }

        }
        $('html,body').animate({scrollTop: $("#netreviews_reviews_tab").offset().top}, 'slow');
    }

    /**
     * add More Reviews
     */
    function netReviewsMoreReviews() {
        if ($('#netreviews_button_more_reviews').find(".active").length == 0) {
            $('#netreviews_button_more_reviews').find('a').addClass('active');
            $('#avisVerifiesAjaxImage').css('display', 'block');
            var avisVerifiesAjaxUrl = $("#avisVerifiesAjaxUrl").val();
            var avisVerifiesProductRef = $("#avisVerifiesProductRef").val();
            var avisVerifiesPageNumber = $("#avisVerifiesPageNumber").val();
            var avisVerifiesFilter = $("#netreviews_reviews_filter").val();
            var avisVerifiesRateFilter = $("#avisverifiesRateFilter").val();
            var $content = $(".netreviews_reviews_section");
            $.ajax({
                url: avisVerifiesAjaxUrl,
                type: "POST",
                data: {
                    'avisVerifiesProductRef': avisVerifiesProductRef,
                    'avisVerifiesPageNumber': avisVerifiesPageNumber,
                    'avisVerifiesFilter': avisVerifiesFilter,
                    'avisVerifiesRateFilter': avisVerifiesRateFilter,
                    'function': 'getMoreReviews'
                },
                success: function (data) {
                    $("#avisVerifiesAjaxImage").css('display', 'none');
                    $("#netreviews_button_more_reviews").find("a").removeClass("active");

                    if (data.output != '') {
                        $content.append(data.output);
                        // Display existing votes
                        nrHelpful.avDisplayVotes();
                        var pageNumber = $("#avisVerifiesPageNumber").val();
                        pageNumber = Number(pageNumber) + Number(1);
                        $("#avisVerifiesPageNumber").val(pageNumber);
                        $('.netreviewsVote').on('click', function (e) {
                            var reviewId = $(this).data('reviewId');
                            var vote = $(this).data('vote');
                            var sign = $(this).data('sign');
                            nrHelpful.avHelpfulClick(reviewId, vote, sign);
                        });

                        $(".netreviews_button_comment.hide").click(function (event) {
                            hideComments(this);
                        });
                        $(".netreviews_button_comment.show").click(function (event) {
                            showComments(this);
                        });
                        avisVerifiesPageNumber++;
                        $("#avisVerifiesPageNumber").val(avisVerifiesPageNumber);
                        // Hide more reviews button if all reviews are displayed
                        if ($(".netreviews_review_part").length == $("#avisverifiesNbTotalReviews" + avisVerifiesRateFilter).val()) {
                            $("#netreviews_button_more_reviews").hide();
                        } else {
                            $("#netreviews_button_more_reviews").show();
                        }

                        // Hide more reviews button if all reviews of the selected rate are displayed
                        if ($("#avisverifiesRateFilter").val().length != 0) {
                            if ($(".netreviews_review_part").length == $(".netreviews_rate_total" + $("#avisverifiesRateFilter").val()).html()) {
                                $("#netreviews_button_more_reviews").hide();
                            } else {
                                $("#netreviews_button_more_reviews").show();
                            }
                        }

                    } else {
                        var rateFilter = $("#avisverifiesRateFilter").val();
                        if (rateFilter != "") {
                            $content.append("<p class='netreviews_no_reviews_block'>" + $.mage.__('There are no reviews for this rating.') + "</p>");
                        } else {
                            $content.append("<p class='netreviews_no_reviews_block'>" + $.mage.__('There are no reviews for this product yet.') + "</p>");
                        }
                        $("#netreviews_button_more_reviews").hide();
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('something went wrong netReviewsMoreReviews...');
                    var err = eval("(" + jqXHR.responseText + ")" );
                    console.log(err.Message);
                    $("#avisVerifiesAjaxImage").css('display', 'none');
                    $("#netreviews_button_more_reviews").hide();
                }
            });
        }
    }

    /**
     * delete cache
     */
    function netReviewsDeleteCache() {
        var avisVerifiesAjaxUrl = $("#avisVerifiesAjaxUrl").val();
        var avisVerifiesProductRef = $("#avisVerifiesProductRef").val();
        var avisVerifiesPageNumber = $("#avisVerifiesPageNumber").val();
        var avisVerifiesFilter = $("#netreviews_reviews_filter").val();
        var avisVerifiesRateFilter = $("#avisverifiesRateFilter").val();
        $.ajax({
            url: avisVerifiesAjaxUrl,
            type: "POST",
            data: {
                'avisVerifiesProductRef': avisVerifiesProductRef,
                'avisVerifiesPageNumber': avisVerifiesPageNumber,
                'avisVerifiesFilter': avisVerifiesFilter,
                'avisVerifiesRateFilter': avisVerifiesRateFilter,
                'function': 'deleteCacheByTag'
            },
            success: function (data) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('something went wrong netReviewsDeleteCache...');
                var err = eval("(" + jqXHR.responseText + ")" );
                console.log(err.Message);
            }
        });
    }


    function updateReviewsFilter() {
        $('#netreviews_button_more_reviews').hide();
        $('.netreviews_reviews_section').html('<div id="avisVerifiesAjaxImage"></div>');
        $("#avisverifiesRateFilter").val('');
        $('#avisVerifiesPageNumber').val("0");
        netReviewsMoreReviews();
    }

    function netreviewsRateFilter(chosenRate) {
        $('#netreviews_button_more_reviews').hide();
        $('#netreviews_reviews_filter option:selected').prop("selected", false);
        $('#avisVerifiesPageNumber').val("0");
        $('#avisverifiesRateFilter').val(chosenRate);
        $('.netreviews_reviews_section').html('<div id="avisVerifiesAjaxImage"></div>');
        netReviewsMoreReviews();
    }

    function showComments(obj) {
        var $this = $(obj);
        var $parent = $this.parent();
        $this.hide();
        $parent.find('.netreviews_button_comment.hide').show();
        $parent.find('.netreviews_website_answer').each(function (index, value) {
            $(value).show('slow');
        });
    }

    function hideComments(obj) {
        var $this = $(obj);
        var $parent = $this.parent();
        $this.hide();
        $parent.find('.netreviews_button_comment.show').show();
        $parent.find('.netreviews_website_answer').each(function (index, value) {
            if (index != 0)
                $(value).hide('slow');
        });
    }

    function manageAnimations() {
        $('#netreviews_informations_label').on("click", function () {
            if (!$('.netreviews_rating_header div span').hasClass('netreviews_active_info')) {
                $('.netreviews_rating_header div span').addClass("netreviews_active_info");
                $('.netreviews_rating_header div span').fadeIn();
            }
            else {
                $('.netreviews_rating_header div span').removeClass("netreviews_active_info");
                $('.netreviews_rating_header div span').fadeOut();
            }
        });
        $('#netreviews_informations').on('click', function () {
            if ($('.netreviews_rating_header div span').hasClass('netreviews_active_info')) {
                $('.netreviews_rating_header div span').removeClass("netreviews_active_info");
                $('.netreviews_rating_header div span').fadeOut();
            }
        });
    }


    return function (options, element) {
        // load netreviews_helpful
        nrHelpful.avLoadCookie();
        isEnableHelpfulReviews = options.isEnableHelpfulReviews;
        if (isEnableHelpfulReviews === 'yes') {
            // Display existing votes
            nrHelpful.avDisplayVotes();
        }

        // Animation info on reviews
        manageAnimations();
        $(".netreviews-stars-link").click(function (event) {
            showNetreviewsTab();
        });
        $(".netreviews_button_comment.hide").click(function (event) {
            hideComments(this);
        });
        $(".netreviews_button_comment.show").click(function (event) {
            showComments(this);
        });
        $('.netreviews_rate_list_item').on('click', function (e) {
            netreviewsRateFilter($(this).data('rateFilter'));
        });
        $("#netreviews_button_more_reviews").click(function (event) {
            netReviewsMoreReviews();
        });
        $('#netreviews_reviews_filter').change(function () {
            updateReviewsFilter();
        });
        $('.netreviewsVote').on('click', function (e) {
            var reviewId = $(this).data('reviewId');
            var vote = $(this).data('vote');
            var sign = $(this).data('sign');
            nrHelpful.avHelpfulClick(reviewId, vote, sign);
            netReviewsDeleteCache();
        });
    };

});

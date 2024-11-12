
function setHeightReviewContainer() {
    const reviewContainer = $('.review-container');
    const elements = $('.reviews');
    let maxHeight = 0;
    elements.each(function () {
        maxHeight = Math.max(maxHeight, $(this).outerHeight());
        console.log(maxHeight);
        reviewContainer.width("100%").height(maxHeight + "px");
    });

}

$(document).ready(setHeightReviewContainer);


$(window).on('load', setHeightReviewContainer);




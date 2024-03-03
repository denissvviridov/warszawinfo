import {showMore, showMoreCategoryPosts, showMoreComments} from "./showMore.js";
import {promoSlide} from "./promoSlide.js";
import {shortText} from "./shortText.js";
import {showOnCategory} from "./showOnCategory.js";
import {helper} from "./helper.js";
import {allPostsCategory} from "./allPostsCategory.js";
import {Like} from "./like.js";
import {Comments} from "./Comments.js";


document.addEventListener("DOMContentLoaded", function () {

    showMoreComments()
    Like()
    shortText()
    showMoreCategoryPosts()
    allPostsCategory()
    helper()
    showOnCategory()
    showMore()
    promoSlide()
    Comments()

});






















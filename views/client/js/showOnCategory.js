import {shortText} from "./shortText.js";
import {createPostElement, title, img, link, description, titleCategory, publishDate} from "./builder.js";

export function showOnCategory() {

    let selectCategory = document.querySelectorAll('.category')
    let postContainer = document.querySelector('#post-container');
    let btnAll = document.querySelector('#all_product-btn');
    let changeNameBlock = document.querySelector('.main_posts-title .posts_title');



    function reloadPage() {
        window.location.reload()
    }

    if (btnAll){
        btnAll.addEventListener('click', reloadPage)

    }


    selectCategory.forEach(function (elem) {

        elem.addEventListener('click', function () {
            changeNameBlock.innerHTML = elem.innerHTML
        })


        let dataCategory = elem.getAttribute('data-category')

        if (elem) {
            elem.addEventListener('click', showPostsOnCategory)
        }

        function showPostsOnCategory() {

            fetch(`/posts_show?category=${dataCategory}`)
                .then(response => response.json())
                .then(data => {
                    postContainer.innerHTML = ''

                    data.forEach(post => {

                        const postBlock = createPostElement()

                        title.innerHTML  =  post.title
                        titleCategory.innerHTML = post.category_ru
                        img.src = 'uploads/' + post.img_path
                        link.href = '/post/read/'+post.id
                        link.innerHTML = 'Читать подробнее...'
                        description.innerHTML = post.description
                        publishDate.innerHTML = post.publish_date

                        postContainer.append(postBlock);
                    })

                    shortText()

                })
                .catch(err => console.error('error', err))

        }
    })


}
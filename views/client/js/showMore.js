import {shortText} from "./shortText.js";
import {createPostElement, title, img, link, description, titleCategory, publishDate} from "./builder.js";

export function showMore() {
    const btnShowMore = document.querySelector('#btn-show_more');

    const postContainer = document.querySelector('#post-container');

    let offset = 5;
    let postPerPage = 10


    if (btnShowMore) {
        btnShowMore.addEventListener("click", loadMorePosts)
    }


    function loadMorePosts() {

        fetch(`helpers/load_more.php?offset=${offset}&limit=${postPerPage}`)
            .then(response => response.json())
            .then(data => {

                if (data.length === 0) {
                    btnShowMore.style.display = 'none'
                } else {
                    data.forEach(post => {

                        const postBlock = createPostElement()

                        title.innerHTML = post.title
                        titleCategory.innerHTML = post.category_ru
                        img.src = 'uploads/' + post.img_path
                        link.href = '/post/read/' + post.id
                        link.innerHTML = 'Читать подробнее...'
                        description.innerHTML = post.description
                        publishDate.innerHTML = post.publish_date

                        postContainer.appendChild(postBlock)

                    })
                    shortText()
                    offset += postPerPage;
                }
            })
    }
}


export function showMoreCategoryPosts() {

    const btnShowMore = document.querySelector('#btn-show_more-first');
    const postsList = document.querySelector('.posts_list')


    let offset = 2
    let limit = 2


    if (btnShowMore) {
        btnShowMore.addEventListener('click', showMorePosts)

        const categoryName = btnShowMore.getAttribute('data-category')


        function showMorePosts() {
            fetch(`/show_more?offset=${offset}&limit=${limit}&category=${categoryName}`)

                .then(response => response.json())
                .then((data) => {

                    data.forEach((post) => {

                        const postBlock = document.createElement('div')
                        postBlock.classList.add('post')

                        const categoryImgWrap = document.createElement('div')
                        categoryImgWrap.classList.add('category-img_wrap')

                        const img = document.createElement('img')

                        const infoBlock = document.createElement('div')
                        infoBlock.classList.add('info_block')

                        const categoryTitle = document.createElement('div')
                        categoryTitle.classList.add('category_title')

                        const description = document.createElement('div')
                        description.classList.add('description')

                        const link = document.createElement('a')
                        link.classList.add('read_more-link')


                        categoryTitle.innerHTML = post.title
                        description.innerHTML = post.description

                        link.href = '/post/read/' + post.id
                        link.innerHTML = 'Читать подробнее...'

                        infoBlock.append(categoryTitle, description, link)


                        img.src = '/uploads/' + post.img_path
                        categoryImgWrap.append(img)


                        postBlock.append(categoryImgWrap, infoBlock)
                        postsList.appendChild(postBlock)
                    })
                    offset += limit
                    shortText()
                })
                .catch((err) => {
                    console.error('Error', err)
                })
        }

    }
}


export function showMoreComments() {

    const btnMoreComments = document.querySelectorAll('.btn_more-comments')
    const comments = document.querySelectorAll('.comment')
    const commentList = document.querySelector('.comment_list')


    btnMoreComments.forEach(function (elem) {

        if (comments.length === 0) {
            elem.style.display = 'none'
        } else {
            elem.style.display = 'block'
        }

        elem.addEventListener('click', showMoreRequest)

        let postIdAtr = elem.getAttribute('data-post')
        let offset = 2
        let limit = 3

        function showMoreRequest() {

            fetch(`/more_comments?offset=${offset}&limit=${limit}&post_id=${postIdAtr}`)
                .then(response => response.json())
                .then((data) => {

                    data.forEach((elem) => {
                        const comment = document.createElement('div')
                        comment.classList.add('comment')

                        const avatarWrap = document.createElement('div')
                        avatarWrap.classList.add('avatar')

                        const img = document.createElement('img')
                        img.src = '/uploads/avatar.jpeg'


                        avatarWrap.append(img)

                        const text = document.createElement('div')
                        text.innerHTML = elem.comment


                        comment.append(avatarWrap,  text)

                        commentList.append(comment)

                        console.log(elem)
                    })

                    offset += limit
                })
                .catch(err => {
                    console.error('Error', err)
                })
        }

    })
}
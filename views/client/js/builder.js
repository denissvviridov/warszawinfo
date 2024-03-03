let title, titleCategory, img, link, description, publishDate

function createPostElement() {

    const postElement = document.createElement('div');
    postElement.classList.add('post')
    titleCategory = document.createElement('h2')
    titleCategory.classList.add('category')

    const imgWrap = document.createElement('div')
    imgWrap.classList.add('img_wrap')
    img = document.createElement('img')
    title = document.createElement('div')
    title.classList.add('title')


    const details = document.createElement('div')
    details.classList.add('details')

    link = document.createElement('a')
    link.classList.add('read_more-link')

    description = document.createElement('div')
    description.classList.add('description')

    publishDate = document.createElement('div')
    publishDate.classList.add('publish_date')



    details.append(link, description)
    imgWrap.append(img)
    postElement.append(titleCategory, imgWrap, title, details, publishDate)
    return postElement
}


export {createPostElement, title, img, link, description, titleCategory, publishDate}